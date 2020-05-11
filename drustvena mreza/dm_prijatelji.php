<?php
require_once "dm_header.php";

    $id = $_SESSION['id']; //id logovanog korisnika

    $sql = "SELECT u.id, u.username, p.user_id, p.name 
            FROM users AS u
            INNER JOIN profiles AS p
            ON u.id = p.user_id
            WHERE u.id != $id
            ORDER BY p.name;"; // svi korisnici osim logovanog
    $result = $conn->query($sql);
    if($result->num_rows == 0) {
        echo "<div class='error'>Vaša mreža nema nijednog korisnika! :( </div>";
    }
    else {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $friend_id = $row['id'];
            echo "<li>" . $row['name'] . " (" . $row['username'] . ") ";
            echo "<a href='dm_prati.php?friend_id=$friend_id'>Follow</a>&nbsp";
            echo "<a href='dm_otprati.php?friend_id=$friend_id'>Unfollow</a>";
            echo "</li>";
        }
        echo "</ul>";
    }
?>
    </body>
</html>