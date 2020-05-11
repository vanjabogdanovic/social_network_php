<?php

require_once "dm_header.php";

$staraErr = $novaErr = $pnovaErr = "*";
$poruka = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    if(empty($_POST['stara'])) {
        $staraErr = "Polje ne sme biti prazno!";
    }
    if(empty($_POST['nova'])) {
        $novaErr = "Polje ne sme biti prazno!";
    }
    if(empty($_POST['pnova'])) {
        $pnovaErr = "Polje ne sme biti prazno!";
    }
    if($staraErr == "*" && $novaErr == "*" && $pnovaErr == "*") {
        // Polja nisu prazna
        $stara = $conn->real_escape_string($_POST['stara']);
        $nova = $conn->real_escape_string($_POST['nova']);
        $pnova = $conn->real_escape_string($_POST['pnova']);

        if($nova != $pnova) {
            // Nove sifre se ne poklapaju
            $novaErr = "Sifre moraju biti iste!";
            $pnovaErr = "Sifre moraju biti iste!";
        }
        else {
            $sql = "SELECT password
                FROM users
                WHERE id = " . $_SESSION['id'];
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $sifra = $row['password']; //kodirana korisnkova sifra iz baze
            if(md5($stara) != $sifra) {
                $staraErr = "Pogresna lozinka!";
            }
            else {
                $sql = "UPDATE users
                    SET password = md5('$nova')
                    WHERE id =" . $_SESSION['id'];
                $conn->query($sql);
                $poruka = "Lozinka uspesni promenjena.
            <a href='dm_prijatelji2.php'>Vrati se na pocetnu stranu</a>";
            }
        }
    }
}

?>

<form action="dm_izmeni_lozinku.php" method="post">
    <fieldset>
        <label>Stara lozinka:</label>
        <input type="password" name="stara" value="">
        <span class="error"><?php echo $staraErr ?></span>
        <br><br>
        <label>Nova lozinka:</label>
        <input type="password" name="nova" value="">
        <span class="error"><?php echo $novaErr ?></span>
        <br><br>
        <label>Ponovite novu lozinku:</label>
        <input type="password" name="pnova">
        <span class="error"><?php echo $pnovaErr ?></span>
        <br><br>
        <input type="submit" value="Posalji">
    </fieldset>
</form>
<div>
    <?php echo $poruka; ?>
</div>
</body>
</html>
