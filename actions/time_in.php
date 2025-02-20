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
$time_in = date("Y-m-d H:i:s");
$date = date("Y-m-d");

$stmt = $conn->prepare("INSERT INTO attendance (user_id, time_in, date, status) VALUES (?, ?, ?, 'Pending')");
$stmt->bind_param("iss", $user_id, $time_in, $date);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Time In recorded successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to record Time In!"]);
}

$stmt->close();
$conn->close();
?>