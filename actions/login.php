<?php
include '../database/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the email to avoid SQL Injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    // Prepare the statement to avoid SQL Injection
    $stmt = $conn->prepare("SELECT id, first_name, last_name, password, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name']; // Combine first and last name
        header("Location: ../dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid email or password"; // Set the error message in session
        header("Location: ../public/index.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
