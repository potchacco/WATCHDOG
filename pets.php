<?php
// Set header FIRST before any output
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    $name = $_POST['name'] ?? '';
    $species = $_POST['species'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';

    if (empty($name) || empty($species)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet name and species are required.']);
        exit;
    }

    $image_url = null;

    // Handle image upload
    if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/pets/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            chmod($uploadDir, 0777);
        }

        $fileExt = strtolower(pathinfo($_FILES['petImage']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
            exit;
        }

        $newFilename = 'pet_' . uniqid() . '.' . $fileExt;
        $targetPath = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES['petImage']['tmp_name'], $targetPath)) {
            $image_url = 'uploads/pets/' . $newFilename;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            exit;
        }
    }

    // INSERT INTO DATABASE
    try {
        $stmt = $pdo->prepare("INSERT INTO pets (user_id, name, species, breed, age, gender, image_url, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $name, $species, $breed, $age, $gender, $image_url]);
        echo json_encode(['status' => 'success', 'message' => 'Pet registered successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// GET request: Fetch pets
try {
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'pets' => $pets]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching pets.']);
}
?>
