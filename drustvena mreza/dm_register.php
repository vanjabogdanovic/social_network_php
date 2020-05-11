<?php
    require_once "db_connect.php";

    $imeErr = $datumErr = $usernameErr = $passwordErr = $repasswordErr = "*";

    if(isset($_POST["potvrdi"])) //if(!empty($_POST["potvrdi"]))
    {
        $ime = $conn->real_escape_string($_POST["ime"]);
        $datum = $conn->real_escape_string($_POST["datum"]);
        $username = $conn->real_escape_string($_POST["username"]);
        $password = $conn->real_escape_string($_POST["password"]);
        $repassword = $conn->real_escape_string($_POST["repassword"]);

        if(empty($ime))
        {
            $imeErr = "Niste uneli ime i prezime";
        }
        if(empty($datum))
        {
            $datumErr = "Niste uneli datum";
        }
        if(empty($username))
        {
            $usernameErr = "Niste uneli korisničko ime";
        }
        if(empty($password))
        {
            $passwordErr = "Niste uneli lozinku";
        }
        if(empty($repassword))
        {
            $repasswordErr = "Niste ponovili lozinku";
        }

        //Ukoliko su svi uslovi ispunjeni, možemo uneti novog korisnika
        if($imeErr == "*" && $datumErr == "*" && $usernameErr=="*" && $passwordErr == "*" && $repasswordErr == "*")
        {
            //Provera da li postoji već takav username u bazi
            //Ukoliko nam je username UNIQUE
            $sql = "SELECT *
            FROM users
            WHERE username = '$username'";

            //Ako postoji taj username
            //Onda će broj redova koji ispunjavaju uslov biti veći od 0
            $result = $conn->query($sql);
            $br = mysqli_num_rows($result);
            if($br != 0)
            {
                $usernameErr = "Odaberite drugo korisničko ime";
            }
            else {
                if($password != $repassword)
                {
                    $passwordErr = $passwordErr . " Lozinka i ponovljena lozinka moraju biti iste";
                    $repasswordErr = $repasswordErr . " Lozinka i ponovljena lozinka moraju biti iste";
                }
                else {
                    //Najpre unosimo username i password jer je id iz ove tabele strani ključ u taveli profiles
                    $sql = "INSERT INTO users(username, password)
                    VALUES('$username', MD5('$password'))";
                    $conn->query($sql);

                    //Da bismo uneli korisnika u tabelu proifiles, moramo da saznamo njegov id
                    //Selektujemo id korisnika, prema username polju
                    $sql = "SELECT id 
                    FROM users
                    WHERE username = '$username'";
                    $result = $conn->query($sql);
                    $red = $result->fetch_assoc();
                    $id = $red['id'];

                    //Izvršimo unos u tabelu profiles prema pronađenom id-u
                    $sql = "INSERT INTO profiles(user_id, name, dob)
                    VALUES($id, '$ime', '$datum')";
                    $conn->query($sql);

                    //Uspesno dodat nov korisnik i profil
                    //Redirekcija na dm_login
                    header('Location:dm_login.php');
                }
            }
        }
    }
?>

<html>
    <head>
    <link rel="stylesheet" type="text/css"
             href="style.css">
    </head>
    <body>
        <form action="dm_register.php" method="POST">
            Ime i prezime: 
            <input type="text" name="ime">
            <span class="error"> <?php echo $imeErr; ?> </span>
            <br><br>
            Datum rođenja:
            <input type="date" name="datum">
            <span class="error"> <?php echo $datumErr; ?> </span>
            <br><br>
            Korisničko ime:
            <input type="text" name="username">
            <span class="error"> <?php echo $usernameErr; ?> </span>
            <br><br>
            Lozinka:
            <input type="password" name="password">
            <span class="error"> <?php echo $passwordErr; ?> </span>
            <br><br>
            Potvrdite lozinku:
            <input type="password" name="repassword">
            <span class="error"> <?php echo $repasswordErr; ?> </span>
            <br><br>
            <input type="submit" name="potvrdi" value="Potvrdi">
        </form>
    </body>       

</html>