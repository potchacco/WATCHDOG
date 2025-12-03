<?php
require_once 'check_session.php';
require_once 'config/database.php';

header('Content-Type: application/json');

// Only admins
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // list users, pending first
        $stmt = $pdo->query("SELECT id, name, email, address, status, created_at FROM users ORDER BY status = 'pending' DESC, created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'users' => $users]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_POST['user_id'] ?? null;
        $action = $_POST['action'] ?? '';

        if (!$userId || !in_array($action, ['approve','reject'], true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        $newStatus = $action === 'approve' ? 'approved' : 'rejected';

        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $userId]);

        echo json_encode(['status' => 'success', 'message' => 'User status updated', 'new_status' => $newStatus]);
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
} catch (PDOException $e) {
    error_log('Admin users error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
