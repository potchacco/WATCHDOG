<?php
require_once 'check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WATCHDOG - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard">
        <!-- Hamburger Menu -->
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- MODERN SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-paw"></i>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="#" class="active" data-section="dashboard">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-section="pets">
                        <i class="fas fa-dog"></i>
                        <span>My Pets</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-section="vaccinations">
                        <i class="fas fa-syringe"></i>
                        <span>Vaccinations</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-section="incidents">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Incidents</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-section="analytics">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-section="settings">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content" id="mainContent">
            <!-- HERO WELCOME BANNER -->
            <div class="hero-banner">
                <div class="hero-content">
                    <div class="hero-text">
                        <div class="date-badge">
                            <i class="fas fa-calendar"></i>
                            <?php echo date('M d, Y • g:i a'); ?>
                        </div>
                        <h1>Good Day, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</h1>
                        <p>Have a Nice Managing Day!</p>
                    </div>
                    <div class="hero-illustration">
                        <i class="fas fa-dog"></i>
                        <i class="fas fa-bone"></i>
                        <i class="fas fa-paw"></i>
                    </div>
                </div>
            </div>

            <!-- STATS CARDS ROW -->
            <div class="stats-row">
                <div class="stat-card-modern blue">
                    <div class="stat-header-modern">
                        <div class="stat-info">
                            <span class="stat-label">Total Pets</span>
                            <h2 class="stat-value">0</h2>
                            <span class="stat-change">-6% than average</span>
                        </div>
                        <div class="stat-icon-modern">
                            <i class="fas fa-paw"></i>
                        </div>
                    </div>
                    <div class="stat-mini-chart">
                        <svg viewBox="0 0 100 30" class="mini-wave">
                            <path d="M0,15 Q15,5 30,15 T60,15 T90,15 L100,15 L100,30 L0,30 Z" fill="rgba(239,68,68,0.2)" stroke="rgba(239,68,68,0.5)" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <div class="stat-card-modern green">
                    <div class="stat-header-modern">
                        <div class="stat-info">
                            <span class="stat-label">Vaccinations Due</span>
                            <h2 class="stat-value">0</h2>
                            <span class="stat-change positive">+12% than average</span>
                        </div>
                        <div class="stat-icon-modern">
                            <i class="fas fa-syringe"></i>
                        </div>
                    </div>
                    <div class="stat-mini-chart">
                        <svg viewBox="0 0 100 30" class="mini-wave">
                            <path d="M0,20 Q15,10 30,20 T60,20 T90,20 L100,20 L100,30 L0,30 Z" fill="rgba(34,197,94,0.2)" stroke="rgba(34,197,94,0.5)" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <div class="stat-card-modern purple">
                    <div class="stat-header-modern">
                        <div class="stat-info">
                            <span class="stat-label">Active Incidents</span>
                            <h2 class="stat-value">0</h2>
                            <span class="stat-change">No change</span>
                        </div>
                        <div class="stat-icon-modern">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-mini-chart">
                        <svg viewBox="0 0 100 30" class="mini-wave">
                            <path d="M0,10 Q15,20 30,10 T60,10 T90,10 L100,10 L100,30 L0,30 Z" fill="rgba(147,51,234,0.2)" stroke="rgba(147,51,234,0.5)" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- MAIN DASHBOARD GRID -->
            <div class="dashboard-main-grid">
                <!-- LEFT COLUMN -->
                <div class="dashboard-left-col">
                    <!-- PETS SECTION -->
                    <div class="dashboard-section">
                        <div class="section-header-modern">
                            <h2><i class="fas fa-paw"></i> My Pets</h2>
                            <button class="btn-modern btn-primary" id="registerPetBtn">
                                <i class="fas fa-plus"></i> Add Pet
                            </button>
                        </div>
                        <div class="pets-grid" id="petsGrid">
                            <p style="text-align: center; padding: 40px; color: #999;">Loading pets...</p>
                        </div>
                    </div>

                    <!-- RECENT ALERTS -->
                    <div class="dashboard-section">
                        <div class="section-header-modern">
                            <h2><i class="fas fa-bell"></i> Recent Activity</h2>
                        </div>
                        <div class="alerts-section"></div>
                    </div>
                </div>

                <!-- RIGHT COLUMN - PROFILE & QUICK ACTIONS -->
                <div class="dashboard-right-col">
                    <!-- PROFILE CARD -->
                    <div class="profile-card-modern">
                        <div class="profile-header">
                            <button class="btn-edit"><i class="fas fa-pen"></i></button>
                        </div>
                        <div class="profile-avatar">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'User'); ?>&background=5B7FDB&color=fff&size=200" alt="Profile" id="userAvatar">
                        </div>
                        <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></h3>
                        <p class="profile-role">Pet Owner</p>
                        
                        <div class="profile-stats-grid">
                            <div class="profile-stat-item">
                                <span class="profile-stat-label">Date Joined</span>
                                <span class="profile-stat-value"><?php echo date('M d, Y'); ?></span>
                            </div>
                            <div class="profile-stat-item">
                                <span class="profile-stat-label">Status</span>
                                <span class="profile-stat-value"><span class="status-badge active">Active</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- QUICK ACTIONS CARD -->
                    <div class="quick-actions-card">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                        <div class="quick-actions-grid">
                            <button class="quick-action-btn blue" id="quickAddPetBtn">
                            <i class="fas fa-plus-circle"></i>
                                <span>Add Pet</span>
                            </button>

                            <button class="quick-action-btn green" onclick="document.querySelector('[data-section=vaccinations]').click()">
                                <i class="fas fa-syringe"></i>
                                <span>Vaccinations</span>
                            </button>
                            <button class="quick-action-btn orange" onclick="document.querySelector('[data-section=incidents]').click()">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Report Issue</span>
                            </button>
                            <button class="quick-action-btn purple" onclick="document.querySelector('[data-section=analytics]').click()">
                                <i class="fas fa-chart-line"></i>
                                <span>Analytics</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- SIDEBAR OVERLAY for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>

    <!-- PET REGISTRATION MODAL -->
    <div class="modal" id="petRegistrationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-paw"></i> Register New Pet</h2>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="petRegistrationForm" enctype="multipart/form-data">
                    <div class="image-upload-container">
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-camera"></i>
                            <span>Click to upload pet image</span>
                        </div>
                        <input type="file" name="pet_image" id="petImage" accept="image/*" class="image-input">
                    </div>

                    <div class="form-group">
                        <label for="petName">Pet Name *</label>
                        <div class="input-group">
                            <input type="text" id="petName" name="name" required placeholder="Enter pet name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="species">Species *</label>
                        <div class="input-group">
                            <select id="species" name="species" required>
                                <option value="">Select species</option>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                                <option value="Bird">Bird</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="breed">Breed</label>
                        <div class="input-group">
                            <input type="text" id="breed" name="breed" placeholder="Enter breed">
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="age">Age (years)</label>
                            <div class="input-group">
                                <input type="number" id="age" name="age" min="0" placeholder="Age">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <div class="input-group">
                                <select id="gender" name="gender">
                                    <option value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary btn-block">
                        <i class="fas fa-paw"></i> Register Pet
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- VACCINATION MODAL -->
    <div class="modal" id="vaccinationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-syringe"></i> Add Vaccination Record</h2>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="vaccinationForm">
                    <div class="form-group">
                        <label for="vaccPetId">Select Pet *</label>
                        <select id="vaccPetId" name="pet_id" required>
                            <option value="">Select a pet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="vaccineName">Vaccine Name *</label>
                        <input type="text" id="vaccineName" name="vaccine_name" required placeholder="E.g., Rabies, DHPP">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="dateGiven">Date Given *</label>
                            <input type="date" id="dateGiven" name="date_given" required>
                        </div>

                        <div class="form-group">
                            <label for="nextDueDate">Next Due Date</label>
                            <input type="date" id="nextDueDate" name="next_due_date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="veterinarian">Veterinarian</label>
                        <input type="text" id="veterinarian" name="veterinarian" placeholder="Vet name">
                    </div>

                    <div class="form-group">
                        <label for="vaccNotes">Notes</label>
                        <textarea id="vaccNotes" name="notes" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary btn-block">
                        <i class="fas fa-syringe"></i> Save Vaccination Record
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- INCIDENT MODAL -->
    <div class="modal" id="incidentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Report Incident</h2>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="incidentForm">
                    <div class="form-group">
                        <label for="incidentPetId">Related Pet</label>
                        <select id="incidentPetId" name="pet_id">
                            <option value="">No specific pet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="incidentType">Incident Type *</label>
                        <input type="text" id="incidentType" name="incident_type" required placeholder="E.g., Lost Pet, Injury, Bite">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="incidentDate">Date & Time *</label>
                            <input type="datetime-local" id="incidentDate" name="incident_date" required>
                        </div>

                        <div class="form-group">
                            <label for="incidentSeverity">Severity *</label>
                            <select id="incidentSeverity" name="severity" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="incidentLocation">Location</label>
                        <input type="text" id="incidentLocation" name="location" placeholder="Where did it happen?">
                    </div>

                    <div class="form-group">
                        <label for="incidentDescription">Description *</label>
                        <textarea id="incidentDescription" name="description" rows="4" required placeholder="Describe what happened..."></textarea>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary btn-block">
                        <i class="fas fa-exclamation-triangle"></i> Report Incident
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- UPDATE INCIDENT MODAL -->
    <div class="modal" id="updateIncidentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Update Incident Status</h2>
                <button class="modal-close" id="closeUpdateIncidentModal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="updateIncidentForm">
                    <input type="hidden" id="updateIncidentId" name="incident_id">
                    <input type="hidden" name="action" value="update">

                    <div class="form-group">
                        <label for="updateIncidentStatus">Status *</label>
                        <select id="updateIncidentStatus" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="updateIncidentNotes">Update Notes</label>
                        <textarea id="updateIncidentNotes" name="notes" rows="4" placeholder="Add any updates or notes..."></textarea>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update Incident
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js?v=<?php echo time(); ?>"></script>
