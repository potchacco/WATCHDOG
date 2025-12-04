<?php
require_once 'check_session.php';
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? '';

/**
 * ==============
 * ADD VACCINATION  (action=add)
 * ==============
 */
if ($method === 'POST' && $action === 'add') {
    // Accept multiple possible field names to match the form/JS
    $petId     = $_POST['pet_id']        ?? $_POST['vaccPetId'] ?? $_POST['petId'] ?? '';
    $vaccine   = $_POST['vaccine_name']  ?? $_POST['vaccineName'] ?? '';
    $dateGiven = $_POST['date_given']    ?? $_POST['dateGiven']   ?? '';
    $nextDue   = $_POST['next_due_date'] ?? $_POST['nextDueDate'] ?? null;
    $vet       = $_POST['veterinarian']  ?? '';
    $notes     = $_POST['notes']         ?? '';

    if (empty($petId) || empty($vaccine) || empty($dateGiven)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet, vaccine name and date given are required.']);
        exit;
    }

    try {
        // Ensure this pet belongs to the logged-in user
        $check = $pdo->prepare("SELECT id, name FROM pets WHERE id = ? AND user_id = ?");
        $check->execute([$petId, $userId]);
        $pet = $check->fetch(PDO::FETCH_ASSOC);

        if (!$pet) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid pet selected.']);
            exit;
        }

        $stmt = $pdo->prepare("
    INSERT INTO vaccinations (user_id, pet_id, vaccine_name, date_given, next_due_date, veterinarian, notes)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$userId, $petId, $vaccine, $dateGiven, $nextDue ?: null, $vet, $notes]);


        // Log activity
        $log = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'vaccination_added', ?, NOW())
        ");
        $log->execute([$userId, "Vaccination '$vaccine' recorded for pet {$pet['name']}"]);

        echo json_encode(['status' => 'success', 'message' => 'Vaccination added successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * ==============
 * UPDATE VACCINATION (action=update)
 * ==============
 */
if ($method === 'POST' && $action === 'update') {
    // JS sends "vaccid" for updates
    $vaccId = $_POST['vaccid'] ?? $_POST['vacc_id'] ?? '';
    $vaccine   = $_POST['vaccine_name']  ?? $_POST['vaccineName'] ?? '';
    $dateGiven = $_POST['date_given']    ?? $_POST['dateGiven']   ?? '';
    $nextDue   = $_POST['next_due_date'] ?? $_POST['nextDueDate'] ?? null;
    $vet       = $_POST['veterinarian']  ?? '';
    $notes     = $_POST['notes']         ?? '';

    if (empty($vaccId)) {
        echo json_encode(['status' => 'error', 'message' => 'Vaccination ID is required.']);
        exit;
    }

    try {
        // Load existing record and pet (ensures ownership and gives us current values)
        $check = $pdo->prepare("
            SELECT v.id,
                   v.pet_id,
                   v.vaccine_name,
                   v.date_given,
                   v.next_due_date,
                   v.veterinarian,
                   v.notes,
                   p.name AS pet_name
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE v.id = ? AND p.user_id = ?
        ");
        $check->execute([$vaccId, $userId]);
        $row = $check->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'Vaccination record not found.']);
            exit;
        }

        // Keep existing values when fields are left empty in the form
        $petId    = $row['pet_id']; // pet is locked, cannot be changed here
        $vaccine  = $vaccine   !== '' ? $vaccine   : $row['vaccine_name'];
        $dateGiven = $dateGiven !== '' ? $dateGiven : $row['date_given'];
        $nextDue  = $nextDue   !== null && $nextDue !== '' ? $nextDue : $row['next_due_date'];
        $vet      = $vet       !== '' ? $vet       : $row['veterinarian'];
        $notes    = $notes     !== '' ? $notes     : $row['notes'];

        $stmt = $pdo->prepare("
            UPDATE vaccinations
            SET pet_id = ?, vaccine_name = ?, date_given = ?, next_due_date = ?, veterinarian = ?, notes = ?
            WHERE id = ?
        ");
        $stmt->execute([$petId, $vaccine, $dateGiven, $nextDue, $vet, $notes, $vaccId]);

        // Log activity
        $petName = $row['pet_name'];
        $log = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'vaccination_updated', ?, NOW())
        ");
        $log->execute([$userId, "Vaccination '$vaccine' updated for pet $petName"]);

        echo json_encode(['status' => 'success', 'message' => 'Vaccination updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}



/**
 * ==============
 * DELETE VACCINATION (action=delete)
 * ==============
 */
if ($method === 'POST' && $action === 'delete') {
    $vaccId = $_POST['vaccid'] ?? $_POST['vacc_id'] ?? '';

    if (empty($vaccId)) {
        echo json_encode(['status' => 'error', 'message' => 'Vaccination ID is required.']);
        exit;
    }

    try {
        // Get info for log and ownership check
        $check = $pdo->prepare("
            SELECT v.id, v.vaccine_name, p.name AS pet_name
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE v.id = ? AND p.user_id = ?
        ");
        $check->execute([$vaccId, $userId]);
        $row = $check->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'Vaccination record not found.']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM vaccinations WHERE id = ?");
        $stmt->execute([$vaccId]);

        // Log activity
        $log = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'vaccination_deleted', ?, NOW())
        ");
        $log->execute([$userId, "Vaccination '{$row['vaccine_name']}' deleted for pet {$row['pet_name']}"]);

        echo json_encode(['status' => 'success', 'message' => 'Vaccination record deleted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * ==============
 * DEFAULT: LIST VACCINATIONS (GET or no action)
 * ==============
 * Used by: loadVaccinationHistory(), editVaccination(), updateVaccinationStats()
 */
try {
    $stmt = $pdo->prepare("
        SELECT v.id,
               v.pet_id,
               p.name       AS pet_name,
               v.vaccine_name,
               v.date_given,
               v.next_due_date,
               v.veterinarian,
               v.notes
        FROM vaccinations v
        INNER JOIN pets p ON v.pet_id = p.id
        WHERE p.user_id = ?
        ORDER BY v.date_given DESC, v.id DESC
    ");
    $stmt->execute([$userId]);
    $vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'       => 'success',
        'vaccinations' => $vaccinations
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching vaccination records.']);
}
exit;
