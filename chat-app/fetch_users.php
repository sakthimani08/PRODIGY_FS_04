<?php
include 'db.php';

$timeout_seconds = 10; // Mark users offline after 10 seconds of inactivity
$cutoff_time = date('Y-m-d H:i:s', time() - $timeout_seconds);

$result = $conn->query("SELECT username, last_active FROM users");

$users = [];

while ($row = $result->fetch_assoc()) {
    $row['status'] = ($row['last_active'] >= $cutoff_time) ? 'Online' : 'Offline';
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
