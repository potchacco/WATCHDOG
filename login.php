<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs to avoid whitespace bugs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'resident');
    
    // Validation
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            if ($user['role'] === 'admin') {
                echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'redirect' => 'admin_dashboard.php']);
                exit();
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'redirect' => 'dashboard.php']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials or wrong user type selected']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Login failed: ' . $e->getMessage()]);
        exit();
    }
}
?>
