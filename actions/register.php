<?php
include '../database/db_connect.php';  
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $ojt_hours = $_POST['ojt_hours'];
    $start_date = $_POST['start_date'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match!';
        header("Location: ../public/signup.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = 'Email already exists!';
        header("Location: ../public/signup.php");
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, company, ojt_hours, start_date, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $company, $ojt_hours, $start_date, $hashed_password);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Registration successful!';
        header("Location: ../public/signup.php");
    } else {
        $_SESSION['error_message'] = 'Registration failed!';
        header("Location: ../public/signup.php");
    }
    
    $stmt->close();
    $conn->close();
}
?>
