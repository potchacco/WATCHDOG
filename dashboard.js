// ===== GLOBAL FUNCTIONS (outside DOMContentLoaded) =====

// ===== Load pets on dashboard =====
async function loadPets() {
    const petsGrid = document.getElementById('petsGrid');
    if (!petsGrid) return;
    
    petsGrid.innerHTML = '<p>Loading pets...</p>';
    try {
        const res = await fetch('pets.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.pets.length === 0) {
                petsGrid.innerHTML = '<p>No pets registered yet.</p>';
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
                        <p>Breed - ${pet.breed || 'Unknown'}</p>
                        <p>Age: ${pet.age || 'N/A'} <br> Gender: ${pet.gender || 'N/A'}</p>
                    </div>
                `;
                petsGrid.appendChild(petDiv);
            });
        } else {
            petsGrid.innerHTML = '<p>Failed to load pets.</p>';
        }
    } catch (err) {
        petsGrid.innerHTML = '<p>Error loading pets.</p>';
        console.error(err);
    }
}

// ===== Load pets in sidebar =====
async function loadPetsInSidebar() {
    const sidebarPetsGrid = document.getElementById('sidebarPetsGrid');
    if (!sidebarPetsGrid) return;

    try {
        const res = await fetch('pets.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.pets.length === 0) {
                sidebarPetsGrid.innerHTML = '<p style="text-align: center; padding: 40px;">No pets registered yet. Click "Add Pet" to register one.</p>';
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
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="dashboard-btn btn-secondary btn-sm edit-pet-btn" data-pet-id="${pet.id}" style="flex: 1;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="dashboard-btn btn-secondary btn-sm delete-pet-btn" data-pet-id="${pet.id}" style="flex: 1; background: #f44336;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
                sidebarPetsGrid.appendChild(petCard);
            });

            document.querySelectorAll('.edit-pet-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    editPet(petId, data.pets);
                });
            });

            document.querySelectorAll('.delete-pet-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    deletePet(petId);
                });
            });
        } else {
            sidebarPetsGrid.innerHTML = '<p>Failed to load pets.</p>';
        }
    } catch (err) {
        console.error('Error:', err);
        sidebarPetsGrid.innerHTML = '<p>Error loading pets.</p>';
    }
}

async function deletePet(petId) {
    if (!confirm('Are you sure you want to delete this pet? This action cannot be undone.')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('pet_id', petId);

        const res = await fetch('pets.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            alert('Pet deleted successfully!');
            loadPetsInSidebar();
            loadPets();
            loadDashboardStats();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (err) {
        console.error('Error:', err);
        alert('Failed to delete pet. Please try again.');
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

async function loadDashboardStats() {
    try {
        const res = await fetch('get_stats.php');
        const data = await res.json();

        if (data.status === 'success') {
            const totalPetsEl = document.querySelector('.stat-card:nth-child(1) .stat-value');
            if (totalPetsEl) totalPetsEl.textContent = data.stats.total_pets;
            
            const vaccinationsEl = document.querySelector('.stat-card:nth-child(2) .stat-value');
            if (vaccinationsEl) vaccinationsEl.textContent = data.stats.vaccinations_due;
            
            const incidentsEl = document.querySelector('.stat-card:nth-child(3) .stat-value');
            if (incidentsEl) incidentsEl.textContent = data.stats.active_incidents;
            
            const renewalsEl = document.querySelector('.stat-card:nth-child(4) .stat-value');
            if (renewalsEl) renewalsEl.textContent = data.stats.license_renewals;
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

        const vaccRes = await fetch('vaccinations.php');
        const vaccData = await vaccRes.json();

        if (petsData.status === 'success') {
            if (petsData.pets.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-paw" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                        <p style="font-size: 18px; color: #666;">No pets registered yet.</p>
                        <p style="color: #999;">Please register a pet first to manage vaccinations.</p>
                    </div>
                `;
                return;
            }

            const vaccinationsByPet = {};
            if (vaccData.status === 'success') {
                vaccData.vaccinations.forEach(vacc => {
                    if (!vaccinationsByPet[vacc.pet_id]) {
                        vaccinationsByPet[vacc.pet_id] = [];
                    }
                    vaccinationsByPet[vacc.pet_id].push(vacc);
                });
            }

            container.innerHTML = '<div style="display: grid; gap: 20px;"></div>';
            const petsContainer = container.querySelector('div');

            petsData.pets.forEach(pet => {
                const petVaccinations = vaccinationsByPet[pet.id] || [];
                const imgSrc = pet.image_url || 'https://via.placeholder.com/150x150?text=No+Image';
                
                let vaccinationList = '';
                if (petVaccinations.length === 0) {
                    vaccinationList = '<p style="color: #999; font-style: italic;">No vaccination records yet</p>';
                } else {
                    vaccinationList = '<div style="margin-top: 10px;">';
                    petVaccinations.forEach(vacc => {
                        const dateGiven = new Date(vacc.date_given).toLocaleDateString();
                        const nextDue = vacc.next_due_date ? new Date(vacc.next_due_date).toLocaleDateString() : 'N/A';
                        const isOverdue = vacc.next_due_date && new Date(vacc.next_due_date) < new Date();
                        
                        vaccinationList += `
                            <div style="background: #f8f9fa; padding: 10px; margin-bottom: 8px; border-radius: 5px; border-left: 3px solid ${isOverdue ? '#f44336' : '#4CAF50'};">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong>${vacc.vaccine_name}</strong><br>
                                        <small style="color: #666;">Given: ${dateGiven} | Next Due: ${nextDue}</small>
                                        ${vacc.veterinarian ? `<br><small style="color: #666;">Vet: ${vacc.veterinarian}</small>` : ''}
                                    </div>
                                    <div>
                                        <button class="dashboard-btn btn-secondary btn-sm edit-vacc-btn" data-vacc-id="${vacc.id}" style="margin-right: 5px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="dashboard-btn btn-secondary btn-sm delete-vacc-btn" data-vacc-id="${vacc.id}" style="background: #f44336;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    vaccinationList += '</div>';
                }

                const petCard = document.createElement('div');
                petCard.style.cssText = 'background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px; display: grid; grid-template-columns: 150px 1fr; gap: 20px;';
                petCard.innerHTML = `
                    <div>
                        <div style="width: 150px; height: 150px; border-radius: 8px; background-image: url('${imgSrc}'); background-size: cover; background-position: center;"></div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h2 style="margin: 0 0 5px 0;">${pet.name}</h2>
                                <p style="margin: 0; color: #666;">
                                    <i class="fas fa-dog"></i> ${pet.species} - ${pet.breed || 'Unknown breed'}
                                    <br>
                                    <i class="fas fa-birthday-cake"></i> Age: ${pet.age || 'N/A'} years | 
                                    <i class="fas fa-venus-mars"></i> ${pet.gender || 'N/A'}
                                </p>
                            </div>
                            <button class="dashboard-btn btn-primary add-vaccination-for-pet-btn" data-pet-id="${pet.id}" data-pet-name="${pet.name}">
                                <i class="fas fa-syringe"></i> Update Vaccination Status
                            </button>
                        </div>
                        <div>
                            <h3 style="margin: 10px 0; font-size: 16px;">Vaccination History</h3>
                            ${vaccinationList}
                        </div>
                    </div>
                `;
                petsContainer.appendChild(petCard);
            });

            document.querySelectorAll('.add-vaccination-for-pet-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const petId = this.getAttribute('data-pet-id');
                    const petName = this.getAttribute('data-pet-name');
                    openVaccinationModalForPet(petId, petName);
                });
            });

            document.querySelectorAll('.edit-vacc-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const vaccId = this.getAttribute('data-vacc-id');
                    editVaccinationById(vaccId);
                });
            });

            document.querySelectorAll('.delete-vacc-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const vaccId = this.getAttribute('data-vacc-id');
                    deleteVaccination(vaccId);
                });
            });
        } else {
            container.innerHTML = '<p style="color: #f44336;">Failed to load pets.</p>';
        }
    } catch (err) {
        console.error('Error:', err);
        container.innerHTML = '<p style="color: #f44336;">Error loading data.</p>';
    }
}

async function openVaccinationModalForPet(petId, petName) {
    const modal = document.getElementById('vaccinationModal');
    const form = document.getElementById('vaccinationForm');
    const petSelect = document.getElementById('vaccPetId');

    form.reset();
    delete form.dataset.mode;
    delete form.dataset.vaccId;
    form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-syringe"></i> Save Vaccination Record';
    document.querySelector('#vaccinationModal .modal-header h2').textContent = `Add Vaccination for ${petName}`;

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

async function editVaccinationById(vaccId) {
    try {
        const res = await fetch('vaccinations.php');
        const data = await res.json();

        if (data.status === 'success') {
            const vacc = data.vaccinations.find(v => v.id == vaccId);
            if (vacc) {
                editVaccination(vaccId, data.vaccinations);
            }
        }
    } catch (err) {
        console.error('Error:', err);
    }
}

function editVaccination(vaccId, vaccinations) {
    const vacc = vaccinations.find(v => v.id == vaccId);
    if (!vacc) return;

    const form = document.getElementById('vaccinationForm');
    const modal = document.getElementById('vaccinationModal');

    document.getElementById('vaccPetId').value = vacc.pet_id;
    document.getElementById('vaccineName').value = vacc.vaccine_name;
    document.getElementById('dateGiven').value = vacc.date_given;
    document.getElementById('nextDueDate').value = vacc.next_due_date || '';
    document.getElementById('veterinarian').value = vacc.veterinarian || '';
    document.getElementById('vaccNotes').value = vacc.notes || '';

    form.dataset.mode = 'update';
    form.dataset.vaccId = vaccId;
    form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Update Vaccination Record';
    document.querySelector('#vaccinationModal .modal-header h2').textContent = 'Edit Vaccination Record';

    modal.classList.add('active');
}

async function deleteVaccination(vaccId) {
    if (!confirm('Are you sure you want to delete this vaccination record?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('vacc_id', vaccId);

        const res = await fetch('vaccinations.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            alert('Vaccination record deleted successfully!');
            loadPetsWithVaccinations();
            loadDashboardStats();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (err) {
        console.error('Error:', err);
        alert('Failed to delete vaccination record.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const petRegistrationModal = document.getElementById('petRegistrationModal');
    const registerPetBtn = document.getElementById('registerPetBtn');
    const petRegistrationForm = document.getElementById('petRegistrationForm');
    const petsGrid = document.getElementById('petsGrid');
    const imagePreview = document.getElementById('imagePreview');
    const petImageInput = document.getElementById('petImage');

    if (registerPetBtn) {
        registerPetBtn.addEventListener('click', () => {
            petRegistrationModal.classList.add('active');
        });
    }

    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => {
            const petModal = document.getElementById('petRegistrationModal');
            if (petModal) petModal.classList.remove('active');
            
            const vaccModal = document.getElementById('vaccinationModal');
            if (vaccModal) vaccModal.classList.remove('active');
            
            const petForm = document.getElementById('petRegistrationForm');
            if (petForm) {
                petForm.reset();
                delete petForm.dataset.mode;
                delete petForm.dataset.petId;
                
                const submitBtn = petForm.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-paw"></i> Register Pet';
            }
            
            const imgPreview = document.getElementById('imagePreview');
            if (imgPreview) {
                imgPreview.style.backgroundImage = '';
                imgPreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
            }
        });
    });

    if (petImageInput && imagePreview) {
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
            } else {
                imagePreview.style.backgroundImage = '';
                imagePreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
            }
        });
    }

    if (petRegistrationForm) {
        petRegistrationForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(petRegistrationForm);

            const mode = petRegistrationForm.dataset.mode || 'register';
            const petId = petRegistrationForm.dataset.petId;

            if (mode === 'update') {
                formData.set('action', 'update');
                formData.append('pet_id', petId);
            }

            try {
                const res = await fetch('pets.php', { method: 'POST', body: formData });
                const text = await res.text();
                const data = JSON.parse(text);

                if (data.status === 'success') {
                    alert(mode === 'update' ? 'Pet updated successfully!' : 'Pet registered successfully!');
                    petRegistrationForm.reset();
                    imagePreview.style.backgroundImage = '';
                    imagePreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
                    petRegistrationModal.classList.remove('active');
                    
                    delete petRegistrationForm.dataset.mode;
                    delete petRegistrationForm.dataset.petId;
                    
                    const submitBtn = petRegistrationForm.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i class="fas fa-paw"></i> Register Pet';
                    
                    loadPets();
                    loadPetsInSidebar();
                    loadDashboardStats();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error('Full error:', err);
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
            const vaccId = vaccinationForm.dataset.vaccId;

            if (mode === 'update') {
                formData.set('action', 'update');
                formData.append('vacc_id', vaccId);
            }

            try {
                const res = await fetch('vaccinations.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.status === 'success') {
                    alert(mode === 'update' ? 'Vaccination updated successfully!' : 'Vaccination added successfully!');
                    document.getElementById('vaccinationModal').classList.remove('active');
                    vaccinationForm.reset();
                    loadPetsWithVaccinations();
                    loadDashboardStats();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                console.error('Error:', err);
                alert('Failed to save vaccination record.');
            }
        });
    }

    loadPets();
    loadDashboardStats();
});
