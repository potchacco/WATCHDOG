<?php
// dashboard_stats.php
require_once 'check_session.php';           // already used in dashboard.php
require_once 'config/database.php';                  // ⬅️ change to your actual DB connection file

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Not logged in',
    ]);
    exit;
}

$userId = (int) $_SESSION['user_id'];

// Helper to run a single COUNT(*) query safely
function get_count(mysqli $conn, string $sql, int $userId): int {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return 0;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $row && isset($row['c']) ? (int)$row['c'] : 0;
}

// Total pets for this resident
$totalPets = get_count(
    $conn,
    "SELECT COUNT(*) AS c FROM pets WHERE user_id = ?",
    $userId
);

// Total vaccination records for this resident
$totalVaccinations = get_count(
    $conn,
    "SELECT COUNT(*) AS c FROM vaccinations WHERE user_id = ?",
    $userId
);

// Total incidents reported by this resident (all statuses)
$totalIncidents = get_count(
    $conn,
    "SELECT COUNT(*) AS c FROM incidents WHERE user_id = ?",
    $userId
);

// Active incidents = anything not Resolved/Closed
$activeIncidents = get_count(
    $conn,
    "SELECT COUNT(*) AS c 
     FROM incidents 
     WHERE user_id = ? 
       AND (status IS NULL OR status NOT IN ('Resolved','Closed'))",
    $userId
);

// Appointments = upcoming next_due_date (acts as schedule)
$appointments = 0;
$stmt = $conn->prepare("
    SELECT COUNT(*) AS c 
    FROM vaccinations 
    WHERE user_id = ? 
      AND next_due_date IS NOT NULL 
      AND next_due_date >= CURDATE()
");
if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $appointments = $row && isset($row['c']) ? (int)$row['c'] : 0;
    $stmt->close();
}

echo json_encode([
    'status' => 'success',
    'stats'  => [
        'total_pets'         => $totalPets,
        'total_vaccinations' => $totalVaccinations,
        'total_incidents'    => $totalIncidents,
        'active_incidents'   => $activeIncidents,
        'appointments'       => $appointments,
    ],
]);
