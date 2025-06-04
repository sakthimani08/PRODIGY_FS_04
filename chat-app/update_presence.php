<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("User not logged in");
}

$user_id = $_SESSION['user_id'];
$conn->query("UPDATE users SET last_active = NOW() WHERE id = $user_id");
