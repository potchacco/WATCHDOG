<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

// ===== ADD Incident =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $pet_id = !empty($_POST['pet_id']) ? $_POST['pet_id'] : null;
    $incident_type = $_POST['incident_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $severity = $_POST['severity'] ?? 'Medium';
    $incident_date = $_POST['incident_date'] ?? '';

    if (empty($incident_type) || empty($description) || empty($incident_date)) {
        echo json_encode(['status' => 'error', 'message' => 'Incident type, description, and date are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO incidents (user_id, pet_id, incident_type, description, location, severity, incident_date, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $pet_id, $incident_type, $description, $location, $severity, $incident_date]);
        
        // Log activity
        $logStmt = $pdo->prepare("INSERT INTO activity_log (user_id, activity_type, description, created_at) 
                                  VALUES (?, 'incident_reported', ?, NOW())");
        $logStmt->execute([$_SESSION['user_id'], "New incident reported: $incident_type"]);
        
        echo json_encode(['status' => 'success', 'message' => 'Incident reported successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== UPDATE Incident =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $incident_id = $_POST['incident_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (empty($incident_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Incident ID is required.']);
        exit;
    }

    try {
        $resolved_date = ($status === 'Resolved' || $status === 'Closed') ? date('Y-m-d H:i:s') : null;
        
        $stmt = $pdo->prepare("UPDATE incidents SET status = ?, notes = ?, resolved_date = ? 
                               WHERE id = ? AND user_id = ?");
        $stmt->execute([$status, $notes, $resolved_date, $incident_id, $_SESSION['user_id']]);
        
        // Log activity
        $logStmt = $pdo->prepare("INSERT INTO activity_log (user_id, activity_type, description, created_at) 
                                  VALUES (?, 'incident_updated', ?, NOW())");
        $logStmt->execute([$_SESSION['user_id'], "Incident status updated to: $status"]);
        
        echo json_encode(['status' => 'success', 'message' => 'Incident updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== DELETE Incident =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $incident_id = $_POST['incident_id'] ?? '';

    if (empty($incident_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Incident ID is required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM incidents WHERE id = ? AND user_id = ?");
        $stmt->execute([$incident_id, $_SESSION['user_id']]);
        echo json_encode(['status' => 'success', 'message' => 'Incident deleted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== GET Incidents =====
try {
    $stmt = $pdo->prepare("SELECT i.*, p.name as pet_name FROM incidents i 
                           LEFT JOIN pets p ON i.pet_id = p.id 
                           WHERE i.user_id = ? 
                           ORDER BY i.incident_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'incidents' => $incidents]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching incidents: ' . $e->getMessage()]);
}
?>
