<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

// ========= CONFIG FOR INCIDENT IMAGE UPLOAD =========
$incidentUploadDir = __DIR__ . '/uploads/incidents/';   // filesystem path
$incidentUploadWeb = 'uploads/incidents/';              // path stored in DB / used by frontend

// Ensure upload directory exists
if (!is_dir($incidentUploadDir)) {
    @mkdir($incidentUploadDir, 0775, true);
}

// Small helper for safe JSON responses
function json_response($arr)
{
    echo json_encode($arr);
    exit;
}

// ========= ADD Incident =========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $pet_id        = !empty($_POST['pet_id']) ? $_POST['pet_id'] : null; // optional
    $animal_species = $_POST['animal_species'] ?? null;
    $incident_type  = $_POST['incident_type'] ?? '';
    $description    = $_POST['description'] ?? '';
    $location       = $_POST['location'] ?? '';
    $severity       = $_POST['severity'] ?? 'Medium';
    $incident_date  = $_POST['incident_date'] ?? '';

    if (empty($incident_type) || empty($description) || empty($incident_date)) {
        json_response([
            'status'  => 'error',
            'message' => 'Incident type, description, and date are required.'
        ]);
    }

    // ===== HANDLE IMAGE UPLOAD (optional) =====
    $incidentImagePath = null;

    if (!empty($_FILES['incident_image']['name'] ?? '')) {
        $file      = $_FILES['incident_image'];
        $fileError = $file['error'];

        if ($fileError === UPLOAD_ERR_OK) {
            $tmpName  = $file['tmp_name'];
            $fileName = $file['name'];

            // Basic validation: size + extension
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                json_response([
                    'status'  => 'error',
                    'message' => 'Incident image is too large. Maximum size is 5MB.'
                ]);
            }

            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed, true)) {
                json_response([
                    'status'  => 'error',
                    'message' => 'Invalid image format. Allowed: jpg, jpeg, png, gif, webp.'
                ]);
            }

            // Generate unique filename
            $newName = 'incident_' . $_SESSION['user_id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destFs  = $incidentUploadDir . $newName;          // filesystem
            $destWeb = $incidentUploadWeb . $newName;          // DB / frontend

            if (!move_uploaded_file($tmpName, $destFs)) {
                json_response([
                    'status'  => 'error',
                    'message' => 'Failed to save incident image on the server.'
                ]);
            }

            $incidentImagePath = $destWeb;
        } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
            // Some upload error other than "no file"
            json_response([
                'status'  => 'error',
                'message' => 'Error uploading incident image (code ' . $fileError . ').'
            ]);
        }
    }

    try {
        // Make sure your DB table `incidents` has an `image_path` column (see SQL below)
        $stmt = $pdo->prepare(
            "INSERT INTO incidents 
                (user_id, pet_id, animal_species, incident_type, description, location, severity, incident_date, image_path, status, created_at)
             VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Open', NOW())"
        );
        $stmt->execute([
            $_SESSION['user_id'],
            $pet_id,           // usually null
            $animal_species,
            $incident_type,
            $description,
            $location,
            $severity,
            $incident_date,
            $incidentImagePath
        ]);

        // Log activity
        $logStmt = $pdo->prepare(
            "INSERT INTO activity_log (user_id, activity_type, description, created_at) 
             VALUES (?, 'incident_reported', ?, NOW())"
        );
        $logStmt->execute([
            $_SESSION['user_id'],
            "New incident reported: $incident_type"
        ]);

        json_response([
            'status'  => 'success',
            'message' => 'Incident reported successfully!'
        ]);
    } catch (PDOException $e) {
        json_response([
            'status'  => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

// ========= UPDATE Incident (status/notes only) =========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $incident_id = $_POST['incident_id'] ?? '';
    $status      = $_POST['status'] ?? '';
    $notes       = $_POST['notes'] ?? '';

    if (empty($incident_id)) {
        json_response([
            'status'  => 'error',
            'message' => 'Incident ID is required.'
        ]);
    }

    try {
        $resolved_date = ($status === 'Resolved' || $status === 'Closed')
            ? date('Y-m-d H:i:s')
            : null;

        $stmt = $pdo->prepare(
            "UPDATE incidents 
             SET status = ?, notes = ?, resolved_date = ? 
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([
            $status,
            $notes,
            $resolved_date,
            $incident_id,
            $_SESSION['user_id']
        ]);

        // Log activity
        $logStmt = $pdo->prepare(
            "INSERT INTO activity_log (user_id, activity_type, description, created_at) 
             VALUES (?, 'incident_updated', ?, NOW())"
        );
        $logStmt->execute([
            $_SESSION['user_id'],
            "Incident status updated to: $status"
        ]);

        json_response([
            'status'  => 'success',
            'message' => 'Incident updated successfully!'
        ]);
    } catch (PDOException $e) {
        json_response([
            'status'  => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

// ========= DELETE Incident (and its image file) =========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $incident_id = $_POST['incident_id'] ?? '';

    if (empty($incident_id)) {
        json_response([
            'status'  => 'error',
            'message' => 'Incident ID is required.'
        ]);
    }

    try {
        // First fetch to know if there is an image
        $fetchStmt = $pdo->prepare(
            "SELECT image_path 
             FROM incidents 
             WHERE id = ? AND user_id = ?"
        );
        $fetchStmt->execute([$incident_id, $_SESSION['user_id']]);
        $row = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        // Delete DB row
        $stmt = $pdo->prepare(
            "DELETE FROM incidents 
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$incident_id, $_SESSION['user_id']]);

        // Attempt to delete the image file if it exists
        if ($row && !empty($row['image_path'])) {
            $fsPath = __DIR__ . '/' . ltrim($row['image_path'], '/');
            if (is_file($fsPath)) {
                @unlink($fsPath);
            }
        }

        json_response([
            'status'  => 'success',
            'message' => 'Incident deleted successfully!'
        ]);
    } catch (PDOException $e) {
        json_response([
            'status'  => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

// ========= GET Incidents =========
try {
    $stmt = $pdo->prepare(
        "SELECT i.*, p.name AS pet_name 
         FROM incidents i
         LEFT JOIN pets p ON i.pet_id = p.id 
         WHERE i.user_id = ? 
         ORDER BY i.incident_date DESC"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response([
        'status'    => 'success',
        'incidents' => $incidents
    ]);
} catch (PDOException $e) {
    json_response([
        'status'  => 'error',
        'message' => 'Error fetching incidents: ' . $e->getMessage()
    ]);
}
