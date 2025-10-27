<?php
require 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: signup.php?error=" . urlencode("All fields are required."));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=" . urlencode("Invalid email format."));
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: signup.php?error=" . urlencode("Password must be at least 6 characters."));
        exit();
    }

    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: signup.php?error=" . urlencode("Email or Username already exists."));
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php?success=" . urlencode("Registration successful! Please login."));
    } else {
        header("Location: signup.php?error=" . urlencode("Error: " . $stmt->error));
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup.php");
    exit();
}
?>