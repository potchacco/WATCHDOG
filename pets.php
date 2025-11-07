<?php
require_once 'check_session.php';
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'register') {
        $name = trim($_POST['name']);
        $species = trim($_POST['species']);
        $breed = trim($_POST['breed']);
        $age = intval($_POST['age']);
        $gender = $_POST['gender'];
        $user_id = $_SESSION['user_id'];
        $image_url = null;

        if (empty($name) || empty($species)) {
            echo json_encode(['status' => 'error', 'message' => 'Name and species are required']);
            exit;
        }

        // Handle image upload
        if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['petImage'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($file_extension, $allowed_extensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF allowed']);
                exit;
            }

            // Create uploads directory if it doesn't exist
            $upload_dir = __DIR__ . '../uploads/pets';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $new_filename = uniqid('pet_') . '.' . $file_extension;
            $upload_path = $upload_dir . '/' . $new_filename;
            $image_url = 'uploads/pets' . $new_filename;  // URL path for database

            // Check if file was uploaded successfully
            if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                $error = error_get_last();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to upload image: ' . ($error ? $error['message'] : 'Unknown error'),
                    'debug' => [
                        'upload_path' => $upload_path,
                        'tmp_name' => $file['tmp_name'],
                        'permissions' => [
                            'upload_dir_exists' => file_exists($upload_dir),
                            'upload_dir_writable' => is_writable($upload_dir),
                        ]
                    ]
                ]);
                exit;
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO pets (user_id, name, species, breed, age, gender, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$user_id, $name, $species, $breed, $age, $gender, $image_url])) {
                echo json_encode(['status' => 'success', 'message' => 'Pet registered successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to register pet']);
            }
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
        }
        exit;
    }
}

// Get all pets for the current user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, COUNT(v.id) as vaccination_count 
            FROM pets p 
            LEFT JOIN vaccinations v ON p.id = v.pet_id 
            WHERE p.user_id = ? 
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'pets' => $pets]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch pets']);
    }
    exit;
}
?>