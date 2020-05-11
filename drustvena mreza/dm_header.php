<?php

    session_start();
    require_once "db_connect.php";

    // provera da li je logovan
    if(empty($_SESSION['id'])) {
        header('Location: dm_login.php');
    }

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <nav>
            <ul class="menu">
                <li>
                    <div>Zdravo, <?php echo $_SESSION['name'] ?>!</div>
                </li>
                <li>
                    <a href="dm_prijatelji2.php">Prijatelji</a>
                </li>
                <li>
                    <a href="dm_izmeni_profil.php">Izmeni profil</a>
                </li>
                <li>
                    <a href="dm_izmeni_lozinku.php">Promeni lozinku</a>
                </li>
                <li class="right">
                    <a href="dm_logout.php">Logout</a>
                </li>
            </ul>
        </nav>
        <br>
