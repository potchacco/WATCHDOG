<?php
require_once 'check_session.php';

header('Content-Type: application/json');

// Only allow residents
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'resident') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Assistant is only available to resident users.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$question = strtolower(trim($input['question'] ?? ''));

if ($question === '') {
    echo json_encode(['status' => 'error', 'message' => 'Empty question']);
    exit;
}

// Simple rule-based helper (no external AI)
$answer = get_help_answer($question);

echo json_encode([
    'status' => 'success',
    'answer' => $answer,
]);

// ----------------- HELPER FUNCTION -----------------
function get_help_answer(string $q): string
{
    // PET REGISTRATION
    if (contains($q, ['register pet', 'add pet', 'pet registration', 'new pet'])) {
        return "To register a pet: go to your dashboard, click the Pets or Register Pet button, fill in the pet details (name, species, breed, age, gender), upload a photo if needed, then click Submit. Your pet will appear in your pet list after saving.";
    }

    // VACCINATIONS
    if (contains($q, ['add vaccination', 'vaccination record', 'vaccine', 'vaccination'])) {
        return "To add a vaccination: open your dashboard, go to the Vaccinations section (or your pet's details), click Add Vaccination, fill in the vaccine name, date given, next due date, and notes, then save. The record will appear in the vaccination list and update your stats.";
    }

    // INCIDENT REPORTING
    if (contains($q, ['report incident', 'report bite', 'stray', 'incident'])) {
        return "To report an incident: go to the Incidents section or the incident form on your dashboard, enter the animal type, incident type (e.g. bite or stray), location, description, severity, and optionally upload a photo, then submit. The incident will be saved and visible in your incident list.";
    }

    // ACCOUNT STATUS
    if (contains($q, ['pending', 'approved', 'rejected', 'account status'])) {
        return "Account statuses: Pending means your registration is waiting for barangay approval. Approved means you can use all resident features. Rejected means your registration was not accepted; you may contact the barangay office to correct or resubmit your details.";
    }

    // INCIDENT STATUS
    if (contains($q, ['incident status', 'open incident', 'closed incident', 'resolved'])) {
        return "Incident statuses: Open means the report is recorded and not yet resolved. Resolved/Closed means the barangay has handled the case. Some systems may also show In Progress or Pending review depending on how the officials manage incidents.";
    }

    // VACCINATION DUE / OVERDUE
    if (contains($q, ['vaccination due', 'overdue', 'due soon', 'next due date'])) {
        return "Vaccination due/overdue: the system tracks the next due date you set for each vaccine. If today is close to that date, it may be marked as due soon; if the date has passed, it becomes overdue. This helps you remember when to bring your pet for the next shot.";
    }

    // DASHBOARD / STATS
    if (contains($q, ['dashboard', 'stats', 'statistics', 'summary'])) {
        return "The dashboard shows a summary of your pets, vaccinations, and incidents. From there you can quickly access pet registration, add vaccination records, and report incidents. It helps you see your responsibilities and recent activity at a glance.";
    }

    // SCOPE / LIMITATIONS
    if (contains($q, ['what can this system do', 'limitations', 'scope', 'what is this system'])) {
        return "This system is for barangay-level pet registration and tracking. It manages pet records, vaccinations, and incident reports. It does not provide medical treatment, real-time GPS tracking, or automatic SMS/email reminders. It is mainly for record-keeping and helping barangay officials monitor pet-related activities.";
    }

    // LOGIN / REGISTER
    if (contains($q, ['login', 'log in', 'sign in', 'register account', 'create account'])) {
        return "To use the system, you first create an account as a resident, then wait for barangay approval. After approval, you can log in with your email and password to register pets, add vaccinations, and report incidents. If you have trouble logging in, check your email and password or contact the barangay office.";
    }

    // DEFAULT RESPONSE
    return "I can help explain how to use this system—for example: how to register pets, add vaccinations, report incidents, or understand statuses like pending/approved or open/closed incidents. Please try asking your question about those topics.";
}

function contains(string $text, array $keywords): bool
{
    foreach ($keywords as $k) {
        if (strpos($text, $k) !== false) {
            return true;
        }
    }
    return false;
}
