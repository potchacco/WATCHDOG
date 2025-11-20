<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'resident');
    
    // Validation
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit();
    }
    
    try {
        // First check if user exists with this email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'No account found with this email']);
            exit();
        }
        
        // Check if role matches
        if ($user['role'] !== $role) {
            echo json_encode(['status' => 'error', 'message' => 'Wrong user type selected. Please select ' . ucfirst($user['role'])]);
            exit();
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
            exit();
        }
        
        // Success - Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirect based on role
        $redirect = ($user['role'] === 'admin') ? 'admin_dashboard.php' : 'dashboard.php';
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login successful!', 
            'redirect' => $redirect
        ]);
        exit();
        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}
?>
