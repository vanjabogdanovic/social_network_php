<?php
session_start();

require_once "db_connect.php";

$friend_id = $conn->real_escape_string($_GET['friend_id']);
// id korisnika kome brisemo pracenje
$id = $_SESSION['id'];
$sql = "DELETE
        FROM follow
        WHERE user_id = $id
        AND friend_id = $friend_id;";
$result = $conn->query($sql);
if(!$result) {
    die("Neuspeli upit: " . $conn->error);
}

header('Location: dm_prijatelji.php');