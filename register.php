<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = trim($_POST['name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');
    $address         = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '' || $address === '') {
        echo 'Please fill in all fields';
        exit();
    }

    if ($password !== $confirmPassword) {
        echo 'Passwords do not match';
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format';
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo 'Email already registered';
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, address, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        if ($stmt->execute([$name, $email, $hashedPassword, $address])) {
            // user stays pending; no auto-login
            echo 'success';
            exit();
        } else {
            echo 'Failed to insert user';
            exit();
        }
    } catch (PDOException $e) {
        error_log('Registration error: ' . $e->getMessage());
        echo 'Database error occurred: ' . $e->getMessage();
        exit();
    }
}
?>
