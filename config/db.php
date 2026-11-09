<?php
$host = 'localhost';
$db   = 'inventory';
$user = 'root';
$pass = '';                     // change if needed

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>