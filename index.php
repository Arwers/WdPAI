<?php
header("Location: login.html");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded credentials
    $valid_username = "admin";
    $valid_password = "admin";

    // Check if the credentials match
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to a dashboard or home page
        exit;
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
