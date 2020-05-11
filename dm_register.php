<?php
require_once "db_connect.php";

$imeErr = $datumErr = $usernameErr = $passwordErr = $repasswordErr = "*";

if(isset($_POST['potvrdi'])) {
    $ime = $conn->real_escape_string($_POST['ime']);
    $datum = $conn->real_escape_string($_POST['datum']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $repassword = $conn->real_escape_string($_POST['repassword']);

    if (empty($ime)) {
        $imeErr = "Niste uneli ime!";
    }
    if (empty($datum)) {
        $datumErr = "Niste uneli datum!";
    }
    if (empty($username)) {
        $usernameErr = "Niste uneli korisničko ime!";
    }
    if (empty($password)) {
        $passwordErr = "Niste uneli lozinku!";
    }
    if (empty($repassword)) {
        $repasswordErr = "Niste ponovili lozinku!";
    }
    if ($password != $repassword) {
        $repasswordErr .= " Lozinka i ponovljena lozinka moraju biti iste!";
    }
    if ($imeErr == "*" && $datumErr == "*" && $usernameErr == "*" && $passwordErr == "*" && $repasswordErr = "*") {
        $sql = "INSERT INTO users(username, password)
                VALUES ('$username', '$password',);";
        $result = $conn->query($sql);
        $sql = "SELECT id
                FROM users
                ORDER BY id DESC";
        $result = $conn->query($sql);
        $red = $result->fetch_assoc();
        $id = $red['id'];
        $sql = "INSERT INTO profiles(name, dob, user_id)
                VALUES ('$ime', '$datum', '$id');";
        $result = $conn->query($sql);
    }
}
?>

<html>
    <head>
    </head>
    <body>
        <form action="dm_register.php" method="post">
            Ime i prezime:
            <input type="text" name="ime">
            <span class="error"><?php echo $imeErr ?></span>
            <br><br>

            Datum rođenja:
            <input type="date" name="datum">
            <span class="error"><?php echo $datumErr ?></span>
            <br><br>

            Korisničko ime:
            <input type="text" name="username">
            <span class="error"><?php echo $usernameErr ?></span>
            <br><br>

            Lozinka:
            <input type="password" name="password">
            <span class="error"><?php echo $passwordErr ?></span>
            <br><br>

            Potvrdite lozinku:
            <input type="password" name="repassword">
            <span class="error"><?php echo $repasswordErr ?></span>
            <br><br>

            <input type="submit" name="potvrdi" value="Potvrdi">

            <input type="hidden">
        </form>
    </body>
</html>