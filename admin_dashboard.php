<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WATCHDOG - Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="admin-logo">
                    <i class="fas fa-shield-dog"></i>
                    <span>ADMIN</span>
                </div>
                <button class="sidebar-close" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-link active" data-section="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-link" data-section="all-pets">
                    <i class="fas fa-paw"></i>
                    <span>All Pets</span>
                </a>
                <a href="#" class="nav-link" data-section="all-owners">
                    <i class="fas fa-users"></i>
                    <span>Pet Owners</span>
                </a>
                <a href="#" class="nav-link" data-section="incidents">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Incidents</span>
                </a>
                <a href="#" class="nav-link" data-section="vaccinations">
                    <i class="fas fa-syringe"></i>
                    <span>Vaccinations</span>
                </a>
                <a href="#" class="nav-link" data-section="reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
                <a href="logout.php" class="nav-link logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="admin-title">Barangay Dog Monitoring System</h1>
                <div class="admin-profile">
                    <span class="admin-name">
                        <i class="fas fa-user-shield"></i>
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Pets</h3>
                        <p class="stat-value" id="adminTotalPets">0</p>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pet Owners</h3>
                        <p class="stat-value" id="adminTotalOwners">0</p>
                    </div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Incidents</h3>
                        <p class="stat-value" id="adminActiveIncidents">0</p>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Vaccinations Due</h3>
                        <p class="stat-value" id="adminVaccinationsDue">0</p>
                    </div>
                </div>
            </div>

            <!-- Dynamic Content Area -->
            <div id="adminContent" class="admin-content">
                <!-- Content will be loaded here by JavaScript -->
                <div class="welcome-section">
                    <h2>Welcome to Admin Dashboard!</h2>
                    <p>Select a section from the sidebar to get started.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="admin_dashboard.js"></script>
</body>
</html>
