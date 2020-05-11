<?php

require_once "dm_header.php";

$id = $_SESSION['id'];
$sql = "SELECT p.name, p.dob, u.username
        FROM profiles AS p
        INNER JOIN users AS u 
        ON u.id = p.user_id
        WHERE p.user_id = $id";
if(!$conn->query($sql)) {
    echo "Greška: " . $conn->error;
}
$result = $conn->query($sql);
$pom = $result->fetch_assoc();
$imeValue = $pom['name'];
$dobValue = $pom['dob'];
$usernameValue = $pom['username'];

$imeErr = $datumErr = $usernameErr = "*";
$poruka = "";

if(isset($_POST['potvrdi'])) { // ili if(!empty($_POST['potvrdi'])
    $ime = $conn->real_escape_string($_POST['ime']);
    $datum = $conn->real_escape_string($_POST['datum']);
    $username = $conn->real_escape_string($_POST['username']);

    if(empty($ime)){
        $imeErr = "Niste uneli ime!";
    }
    if(empty($datum)) {
        $datumErr = "Niste uneli datum!";
    }
    if(empty($username)) {
        $usernameErr = "Niste uneli korisničko ime!";
    }
    else {
        // Dohvatamo sva korisnicna imena drugih korisnika
        // i proveravamo da nije doslo do poklapanja
        $sql = "SELECT username 
                FROM users
                WHERE id != $id
                AND username == $username;";
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $usernameErr = "Zauzeto korisnicko ime.";
        }
    }

//    $sql = "UPDATE profiles
//            SET name = '$ime', dob = '$datum'
//            WHERE user_id = $id";
//    $conn->query($sql);
//    $sql = "UPDATE users
//            SET username = '$username', password = '$password'
//            WHERE id = $id";
//    $conn->query($sql);
    if($imeErr == "*" && $datumErr == "*"  && $usernameErr == "*") {
        $sql = "UPDATE profiles AS p, users AS u
            SET p.name = '$ime', p.dob = '$datum', u.username = '$username'
            WHERE p.user_id = $id
            AND u.id = $id";
        $result = $conn->query($sql);

        // Nakon update-a, azuriramo i promenljive cije vrednosti upisujemo u inpute u formi
        $imeValue = $ime;
        $dobValue = $datum;
        $usernameValue = $username;
        $poruka = "Podaci uspesno promenjeni.
            <a href='dm_prijatelji2.php'>Vrati se na pocetnu stranu</a>";
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <form action="dm_izmeni_profil.php" method="post">
            <fieldset>
                Ime i prezime:
                <input type="text" name="ime" value="<?php echo $imeValue ?>">
                <span class="error"><?php echo $imeErr ?></span>
                <br><br>
                Datum rođenja:
                <input type="date" name="datum" value="<?php echo $dobValue ?>">
                <span class="error"><?php echo $datumErr ?></span>
                <br><br>
                Korisničko ime:
                <input type="text" name="username" value="<?php echo $usernameValue ?>">
                <span class="error"><?php echo $usernameErr ?></span>
                <br><br>
                <input type="submit" name="potvrdi" value="Potvrdi">
            </fieldset>
        </form>
        <div>
            <?php echo $poruka; ?>
        </div>
        <br><br>
    </body>
</html>
