<?php
session_start();
include 'db.php';

if (!isset($_GET['room_id'])) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$room_id = intval($_GET['room_id']);

$sql = "SELECT messages.*, users.username 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        WHERE messages.room_id = $room_id 
        ORDER BY messages.created_at ASC";

$result = $conn->query($sql);
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'username' => $row['username'],
        'message' => $row['message'],
        'file_path' => $row['file_path'],
        'created_at' => $row['created_at']
    ];
}

header('Content-Type: application/json');
echo json_encode($messages);
