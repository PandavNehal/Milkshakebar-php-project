<?php
$servername = "127.0.0.1";  // localhost alternative
$username = "root";
$password = "";             // XAMPP default password blank
$dbname = "milkshakebar";
$port = 3306;               // Default MySQL port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
