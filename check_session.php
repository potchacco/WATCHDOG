<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Are we directly hitting check_session.php in the browser?
$isDirectRequest = (basename($_SERVER['SCRIPT_NAME']) === 'check_session.php');

if ($isDirectRequest && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    echo json_encode([
        'loggedIn'  => isset($_SESSION['user_id']),
        'user_id'   => $_SESSION['user_id']   ?? null,
        'user_role' => $_SESSION['user_role'] ?? null,
        'user_name' => $_SESSION['user_name'] ?? null,
    ]);
    exit;
}

// When included by other scripts (dashboard.php, admin_dashboard.php, etc.),
// just enforce login and DO NOT echo anything or exit on GET.
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }
    header('Location: index.php');
    exit;
}
