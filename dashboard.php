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
        <aside class="sidebar">
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
        <div class="dashboard-header">
            <h1>Incident Reports</h1>
            <button class="dashboard-btn btn-primary"><i class="fas fa-plus"></i> Report New Incident</button>
        </div>
        <div class="incidents-grid">
            <div class="no-incidents-message">
                <i class="fas fa-clipboard-check"></i>
                <p>No incidents reported. Click the button above to report an incident.</p>
            </div>
        </div>
    `;
    document.querySelector('.main-content').appendChild(incidentsContent);
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
        <div class="dashboard-header"><h1>Settings</h1></div>
    `;
    document.querySelector('.main-content').appendChild(settingsContent);
});

</script>


</body>
</html>
