<?php
require_once 'check_session.php';

// Check if user is admin
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Pet Registration and Tracking Platform - Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=<?php echo time(); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="admin-dashboard">
        <!-- Top Navigation Bar -->
        <nav class="admin-navbar">
            <div class="navbar-left">
                <!-- Hamburger for Mobile -->
        <button class="admin-hamburger" id="adminHamburger" style="display: none;">
            <span></span>
            <span></span>
            <span></span>
        </button>
                <div class="admin-logo">
                    <i class="fas fa-paw"></i>
                    <span>Digital Pet Registration and Tracking Platform Admin</span>
                </div>
            </div>
            <div class="navbar-right">
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=667eea&color=fff&size=40" alt="Admin">
                    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </div>
                <a href="#" class="btn-logout" id="adminLogoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>

        <div class="admin-layout">
            <!-- Sidebar -->
            <aside class="admin-sidebar">
                <ul class="admin-menu">
                    <li><a href="#" class="active" data-section="overview">
                        <i class="fas fa-home"></i> Overview
                    </a></li>
                    <li><a href="#" data-section="users">
                        <i class="fas fa-users"></i> User Management
                    </a></li>
                    <li><a href="#" data-section="pets">
                        <i class="fas fa-dog"></i> All Pets
                    </a></li>
                    <li><a href="#" data-section="incidents">
                        <i class="fas fa-exclamation-triangle"></i> All Incidents
                    </a></li>
                    <li><a href="#" data-section="vaccinations">
                        <i class="fas fa-syringe"></i> All Vaccinations
                    </a></li>
                    <li><a href="#" data-section="reports">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a></li>
                    <li><a href="#" data-section="settings">
                        <i class="fas fa-cog"></i> Settings
                    </a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="admin-main" id="adminMainContent">
                <!-- Overview Section (Default) -->
                <div class="admin-header">
                    <h1>System Overview</h1>
                    <p>Monitor and manage your entire pet monitoring system</p>
                </div>

                <!-- Stats Grid -->
                <div class="admin-stats-grid">
                    <div class="admin-stat-card blue">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalUsers">0</h3>
                            <p>Total Users</p>
                        </div>
                    </div>

                    <div class="admin-stat-card green">
                        <div class="stat-icon">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalPets">0</h3>
                            <p>Total Pets</p>
                        </div>
                    </div>

                    <div class="admin-stat-card orange">
                        <div class="stat-icon" id="transparent">
                            <i class="fa-solid fa-triangle-exclamation fa-lg" style="color: #ff1900;"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalIncidents">0</h3>
                            <p>Total Incidents</p>
                        </div>
                    </div>

                    <div class="admin-stat-card purple">
                        <div class="stat-icon">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalVaccinations">0</h3>
                            <p>Vaccinations</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="admin-section">
                    <h2>Recent System Activity</h2>
                    <div id="recentActivity" class="activity-feed">
                        <p>Loading...</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

        <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-icon">
            <i class="fas fa-paw"></i>
        </div>
        <div class="loading-spinner"></div>
        <div class="loading-text">Logging out...</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="admin_dashboard.js?v=<?php echo time(); ?>"></script>
</body>
</html>