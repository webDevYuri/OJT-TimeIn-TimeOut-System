<?php
$host = "localhost";
$user = "root"; // Change this if needed
$password = "";
$database = "ojt_portal";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
