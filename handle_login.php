<?php
session_start(); // Start the session at the very beginning
require 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password']; // This is the plain password from form

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Email and password are required."));
        exit();
    }

    // Fetch user by email, selecting username, password_hash, and created_at
    $stmt = $conn->prepare("SELECT id, username, password_hash, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password against password_hash
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_created_at'] = $user['created_at']; // Store created_at

            $stmt->close();
            $conn->close();
            header("Location: dashboard.php");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: login.php?error=" . urlencode("Invalid email or password."));
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: login.php?error=" . urlencode("Invalid email or password."));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>