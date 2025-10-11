document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    const petRegistrationModal = document.getElementById('petRegistrationModal');
    const registerPetBtn = document.getElementById('registerPetBtn');
    const petRegistrationForm = document.getElementById('petRegistrationForm');
    const closeButtons = document.querySelectorAll('.modal-close');

    // Load pets on page load
    loadPets();

    // Sidebar active state
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            sidebarLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Register Pet Button Click
    if (registerPetBtn) {
        registerPetBtn.addEventListener('click', () => {
            petRegistrationModal.classList.add('active');
        });
    }

    // Close Modal Buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
            }
        });
    });

    // Image Preview
    const petImage = document.getElementById('petImage');
    const imagePreview = document.getElementById('imagePreview');

    if (petImage && imagePreview) {
        petImage.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.style.backgroundImage = `url(${e.target.result})`;
                    imagePreview.innerHTML = '';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.backgroundImage = '';
                imagePreview.innerHTML = `
                    <i class="fas fa-camera"></i>
                    <span>Click to upload image</span>
                `;
            }
        });
    }

    // Pet Registration Form Submit
    if (petRegistrationForm) {
        petRegistrationForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(petRegistrationForm);
            const messageContainer = petRegistrationForm.querySelector('.form-message-container');

            // Add the action parameter
            formData.append('action', 'register');

            // Log the FormData contents for debugging
            console.log('Submitting form with data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            try {
                const response = await fetch('pets.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                console.log('Server response:', data); // Debug logging

                if (data.status === 'success') {
                    showMessage(messageContainer, data.message, 'success');
                    petRegistrationForm.reset();
                    setTimeout(() => {
                        petRegistrationModal.classList.remove('active');
                        loadPets(); // Reload pets list
                    }, 2000);
                } else {
                    showMessage(messageContainer, data.message, 'error');
                }
            } catch (error) {
                showMessage(messageContainer, 'An error occurred. Please try again.', 'error');
            }
        });
    }

    // Add click handlers for action buttons
    document.querySelectorAll('.dashboard-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const action = this.querySelector('i')?.className || '';
            
            if (action.includes('flag')) {
                // Handle Report Incident
                showReportIncidentModal();
            } else if (action.includes('calendar')) {
                // Handle Schedule Vaccination
                showVaccinationModal();
            }
        });
    });
});

// Fetch dashboard statistics
async function fetchDashboardStats() {
    try {
        const response = await fetch('get_dashboard_stats.php');
        const data = await response.json();
        
        // Update statistics
        updateStatCard('total-pets', data.totalPets);
        updateStatCard('upcoming-vaccinations', data.upcomingVaccinations);
        updateStatCard('active-incidents', data.activeIncidents);
        updateStatCard('license-renewals', data.licenseRenewals);
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
}

function updateStatCard(id, value) {
    const statCard = document.getElementById(id);
    if (statCard) {
        statCard.querySelector('.stat-value').textContent = value;
    }
}

// Modal handling functions (to be implemented)
function showAddPetModal() {
    // Implementation coming in the pet registration module
    console.log('Opening Add Pet Modal...');
}

function showReportIncidentModal() {
    // Implementation coming in the incident reporting module
    console.log('Opening Report Incident Modal...');
}

function showVaccinationModal() {
    // Implementation coming in the vaccination module
    console.log('Opening Vaccination Modal...');
}

// Load pets
async function loadPets() {
    const petsGrid = document.getElementById('petsGrid');
    if (!petsGrid) return;

    try {
        const response = await fetch('pets.php');
        const data = await response.json();

        if (data.status === 'success') {
            if (data.pets.length === 0) {
                petsGrid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-paw" style="font-size: 3rem; color: #78909C;"></i>
                        <p>No pets registered yet. Click the "Register New Pet" button to add your first pet.</p>
                    </div>
                `;
                return;
            }

            petsGrid.innerHTML = data.pets.map(pet => `
                <div class="pet-card" data-pet-id="${pet.id}">
                    <div class="pet-image" style="background-image: url('${pet.image_url || ''}')">
                        ${!pet.image_url ? `<div class="pet-avatar">
                            <i class="fas fa-${pet.species.toLowerCase() === 'dog' ? 'dog' : 
                                           pet.species.toLowerCase() === 'cat' ? 'cat' : 'paw'}"></i>
                        </div>` : ''}
                    </div>
                    <div class="pet-header">
                        <div class="pet-info">
                            <h3>${pet.name}</h3>
                            <p>${pet.breed || pet.species}</p>
                        </div>
                    </div>
                    <div class="pet-stats">
                        <div class="pet-stat">
                            <div class="pet-stat-value">${pet.age || 'N/A'}</div>
                            <div class="pet-stat-label">Age</div>
                        </div>
                        <div class="pet-stat">
                            <div class="pet-stat-value">${pet.gender || 'N/A'}</div>
                            <div class="pet-stat-label">Gender</div>
                        </div>
                        <div class="pet-stat">
                            <div class="pet-stat-value">${pet.vaccination_count}</div>
                            <div class="pet-stat-label">Vaccines</div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            petsGrid.innerHTML = '<div class="error-message">Failed to load pets</div>';
        }
    } catch (error) {
        console.error('Error loading pets:', error);
        petsGrid.innerHTML = '<div class="error-message">Error loading pets</div>';
    }
}

// Show message in form
function showMessage(container, message, type) {
    container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

// Initialize dashboard
fetchDashboardStats();
loadPets();