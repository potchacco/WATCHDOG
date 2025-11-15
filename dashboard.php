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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard">
         <!-- Hamburger button -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        <!-- SIDEBAR WITH BURGER MENU -->
        <aside class="sidebar" id="sidebar">
           
            
            <div class="sidebar-header">
                <span class="logo-text" id="span-h2">WATCHD<i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i>G</span>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-paw"></i> Pets</a></li>
                <li><a href="#"><i class="fas fa-syringe"></i> Vaccinations</a></li>
                <li><a href="#"><i class="fas fa-exclamation-triangle"></i> Incidents</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="index.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <img src="https://i.pinimg.com/1200x/e0/d9/fc/e0d9fc9b4f89a5debdfd0955344861f9.jpg" alt="Playful dog" class="hero-image" width="240px" height="130px"/>
            </ul>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <div class="minimal-search">
                    <svg class="minimal-icon" viewBox="0 0 20 20" fill="none">
                        <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" 
                              stroke="currentColor" 
                              stroke-width="2" 
                              stroke-linecap="round"/>
                    </svg>
                    <input 
                        type="text" 
                        class="minimal-input" 
                        placeholder="Search by pet name, owner, or registration ID"
                        aria-label="Search">
                </div>
                <div class="user-profile">
                    <span id="userEmail"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=random" 
                         alt="User avatar" id="userAvatar">
                </div>
            </div>

            <!-- Overview Stats -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Total Pets</div>
                        <div class="stat-icon" style="background: #2196F3;"><i class="fas fa-paw"></i></div>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-description">Registered pets in the system</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Vaccination Information</div>
                        <div class="stat-icon" style="background: #FF9800;"><i class="fas fa-syringe"></i></div>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-description">Vaccinations due this month</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">Active Incidents</div>
                        <div class="stat-icon" style="background: #F44336;"><i class="fas fa-exclamation-circle"></i></div>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-description">Reported incidents pending resolution</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-title">License Renewals</div>
                        <div class="stat-icon" style="background: #4CAF50;"><i class="fas fa-certificate"></i></div>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-description">Licenses due for renewal</div>
                </div>
            </div>

            <!-- Alerts Section -->
            <div class="alerts-section">
                <div class="action-header">
                    <h2 class="action-title">Recent Alerts</h2>
                    <button class="dashboard-btn btn-secondary">View All</button>
                </div>
                <div class="alert-item warning">
                    <i class="fas fa-clock"></i>
                    <span>Max's rabies vaccination is due in 5 days</span>
                </div>
                <div class="alert-item danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Stray dog reported in Barangay 123</span>
                </div>
                <div class="alert-item success">
                    <i class="fas fa-check-circle"></i>
                    <span>Luna's pet license has been renewed successfully</span>
                </div>
            </div>

            <!-- Pets Section -->
            <div class="pets-section">
                <div class="section-header">
                    <h2>My Pets</h2>
                    <button class="dashboard-btn btn-primary" id="registerPetBtn">
                        <i class="fas fa-plus"></i> Register New Pet
                    </button>
                </div>
                <div class="pets-grid" id="petsGrid">
                    <!-- Pets will be loaded here dynamically -->
                </div>
            </div>

            <!-- Pet Registration Modal -->
            <div class="modal" id="petRegistrationModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Register New Pet</h2>
                        <button type="button" class="modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="petRegistrationForm" enctype="multipart/form-data" novalidate>
                            <input type="hidden" name="action" value="register">
                            
                            <div class="form-group">
                                <label for="petName">Pet Name</label>
                                <div class="input-group">
                                    <input type="text" id="petName" name="name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="species">Species</label>
                                <div class="input-group">
                                    <select id="species" name="species" required>
                                        <option value="">Select Species</option>
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
                                    <input type="text" id="breed" name="breed">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="age">Age (years)</label>
                                <div class="input-group">
                                    <input type="number" id="age" name="age" min="0" max="30">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <div class="input-group">
                                    <select id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="petImage">Pet Image</label>
                                <div class="image-upload-container">
                                    <div class="image-preview" id="imagePreview">
                                        <i class="fas fa-camera"></i>
                                        <span>Click to upload image</span>
                                    </div>
                                    <input type="file" id="petImage" name="petImage" accept="image/jpeg,image/png,image/gif" class="image-input">
                                </div>
                            </div>

                            <div class="form-message-container"></div>

                            <button type="submit" class="dashboard-btn btn-primary btn-block">
                                <i class="fas fa-paw"></i> Register Pet
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Vaccination Modal -->
<div class="modal" id="vaccinationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Vaccination Record</h2>
            <button type="button" class="modal-close" id="closeVaccinationModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="vaccinationForm">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="vaccPetId">Select Pet</label>
                    <select id="vaccPetId" name="pet_id" required>
                        <option value="">Select a pet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="vaccineName">Vaccine Name</label>
                    <select id="vaccineName" name="vaccine_name" required>
                        <option value="">Select Vaccine</option>
                        <option value="Rabies">Rabies</option>
                        <option value="Distemper">Distemper</option>
                        <option value="Parvovirus">Parvovirus</option>
                        <option value="Bordetella">Bordetella</option>
                        <option value="Leptospirosis">Leptospirosis</option>
                        <option value="Feline Leukemia">Feline Leukemia</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="dateGiven">Date Given</label>
                    <input type="date" id="dateGiven" name="date_given" required>
                </div>

                <div class="form-group">
                    <label for="nextDueDate">Next Due Date</label>
                    <input type="date" id="nextDueDate" name="next_due_date">
                </div>

                <div class="form-group">
                    <label for="veterinarian">Veterinarian</label>
                    <input type="text" id="veterinarian" name="veterinarian" placeholder="Dr. Smith">
                </div>

                <div class="form-group">
                    <label for="vaccNotes">Notes</label>
                    <textarea id="vaccNotes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                </div>

                <button type="submit" class="dashboard-btn btn-primary btn-block">
                    <i class="fas fa-syringe"></i> Save Vaccination Record
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Incident Modal -->
<div class="modal" id="incidentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Report New Incident</h2>
            <button type="button" class="modal-close" id="closeIncidentModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="incidentForm">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="incidentPetId">Related Pet (Optional)</label>
                    <select id="incidentPetId" name="pet_id">
                        <option value="">No specific pet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="incidentType">Incident Type</label>
                    <select id="incidentType" name="incident_type" required>
                        <option value="">Select Type</option>
                        <option value="Lost Pet">Lost Pet</option>
                        <option value="Found Pet">Found Pet</option>
                        <option value="Aggressive Behavior">Aggressive Behavior</option>
                        <option value="Injury">Injury</option>
                        <option value="Illness">Illness</option>
                        <option value="Stray Animal">Stray Animal</option>
                        <option value="Noise Complaint">Noise Complaint</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="incidentDescription">Description</label>
                    <textarea id="incidentDescription" name="description" rows="4" required placeholder="Describe the incident..."></textarea>
                </div>

                <div class="form-group">
                    <label for="incidentLocation">Location</label>
                    <input type="text" id="incidentLocation" name="location" placeholder="Street, Barangay, etc.">
                </div>

                <div class="form-group">
                    <label for="incidentSeverity">Severity</label>
                    <select id="incidentSeverity" name="severity" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="incidentDate">Incident Date & Time</label>
                    <input type="datetime-local" id="incidentDate" name="incident_date" required>
                </div>

                <button type="submit" class="dashboard-btn btn-primary btn-block">
                    <i class="fas fa-exclamation-triangle"></i> Report Incident
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Update Incident Modal -->
<div class="modal" id="updateIncidentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Incident Status</h2>
            <button type="button" class="modal-close" id="closeUpdateIncidentModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="updateIncidentForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="updateIncidentId" name="incident_id">
                
                <div class="form-group">
                    <label for="updateIncidentStatus">Status</label>
                    <select id="updateIncidentStatus" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="updateIncidentNotes">Notes (Optional)</label>
                    <textarea id="updateIncidentNotes" name="notes" rows="4" placeholder="Add notes about this status update..."></textarea>
                </div>

                <button type="submit" class="dashboard-btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </form>
        </div>
    </div>
</div>




            <!-- Quick Actions -->
            <div class="dashboard-grid">
                <div class="action-card">
                    <div class="action-header">
                        <h3 class="action-title">Register New Pet</h3>
                        <i class="fas fa-plus-circle" style="color: #1976D2;"></i>
                    </div>
                    <div class="action-content">
                        Add a new pet to the system with complete vaccination and ownership details.
                    </div>
                    <button class="dashboard-btn btn-primary" onclick="document.getElementById('registerPetBtn').click()">
                        <i class="fas fa-plus"></i> Add Pet
                    </button>
                </div>

                <div class="action-card">
                    <div class="action-header">
                        <h3 class="action-title">Edit Vaccination</h3>
                        <i class="fas fa-calendar-plus" style="color: #FF9800;"></i>
                    </div>
                    <div class="action-content">
                        Edit vaccinations Information for real time tracking and get reminders.
                    </div>
                    <button class="dashboard-btn btn-primary" id="schedule-btn">
                        <i class="fas fa-calendar"></i> Edit
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js"></script>
    <script>
    // ===== Logout button =====
document.getElementById('logoutBtn').addEventListener('click', async (e) => {
    e.preventDefault();
    const logoutBtn = e.currentTarget;
    const originalContent = logoutBtn.innerHTML;
    logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging out...';
    logoutBtn.disabled = true;
    
    try {
        const formData = new FormData();
        formData.append('action', 'logout');
        const response = await fetch('auth.php', { method: 'POST', body: formData });
        const data = await response.json();
        setTimeout(() => {
            if (data.status === 'success') {
                window.location.href = 'index.php';
            }
        }, 2000);
    } catch (error) {
        console.error('Logout failed:', error);
        logoutBtn.innerHTML = originalContent;
        logoutBtn.disabled = false;
    }
});

// ===== Helper: Reset to Dashboard =====
function showDashboard() {
    document.querySelectorAll('.main-content > div').forEach(div => div.style.display = '');
    const dynamicSections = document.querySelectorAll(
        '.pets-content, .vaccination-content, .incidents-content, .analytics-content, .settings-content'
    );
    dynamicSections.forEach(section => section.remove());
}

// ===== Helper: Clear and prepare new page =====
function clearMainContent() {
    document.querySelectorAll('.main-content > div').forEach(div => div.style.display = 'none');
    const dynamicSections = document.querySelectorAll(
        '.pets-content, .vaccination-content, .incidents-content, .analytics-content, .settings-content'
    );
    dynamicSections.forEach(section => section.remove());
}

// ===== DASHBOARD =====
document.querySelector('.sidebar-menu li:nth-child(1) a').addEventListener('click', function(e) {
    e.preventDefault();
    showDashboard();
});

// ===== PETS =====
document.querySelector('.sidebar-menu li:nth-child(2) a').addEventListener('click', async function(e) {
    e.preventDefault();
    clearMainContent();

    const petsContent = document.createElement('div');
    petsContent.className = 'pets-content';
    petsContent.innerHTML = `
        <div class="dashboard-header" style="margin-bottom: 20px;">
            <h1>Pet Management</h1>
            <button class="dashboard-btn btn-primary" id="addPetFromSidebar">
                <i class="fas fa-plus"></i> Add Pet
            </button>
        </div>
        <div class="pets-grid" id="sidebarPetsGrid">
            <p>Loading pets...</p>
        </div>
    `;
    document.querySelector('.main-content').appendChild(petsContent);

    document.getElementById('addPetFromSidebar').addEventListener('click', function() {
        document.getElementById('petRegistrationModal').classList.add('active');
    });

    loadPetsInSidebar();
});

// ===== VACCINATIONS =====
document.querySelector('.sidebar-menu li:nth-child(3) a').addEventListener('click', async function(e) {
    e.preventDefault();
    clearMainContent();

    const vaccContent = document.createElement('div');
    vaccContent.className = 'vaccination-content';
    vaccContent.innerHTML = `
        <div class="dashboard-header" style="margin-bottom: 20px;">
            <h1>Vaccination Management</h1>
        </div>
        <div id="vaccinationPetsContainer">
            <p>Loading pets...</p>
        </div>
    `;
    document.querySelector('.main-content').appendChild(vaccContent);

    loadPetsWithVaccinations();
});

// ===== INCIDENTS =====
document.querySelector('.sidebar-menu li:nth-child(4) a').addEventListener('click', function(e) {
    e.preventDefault();
    clearMainContent();

    const incidentsContent = document.createElement('div');
    incidentsContent.className = 'incidents-content';
    incidentsContent.innerHTML = `
        <div class="dashboard-header" style="margin-bottom: 20px;">
            <h1>Incident Reports</h1>
            <button class="dashboard-btn btn-primary" id="reportIncidentBtn">
                <i class="fas fa-plus"></i> Report New Incident
            </button>
        </div>
        <div id="incidentsContainer">
            <p>Loading incidents...</p>
        </div>
    `;
    document.querySelector('.main-content').appendChild(incidentsContent);

    document.getElementById('reportIncidentBtn').addEventListener('click', function() {
        openIncidentModal();
    });

    loadIncidents();
});


// ===== ANALYTICS =====
document.querySelector('.sidebar-menu li:nth-child(5) a').addEventListener('click', function(e) {
    e.preventDefault();
    clearMainContent();

    const analyticsContent = document.createElement('div');
    analyticsContent.className = 'analytics-content';
    analyticsContent.innerHTML = `
        <div class="dashboard-header">
            <h1>Analytics Dashboard</h1>
        </div>
        <div class="analytics-sections">
            <div class="chart-card">
                <h3>Pet Registrations (Monthly)</h3>
                <p>Placeholder chart showing pet registrations per month.</p>
            </div>
            <div class="chart-card">
                <h3>Vaccination Status</h3>
                <p>Placeholder chart showing completed vs pending vaccinations.</p>
            </div>
        </div>
    `;
    document.querySelector('.main-content').appendChild(analyticsContent);
});

// ===== SETTINGS =====
document.querySelector('.sidebar-menu li:nth-child(6) a').addEventListener('click', function(e) {
    e.preventDefault();
    clearMainContent();

    const settingsContent = document.createElement('div');
    settingsContent.className = 'settings-content';
    settingsContent.innerHTML = `
        <div class="dashboard-header">
            <h1>Settings</h1>
            <p style="color: #666; margin-top: 10px;">Manage your account preferences and application settings</p>
        </div>
        
        <div class="settings-container">
            <!-- Profile Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Profile Information</h3>
                </div>
                <form id="profileSettingsForm" class="settings-form">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="settingsFullName">Full Name</label>
                        <input type="text" id="settingsFullName" name="full_name" 
                               value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="settingsEmail">Email Address</label>
                        <input type="email" id="settingsEmail" name="email" 
                               value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="settingsPhone">Phone Number</label>
                        <input type="tel" id="settingsPhone" name="phone" 
                               placeholder="+63 XXX XXX XXXX">
                    </div>

                    <div class="form-group">
                        <label for="settingsAddress">Address</label>
                        <input type="text" id="settingsAddress" name="address" 
                               placeholder="Street, Barangay, City">
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary">
                        <i class="fas fa-save"></i> Save Profile Changes
                    </button>
                </form>
            </div>

            <!-- Security Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-lock"></i>
                    <h3>Security & Privacy</h3>
                </div>
                <form id="securitySettingsForm" class="settings-form">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" name="current_password" 
                               placeholder="Enter current password" required>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="new_password" 
                               placeholder="Enter new password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirm_password" 
                               placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </form>
            </div>

            <!-- Notification Settings -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-bell"></i>
                    <h3>Notification Preferences</h3>
                </div>
                <form id="notificationSettingsForm" class="settings-form">
                    <input type="hidden" name="action" value="update_notifications">
                    
                    <div class="settings-toggle-group">
                        <div class="settings-toggle-item">
                            <div class="toggle-info">
                                <label for="notifVaccination">Vaccination Reminders</label>
                                <span>Get notified before vaccination due dates</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="notifVaccination" name="notif_vaccination" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="settings-toggle-item">
                            <div class="toggle-info">
                                <label for="notifIncidents">Incident Alerts</label>
                                <span>Receive alerts for nearby incidents</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="notifIncidents" name="notif_incidents" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="settings-toggle-item">
                            <div class="toggle-info">
                                <label for="notifLicense">License Renewal</label>
                                <span>Reminders for pet license renewals</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="notifLicense" name="notif_license" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="settings-toggle-item">
                            <div class="toggle-info">
                                <label for="notifEmail">Email Notifications</label>
                                <span>Send notifications to your email</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="notifEmail" name="notif_email">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary">
                        <i class="fas fa-save"></i> Save Preferences
                    </button>
                </form>
            </div>

            <!-- App Preferences -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-sliders-h"></i>
                    <h3>Application Preferences</h3>
                </div>
                <form id="appSettingsForm" class="settings-form">
                    <input type="hidden" name="action" value="update_app_settings">
                    
                    <div class="form-group">
                        <label for="defaultView">Default Dashboard View</label>
                        <select id="defaultView" name="default_view">
                            <option value="overview">Overview Dashboard</option>
                            <option value="pets">Pets Management</option>
                            <option value="vaccinations">Vaccinations</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="itemsPerPage">Items Per Page</label>
                        <select id="itemsPerPage" name="items_per_page">
                            <option value="5">5 items</option>
                            <option value="10" selected>10 items</option>
                            <option value="20">20 items</option>
                            <option value="50">50 items</option>
                        </select>
                    </div>

                    <div class="settings-toggle-group">
                        <div class="settings-toggle-item">
                            <div class="toggle-info">
                                <label for="darkMode">Dark Mode</label>
                                <span>Switch to dark theme</span>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="darkMode" name="dark_mode">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="dashboard-btn btn-primary">
                        <i class="fas fa-save"></i> Save Preferences
                    </button>
                </form>
            </div>

            <!-- Account Actions -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-user-cog"></i>
                    <h3>Account Management</h3>
                </div>
                <div class="settings-form">
                    <div class="account-actions">
                        <div class="account-action-item">
                            <div>
                                <h4>Export Data</h4>
                                <p>Download all your pet records and data</p>
                            </div>
                            <button class="dashboard-btn btn-secondary" id="exportDataBtn">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>

                        <div class="account-action-item">
                            <div>
                                <h4>Delete Account</h4>
                                <p>Permanently delete your account and all data</p>
                            </div>
                            <button class="dashboard-btn btn-danger" id="deleteAccountBtn">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>About</h3>
                </div>
                <div class="about-content">
                    <div class="about-item">
                        <span>Version</span>
                        <strong>1.0.0</strong>
                    </div>
                    <div class="about-item">
                        <span>Last Updated</span>
                        <strong>November 2025</strong>
                    </div>
                    <div class="about-item">
                        <span>Support</span>
                        <a href="mailto:support@watchdog.com">support@watchdog.com</a>
                    </div>
                    <div class="about-item">
                        <span>Terms & Privacy</span>
                        <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.querySelector('.main-content').appendChild(settingsContent);

    // Initialize settings event handlers
    initializeSettingsHandlers();
});

// Settings handlers
function initializeSettingsHandlers() {
    // Profile Settings Form
    document.getElementById('profileSettingsForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        try {
            // Simulate save - replace with actual API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            showSettingsMessage('Profile updated successfully!', 'success');
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        } catch (error) {
            showSettingsMessage('Failed to update profile', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // Security Settings Form
    document.getElementById('securitySettingsForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            showSettingsMessage('Passwords do not match', 'error');
            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;

        try {
            // Simulate password update - replace with actual API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            showSettingsMessage('Password updated successfully!', 'success');
            this.reset();
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Updated!';
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        } catch (error) {
            showSettingsMessage('Failed to update password', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // Notification Settings Form
    document.getElementById('notificationSettingsForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        try {
            await new Promise(resolve => setTimeout(resolve, 800));
            showSettingsMessage('Notification preferences saved!', 'success');
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        } catch (error) {
            showSettingsMessage('Failed to save preferences', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // App Settings Form
    document.getElementById('appSettingsForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        try {
            await new Promise(resolve => setTimeout(resolve, 800));
            showSettingsMessage('Application preferences saved!', 'success');
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        } catch (error) {
            showSettingsMessage('Failed to save preferences', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    // Dark Mode Toggle
    document.getElementById('darkMode')?.addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            showSettingsMessage('Dark mode enabled', 'success');
        } else {
            document.body.classList.remove('dark-mode');
            showSettingsMessage('Dark mode disabled', 'success');
        }
    });

    // Export Data
    document.getElementById('exportDataBtn')?.addEventListener('click', async function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
        this.disabled = true;
        
        try {
            await new Promise(resolve => setTimeout(resolve, 1500));
            showSettingsMessage('Data exported successfully!', 'success');
            this.innerHTML = '<i class="fas fa-download"></i> Export';
            this.disabled = false;
        } catch (error) {
            showSettingsMessage('Export failed', 'error');
            this.innerHTML = '<i class="fas fa-download"></i> Export';
            this.disabled = false;
        }
    });

    // Delete Account
    document.getElementById('deleteAccountBtn')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            if (confirm('This will permanently delete all your pet records and data. Continue?')) {
                showSettingsMessage('Account deletion initiated. Please contact support.', 'warning');
            }
        }
    });
}

// Helper function to show messages in settings
function showSettingsMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `settings-message ${type}`;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    const settingsContainer = document.querySelector('.settings-container');
    if (settingsContainer) {
        settingsContainer.insertBefore(messageDiv, settingsContainer.firstChild);
        setTimeout(() => messageDiv.remove(), 4000);
    }
}

// ===== BURGER MENU FUNCTIONALITY =====
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
let sidebarOverlay;

// Create overlay for mobile
function createOverlay() {
    if (!sidebarOverlay) {
        sidebarOverlay = document.createElement('div'); 
        sidebarOverlay.className = 'sidebar-overlay';
        document.body.appendChild(sidebarOverlay);
        
        sidebarOverlay.addEventListener('click', closeMobileMenu);
    }
}

function toggleMobileMenu() {
    hamburger.classList.toggle('active');
    sidebar.classList.toggle('active');
    
    if (sidebarOverlay) {
        sidebarOverlay.classList.toggle('active');
    }
    
    // Prevent body scroll when menu is open
    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
}

function closeMobileMenu() {
    hamburger.classList.remove('active');
    sidebar.classList.remove('active');
    
    if (sidebarOverlay) {
        sidebarOverlay.classList.remove('active');
    }
    
    document.body.style.overflow = '';
}

// Event listener for hamburger
hamburger.addEventListener('click', toggleMobileMenu);

// Create overlay on page load
createOverlay();

// Close menu when clicking sidebar links (mobile)
const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
sidebarLinks.forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            closeMobileMenu();
        }
    });
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeMobileMenu();
    }
});

</script>

</body>
</html>
