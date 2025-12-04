<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action) {
        case 'stats':
            // Get system-wide statistics
            $stats = [];

            // Total users
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'resident'");
            $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Total pets
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM pets");
            $stats['total_pets'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Total incidents
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM incidents");
            $stats['total_incidents'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Total vaccinations
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM vaccinations");
            $stats['total_vaccinations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            echo json_encode(['status' => 'success', 'stats' => $stats]);
            break;

        case 'recent_activity':
            // Get recent system activity
            $stmt = $pdo->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 10");
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Map activity types
            foreach ($activities as &$activity) {
                if (strpos($activity['activity_type'], 'pet') !== false) {
                    $activity['type'] = 'pet';
                } elseif (strpos($activity['activity_type'], 'user') !== false) {
                    $activity['type'] = 'user';
                } else {
                    $activity['type'] = 'incident';
                }
            }

            echo json_encode(['status' => 'success', 'activities' => $activities]);
            break;

                case 'get_users':
            // Get all resident users with pet count, pending first
            $stmt = $pdo->query("
                SELECT u.*, COUNT(p.id) as pet_count 
                FROM users u 
                LEFT JOIN pets p ON u.id = p.user_id 
                WHERE u.role = 'resident'
                GROUP BY u.id 
                ORDER BY u.status = 'pending' DESC, u.created_at DESC
            ");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'users' => $users]);
            break;


        case 'delete_user':
            $userId = $_POST['user_id'] ?? '';

            if (empty($userId)) {
                echo json_encode(['status' => 'error', 'message' => 'User ID required']);
                exit;
            }

            // Delete user and all related data
            $pdo->beginTransaction();

            // Delete vaccinations for user's pets
            $pdo->prepare("
                DELETE v FROM vaccinations v 
                INNER JOIN pets p ON v.pet_id = p.id 
                WHERE p.user_id = ?
            ")->execute([$userId]);

            // Delete user's pets
            $pdo->prepare("DELETE FROM pets WHERE user_id = ?")->execute([$userId]);

            // Delete user's incidents
            $pdo->prepare("DELETE FROM incidents WHERE user_id = ?")->execute([$userId]);

            // Delete user
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
            break;

                    case 'update_user_status':
            $userId = $_POST['user_id'] ?? '';
            $status = $_POST['status'] ?? '';

            if (empty($userId) || !in_array($status, ['pending', 'approved', 'rejected'], true)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
                exit;
            }

            // Optional safety: prevent demoting/deleting yourself or other admins
            // $check = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            // $check->execute([$userId]);
            // $u = $check->fetch(PDO::FETCH_ASSOC);
            // if ($u && $u['role'] === 'admin') {
            //     echo json_encode(['status' => 'error', 'message' => 'Cannot change admin status']);
            //     exit;
            // }

            $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            $stmt->execute([$status, $userId]);

            echo json_encode(['status' => 'success', 'message' => 'User status updated']);
            break;


        case 'get_all_pets':
            // Get all pets with owner info
            $stmt = $pdo->query("
                SELECT 
                    p.id,
                    p.user_id,
                    p.name,
                    p.species,
                    p.breed,
                    p.age,
                    p.gender,
                    p.image_url,
                    p.image_url AS imageurl,
                    p.created_at,
                    u.name  AS ownername,
                    u.email AS owneremail
                FROM pets p
                INNER JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC
            ");
            $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'pets' => $pets]);
            break;

        case 'get_all_incidents':
            // Get all incidents with reporter info
            $stmt = $pdo->query("
                SELECT i.*, u.name as reporter_name, u.email as reporter_email,
                       p.name as pet_name
                FROM incidents i 
                INNER JOIN users u ON i.user_id = u.id 
                LEFT JOIN pets p ON i.pet_id = p.id 
                ORDER BY i.incident_date DESC
            ");
            $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'incidents' => $incidents]);
            break;

        case 'delete_pet':
            $petId = $_POST['pet_id'] ?? '';

            if (empty($petId)) {
                echo json_encode(['status' => 'error', 'message' => 'Pet ID required']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Delete vaccinations for this pet
                $stmt = $pdo->prepare("DELETE FROM vaccinations WHERE pet_id = ?");
                $stmt->execute([$petId]);

                // Delete the pet
                $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
                $stmt->execute([$petId]);

                $pdo->commit();

                echo json_encode(['status' => 'success', 'message' => 'Pet deleted successfully']);
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            }
            break;

        case 'update_incident_status':
            $incidentId = $_POST['incident_id'] ?? '';
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';

            if (empty($incidentId) || empty($status)) {
                echo json_encode(['status' => 'error', 'message' => 'Incident ID and status are required']);
                exit;
            }

            try {
                $resolvedDate = null;
                if ($status === 'Resolved' || $status === 'Closed') {
                    $resolvedDate = date('Y-m-d H:i:s');
                }

                $stmt = $pdo->prepare("
                    UPDATE incidents 
                    SET status = ?, notes = ?, resolved_date = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$status, $notes, $resolvedDate, $incidentId]);

                // Log the activity
                $logStmt = $pdo->prepare("
                    INSERT INTO activity_log (user_id, activity_type, description, created_at) 
                    VALUES (?, 'incident_updated', ?, NOW())
                ");
                $logStmt->execute([$_SESSION['user_id'], "Admin updated incident #$incidentId to status: $status"]);

                echo json_encode(['status' => 'success', 'message' => 'Incident status updated successfully']);
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            }
            break;

        case 'get_all_vaccinations':
            // Get all vaccinations with pet and owner info (using NEW column names)
            $stmt = $pdo->query("
                SELECT v.*, 
                       p.name AS pet_name, 
                       u.name AS owner_name, 
                       u.email AS owner_email,
                       CASE 
                           WHEN v.next_due_date IS NOT NULL AND v.next_due_date < CURDATE() THEN 1 
                           ELSE 0 
                       END AS is_overdue,
                       CASE 
                           WHEN v.next_due_date IS NOT NULL 
                                AND v.next_due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                                THEN 1 
                           ELSE 0 
                       END AS is_due_soon,
                       v.warning_status,
                       v.warning_note,
                       v.warning_date
                FROM vaccinations v
                INNER JOIN pets p ON v.pet_id = p.id
                INNER JOIN users u ON v.user_id = u.id
                ORDER BY v.date_given DESC
            ");
            $vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate stats
            $stats = [
                'vaccinated_pets' => 0,
                'due_soon'        => 0,
                'overdue'         => 0
            ];

            $vaccinatedPets = [];
            foreach ($vaccinations as $vacc) {
                if (!in_array($vacc['pet_id'], $vaccinatedPets, true)) {
                    $vaccinatedPets[] = $vacc['pet_id'];
                }
                if ($vacc['is_overdue']) {
                    $stats['overdue']++;
                } elseif ($vacc['is_due_soon']) {
                    $stats['due_soon']++;
                }
            }
            $stats['vaccinated_pets'] = count($vaccinatedPets);

            echo json_encode([
                'status'        => 'success',
                'vaccinations'  => $vaccinations,
                'stats'         => $stats
            ]);
            break;

        case 'send_warning':
            $vaccId = $_POST['vacc_id'] ?? '';
            $note   = $_POST['note'] ?? '';

            if (empty($vaccId)) {
                echo json_encode(['status' => 'error', 'message' => 'Vaccination ID required']);
                exit;
            }

            try {
                $stmt = $pdo->prepare("
                    UPDATE vaccinations 
                    SET warning_status = 'Warning Sent',
                        warning_note   = ?,
                        warning_date   = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$note, $vaccId]);

                // Log warning
                $logStmt = $pdo->prepare("
                    INSERT INTO activity_log (user_id, activity_type, description, created_at)
                    VALUES (?, 'vaccination_warning_sent', ?, NOW())
                ");
                $logStmt->execute([
                    $_SESSION['user_id'],
                    "Warning sent for vaccination id $vaccId" . ($note ? " (Note: $note)" : "")
                ]);

                echo json_encode(['status' => 'success', 'message' => 'Warning sent successfully']);
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            }
            break;

        case 'get_report':
            $type = $_GET['type'] ?? '';
            $chartData = ['labels' => [], 'data' => [], 'label' => ''];

            if ($type === 'registrations') {
                // Pet registrations by month (last 6 months)
                $stmt = $pdo->query("
                    SELECT DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count 
                    FROM pets 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY created_at ASC
                ");
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $chartData['label'] = 'Pet Registrations';
                foreach ($results as $row) {
                    $chartData['labels'][] = $row['month'];
                    $chartData['data'][]   = (int)$row['count'];
                }

            } elseif ($type === 'vaccinations') {
                // Vaccinations by month (last 6 months) using NEW column date_given
                $stmt = $pdo->query("
                    SELECT DATE_FORMAT(date_given, '%b %Y') as month, COUNT(*) as count 
                    FROM vaccinations 
                    WHERE date_given >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                    GROUP BY DATE_FORMAT(date_given, '%Y-%m')
                    ORDER BY date_given ASC
                ");
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $chartData['label'] = 'Vaccinations Given';
                foreach ($results as $row) {
                    $chartData['labels'][] = $row['month'];
                    $chartData['data'][]   = (int)$row['count'];
                }

            } elseif ($type === 'incidents') {
                // Incidents by status
                $stmt = $pdo->query("
                    SELECT status, COUNT(*) as count 
                    FROM incidents 
                    GROUP BY status
                    ORDER BY count DESC
                ");
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $chartData['label'] = 'Incidents by Status';
                foreach ($results as $row) {
                    $chartData['labels'][] = $row['status'];
                    $chartData['data'][]   = (int)$row['count'];
                }
            }

            echo json_encode([
                'status'     => 'success',
                'chart_data' => $chartData
            ]);
            break;

        case 'export_data':
            // Export all data as CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="watchdog_export_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // Export Users
            fputcsv($output, ['=== USERS ===']);
            fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Created At']);

            $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row);
            }

            fputcsv($output, []);

            // Export Pets
            fputcsv($output, ['=== PETS ===']);
            fputcsv($output, ['ID', 'Name', 'Species', 'Breed', 'Age', 'Gender', 'Owner', 'Created At']);

            $stmt = $pdo->query("
                SELECT p.id, p.name, p.species, p.breed, p.age, p.gender, u.name as owner, p.created_at 
                FROM pets p 
                INNER JOIN users u ON p.user_id = u.id
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row);
            }

            fputcsv($output, []);

            // Export Vaccinations (NEW column names)
            fputcsv($output, ['=== VACCINATIONS ===']);
            fputcsv($output, ['ID', 'Pet', 'Vaccine Name', 'Date Given', 'Next Due', 'Created At']);

            $stmt = $pdo->query("
                SELECT v.id, p.name as pet, v.vaccine_name, v.date_given, v.next_due_date, v.created_at 
                FROM vaccinations v 
                INNER JOIN pets p ON v.pet_id = p.id
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row);
            }

            fputcsv($output, []);

            // Export Incidents
            fputcsv($output, ['=== INCIDENTS ===']);
            fputcsv($output, ['ID', 'Type', 'Description', 'Status', 'Severity', 'Reporter', 'Date', 'Created At']);

            $stmt = $pdo->query("
                SELECT i.id, i.incident_type, i.description, i.status, i.severity, 
                       u.name as reporter, i.incident_date, i.created_at 
                FROM incidents i 
                INNER JOIN users u ON i.user_id = u.id
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row);
            }

            fclose($output);
            exit;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
