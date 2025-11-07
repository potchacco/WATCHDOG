<?php
require_once 'check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WATCHDOG - Dashboard</title>
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <span class="logo-text" id="span-h2">WATCHD<i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i>G</span></span>
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
                <!-- <h1>Dashboard</h1> -->
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
            aria-label="Search"
        >
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
                        <form id="petRegistrationForm" novalidate>
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
                        <h3 class="action-title">Report Incident</h3>
                        <i class="fas fa-exclamation-triangle" style="color: #F44336;"></i>
                    </div>
                    <div class="action-content">
                        Report stray animals, bites, or other pet-related incidents in your area.
                    </div>
                    <button class="dashboard-btn btn-primary" id="report-btn">
                        <i class="fas fa-flag"></i> Report
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
        document.getElementById('logoutBtn').addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                const formData = new FormData();
                formData.append('action', 'logout');

                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.status === 'success') {
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Logout failed:', error);
            }
        });
    </script>
</body>
</html>