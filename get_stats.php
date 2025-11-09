<?php
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

try {
    // Count total pets
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_pets FROM pets WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalPets = $stmt->fetchColumn();

    // You can add more stats here later
    echo json_encode([
        'status' => 'success',
        'stats' => [
            'total_pets' => $totalPets,
            'vaccinations_due' => 0, // TODO: implement later
            'active_incidents' => 0, // TODO: implement later
            'license_renewals' => 0  // TODO: implement later
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching stats']);
}
?>
