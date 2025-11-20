document.addEventListener("DOMContentLoaded", function () {
    // Hamburger + sidebar overlay references
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    // Show/hide hamburger based on window size
    function updateHamburgerDisplay() {
        if (window.innerWidth <= 768) {
            hamburger.style.display = "flex";
        } else {
            hamburger.style.display = "none";
            sidebar.classList.remove("active");
            sidebarOverlay.classList.remove("active");
            hamburger.classList.remove("active");
        }
    }

    updateHamburgerDisplay();
    window.addEventListener("resize", updateHamburgerDisplay);

    // Hamburger click toggles menu
    hamburger.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        sidebarOverlay.classList.toggle("active");
        hamburger.classList.toggle("active");
    });

    // Sidebar overlay click closes menu
    sidebarOverlay.addEventListener("click", function () {
        sidebar.classList.remove("active");
        sidebarOverlay.classList.remove("active");
        hamburger.classList.remove("active");
    });

    // Sidebar link click closes menu on mobile
    document.querySelectorAll(".sidebar-menu a").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("active");
                sidebarOverlay.classList.remove("active");
                hamburger.classList.remove("active");
            }
        });
    });
    
    // (The rest of your dashboard initialization code follows below...)
    
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard Loaded');

    
    
    // ========================================
    // MOBILE HAMBURGER MENU
    // ========================================
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (hamburger && sidebar && sidebarOverlay) {
        // Show hamburger on mobile
        if (window.innerWidth <= 768) {
            hamburger.style.display = 'flex';
        }
        
        // Toggle sidebar
        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            hamburger.classList.remove('active');
        });
        
        // Close sidebar when clicking menu item
        const menuLinks = sidebar.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    hamburger.classList.remove('active');
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                hamburger.style.display = 'flex';
            } else {
                hamburger.style.display = 'none';
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    }
    
    // Rest of your existing code...
});



// ========================================
// POPUP NOTIFICATION SYSTEM (ADD AT TOP)
// ========================================
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                 type === 'error' ? 'fa-exclamation-circle' : 
                 type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    notification.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ========================================
// END NOTIFICATION SYSTEM
// ========================================


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

// Analytics Chart Initialization - FIXED VERSION
let analyticsChartInstance = null;

function initAnalyticsChart() {
    const canvas = document.getElementById('analyticsChart');
    if (!canvas) return;

    if (analyticsChartInstance) {
        analyticsChartInstance.destroy();
    }

    const ctx = canvas.getContext('2d');
    
    analyticsChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [
                {
                    label: 'This Month',
                    data: [25, 35, 42, 48],
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 8,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#8B5CF6',
                    borderWidth: 3
                },
                {
                    label: 'Previous Month',
                    data: [20, 28, 35, 40],
                    borderColor: '#E5E7EB',
                    backgroundColor: 'rgba(229, 231, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 8,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#E5E7EB',
                    borderWidth: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 60,
                    grid: {
                        color: '#F3F4F6'
                    },
                    ticks: {
                        stepSize: 10,
                        font: { size: 12 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 12 }
                    }
                }
            }
        }
    });
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
            
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    editPet(petId, data.pets);
                });
            });
            
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
    if (!pet) {
        showNotification('Pet not found', 'error');
        return;
    }

    // Populate form fields
    document.getElementById('petName').value = pet.name;
    document.getElementById('species').value = pet.species;
    document.getElementById('breed').value = pet.breed || '';
    document.getElementById('age').value = pet.age || '';
    document.getElementById('gender').value = pet.gender || '';

    // Show existing image if available
    const imagePreview = document.getElementById('imagePreview');
    if (pet.image_url && pet.image_url.trim() !== '') {
        imagePreview.style.backgroundImage = `url('${pet.image_url}')`;
        imagePreview.innerHTML = '';
    }

    // Set form to update mode
    const form = document.getElementById('petRegistrationForm');
    form.dataset.mode = 'update';
    form.dataset.petId = petId;

    // Change button text
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Pet';

    // Open modal
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
                        <button class="pet-btn edit-btn" data-pet-id="${pet.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                `;
                sidebarPetsGrid.appendChild(petCard);
            });
             document.querySelectorAll('#sidebarPetsGrid .edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    editPet(petId, data.pets);
                });
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
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-paw"></i>
                        <h3>No Pets Registered</h3>
                        <p>Register your first pet to start tracking vaccinations</p>
                        <button class="btn-enhanced btn-primary" onclick="document.getElementById('petRegistrationModal').classList.add('active')">
                            <i class="fas fa-plus"></i> Register Pet
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = '<div class="vaccination-pets-grid"></div>';
            const petsContainer = container.querySelector('.vaccination-pets-grid');

            petsData.pets.forEach(pet => {
                const imgSrc = pet.image_url && pet.image_url.trim() !== '' 
                    ? pet.image_url 
                    : 'https://via.placeholder.com/300x200?text=No+Image';
                
                const petCard = document.createElement('div');
                petCard.className = 'vaccination-pet-card';
                petCard.innerHTML = `
                    <div class="pet-card-header">
                        <div class="pet-image-small" style="background-image: url('${imgSrc}');"></div>
                        <div class="pet-info">
                            <h3>${pet.name}</h3>
                            <p class="pet-breed"><i class="fas fa-dog"></i> ${pet.species} - ${pet.breed || 'Mixed Breed'}</p>
                            <div class="pet-details-tags">
                                <span class="tag blue"><i class="fas fa-calendar"></i> Age: ${pet.age || 'N/A'}</span>
                                <span class="tag purple"><i class="fas fa-venus-mars"></i> ${pet.gender || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="vaccination-actions">
                        <button class="btn-vaccination-primary" onclick="openVaccinationModalForPet('${pet.id}', '${pet.name}')">
                            <i class="fas fa-syringe"></i> Add Vaccination Record
                        </button>
                        <button class="btn-vaccination-secondary" onclick="viewVaccinationHistory('${pet.id}')">
                            <i class="fas fa-history"></i> View History
                        </button>
                    </div>
                    
                    <div class="vaccination-status">
                        <div class="status-indicator up-to-date">
                            <i class="fas fa-check-circle"></i>
                            <span>Vaccination Status: Up to Date</span>
                        </div>
                    </div>
                `;
                petsContainer.appendChild(petCard);
            });
        }
    } catch (err) {
        console.error('Error:', err);
        container.innerHTML = `
            <div class="empty-state error">
                <i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i>
                <h3>Error Loading Pets</h3>
                <p>Unable to load pet data. Please try again.</p>
            </div>
        `;
    }
}

// Add this new function for viewing vaccination history
function viewVaccinationHistory(petId) {
    alert('Vaccination history for pet ID: ' + petId + ' (Feature coming soon!)');
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

    try {
        const res = await fetch('incidents.php');
        const data = await res.json();
        
        if (data.status === 'success' && data.incidents.length > 0) {
            alertsContainer.innerHTML = '';
            
            // Show last 5 incidents as alerts
            data.incidents.slice(0, 5).forEach(incident => {
                const alertType = incident.status === 'Resolved' ? 'success' : 
                                 incident.severity === 'Critical' || incident.severity === 'High' ? 'error' : 'warning';
                
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert-item ${alertType}`;
                alertDiv.innerHTML = `
                    <i class="fas ${alertType === 'success' ? 'fa-check-circle' : alertType === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'}"></i>
                    <div>
                        <strong>${incident.incident_type}</strong>
                        <span>${new Date(incident.incident_date).toLocaleString()}</span>
                    </div>
                `;
                alertsContainer.appendChild(alertDiv);
            });
        } else {
            alertsContainer.innerHTML = `
                <div class="alert-item success">
                    <i class="fas fa-info-circle"></i>
                    <span>No recent incidents</span>
                </div>
            `;
        }
    } catch (err) {
        console.error('Error loading alerts:', err);
        alertsContainer.innerHTML = `
            <div class="alert-item success">
                <i class="fas fa-info-circle"></i>
                <span>Dashboard loaded successfully</span>
            </div>
        `;
    }
}


// ===== MAIN INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    console.log("Dashboard initializing...");

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
    mainContent.innerHTML = `
        <div class="enhanced-section-wrapper">
            <div class="section-header-enhanced">
                <div class="header-left">
                    <div class="icon-badge purple">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div>
                        <h2>My Pets</h2>
                        <p class="section-subtitle">Manage all your registered pets</p>
                    </div>
                </div>
                <button class="btn-enhanced btn-primary" id="registerPetBtn2">
                    <i class="fas fa-plus"></i> Add New Pet
                </button>
            </div>
            
            <div class="stats-mini-row">
                <div class="stat-mini blue">
                    <i class="fas fa-dog"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Total Pets</div>
                    </div>
                </div>
                <div class="stat-mini green">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Vaccinated</div>
                    </div>
                </div>
                <div class="stat-mini orange">
                    <i class="fas fa-calendar-check"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Upcoming</div>
                    </div>
                </div>
            </div>
            
            <div id="sidebarPetsGrid" class="pets-grid-enhanced"></div>
        </div>
    `;
    document.getElementById('registerPetBtn2').addEventListener('click', () => {
        document.getElementById('petRegistrationModal').classList.add('active');
    });
    loadPetsInSidebar();
    break;

                    
                case 'vaccinations':
    mainContent.innerHTML = `
        <div class="enhanced-section-wrapper">
            <div class="section-header-enhanced">
                <div class="header-left">
                    <div class="icon-badge green">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div>
                        <h2>Vaccination Records</h2>
                        <p class="section-subtitle">Track all vaccination schedules and records</p>
                    </div>
                </div>
                <button class="btn-enhanced btn-success" id="addVaccinationBtnTop">
                    <i class="fas fa-plus"></i> Add Vaccination
                </button>
            </div>

            <div class="stats-mini-row">
                <div class="stat-mini green">
                    <i class="fas fa-syringe"></i>
                    <div>
                        <div class="stat-mini-value" id="totalVaccinesCount">0</div>
                        <div class="stat-mini-label">Total Vaccines</div>
                    </div>
                </div>
                <div class="stat-mini orange">
                    <i class="fas fa-clock"></i>
                    <div>
                        <div class="stat-mini-value" id="dueSoonCount">0</div>
                        <div class="stat-mini-label">Due Soon</div>
                    </div>
                </div>
                <div class="stat-mini blue">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <div class="stat-mini-value" id="thisMonthCount">0</div>
                        <div class="stat-mini-label">This Month</div>
                    </div>
                </div>
            </div>

            <!-- Pets Display with Quick Add Vaccination -->
            <div id="vaccinationPetsContainer" class="vaccinations-grid-enhanced"></div>

            <!-- Vaccination History Section -->
            <div class="vaccination-history-section">
                <div class="history-header">
                    <h3><i class="fas fa-history"></i> Vaccination History</h3>
                    <button class="btn-enhanced btn-success" id="addVaccinationBtnBottom">
                        <i class="fas fa-plus"></i> Add Vaccination
                    </button>
                </div>
                <div id="vaccinationHistoryContainer">
                    <p style="text-align: center; padding: 40px; color: #999;">Loading vaccination records...</p>
                </div>
            </div>
        </div>
    `;
    
    // Add event listeners for both Add Vaccination buttons
    document.getElementById('addVaccinationBtnTop').addEventListener('click', () => {
        openVaccinationModal();
    });
    
    document.getElementById('addVaccinationBtnBottom').addEventListener('click', () => {
        openVaccinationModal();
    });
    
    // Load pets with vaccination status
    loadPetsWithVaccinations();
    
    // Load vaccination history
    loadVaccinationHistory();
    break;



                    
                case 'incidents':
    mainContent.innerHTML = `
        <div class="enhanced-section-wrapper">
            <div class="section-header-enhanced">
                <div class="header-left">
                    <div class="icon-badge red">
                        <i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i>
                    </div>
                    <div>
                        <h2>Incident Reports</h2>
                        <p class="section-subtitle">View and manage all incident reports</p>
                    </div>
                </div>
                <button class="btn-enhanced btn-danger" onclick="openIncidentModal()">
                    <i class="fas fa-plus"></i> Report Incident
                </button>
            </div>
            
            <div class="stats-mini-row">
                <div class="stat-mini red">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Active</div>
                    </div>
                </div>
                <div class="stat-mini yellow">
                    <i class="fas fa-hourglass-half"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Pending</div>
                    </div>
                </div>
                <div class="stat-mini green">
                    <i class="fas fa-check-double"></i>
                    <div>
                        <div class="stat-mini-value">0</div>
                        <div class="stat-mini-label">Resolved</div>
                    </div>
                </div>
            </div>
            
            <div id="incidentsContainer" class="incidents-grid-enhanced"></div>
        </div>
    `;
    loadIncidents();
    break;

                    
                case 'analytics':
                    mainContent.innerHTML = `
                        <div id="analyticsSection" class="analytics-content">
                            <div class="analytics-header">
                                <div class="analytics-greeting">
                                    <h1>Hello User!</h1>
                                    <p class="subtitle">Welcome back to WATCHDOG!</p>
                                </div>
                                <div class="analytics-search">
                                    <div class="search-bar">
                                        <i class="fas fa-search"></i>
                                        <input type="text" placeholder="Search pets, records...">
                                    </div>
                                    <button class="btn-add-task">
                                        <i class="fas fa-plus"></i> Add New Pet
                                    </button>
                                </div>
                            </div>

                            <div class="analytics-grid-centered">
                                <div class="analytics-left">
                                    <div class="projects-widget">
                                        <h3>Active Categories</h3>
                                        <ul class="project-list">
                                            <li class="project-item">
                                                <i class="fas fa-dog" style="color: #8B5CF6;"></i>
                                                <span>Pet Registrations</span>
                                            </li>
                                            <li class="project-item">
                                                <i class="fas fa-syringe" style="color: #3B82F6;"></i>
                                                <span>Vaccinations</span>
                                            </li>
                                            <li class="project-item">
                                                <i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i>
                                                <span>Incident Reports</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="analytics-stats-left">
                                        <div class="stat-card-analytics purple">
                                            <div class="stat-number">50+</div>
                                            <div class="stat-label">REGISTERED PETS</div>
                                            <div class="stat-icon-mini">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                        </div>

                                        <div class="stat-card-analytics purple">
                                            <div class="stat-number">45</div>
                                            <div class="stat-label">VACCINATIONS</div>
                                            <div class="stat-icon-mini">
                                                <i class="fas fa-syringe"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="activities-widget">
                                        <h3>Recent Activities</h3>
                                        <ul class="activity-list">
                                            <li class="activity-item">
                                                <i class="fas fa-dog"></i>
                                                <div class="activity-info">
                                                    <span class="activity-name">Pet Registration</span>
                                                    <span class="activity-time">10:42:23 AM</span>
                                                    <span class="activity-amount">New: Max</span>
                                                </div>
                                                <span class="activity-status completed">Completed</span>
                                            </li>
                                            <li class="activity-item">
                                                <i class="fas fa-syringe"></i>
                                                <div class="activity-info">
                                                    <span class="activity-name">Vaccination Record</span>
                                                    <span class="activity-time">09:23:46 AM</span>
                                                    <span class="activity-amount">Updated: Bella</span>
                                                </div>
                                                <span class="activity-status completed">Completed</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="analytics-center-large">
                                    <div class="chart-widget-large">
                                        <div class="chart-header">
                                            <div class="chart-legend">
                                                <span class="legend-item">
                                                    <span class="legend-dot income"></span> This Month
                                                </span>
                                                <span class="legend-item">
                                                    <span class="legend-dot previous"></span> Previous Month
                                                </span>
                                            </div>
                                            <div class="chart-period">
                                                <button class="period-badge active">November 2025</button>
                                            </div>
                                        </div>
                                        <div class="chart-container-large">
                                            <canvas id="analyticsChart"></canvas>
                                        </div>
                                        <div class="chart-stats-row">
                                            <div class="chart-stat-item">
                                                <i class="fas fa-paw"></i>
                                                <div>
                                                    <div class="stat-value">124</div>
                                                    <div class="stat-label">Total Pets</div>
                                                </div>
                                            </div>
                                            <div class="chart-stat-item">
                                                <i class="fas fa-syringe"></i>
                                                <div>
                                                    <div class="stat-value">89</div>
                                                    <div class="stat-label">Vaccinations</div>
                                                </div>
                                            </div>
                                            <div class="chart-stat-item">
                                                <i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i>
                                                <div>
                                                    <div class="stat-value">12</div>
                                                    <div class="stat-label">Incidents</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-widget">
                                        <h3>Vaccination Coverage</h3>
                                        <div class="progress-circle-container">
                                            <svg class="progress-ring" width="200" height="200">
                                                <circle class="progress-ring-circle-bg" stroke="#E5E7EB" stroke-width="20" fill="transparent" r="80" cx="100" cy="100"/>
                                                <circle class="progress-ring-circle" stroke="url(#gradient)" stroke-width="20" fill="transparent" r="80" cx="100" cy="100" stroke-dasharray="502.65" stroke-dashoffset="55.66"/>
                                                <defs>
                                                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                        <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#EC4899;stop-opacity:1" />
                                                    </linearGradient>
                                                </defs>
                                            </svg>
                                            <div class="progress-text">
                                                <div class="progress-percent">89%</div>
                                                <div class="progress-label">Up to Date</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="analytics-right">
                                    <div class="calendar-widget">
                                        <div class="calendar-header">
                                            <h3>Appointment Calendar</h3>
                                            <span class="calendar-subtitle">November 2025</span>
                                            <div class="calendar-nav">
                                                <button><i class="fas fa-chevron-left"></i></button>
                                                <button><i class="fas fa-chevron-right"></i></button>
                                            </div>
                                        </div>
                                        <div class="calendar-grid">
                                            <div class="calendar-days">
                                                <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                                            </div>
                                            <div class="calendar-dates">
                                                <span></span><span></span><span></span><span>1</span><span>2</span><span>3</span><span>4</span>
                                                <span>5</span><span>6</span><span>7</span><span>8</span><span>9</span><span>10</span><span>11</span>
                                                <span>12</span><span class="today">13</span><span>14</span><span>15</span><span class="selected">16</span><span>17</span><span>18</span>
                                                <span>19</span><span>20</span><span>21</span><span>22</span><span>23</span><span>24</span><span>25</span>
                                                <span>26</span><span>27</span><span>28</span><span>29</span><span>30</span><span></span><span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="message-widget">
                                        <h3>Recent Notifications</h3>
                                        <ul class="message-list">
                                            <li class="message-item">
                                                <div class="message-icon"><i class="fas fa-syringe"></i></div>
                                                <div class="message-content">
                                                    <div class="message-name">Vaccination Due</div>
                                                    <div class="message-text">Max needs rabies booster shot</div>
                                                </div>
                                            </li>
                                            <li class="message-item">
                                                <div class="message-icon warning"><i class="fa-solid fa-triangle-exclamation" style="color: #ff2600;"></i></div>
                                                <div class="message-content">
                                                    <div class="message-name">Incident Report</div>
                                                    <div class="message-text">New incident reported in Area 5</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    setTimeout(initAnalyticsChart, 100);
                    break;
                    
                case 'settings':
                    mainContent.innerHTML = `
                        <div class="settings-grid">
                            <div class="settings-left-col">
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

                            <div class="settings-right-col">
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

    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.modal').forEach(modal => modal.classList.remove('active'));
        });
    });

    const registerPetBtn = document.getElementById('registerPetBtn');
if (registerPetBtn) {
    registerPetBtn.addEventListener('click', () => {
        // Reset form for new pet
        const form = document.getElementById('petRegistrationForm');
        form.reset();
        delete form.dataset.mode;
        delete form.dataset.petId;
        
        // Reset image preview
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.style.backgroundImage = '';
        imagePreview.innerHTML = '<i class="fas fa-camera"></i><span>Click to upload image</span>';
        
        // Reset button text
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-paw"></i> Register Pet';
        
        document.getElementById('petRegistrationModal').classList.add('active');
    });
}


    const quickAddPetBtn = document.getElementById('quickAddPetBtn');
if (quickAddPetBtn) {
    quickAddPetBtn.addEventListener('click', () => {
        // Reset form for new pet
        const form = document.getElementById('petRegistrationForm');
        form.reset();
        delete form.dataset.mode;
        delete form.dataset.petId;
        
        // Reset image preview
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.style.backgroundImage = '';
        imagePreview.innerHTML = '<i class="fas fa-camera"></i><span>Click to upload image</span>';
        
        // Reset button text
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-paw"></i> Register Pet';
        
        document.getElementById('petRegistrationModal').classList.add('active');
    });
}


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
                    showNotification(mode === 'update' ? 'Pet updated successfully!' : 'Pet registered successfully!', 'success');

                    petRegistrationForm.reset();
                    
                    const imagePreview = document.getElementById('imagePreview');
                    imagePreview.style.backgroundImage = '';
                    imagePreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
                    
                    document.getElementById('petRegistrationModal').classList.remove('active');
                    
                    delete petRegistrationForm.dataset.mode;
                    delete petRegistrationForm.dataset.petId;
                    
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

    const vaccinationForm = document.getElementById('vaccinationForm');
if (vaccinationForm) {
    vaccinationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(vaccinationForm);
        const mode = vaccinationForm.dataset.mode || 'add';
        
        formData.append('action', mode);
        
        if (mode === 'update') {
            formData.append('vacc_id', vaccinationForm.dataset.vaccId);
        }
        
        try {
            const res = await fetch('vaccinations.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await res.json();
            
            if (data.status === 'success') {
                showNotification(mode === 'update' ? 'Vaccination updated successfully!' : 'Vaccination added successfully!', 'success');
                document.getElementById('vaccinationModal').classList.remove('active');
                vaccinationForm.reset();
                
                // Reset form mode
                delete vaccinationForm.dataset.mode;
                delete vaccinationForm.dataset.vaccId;
                
                // Reset button text
                const submitBtn = vaccinationForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-syringe"></i> Save Vaccination Record';
                
                // Reload both sections
                loadVaccinationHistory();
                loadPetsWithVaccinations();
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showNotification('Failed to save vaccination record', 'error');
        }
    });
}



    const incidentForm = document.getElementById('incidentForm');
if (incidentForm) {
    incidentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(incidentForm);
        
        // ✅ ADD THIS LINE - THIS WAS MISSING!
        formData.append('action', 'add');

        try {
            const res = await fetch('incidents.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.status === 'success') {
                // ✅ REPLACED alert with showNotification
                showNotification('Incident reported successfully!', 'success');
                document.getElementById('incidentModal').classList.remove('active');
                incidentForm.reset();
                loadIncidents();
                loadRecentAlerts(); // ✅ Refresh alerts
            } else {
                // ✅ REPLACED alert with showNotification
                showNotification('Error: ' + data.message, 'error');
            }
        } catch (err) {
            // ✅ REPLACED alert with showNotification
            showNotification('Failed to report incident.', 'error');
            console.error(err);
        }
    });
}


    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Logging out...</span>';
            setTimeout(() => window.location.href = 'logout.php', 800);
        });
    }

    loadPets();
    loadDashboardStats();
    loadRecentAlerts();

    // ===== VACCINATION FUNCTIONS =====

// Function to open vaccination modal
async function openVaccinationModal() {
    const modal = document.getElementById('vaccinationModal');
    const petSelect = document.getElementById('vaccPetId');
    
    try {
        const res = await fetch('pets.php');
        const data = await res.json();
        
        if (data.status === 'success' && data.pets.length > 0) {
            petSelect.innerHTML = '<option value="">Select a pet</option>';
            data.pets.forEach(pet => {
                petSelect.innerHTML += `<option value="${pet.id}">${pet.name}</option>`;
            });
        } else {
            petSelect.innerHTML = '<option value="">No pets registered</option>';
            showNotification('Please register a pet first', 'warning');
            return;
        }
    } catch (err) {
        console.error('Error loading pets:', err);
    }
    
    modal.classList.add('active');
}

// ===== VACCINATION FUNCTIONS =====

// Function to open vaccination modal
async function openVaccinationModal() {
    const modal = document.getElementById('vaccinationModal');
    const petSelect = document.getElementById('vaccPetId');
    
    try {
        const res = await fetch('pets.php');
        const data = await res.json();
        
        if (data.status === 'success' && data.pets.length > 0) {
            petSelect.innerHTML = '<option value="">Select a pet</option>';
            data.pets.forEach(pet => {
                petSelect.innerHTML += `<option value="${pet.id}">${pet.name}</option>`;
            });
        } else {
            petSelect.innerHTML = '<option value="">No pets registered</option>';
            showNotification('Please register a pet first', 'warning');
            return;
        }
    } catch (err) {
        console.error('Error loading pets:', err);
    }
    
    modal.classList.add('active');
}


// Helper function to get vaccination status
function getVaccinationStatus(nextDueDate) {
    if (!nextDueDate) return 'No Due Date';
    
    const today = new Date();
    const dueDate = new Date(nextDueDate);
    const diffTime = dueDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) return 'Overdue';
    if (diffDays <= 30) return 'Due Soon';
    return 'Up to Date';
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Function to update vaccination stats
function updateVaccinationStats(vaccinations) {
    const totalCount = vaccinations.length;
    
    // Count due soon (within 30 days)
    const today = new Date();
    const dueSoonCount = vaccinations.filter(v => {
        if (!v.next_due_date) return false;
        const dueDate = new Date(v.next_due_date);
        const diffDays = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
        return diffDays > 0 && diffDays <= 30;
    }).length;
    
    // Count this month
    const thisMonthCount = vaccinations.filter(v => {
        const vaccDate = new Date(v.date_given);
        return vaccDate.getMonth() === today.getMonth() && vaccDate.getFullYear() === today.getFullYear();
    }).length;
    
    // Update UI
    const totalEl = document.getElementById('totalVaccinesCount');
    const dueSoonEl = document.getElementById('dueSoonCount');
    const thisMonthEl = document.getElementById('thisMonthCount');
    
    if (totalEl) totalEl.textContent = totalCount;
    if (dueSoonEl) dueSoonEl.textContent = dueSoonCount;
    if (thisMonthEl) thisMonthEl.textContent = thisMonthCount;
}

// Function to edit vaccination
async function editVaccination(vaccId) {
    try {
        const res = await fetch('vaccinations.php');
        const data = await res.json();
        
        if (data.status === 'success') {
            const vacc = data.vaccinations.find(v => v.id == vaccId);
            if (!vacc) {
                showNotification('Vaccination record not found', 'error');
                return;
            }
            
            // Populate form
            document.getElementById('vaccPetId').value = vacc.pet_id;
            document.getElementById('vaccineName').value = vacc.vaccine_name;
            document.getElementById('dateGiven').value = vacc.date_given;
            document.getElementById('nextDueDate').value = vacc.next_due_date || '';
            document.getElementById('veterinarian').value = vacc.veterinarian || '';
            document.getElementById('vaccNotes').value = vacc.notes || '';
            
            // Set form to update mode
            const form = document.getElementById('vaccinationForm');
            form.dataset.mode = 'update';
            form.dataset.vaccId = vaccId;
            
            // Change button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Vaccination';
            
            // Open modal
            document.getElementById('vaccinationModal').classList.add('active');
        }
    } catch (err) {
        console.error('Error:', err);
        showNotification('Error loading vaccination record', 'error');
    }
}

// Function to delete vaccination
async function deleteVaccination(vaccId, vaccineName) {
    if (!confirm(`Are you sure you want to delete the vaccination record for "${vaccineName}"?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('vacc_id', vaccId);
    
    try {
        const res = await fetch('vaccinations.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await res.json();
        
        if (data.status === 'success') {
            showNotification('Vaccination record deleted successfully!', 'success');
            loadVaccinationHistory();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (err) {
        console.error('Error:', err);
        showNotification('Failed to delete vaccination record', 'error');
    }
}

// ===== VACCINATION HISTORY FUNCTIONS =====

async function loadVaccinationHistory() {
    const container = document.getElementById('vaccinationHistoryContainer');
    
    if (!container) return;
    
    try {
        const res = await fetch('vaccinations.php');
        const data = await res.json();
        
        if (data.status === 'success') {
            if (data.vaccinations.length === 0) {
                container.innerHTML = `
                    <div class="empty-state-small">
                        <i class="fas fa-syringe" style="font-size: 48px; color: #ddd;"></i>
                        <p>No vaccination records yet</p>
                    </div>
                `;
                updateVaccinationStats([]);
                return;
            }
            
            updateVaccinationStats(data.vaccinations);
            
            let tableHTML = `
                <div class="table-responsive">
                    <table class="vaccination-table">
                        <thead>
                            <tr>
                                <th>Pet Name</th>
                                <th>Vaccine Name</th>
                                <th>Date Given</th>
                                <th>Next Due Date</th>
                                <th>Veterinarian</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.vaccinations.forEach(vacc => {
                const status = getVaccinationStatus(vacc.next_due_date);
                const statusClass = status === 'Overdue' ? 'overdue' : status === 'Due Soon' ? 'due-soon' : 'up-to-date';
                
                tableHTML += `
                    <tr>
                        <td data-label="Pet Name"><strong>${vacc.pet_name}</strong></td>
                        <td data-label="Vaccine">${vacc.vaccine_name}</td>
                        <td data-label="Date Given">${formatDate(vacc.date_given)}</td>
                        <td data-label="Next Due">${vacc.next_due_date ? formatDate(vacc.next_due_date) : 'N/A'}</td>
                        <td data-label="Veterinarian">${vacc.veterinarian || 'N/A'}</td>
                        <td data-label="Status"><span class="status-badge ${statusClass}">${status}</span></td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <button class="action-btn edit" onclick="editVaccination(${vacc.id})" title="Edit">
                                    <i class="fas fa-edit"></i> <span style="margin-left: 6px;">Edit</span>
                                </button>
                                <button class="action-btn delete" onclick="deleteVaccination(${vacc.id}, '${vacc.vaccine_name.replace(/'/g, "\\'")}', '${vacc.pet_name.replace(/'/g, "\\'")}' )" title="Delete">
                                    <i class="fas fa-trash"></i> <span style="margin-left: 6px;">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tableHTML += `
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = tableHTML;
        } else {
            container.innerHTML = `<p style="color: #999; text-align: center; padding: 40px;">Error loading vaccination records</p>`;
        }
    } catch (err) {
        console.error('Error:', err);
        container.innerHTML = `<p style="color: #999; text-align: center; padding: 40px;">Error loading vaccination records</p>`;
    }
}


// Helper function to get vaccination status
function getVaccinationStatus(nextDueDate) {
    if (!nextDueDate) return 'No Due Date';
    
    const today = new Date();
    const dueDate = new Date(nextDueDate);
    const diffTime = dueDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) return 'Overdue';
    if (diffDays <= 30) return 'Due Soon';
    return 'Up to Date';
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Function to update vaccination stats
function updateVaccinationStats(vaccinations) {
    const totalCount = vaccinations.length;
    
    // Count due soon (within 30 days)
    const today = new Date();
    const dueSoonCount = vaccinations.filter(v => {
        if (!v.next_due_date) return false;
        const dueDate = new Date(v.next_due_date);
        const diffDays = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
        return diffDays > 0 && diffDays <= 30;
    }).length;
    
    // Count this month
    const thisMonthCount = vaccinations.filter(v => {
        const vaccDate = new Date(v.date_given);
        return vaccDate.getMonth() === today.getMonth() && vaccDate.getFullYear() === today.getFullYear();
    }).length;
    
    // Update UI
    const totalEl = document.getElementById('totalVaccinesCount');
    const dueSoonEl = document.getElementById('dueSoonCount');
    const thisMonthEl = document.getElementById('thisMonthCount');
    
    if (totalEl) totalEl.textContent = totalCount;
    if (dueSoonEl) dueSoonEl.textContent = dueSoonCount;
    if (thisMonthEl) thisMonthEl.textContent = thisMonthCount;
}

// Function to open vaccination modal
async function openVaccinationModal(petId = null) {
    const modal = document.getElementById('vaccinationModal');
    const petSelect = document.getElementById('vaccPetId');
    
    try {
        const res = await fetch('pets.php');
        const data = await res.json();
        
        if (data.status === 'success' && data.pets.length > 0) {
            petSelect.innerHTML = '<option value="">Select a pet</option>';
            data.pets.forEach(pet => {
                const selected = (pet.id == petId) ? 'selected' : '';
                petSelect.innerHTML += `<option value="${pet.id}" ${selected}>${pet.name}</option>`;
            });
        } else {
            petSelect.innerHTML = '<option value="">No pets registered</option>';
            showNotification('Please register a pet first', 'warning');
            return;
        }
    } catch (err) {
        console.error('Error loading pets:', err);
    }
    
    modal.classList.add('active');
}

// Function to edit vaccination
async function editVaccination(vaccId) {
    try {
        const res = await fetch('vaccinations.php');
        const data = await res.json();
        
        if (data.status === 'success') {
            const vacc = data.vaccinations.find(v => v.id == vaccId);
            if (!vacc) {
                showNotification('Vaccination record not found', 'error');
                return;
            }
            
            // Load pets first
            await openVaccinationModal(vacc.pet_id);
            
            // Populate form
            document.getElementById('vaccineName').value = vacc.vaccine_name;
            document.getElementById('dateGiven').value = vacc.date_given;
            document.getElementById('nextDueDate').value = vacc.next_due_date || '';
            document.getElementById('veterinarian').value = vacc.veterinarian || '';
            document.getElementById('vaccNotes').value = vacc.notes || '';
            
            // Set form to update mode
            const form = document.getElementById('vaccinationForm');
            form.dataset.mode = 'update';
            form.dataset.vaccId = vaccId;
            
            // Change button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Vaccination';
        }
    } catch (err) {
        console.error('Error:', err);
        showNotification('Error loading vaccination record', 'error');
    }
}

// Function to delete vaccination
async function deleteVaccination(vaccId, vaccineName, petName) {
    if (!confirm(`Are you sure you want to delete the "${vaccineName}" vaccination record for ${petName}?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('vacc_id', vaccId);
    
    try {
        const res = await fetch('vaccinations.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await res.json();
        
        if (data.status === 'success') {
            showNotification('Vaccination record deleted successfully!', 'success');
            loadVaccinationHistory();
            loadPetsWithVaccinations(); // Refresh pet cards too
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (err) {
        console.error('Error:', err);
        showNotification('Failed to delete vaccination record', 'error');
    }
}

// Keep your existing openVaccinationModalForPet function
async function openVaccinationModalForPet(petId, petName) {
    await openVaccinationModal(petId);
}

});
