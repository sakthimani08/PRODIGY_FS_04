<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['room_id'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = intval($_POST['room_id']);
$message = trim($_POST['message']);
$file_path = null;

// Handle file upload
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . time() . '_' . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $file_path = $targetFile;
    }
}

$stmt = $conn->prepare("INSERT INTO messages (room_id, user_id, message, file_path) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $room_id, $user_id, $message, $file_path);

if ($stmt->execute()) {
    echo "Message sent";
} else {
    http_response_code(500);
    echo "Database error";
}
