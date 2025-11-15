// ===== HELPER FUNCTIONS (OUTSIDE DOMContentLoaded) =====
function generateCalendarDates() {
    let html = '';
    for (let i = 1; i <= 31; i++) {
        const isToday = i === 24;
        const hasEvent = i === 16 || i === 24;
        html += `<div class="calendar-date ${isToday ? 'today' : ''} ${hasEvent ? 'has-event' : ''}">${i}</div>`;
    }
    return html;
}

async function loadPets() {
    const petsGrid = document.getElementById('petsGrid');
    if (!petsGrid) return;
    
    petsGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #999;">Loading pets...</p>';
    try {
        const res = await fetch('pets.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.pets.length === 0) {
                petsGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #999;">No pets registered yet.</p>';
                return;
            }

            petsGrid.innerHTML = '';
            data.pets.forEach(pet => {
                const imgSrc = pet.image_url && pet.image_url.trim() !== ''
                    ? pet.image_url
                    : 'https://via.placeholder.com/300x200?text=No+Image';
                const petDiv = document.createElement('div');
                petDiv.className = 'pet-card';
                petDiv.innerHTML = `
                    <div class="pet-image" style="background-image: url('${imgSrc}');"></div>
                    <div class="pet-details">
                        <h3>${pet.name}</h3>
                        <p>${pet.species} - ${pet.breed || 'Unknown'}</p>
                        <p>Age: ${pet.age || 'N/A'} | Gender: ${pet.gender || 'N/A'}</p>
                        <div class="pet-actions">
                            <button class="pet-btn edit-btn" data-pet-id="${pet.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            
                        </div>
                    </div>
                `;
                petsGrid.appendChild(petDiv);
            });
            
            // Add event listeners to Edit buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    editPet(petId, data.pets);
                });
            });
            
            // Add event listeners to Delete buttons
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    const petName = data.pets.find(p => p.id == petId)?.name;
                    deletePet(petId, petName);
                });
            });
        } else {
            petsGrid.innerHTML = '<p>Failed to load pets.</p>';
        }
    } catch (err) {
        petsGrid.innerHTML = '<p>Error loading pets.</p>';
        console.error(err);
    }
}

function editPet(petId, pets) {
    const pet = pets.find(p => p.id == petId);
    if (!pet) return;

    document.getElementById('petName').value = pet.name;
    document.getElementById('species').value = pet.species;
    document.getElementById('breed').value = pet.breed || '';
    document.getElementById('age').value = pet.age || '';
    document.getElementById('gender').value = pet.gender || '';

    const form = document.getElementById('petRegistrationForm');
    form.dataset.mode = 'update';
    form.dataset.petId = petId;

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Pet';

    document.getElementById('petRegistrationModal').classList.add('active');
}



async function loadPetsInSidebar() {
    const sidebarPetsGrid = document.getElementById('sidebarPetsGrid');
    if (!sidebarPetsGrid) return;

    try {
        const res = await fetch('pets.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.pets.length === 0) {
                sidebarPetsGrid.innerHTML = '<p style="text-align: center; padding: 40px;">No pets registered yet.</p>';
                return;
            }

            sidebarPetsGrid.innerHTML = '';
            data.pets.forEach(pet => {
                const imgSrc = pet.image_url || 'https://via.placeholder.com/300x200?text=No+Image';
                
                const petCard = document.createElement('div');
                petCard.className = 'pet-card';
                petCard.innerHTML = `
                    <div class="pet-image" style="background-image: url('${imgSrc}'); background-size: cover; background-position: center; height: 200px;"></div>
                    <div class="pet-details" style="padding: 15px;">
                        <h3>${pet.name}</h3>
                        <p>${pet.species} - ${pet.breed || 'Unknown'}</p>
                        <p>Age: ${pet.age || 'N/A'} | Gender: ${pet.gender || 'N/A'}</p>
                    </div>
                `;
                sidebarPetsGrid.appendChild(petCard);
            });
        }
    } catch (err) {
        console.error('Error:', err);
        sidebarPetsGrid.innerHTML = '<p>Error loading pets.</p>';
    }
}

async function loadDashboardStats() {
    try {
        const res = await fetch('get_stats.php');
        const data = await res.json();

        if (data.status === 'success') {
            const totalPetsEl = document.querySelector('.stat-card-modern.blue .stat-value');
            if (totalPetsEl) totalPetsEl.textContent = data.stats.total_pets;
            
            const vaccinationsEl = document.querySelector('.stat-card-modern.green .stat-value');
            if (vaccinationsEl) vaccinationsEl.textContent = data.stats.vaccinations_due;
            
            const incidentsEl = document.querySelector('.stat-card-modern.purple .stat-value');
            if (incidentsEl) incidentsEl.textContent = data.stats.active_incidents;
        }
    } catch (err) {
        console.error('Error loading stats:', err);
    }
}

async function loadPetsWithVaccinations() {
    const container = document.getElementById('vaccinationPetsContainer');
    if (!container) return;

    try {
        const petsRes = await fetch('pets.php');
        const petsData = await petsRes.json();

        if (petsData.status === 'success') {
            if (petsData.pets.length === 0) {
                container.innerHTML = `<div style="text-align: center; padding: 40px;"><i class="fas fa-paw" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i><p style="font-size: 18px; color: #666;">No pets registered yet.</p></div>`;
                return;
            }

            container.innerHTML = '<div style="display: grid; gap: 20px;"></div>';
            const petsContainer = container.querySelector('div');

            petsData.pets.forEach(pet => {
                const petCard = document.createElement('div');
                petCard.style.cssText = 'background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px;';
                petCard.innerHTML = `
                    <h2>${pet.name}</h2>
                    <p>${pet.species} - ${pet.breed || 'Unknown breed'}</p>
                    <button class="dashboard-btn btn-primary" onclick="openVaccinationModalForPet('${pet.id}', '${pet.name}')">
                        <i class="fas fa-syringe"></i> Add Vaccination
                    </button>
                `;
                petsContainer.appendChild(petCard);
            });
        }
    } catch (err) {
        console.error('Error:', err);
    }
}

async function openVaccinationModalForPet(petId, petName) {
    const modal = document.getElementById('vaccinationModal');
    const petSelect = document.getElementById('vaccPetId');
    
    try {
        const res = await fetch('pets.php');
        const data = await res.json();

        if (data.status === 'success' && data.pets.length > 0) {
            petSelect.innerHTML = '<option value="">Select a pet</option>';
            data.pets.forEach(pet => {
                const selected = pet.id == petId ? 'selected' : '';
                petSelect.innerHTML += `<option value="${pet.id}" ${selected}>${pet.name}</option>`;
            });
        }
    } catch (err) {
        console.error('Error loading pets:', err);
    }

    modal.classList.add('active');
}

async function loadIncidents() {
    const container = document.getElementById('incidentsContainer');
    if (!container) return;

    try {
        const res = await fetch('incidents.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.incidents.length === 0) {
                container.innerHTML = `<div style="text-align: center; padding: 40px;"><i class="fas fa-clipboard-check" style="font-size: 64px; color: #ccc;"></i><p>No incidents reported.</p></div>`;
                return;
            }

            container.innerHTML = '<div style="display: grid; gap: 20px;"></div>';
            const incidentsGrid = container.querySelector('div');

            data.incidents.forEach(incident => {
                const incidentCard = document.createElement('div');
                incidentCard.style.cssText = 'background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);';
                incidentCard.innerHTML = `
                    <h3>${incident.incident_type}</h3>
                    <p>${incident.description}</p>
                    <p><small>Status: ${incident.status} | ${new Date(incident.incident_date).toLocaleString()}</small></p>
                `;
                incidentsGrid.appendChild(incidentCard);
            });
        }
    } catch (err) {
        console.error('Error:', err);
    }
}

async function openIncidentModal() {
    const modal = document.getElementById('incidentModal');
    const form = document.getElementById('incidentForm');
    
    form.reset();
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('incidentDate').value = now.toISOString().slice(0, 16);

    modal.classList.add('active');
}

function loadAnalyticsTrendsChart() {
    const canvas = document.getElementById('analyticsTrendsChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov'],
            datasets: [{
                label: 'Incidents',
                data: [4,7,8,6,5,9,10,12,11,8,6],
                borderColor: '#F44336',
                backgroundColor: 'rgba(244,67,54,0.1)'
            }]
        },
        options: { responsive: true }
    });
}

async function loadRecentAlerts() {
    const alertsContainer = document.querySelector('.alerts-section');
    if (!alertsContainer) return;

    alertsContainer.innerHTML = `
        <div class="alert-item success">
            <i class="fas fa-info-circle"></i>
            <span>Dashboard loaded successfully</span>
        </div>
    `;
}

// ===== MAIN INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    console.log("Dashboard initializing...");

    // Hamburger menu
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            hamburger.classList.remove('active');
        });
    }

    // Sidebar navigation
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a[data-section]');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            sidebarLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const section = this.getAttribute('data-section');
            const mainContent = document.getElementById('mainContent');
            
            switch(section) {
                case 'pets':
                    mainContent.innerHTML = `<div class="dashboard-section"><div class="section-header-modern"><h2><i class="fas fa-paw"></i> My Pets</h2><button class="btn-modern btn-primary" id="registerPetBtn2"><i class="fas fa-plus"></i> Add Pet</button></div><div id="sidebarPetsGrid" class="pets-grid"></div></div>`;
                    document.getElementById('registerPetBtn2').addEventListener('click', () => {
                        document.getElementById('petRegistrationModal').classList.add('active');
                    });
                    loadPetsInSidebar();
                    break;
                    
                case 'vaccinations':
                    mainContent.innerHTML = `<div class="dashboard-section"><div class="section-header-modern"><h2><i class="fas fa-syringe"></i> Vaccination Records</h2></div><div id="vaccinationPetsContainer"></div></div>`;
                    loadPetsWithVaccinations();
                    break;
                    
                case 'incidents':
                    mainContent.innerHTML = `<div class="dashboard-section"><div class="section-header-modern"><h2><i class="fas fa-exclamation-triangle"></i> Incident Reports</h2><button class="btn-modern btn-primary" onclick="openIncidentModal()"><i class="fas fa-plus"></i> Report Incident</button></div><div id="incidentsContainer"></div></div>`;
                    loadIncidents();
                    break;
                    
                case 'analytics':
                    mainContent.innerHTML = `<div class="dashboard-section"><h2><i class="fas fa-chart-line"></i> Analytics</h2><canvas id="analyticsTrendsChart" height="100"></canvas></div>`;
                    setTimeout(loadAnalyticsTrendsChart, 100);
                    break;
                    
                case 'settings':
    mainContent.innerHTML = `
        <div class="settings-grid">
            <!-- LEFT COLUMN - Profile -->
            <div class="settings-left-col">
                <!-- Profile Card -->
                <div class="settings-card">
                    <h3 class="settings-card-title">Profile</h3>
                    <div class="profile-settings-content">
                        <div class="profile-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=User&background=5B7FDB&color=fff&size=200" alt="Profile">
                            <button class="edit-avatar-btn"><i class="fas fa-camera"></i></button>
                        </div>
                        <div class="profile-info-grid">
                            <div class="profile-info-item">
                                <label>Full Name</label>
                                <input type="text" value="John Doe" class="settings-input">
                            </div>
                            <div class="profile-info-item">
                                <label>Role</label>
                                <input type="text" value="Pet Owner" class="settings-input" readonly>
                            </div>
                            <div class="profile-info-item">
                                <label>Email</label>
                                <input type="email" value="user@dogmonitor.com" class="settings-input">
                            </div>
                            <div class="profile-info-item">
                                <label>Phone</label>
                                <input type="tel" value="+1 234 567 8900" class="settings-input">
                            </div>
                        </div>
                        <div class="profile-social-links">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="settings-card">
                    <div class="card-header-with-edit">
                        <h3 class="settings-card-title">Basic Information</h3>
                        <button class="edit-btn-small"><i class="fas fa-pen"></i> Edit</button>
                    </div>
                    <div class="info-badges">
                        <span class="info-badge blue">Pet Owner ID: #PO-123</span>
                        <span class="info-badge green">Status: Active</span>
                        <span class="info-badge purple">Level: Premium</span>
                        <span class="info-badge orange">Since: 2025</span>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="settings-card">
                    <div class="card-header-with-edit">
                        <h3 class="settings-card-title">Personal Information</h3>
                        <button class="edit-btn-small"><i class="fas fa-pen"></i> Edit</button>
                    </div>
                    <div class="info-grid-two">
                        <div class="info-item">
                            <span class="info-label">Birth Date</span>
                            <span class="info-value">01/01/1990</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Address</span>
                            <span class="info-value">123 Pet Street, City</span>
                        </div>
                    </div>
                </div>

                <!-- Pet Owner Information -->
                <div class="settings-card">
                    <div class="card-header-with-edit">
                        <h3 class="settings-card-title">Pet Owner Information</h3>
                        <button class="edit-btn-small"><i class="fas fa-pen"></i> Edit</button>
                    </div>
                    <div class="owner-badges-grid">
                        <div class="owner-badge">
                            <i class="fas fa-dog"></i>
                            <span>Total Pets</span>
                            <strong>0</strong>
                        </div>
                        <div class="owner-badge">
                            <i class="fas fa-syringe"></i>
                            <span>Vaccinations</span>
                            <strong>Updated</strong>
                        </div>
                        <div class="owner-badge">
                            <i class="fas fa-clipboard-check"></i>
                            <span>Incidents</span>
                            <strong>0 Active</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="settings-right-col">
                <!-- Calendar Widget -->
                <div class="settings-card">
                    <h3 class="settings-card-title">Calendar</h3>
                    <div class="calendar-widget">
                        <div class="calendar-header">
                            <button class="calendar-nav-btn"><i class="fas fa-chevron-left"></i></button>
                            <span class="calendar-month">November 2025</span>
                            <button class="calendar-nav-btn"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="calendar-days">
                            <div class="calendar-day-label">Sun</div>
                            <div class="calendar-day-label">Mon</div>
                            <div class="calendar-day-label">Tue</div>
                            <div class="calendar-day-label">Wed</div>
                            <div class="calendar-day-label">Thu</div>
                            <div class="calendar-day-label">Fri</div>
                            <div class="calendar-day-label">Sat</div>
                        </div>
                        <div class="calendar-dates">
                            ${generateCalendarDates()}
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="settings-card">
                    <div class="card-header-with-action">
                        <h3 class="settings-card-title">Upcoming Events</h3>
                        <button class="view-all-btn">View All</button>
                    </div>
                    <div class="events-list">
                        <div class="event-item blue">
                            <div class="event-time">9:00 AM - 10:00 AM</div>
                            <div class="event-details">
                                <strong>Vet Checkup - Max</strong>
                                <div class="event-avatars">
                                    <div class="event-avatar">M</div>
                                </div>
                            </div>
                        </div>
                        <div class="event-item pink">
                            <div class="event-time">2:00 PM - 3:00 PM</div>
                            <div class="event-details">
                                <strong>Vaccination Due - Bella</strong>
                                <div class="event-avatars">
                                    <div class="event-avatar">B</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onboarding Progress -->
                <div class="settings-card">
                    <div class="card-header-with-action">
                        <h3 class="settings-card-title">Onboarding</h3>
                        <span class="progress-percentage">75% complete</span>
                    </div>
                    <div class="onboarding-list">
                        <div class="onboarding-item completed">
                            <div class="onboarding-icon"><i class="fas fa-check"></i></div>
                            <div class="onboarding-details">
                                <strong>Create your account</strong>
                                <span>Assigned to: You</span>
                            </div>
                            <span class="onboarding-date">07/15/2025</span>
                            <div class="onboarding-status">
                                <span class="status-badge complete">Complete</span>
                            </div>
                        </div>
                        <div class="onboarding-item completed">
                            <div class="onboarding-icon"><i class="fas fa-check"></i></div>
                            <div class="onboarding-details">
                                <strong>Add your first pet</strong>
                                <span>Assigned to: You</span>
                            </div>
                            <span class="onboarding-date">07/16/2025</span>
                            <div class="onboarding-status">
                                <span class="status-badge complete">Complete</span>
                            </div>
                        </div>
                        <div class="onboarding-item pending">
                            <div class="onboarding-icon"><i class="fas fa-clock"></i></div>
                            <div class="onboarding-details">
                                <strong>Complete pet profile</strong>
                                <span>Assigned to: You</span>
                            </div>
                            <span class="onboarding-date">07/20/2025</span>
                            <div class="onboarding-status">
                                <span class="status-badge pending">In Progress</span>
                            </div>
                        </div>
                        <div class="onboarding-item pending">
                            <div class="onboarding-icon"><i class="fas fa-clock"></i></div>
                            <div class="onboarding-details">
                                <strong>Schedule first vet visit</strong>
                                <span>Assigned to: You</span>
                            </div>
                            <span class="onboarding-date">07/25/2025</span>
                            <div class="onboarding-status">
                                <span class="status-badge todo">To Do</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    break;

                    
                default:
                    location.reload();
            }
            
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    });

    // Modal close buttons
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.modal').forEach(modal => modal.classList.remove('active'));
        });
    });

    // Register Pet Button
    // Register Pet Button (main header)
const registerPetBtn = document.getElementById('registerPetBtn');
if (registerPetBtn) {
    registerPetBtn.addEventListener('click', () => {
        document.getElementById('petRegistrationModal').classList.add('active');
    });
}

// Quick Action Add Pet Button (NEW - ADD THIS)
const quickAddPetBtn = document.getElementById('quickAddPetBtn');
if (quickAddPetBtn) {
    quickAddPetBtn.addEventListener('click', () => {
        document.getElementById('petRegistrationModal').classList.add('active');
    });
}

    

    // Image preview
    const imagePreview = document.getElementById('imagePreview');
    const petImageInput = document.getElementById('petImage');

    if (imagePreview && petImageInput) {
        imagePreview.addEventListener('click', () => {
            petImageInput.click();
        });
        
        petImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    imagePreview.style.backgroundImage = `url(${e.target.result})`;
                    imagePreview.innerHTML = '';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Pet Registration Form
const petRegistrationForm = document.getElementById('petRegistrationForm');
if (petRegistrationForm) {
    petRegistrationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        console.log("🐕 Form submitted!");
        
        const formData = new FormData(petRegistrationForm);
        const mode = petRegistrationForm.dataset.mode || 'register';

        if (mode === 'update') {
            formData.set('action', 'update');
            formData.append('pet_id', petRegistrationForm.dataset.petId);
        } else {
            formData.set('action', 'register');
        }

        console.log("📤 Sending to pets.php...");

        try {
            const res = await fetch('pets.php', { method: 'POST', body: formData });
            const text = await res.text();
            console.log("📥 Response:", text);
            
            const data = JSON.parse(text);

            if (data.status === 'success') {
                alert(mode === 'update' ? 'Pet updated successfully!' : 'Pet registered successfully!');
                petRegistrationForm.reset();
                
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.style.backgroundImage = '';
                imagePreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
                
                document.getElementById('petRegistrationModal').classList.remove('active');
                
                // Reset form mode
                delete petRegistrationForm.dataset.mode;
                delete petRegistrationForm.dataset.petId;
                
                // Reset submit button text
                const submitBtn = petRegistrationForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-paw"></i> Register Pet';
                
                loadPets();
                loadDashboardStats();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (err) {
            console.error('❌ Error:', err);
            alert('Error: ' + err.message);
        }
    });
}


    // Vaccination Form
    const vaccinationForm = document.getElementById('vaccinationForm');
    if (vaccinationForm) {
        vaccinationForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(vaccinationForm);

            try {
                const res = await fetch('vaccinations.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.status === 'success') {
                    alert('Vaccination added successfully!');
                    document.getElementById('vaccinationModal').classList.remove('active');
                    vaccinationForm.reset();
                    loadPetsWithVaccinations();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Failed to save vaccination record.');
            }
        });
    }

    // Incident Form
    const incidentForm = document.getElementById('incidentForm');
    if (incidentForm) {
        incidentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(incidentForm);

            try {
                const res = await fetch('incidents.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.status === 'success') {
                    alert('Incident reported successfully!');
                    document.getElementById('incidentModal').classList.remove('active');
                    incidentForm.reset();
                    loadIncidents();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Failed to report incident.');
            }
        });
    }

    // Logout button
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Logging out...</span>';
            setTimeout(() => window.location.href = 'logout.php', 800);
        });
    }

    // Initial load
    loadPets();
    loadDashboardStats();
    loadRecentAlerts();
});
