<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo 'Please fill in all fields';
        exit();
    } elseif ($password !== $confirmPassword) {
        echo 'Passwords do not match';
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format';
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo 'Email already registered';
            exit();
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        
        if ($stmt->execute([$name, $email, $hashedPassword])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            echo 'success';
            exit();
        } else {
            echo 'Failed to insert user';
            exit();
        }
    } catch(PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        echo 'Database error occurred: ' . $e->getMessage();
        exit();
    }
}
?>