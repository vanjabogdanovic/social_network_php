<?php

session_start();
if(isset($_SESSION['id'])) {
    session_unset();
    session_destroy();
    header('Location: dm_login.php');
}
require_once "db_connect.php";

?>
