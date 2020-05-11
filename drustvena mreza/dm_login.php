<?php

    session_start();

    require_once "db_connect.php";

    $usernameErr = $passwordErr = "*";

    // da li smo dosli post metodom
    if($_SERVER["REQUEST_METHOD"] == "POST") { // !empty($_POST['password'] && !empty($_POST['username']
    // korisnik je poslao username i password i pokusava logovanje
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        if(empty($username)) {
            $usernameErr = "Korisničko ime ne sme biti prazno.";
        }
        if(empty($password)) {
            $usernameErr = "Lozinka ne sme biti prazna.";
        }

        if(!empty($username) && !empty($password)) {
            $sql = "SELECT * 
                    FROM users
                    WHERE username = '$username';";
            $result = $conn->query($sql);
            if($result->num_rows == 0) {
                $usernameErr = "Ne postoji korisnik sa unetim korisničim imenom.";
            }
            else {
                // postoji korisnicko ime, treba proveriti sifre
                $row = $result->fetch_assoc();
                $sifra = $row['password']; //polje password iz baze, sifra je kodirana
                if(md5($password) != $sifra) {
                    $passwordErr = "Pogrešna lozinka.";
                }
                else {
                    // ovde vrsimo logovanje
                    $_SESSION['id'] = $row['id'];
                    $id = $_SESSION['id'];
                    $sql1 = "SELECT name 
                            FROM profiles
                            WHERE user_id = $id";
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();
                    $_SESSION['name'] = $row1['name'];
                    header('Location: dm_prijatelji2.php');
                }
            }
        }
    }
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <form action="dm_login.php" method="post">
            <fieldset>

                <legend>Login</legend>

                    <label>Korisničko ime:</label>
                    <input type="text" name="username" value="">
                    <span class="error"><?php echo $usernameErr ?></span>
                    <br><br>

                    <label>Lozinka:</label>
                    <input type="password" name="password" value="">
                    <span class="error"><?php echo $passwordErr ?></span>
                    <br><br>

                    <input type="submit" name="potvrdi" value="Prijavi se">
            </fieldset>
        </form>
    </body>
</html>