<?php
header('Content-Type: application/json');

require_once 'check_session.php';
require_once 'config/database.php';

try {
    // Count total pets for this logged-in user
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_pets FROM pets WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalPets = (int)$stmt->fetchColumn();

    echo json_encode([
        'status' => 'success',
        'stats'  => [
            'total_pets'         => $totalPets,
            'total_vaccinations' => 0, // TODO: implement later
            'total_incidents'    => 0, // TODO: implement later
            'active_incidents'   => 0, // TODO: implement later
            'appointments'       => 0, // TODO: implement later
        ],
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching stats']);
}

?>
