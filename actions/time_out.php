<?php
include '../database/db_connect.php';
session_start();

// Set timezone to Manila/Philippines
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in!"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$time_out = date("Y-m-d H:i:s");

$stmt = $conn->prepare("UPDATE attendance 
    SET time_out = ?, status = 'Completed' 
    WHERE user_id = ? AND time_out IS NULL 
    ORDER BY id DESC LIMIT 1");
$stmt->bind_param("si", $time_out, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Time Out recorded successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to record Time Out!"]);
}

$stmt->close();
$conn->close();
