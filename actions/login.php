<?php
include '../database/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    $stmt = $conn->prepare("SELECT id, first_name, last_name, password, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name']; 
        header("Location: ../public/dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid email or password"; 
        header("Location: ../index.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
