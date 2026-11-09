<?php
$host = 'localhost';
$db   = 'u838229316_inventory';
$user = 'u838229316_inventory';
$pass = 'Csbm@1997';                     

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>