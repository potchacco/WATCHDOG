<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

// ===== ADD Vaccination =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $pet_id = $_POST['pet_id'] ?? '';
    $vaccine_name = $_POST['vaccine_name'] ?? '';
    $date_given = $_POST['date_given'] ?? '';
    $next_due_date = $_POST['next_due_date'] ?? null;
    $veterinarian = $_POST['veterinarian'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (empty($pet_id) || empty($vaccine_name) || empty($date_given)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet, vaccine name, and date given are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO vaccinations (user_id, pet_id, vaccine_name, date_given, next_due_date, veterinarian, notes, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $pet_id, $vaccine_name, $date_given, $next_due_date, $veterinarian, $notes]);
        
        // Log activity INSIDE the try block
        $logStmt = $pdo->prepare("INSERT INTO activity_log (user_id, activity_type, description, created_at) 
                                  VALUES (?, 'vaccination_added', ?, NOW())");
        $logStmt->execute([$_SESSION['user_id'], "Vaccination added: $vaccine_name"]);
        
        echo json_encode(['status' => 'success', 'message' => 'Vaccination record added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== UPDATE Vaccination =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $vacc_id = $_POST['vacc_id'] ?? '';
    $pet_id = $_POST['pet_id'] ?? '';
    $vaccine_name = $_POST['vaccine_name'] ?? '';
    $date_given = $_POST['date_given'] ?? '';
    $next_due_date = $_POST['next_due_date'] ?? null;
    $veterinarian = $_POST['veterinarian'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (empty($vacc_id) || empty($pet_id) || empty($vaccine_name) || empty($date_given)) {
        echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE vaccinations SET pet_id = ?, vaccine_name = ?, date_given = ?, next_due_date = ?, veterinarian = ?, notes = ? 
                               WHERE id = ? AND user_id = ?");
        $stmt->execute([$pet_id, $vaccine_name, $date_given, $next_due_date, $veterinarian, $notes, $vacc_id, $_SESSION['user_id']]);
        
        // Log activity INSIDE the try block
        $logStmt = $pdo->prepare("INSERT INTO activity_log (user_id, activity_type, description, created_at) 
                                  VALUES (?, 'vaccination_updated', ?, NOW())");
        $logStmt->execute([$_SESSION['user_id'], "Vaccination updated: $vaccine_name"]);
        
        echo json_encode(['status' => 'success', 'message' => 'Vaccination record updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== DELETE Vaccination =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $vacc_id = $_POST['vacc_id'] ?? '';

    if (empty($vacc_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Vaccination ID is required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM vaccinations WHERE id = ? AND user_id = ?");
        $stmt->execute([$vacc_id, $_SESSION['user_id']]);
        echo json_encode(['status' => 'success', 'message' => 'Vaccination record deleted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ===== GET Vaccinations =====
try {
    $stmt = $pdo->prepare("SELECT v.*, p.name as pet_name FROM vaccinations v 
                           JOIN pets p ON v.pet_id = p.id 
                           WHERE v.user_id = ? 
                           ORDER BY v.date_given DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'vaccinations' => $vaccinations]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching vaccinations: ' . $e->getMessage()]);
}
?>
