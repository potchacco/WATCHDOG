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
 * REGISTER PET
 * ==============
 */
if ($method === 'POST' && $action === 'register') {
    $name    = $_POST['name']    ?? '';
    $species = $_POST['species'] ?? '';
    $breed   = $_POST['breed']   ?? '';
    $age     = $_POST['age']     ?? '';
    $gender  = $_POST['gender']  ?? '';

    if (empty($name) || empty($species)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet name and species are required.']);
        exit;
    }

    $image_url = null;

    // Image upload - expects field name "pet_image"
    if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/pets/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExt  = strtolower(pathinfo($_FILES['pet_image']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
            exit;
        }

        $newFilename = 'pet_' . uniqid() . '.' . $fileExt;
        $targetPath  = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES['pet_image']['tmp_name'], $targetPath)) {
            // store web path (relative to project root)
            $image_url = 'uploads/pets/' . $newFilename;
        }
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO pets (user_id, name, species, breed, age, gender, image_url, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$userId, $name, $species, $breed, $age, $gender, $image_url]);

        // Log activity
        $logStmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'pet_registered', ?, NOW())
        ");
        $logStmt->execute([$userId, "New pet registered: $name"]);

        echo json_encode(['status' => 'success', 'message' => 'Pet registered successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * ==============
 * UPDATE PET
 * ==============
 */
if ($method === 'POST' && $action === 'update') {
    // Accept both "petid" and "pet_id" to match existing JS
    $pet_id = $_POST['petid'] ?? $_POST['pet_id'] ?? '';

    $name    = $_POST['name']    ?? '';
    $species = $_POST['species'] ?? '';
    $breed   = $_POST['breed']   ?? '';
    $age     = $_POST['age']     ?? '';
    $gender  = $_POST['gender']  ?? '';

    if (empty($pet_id) || empty($name) || empty($species)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet ID, name and species are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE pets
               SET name = ?, species = ?, breed = ?, age = ?, gender = ?
             WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$name, $species, $breed, $age, $gender, $pet_id, $userId]);

        // Log activity
        $logStmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'pet_updated', ?, NOW())
        ");
        $logStmt->execute([$userId, "Pet updated: $name"]);

        echo json_encode(['status' => 'success', 'message' => 'Pet updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * ==============
 * DELETE PET
 * ==============
 */
if ($method === 'POST' && $action === 'delete') {
    $pet_id = $_POST['pet_id'] ?? '';

    if (empty($pet_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet ID is required.']);
        exit;
    }

    try {
        // Get image URL before deleting
        $stmt = $pdo->prepare("SELECT image_url, name FROM pets WHERE id = ? AND user_id = ?");
        $stmt->execute([$pet_id, $userId]);
        $pet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pet && !empty($pet['image_url'])) {
            $imagePath = __DIR__ . '/' . $pet['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ? AND user_id = ?");
        $stmt->execute([$pet_id, $userId]);

        // Log activity
        $petName = $pet['name'] ?? 'Unknown pet';
        $logStmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, activity_type, description, created_at)
            VALUES (?, 'pet_deleted', ?, NOW())
        ");
        $logStmt->execute([$userId, "Pet deleted: $petName"]);

        echo json_encode(['status' => 'success', 'message' => 'Pet deleted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

/**
 * ==============
 * DEFAULT: LIST PETS (GET or no action)
 * ==============
 */
try {
    // IMPORTANT: alias image_url AS imageurl so JS can use pet.imageurl
    $stmt = $pdo->prepare("
        SELECT 
            id,
            user_id,
            name,
            species,
            breed,
            age,
            gender,
            image_url,
            image_url AS imageurl,
            created_at
        FROM pets
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userId]);
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'pets' => $pets]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching pets.']);
}
exit;
