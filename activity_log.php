<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM activity_log WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$_SESSION['user_id']]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'activities' => $activities]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching activities: ' . $e->getMessage()]);
}
?>
