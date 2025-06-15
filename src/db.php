<?php
$host = "localhost"; // o l'host di altervista
$user = "unibowebprogramming";      // cambia su altervista "unibowebprogramming"
$password = "aU74pudmHUeD";      // cambia su altervista "aU74pudmHUeD
$dbname = "my_unibowebprogramming";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
