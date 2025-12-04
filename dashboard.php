<?php
require_once 'check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <!-- AI Assistant Widget -->
<div id="aiAssistant" class="ai-assistant">
  <div class="ai-header">
    <span>Watchdog Assistant</span>
    <button id="aiToggleBtn">&times;</button>
  </div>
  <div id="aiMessages" class="ai-messages">
    <div class="ai-message ai-message-bot">
      Hi! I can answer questions about using this system, like how to register pets,
      add vaccinations, or report incidents.
    </div>
  </div>
  <form id="aiForm" class="ai-form">
    <input
      type="text"
      id="aiInput"
      placeholder="Ask about the system..."
      autocomplete="off"
      required
    />
    <button type="submit">Send</button>
  </form>
</div>

<button id="aiAssistantToggle" class="ai-fab">
  ?
</button>

    <div class="dashboard">
        <!-- Hamburger Menu -->
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-shield-dog"></i>
                </div>
                <span class="sidebar-brand"></span>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="menu-item">
                        <a href="#" class="active" data-section="dashboard">
                            <i class="fa-solid fa-house fa-lg" style="color: #d6d6d6;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" data-section="pets">
                            <i class="fas fa-paw"></i>
                            <span>My Pets</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" data-section="vaccinations">
                            <i class="fas fa-syringe"></i>
                            <span>Vaccinations</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" data-section="incidents">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Incidents</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" data-section="analytics">
                            <i class="fas fa-chart-pie"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" data-section="settings">
                            <i class="fas fa-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content" id="mainContent">
            <!-- TOP HEADER BAR -->
            <header class="top-header">
                <div class="header-left">
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle"><?php echo date('l, F d, Y'); ?></p>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <button class="header-notification">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="header-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'User'); ?>&background=6366f1&color=fff&size=100" alt="Profile" id="userAvatar">
                        <div class="profile-info">
                            <span class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                            <span class="profile-role">Pet Owner</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- WELCOME HERO SECTION -->
            <section class="hero-banner">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="hero-greeting">Welcome back,</span>
                        <h1><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>! 👋</h1>
                        <p>Here's what's happening with your pets today.</p>
                    </div>
                    <div class="hero-illustration">
                        <div class="hero-icon-group">
                            <i class="fas fa-dog"></i>
                            <i class="fas fa-cat"></i>
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
                <div class="hero-pattern"></div>
            </section>

            <!-- STATS CARDS -->
            <section class="stats-row">
                <div class="stat-card-modern blue">
                    <div class="stat-icon-modern">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="stat-info">
                        <h2 class="stat-value">0</h2>
                        <span class="stat-label">Total Pets</span>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12%</span>
                    </div>
                </div>

                <div class="stat-card-modern green">
                    <div class="stat-icon-modern">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-info">
                        <h2 class="stat-value">0</h2>
                        <span class="stat-label">Vaccinations Due</span>
                    </div>
                    <div class="stat-trend down">
                        <i class="fas fa-arrow-down"></i>
                        <span>-5%</span>
                    </div>
                </div>

                <div class="stat-card-modern purple">
                    <div class="stat-icon-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h2 class="stat-value">0</h2>
                        <span class="stat-label">Active Incidents</span>
                    </div>
                    <div class="stat-trend neutral">
                        <i class="fas fa-minus"></i>
                        <span>0%</span>
                    </div>
                </div>

                <div class="stat-card-modern orange">
                    <div class="stat-icon-modern">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h2 class="stat-value">0</h2>
                        <span class="stat-label">Appointments</span>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8%</span>
                    </div>
                </div>
            </section>

            <!-- MAIN DASHBOARD GRID -->
            <div class="dashboard-main-grid">
                <!-- LEFT COLUMN -->
                <div class="dashboard-left-col">
                    <!-- MY PETS SECTION -->
                    <div class="dashboard-section">
                        <div class="section-header-modern">
                            <div class="section-title">
                                <i class="fas fa-paw"></i>
                                <h2>My Pets</h2>
                            </div>
                            <button class="btn-modern btn-primary" id="registerPetBtn">
                                <i class="fas fa-plus"></i>
                                <span>Add Pet</span>
                            </button>
                        </div>
                        <div class="pets-grid" id="petsGrid">
                            <div class="loading-state">
                                <div class="spinner"></div>
                                <p>Loading pets...</p>
                            </div>
                        </div>
                    </div>

                    <!-- INCIDENT REPORTING SECTION (Hidden by default) -->
                    <div class="dashboard-section" id="incidents-section" style="display: none;">
                        <div class="section-header">
                            <div class="section-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h2>Report Incident</h2>
                            </div>
                            <p class="section-desc">Report any pet-related incidents in your area</p>
                        </div>

                        <div class="incident-form-container">
                            <form id="incidentReportForm" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add">
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="incidentType">
                                            <i class="fas fa-list"></i> Incident Type *
                                        </label>
                                        <select id="incidentType" name="incident_type" required>
                                            <option value="">Select incident type</option>
                                            <option value="Stray Dog">Stray Dog</option>
                                            <option value="Dog Bite">Dog Bite</option>
                                            <option value="Lost Pet">Lost Pet</option>
                                            <option value="Found Pet">Found Pet</option>
                                            <option value="Animal Abuse">Animal Abuse</option>
                                            <option value="Aggressive Behavior">Aggressive Behavior</option>
                                            <option value="Health Concern">Health Concern</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="incidentSeverity">
                                            <i class="fas fa-exclamation-circle"></i> Severity *
                                        </label>
                                        <select id="incidentSeverity" name="severity" required>
                                            <option value="Low">Low - Minor concern</option>
                                            <option value="Medium" selected>Medium - Needs attention</option>
                                            <option value="High">High - Urgent</option>
                                            <option value="Critical">Critical - Emergency</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="incidentPet">
                                            <i class="fas fa-paw"></i> Related Pet (Optional)
                                        </label>
                                        <select id="incidentPet" name="pet_id">
                                            <option value="">Not related to my pet</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="incidentDateNew">
                                            <i class="fas fa-calendar"></i> Date & Time *
                                        </label>
                                        <input type="datetime-local" id="incidentDateNew" name="incident_date" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="incidentLocationNew">
                                        <i class="fas fa-map-marker-alt"></i> Location *
                                    </label>
                                    <input type="text" id="incidentLocationNew" name="location" 
                                           placeholder="e.g., Near Barangay Hall, Corner of Main St." required>
                                    <small class="form-hint">Location will be recorded in: <strong id="userBarangay">Your Area</strong></small>
                                </div>

                                <div class="form-group">
                                    <label for="incidentDescriptionNew">
                                        <i class="fas fa-align-left"></i> Description *
                                    </label>
                                    <textarea id="incidentDescriptionNew" name="description" rows="4" 
                                              placeholder="Provide detailed description of the incident..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="incidentImageUpload">
                                        <i class="fas fa-camera"></i> Upload Image (Optional)
                                    </label>
                                    <div class="image-upload-wrapper">
                                        <input type="file" id="incidentImageUpload" name="incident_image" 
                                               accept="image/jpeg,image/jpg,image/png,image/gif" class="image-input">
                                        <label for="incidentImageUpload" class="image-upload-label">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Click to upload or drag & drop</span>
                                            <small>JPG, PNG, GIF (Max 5MB)</small>
                                        </label>
                                        <div id="imagePreviewNew" class="image-preview-box hidden">
                                            <img id="previewImgNew" src="" alt="Preview">
                                            <button type="button" id="removeImageNew" class="remove-image-btn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Submit Report
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="incidents-list-container">
                            <h3><i class="fas fa-history"></i> Your Reported Incidents</h3>
                            <div id="incidentsList" class="incidents-list"></div>
                        </div>
                    </div>

                    <!-- RECENT ACTIVITY SECTION -->
                    <div class="dashboard-section">
                        <div class="section-header-modern">
                            <div class="section-title">
                                <i class="fas fa-clock"></i>
                                <h2>Recent Activity</h2>
                            </div>
                            <a href="#" class="view-all-link">View All</a>
                        </div>
                        <div class="alerts-section">
                            <div class="activity-placeholder">
                                <i class="fas fa-history"></i>
                                <p>Loading recent activities...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="dashboard-right-col">
                    <!-- PROFILE CARD -->
                    <div class="profile-card-modern">
                        <div class="profile-cover"></div>
                        <div class="profile-body">
                            <div class="profile-avatar-wrapper">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'User'); ?>&background=6366f1&color=fff&size=200" alt="Profile">
                                <span class="status-dot online"></span>
                            </div>
                            <h3 class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></h3>
                            <p class="profile-role">Pet Owner</p>
                            
                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <span class="stat-number">0</span>
                                    <span class="stat-text">Pets</span>
                                </div>
                                <div class="profile-stat">
                                    <span class="stat-number">0</span>
                                    <span class="stat-text">Records</span>
                                </div>
                                <div class="profile-stat">
                                    <span class="stat-number">0</span>
                                    <span class="stat-text">Reports</span>
                                </div>
                            </div>

                            <div class="profile-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Joined <?php echo date('M Y'); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-circle-check"></i>
                                    <span class="status-active">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QUICK ACTIONS -->
                    <div class="quick-actions-card">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                        <div class="quick-actions-grid">
                            <button class="quick-action-btn" id="quickAddPetBtn">
                                <div class="action-icon blue">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span>Add Pet</span>
                            </button>
                            <button class="quick-action-btn" onclick="document.querySelector('[data-section=vaccinations]').click()">
                                <div class="action-icon green">
                                    <i class="fas fa-syringe"></i>
                                </div>
                                <span>Vaccination</span>
                            </button>
                            <button class="quick-action-btn" onclick="document.querySelector('[data-section=incidents]').click()">
                                <div class="action-icon orange">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <span>Report</span>
                            </button>
                            <button class="quick-action-btn" onclick="document.querySelector('[data-section=analytics]').click()">
                                <div class="action-icon purple">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span>Analytics</span>
                            </button>
                        </div>
                    </div>

                    <!-- UPCOMING EVENTS -->
                    <div class="upcoming-card">
                        <h3><i class="fas fa-calendar"></i> Upcoming</h3>
                        <div class="upcoming-list">
                            <div class="upcoming-item">
                                <div class="upcoming-date">
                                    <span class="day"><?php echo date('d'); ?></span>
                                    <span class="month"><?php echo date('M'); ?></span>
                                </div>
                                <div class="upcoming-info">
                                    <h4>No upcoming events</h4>
                                    <p>Add vaccinations to see schedules</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- SIDEBAR OVERLAY -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>

    <!-- ========== MODALS ========== -->

    <!-- PET REGISTRATION MODAL -->
    <div class="modal" id="petRegistrationModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-paw"></i>
                    <h2>Register New Pet</h2>
                </div>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="petRegistrationForm" enctype="multipart/form-data">
                    <div class="image-upload-container">
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-camera"></i>
                            <span>Upload Photo</span>
                        </div>
                        <input type="file" name="pet_image" id="petImage" accept="image/*" class="image-input">
                    </div>

                    <div class="form-group">
                        <label for="petName">Pet Name *</label>
                        <input type="text" id="petName" name="name" required placeholder="Enter pet name">
                    </div>

                    <div class="form-group">
                        <label for="species">Species *</label>
                        <select id="species" name="species" required>
                            <option value="">Select species</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Bird">Bird</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="breed">Breed</label>
                        <input type="text" id="breed" name="breed" placeholder="Enter breed">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="age">Age (years)</label>
                            <input type="number" id="age" name="age" min="0" placeholder="Age">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
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
                <div class="modal-title">
                    <i class="fas fa-syringe"></i>
                    <h2>Add Vaccination Record</h2>
                </div>
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
                <div class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h2>Report Incident</h2>
                </div>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
      <form id="incidentForm" enctype="multipart/form-data">
        <!-- IMAGE UPLOAD FOR INCIDENT -->
        <div class="image-upload-container">
          <div id="incidentImagePreview" class="image-preview">
            <i class="fas fa-camera"></i>
            <span>Click to upload incident photo</span>
          </div>
          <input
            type="file"
            id="incidentImage"
            name="incident_image"
            class="image-input"
            accept="image/*"
          >
        </div>
            <div class="modal-body">
                <form id="incidentForm">
            <div class="form-group">
    <label for="incidentSpecies">
        <i class="fas fa-paw"></i> Animal Type *
    </label>
    <select id="incidentSpecies" name="animal_species" required>
        <option value="">Select animal type</option>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Bird">Bird</option>
        <option value="Other">Other</option>
    </select>
</div>


                    <div class="form-group">
                        <label for="incidentTypeOld">Incident Type *</label>
                        <input type="text" id="incidentTypeOld" name="incident_type" required placeholder="E.g., Lost Pet, Injury, Bite">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="incidentDateOld">Date & Time *</label>
                            <input type="datetime-local" id="incidentDateOld" name="incident_date" required>
                        </div>
                        <div class="form-group">
                            <label for="incidentSeverityOld">Severity *</label>
                            <select id="incidentSeverityOld" name="severity" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
  <label for="incidentLocation">
    <i class="fas fa-location-dot"></i>
    Barangay
  </label>
  <select id="incidentLocation" name="location" required>
    <option value="">Select barangay</option>
    <!-- Replace these with your real barangay list -->
    <option value="Barangay 1">Barangay 1</option>
    <option value="Barangay 2">Barangay 2</option>
    <option value="Barangay 3">Barangay 3</option>
    <option value="Barangay 4">Barangay 4</option>
    <option value="Barangay 5">Barangay 5</option>
  </select>
  <span class="form-hint">Only official barangays are allowed as locations.</span>
</div>


                    <div class="form-group">
                        <label for="incidentDescriptionOld">Description *</label>
                        <textarea id="incidentDescriptionOld" name="description" rows="4" required placeholder="Describe what happened..."></textarea>
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
                <div class="modal-title">
                    <i class="fas fa-edit"></i>
                    <h2>Update Incident Status</h2>
                </div>
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

    <!-- Global message modal -->
<div class="modal" id="appMessageModal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title-text">Message</h2>
      <button type="button" class="modal-close" data-app-modal-close>
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="modal-body">
      <p class="modal-body-text"></p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-app-modal-close>
        OK
      </button>
    </div>
  </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js?v=<?php echo time(); ?>"></script>
</body>
</html>
