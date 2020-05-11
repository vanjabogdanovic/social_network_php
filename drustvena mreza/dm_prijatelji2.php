<?php
require_once "dm_header.php";

//// 1) POVEZIVANJE NA BAZU - izvrseno u dm_header
//require_once "db_connect.php";
//// require - ne sme da se izvrsava na dalje ako nema konekcije
//// once - najvise jedan objekat konekcije $conn

// 2) PRIKAZATI SVE KORISNIKE OSIM LOGOVANOG
$id = $_SESSION['id'];
// id - neka vrednost iz kolone id tabele users

if(!empty($_GET['dodaj'])) { //ukoliko nije prazno
    $f_id = $conn->real_escape_string($_GET['dodaj']);
    $sql1 = "SELECT * 
            FROM follow
            WHERE user_id = $id
            AND friend_id = $f_id;";
    $result1 = $conn->query($sql1);
    if($result1->num_rows == 0) {
        $sql2 = "INSERT INTO follow(user_id, friend_id)
                 VALUES ($id, $f_id);";
        $result2 = $conn->query($sql2);
        if(!$result2) {
            echo "<span class='error'>Neuspesno dodavanje: " . $conn->error . "</span>";
        }
    }
}
if(!empty($_GET['brisi'])) { //ukoliko nije prazno
    $f_id = $conn->real_escape_string($_GET['brisi']);
    $sql3 = "DELETE
             FROM follow
             WHERE user_id = $id
             AND friend_id = $f_id;";
    $result3 = $conn->query($sql3);
    if(!$result3) {
        echo "<span class='error'>Neuspesno brisanje: " . $conn->error . "</span>";
    }
}

$sql = "SELECT u.username, p.name, p.dob, u.id
        FROM users AS u
        INNER JOIN profiles AS p
        ON u.id = p.user_id
        WHERE u.id != $id
        ORDER BY p.name;";
$result = $conn->query($sql);

if(!$result) {
    die("<span class='error'>Greska u upitu " . $conn->error) . "</span>
    </body> </html>";
}
else {
        // ako nema korisnika
    if($result->num_rows == 0) {
        echo "<span class='error'>Drustvena mreza nema nijednog korisnika. </span>";
    }
    else {
        // ako ima korisnika
        $br = 0;
        echo "<table>";
        echo "<tr>";
        echo "<th>Redni broj</th>";
        echo "<th>Ime i prezime</th>";
        echo "<th>Korisnicko ime</th>";
        echo "<th>Datum rodjenja</th>";
        echo "<th>Akcije</th>";
        echo "</tr>";
        $current = strtotime("now");

        while ($row = $result->fetch_assoc()) {
            $friend_id = $row['id'];
            echo "<tr>";
            echo "<td>" . (++$br) . "</td>";
            // br++ - ispise br i nakon toga doda 1
            // ++br doda 1 na broj i ispise ga
            echo "<td>" . $row['name'] . "</td>";
            $person = strtotime($row['dob']);

            if($current - $person >= 18 * 365 * 24 * 60 * 60) {
                echo "<td class='blue'>" . $row['username'] . "</td>";
            }
            else {
                echo "<td class='green'>" . $row['username'] . "</td>";
            }
            echo "<td>" . $row['dob'] . "</td>";

            //ISPITUJEMO DA LI JA PRATIM KORISNIKA
            $sql1 = "SELECT *
                     FROM follow
                     WHERE user_id = $id
                     AND friend_id = $friend_id;";
            $result1 = $conn->query($sql1);
            $i_follow_u = $result1->num_rows; // rezultat je 0 ili 1;
            //ISPITUJEMO DA LI KORISNIK PRATI MENE
            $sql2 = "SELECT * 
                     FROM follow
                     WHERE user_id = $friend_id
                     AND friend_id = $id;";
            $result2 = $conn->query($sql2);
            $u_follow_me = $result2->num_rows; // rezultat je 0 ili 1
            echo "<td>";

            if($i_follow_u == 0) {
                if($u_follow_me == 0) {
                    echo "<a href='dm_prijatelji2.php?dodaj=$friend_id'>Follow</a>";
                }
                else {
                    echo "<a href='dm_prijatelji2.php?dodaj=$friend_id'>Follow back</a>";
                }
            }
            else {
                echo "<a href='dm_prijatelji2.php?brisi=$friend_id'>Unfollow</a>";
            }
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
    </body>
</html>
<?php
