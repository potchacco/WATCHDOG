<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }
    header('Location: index.php');
    exit;
}
?>
