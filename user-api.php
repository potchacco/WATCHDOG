<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

/**
 * STATS FOR DASHBOARD
 */
if ($action === 'stats') {
    try {
        // Total pets for this user
        $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM pets WHERE user_id = ?");
        $stmt->execute([$userId]);
        $totalPets = (int)$stmt->fetchColumn();

        // Total incidents for this user
        $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM incidents WHERE user_id = ?");
        $stmt->execute([$userId]);
        $totalIncidents = (int)$stmt->fetchColumn();

        // Vaccinations due: join vaccinations -> pets so we filter by owner
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS c 
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE p.user_id = ?
              AND v.next_due_date IS NOT NULL
              AND v.next_due_date <= CURDATE()
        ");
        $stmt->execute([$userId]);
        $vaccinationsDue = (int)$stmt->fetchColumn();

        // Total vaccinations for this user
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS c
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE p.user_id = ?
        ");
        $stmt->execute([$userId]);
        $totalVaccinations = (int)$stmt->fetchColumn();

        // Vaccinations this month
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS c
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE p.user_id = ?
              AND v.date_given >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
              AND v.date_given <  DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
        ");
        $stmt->execute([$userId]);
        $vaccinationsThisMonth = (int)$stmt->fetchColumn();

        // Pets that have at least one vaccination
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT v.pet_id) AS c
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE p.user_id = ?
        ");
        $stmt->execute([$userId]);
        $vaccinatedPets = (int)$stmt->fetchColumn();

        // Appointments placeholder
        $appointments         = 0;
        $upcomingAppointments = 0;

        echo json_encode([
            'status' => 'success',
            'stats'  => [
                'total_pets'              => $totalPets,
                'total_incidents'         => $totalIncidents,
                'vaccinations_due'        => $vaccinationsDue,
                'appointments'            => $appointments,
                'total_vaccinations'      => $totalVaccinations,
                'vaccinations_this_month' => $vaccinationsThisMonth,
                'vaccinated_pets'         => $vaccinatedPets,
                'upcoming_appointments'   => $upcomingAppointments,
            ],
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

/**
 * RECENT ACTIVITY FEED
 */
if ($action === 'recent_activity') {
    try {
        $events = [];

        // 1) Incidents for this user
        $stmt = $pdo->prepare("
            SELECT id, incident_type, description, status, severity, incident_date 
            FROM incidents
            WHERE user_id = ?
            ORDER BY incident_date DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $events[] = [
                'type'        => 'incident',
                'title'       => $row['incident_type'],
                'description' => $row['description'],
                'status'      => $row['status'],
                'severity'    => $row['severity'],
                'date'        => $row['incident_date'],
            ];
        }

        // 2) Vaccinations for this user's pets
        $stmt = $pdo->prepare("
            SELECT v.id, v.vaccine_name, v.date_given, p.name AS pet_name
            FROM vaccinations v
            INNER JOIN pets p ON v.pet_id = p.id
            WHERE p.user_id = ?
            ORDER BY v.date_given DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $events[] = [
                'type'        => 'vaccination',
                'title'       => 'Vaccination: ' . $row['vaccine_name'],
                'description' => 'For pet ' . $row['pet_name'],
                'status'      => null,
                'severity'    => null,
                'date'        => $row['date_given'],
            ];
        }

        // 3) Recently added pets
        $stmt = $pdo->prepare("
            SELECT id, name, species, breed, created_at
            FROM pets
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $events[] = [
                'type'        => 'pet',
                'title'       => 'New Pet: ' . $row['name'],
                'description' => trim($row['species'] . ' ' . ($row['breed'] ?? '')),
                'status'      => null,
                'severity'    => null,
                'date'        => $row['created_at'],
            ];
        }

        // Sort all events by date DESC, take top 10
        usort($events, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });
        $events = array_slice($events, 0, 10);

        echo json_encode([
            'status' => 'success',
            'events' => $events,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

// Fallback for unknown actions
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
