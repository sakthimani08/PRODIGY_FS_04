<?php
$servername = "localhost";
$username = "root";
$password = ""; // or your XAMPP MySQL password
$dbname = "chat_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
