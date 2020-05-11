<?php

$servername = "localhost";
$username = "videoman";
$password = "videoman123";
$database = "mreza";

$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset('utf8');
if($conn->connect_error) {
    die("Došlo je do greške: " . $conn->connect_error);
} else {
    echo "Uspesna konekcija";
}
?>