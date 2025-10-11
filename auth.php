<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    require_once 'config/database.php';

    if (!isset($_POST['action'])) {
        throw new Exception('No action specified');
    }

    $action = $_POST['action'];

    switch($action) {
        case 'login':
            handleLogin($pdo);
            break;
        case 'register':
            handleRegister($pdo);
            break;
        case 'logout':
            handleLogout();
            break;
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function handleLogin($pdo) {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        throw new Exception('Missing email or password');
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            throw new Exception('Invalid email or password');
        }
    } catch(PDOException $e) {
        error_log($e->getMessage());
        throw new Exception('Database error occurred');
    }
}

function handleRegister($pdo) {
    if (!isset($_POST['name']) || !isset($_POST['email']) || 
        !isset($_POST['password']) || !isset($_POST['confirmPassword'])) {
        throw new Exception('Missing required fields');
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        throw new Exception('Please fill in all fields');
    }

    if ($password !== $confirmPassword) {
        throw new Exception('Passwords do not match');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('Email already registered');
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $hashedPassword]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        header('Location: dashboard.php');
        exit;
    } catch(PDOException $e) {
        error_log($e->getMessage());
        throw new Exception('Database error occurred');
    }
}

function handleLogout() {
    try {
        session_unset();
        session_destroy();
        echo json_encode([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
        exit;
    } catch (Exception $e) {
        throw new Exception('Logout failed');
    }
}
?>