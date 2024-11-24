<?php
// db.php - Database Connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'restaurant_app';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
