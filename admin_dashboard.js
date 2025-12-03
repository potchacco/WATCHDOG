// ========================================
// ADMIN DASHBOARD JAVASCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin Dashboard Loaded');

    // ========================================
    // MOBILE HAMBURGER MENU (ADMIN)
    // ========================================
    const adminHamburger = document.getElementById('adminHamburger');
    const adminSidebar = document.querySelector('.admin-sidebar');

    if (adminHamburger && adminSidebar) {
        // Show hamburger on mobile
        if (window.innerWidth <= 968) {
            adminHamburger.style.display = 'flex';
        }

        // Toggle sidebar
        adminHamburger.addEventListener('click', () => {
            adminSidebar.classList.toggle('active');
        });

        // Close sidebar when clicking menu item
        const sidebarLinks = adminSidebar.querySelectorAll('.admin-menu a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 968) {
                    adminSidebar.classList.remove('active');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 968) {
                adminHamburger.style.display = 'flex';
            } else {
                adminHamburger.style.display = 'none';
                adminSidebar.classList.remove('active');
            }
        });
    }

    // ========================================
    // LOGOUT WITH LOADING ANIMATION
    // ========================================
    const adminLogoutBtn = document.getElementById('adminLogoutBtn');
    if (adminLogoutBtn) {
        adminLogoutBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.classList.add('active');
            }

            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 1500);
        });
    }

    // ========================================
    // SIDEBAR NAVIGATION
    // ========================================
    const menuLinks = document.querySelectorAll('.admin-menu a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            menuLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            const section = this.getAttribute('data-section');
            loadAdminSection(section);
        });
    });

    // Initial overview (page already has overview HTML)
    loadAdminStats();
    loadRecentActivity();
});

// ========================================
// LOAD ADMIN STATISTICS
// ========================================
async function loadAdminStats() {
    try {
        const res = await fetch('admin_api.php?action=stats');
        const data = await res.json();
        if (data.status !== 'success') return;

        const s = data.stats || {};

        const totalUsers        = s.total_users        ?? s.totalUsers        ?? 0;
        const totalPets         = s.total_pets         ?? s.totalPets         ?? 0;
        const totalIncidents    = s.total_incidents    ?? s.totalIncidents    ?? 0;
        const totalVaccinations = s.total_vaccinations ?? s.totalVaccinations ?? 0;

        const uEl  = document.getElementById('totalUsers');
        const pEl  = document.getElementById('totalPets');
        const iEl  = document.getElementById('totalIncidents');
        const vEl  = document.getElementById('totalVaccinations');

        if (uEl) uEl.textContent = totalUsers;
        if (pEl) pEl.textContent = totalPets;
        if (iEl) iEl.textContent = totalIncidents;
        if (vEl) vEl.textContent = totalVaccinations;
    } catch (err) {
        console.error('Error loading stats', err);
    }
}

// ========================================
// LOAD RECENT ACTIVITY
// ========================================
async function loadRecentActivity() {
    try {
        const res = await fetch('admin_api.php?action=recent_activity');
        const data = await res.json();

        const container = document.getElementById('recentActivity');
        if (!container) return;

        if (data.status === 'success' && data.activities && data.activities.length > 0) {
            container.innerHTML = '';

            data.activities.forEach(activity => {
                const type = activity.type || 'incident';

                const iconClass =
                    type === 'user' ? 'blue' :
                    type === 'pet'  ? 'green' : 'orange';

                const icon =
                    type === 'user' ? 'fa-user-plus' :
                    type === 'pet'  ? 'fa-paw' : 'fa-exclamation-triangle';

                const div = document.createElement('div');
                div.className = 'activity-item';
                div.innerHTML = `
                    <div class="activity-icon ${iconClass}">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="activity-details">
                        <strong>${activity.description}</strong>
                        <span>${new Date(activity.created_at).toLocaleString()}</span>
                    </div>
                `;
                container.appendChild(div);
            });
        } else {
            container.innerHTML =
                '<p style="text-align:center;color:#6b7280;">No recent activity</p>';
        }
    } catch (err) {
        console.error('Error loading activity:', err);
    }
}

// ========================================
// LOAD DIFFERENT ADMIN SECTIONS
// ========================================
function loadAdminSection(section) {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    switch (section) {
        case 'overview':
            // Rebuild overview in-place (no page reload)
            mainContent.innerHTML = `
                <div class="admin-header">
                    <h1>System Overview</h1>
                    <p>Monitor and manage your entire pet monitoring system</p>
                </div>

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
                            <i class="fa-solid fa-triangle-exclamation fa-lg" style="color:#ff1900;"></i>
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

                <div class="admin-section">
                    <h2>Recent System Activity</h2>
                    <div id="recentActivity" class="activity-feed">
                        <p>Loading...</p>
                    </div>
                </div>
            `;
            loadAdminStats();
            loadRecentActivity();
            break;

        case 'users':
            loadUsersSection();
            break;

        case 'pets':
            loadAllPets();
            break;

        case 'incidents':
            loadAllIncidents();
            break;

        case 'vaccinations':
            loadAllVaccinations();
            break;

        case 'reports':
            loadReports();
            break;

        case 'settings':
            mainContent.innerHTML =
                '<div class="admin-section"><h2>Settings - Coming Soon</h2></div>';
            break;

        default:
            break;
    }
}

// ========================================
// USERS SECTION
// ========================================
async function loadUsersSection() {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    mainContent.innerHTML = `
        <div class="admin-header">
            <h1>User Management</h1>
            <p>Approve or reject registered users and manage existing accounts</p>
        </div>
        <div class="admin-section">
            <div class="section-header-flex">
                <h2><i class="fas fa-users"></i> All Users</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" placeholder="Search users...">
                </div>
            </div>
            <div id="usersTableContainer">
                <p style="text-align:center;padding:40px;">Loading users...</p>
            </div>
        </div>
    `;

    loadAllUsers();
}

async function loadAllUsers() {
    try {
        const res = await fetch('admin_api.php?action=get_users');
        const data = await res.json();

        const container = document.getElementById('usersTableContainer');
        if (!container) return;

        if (data.status === 'success' && data.users && data.users.length > 0) {
            let html = `
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Pets</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.users.forEach(user => {
                html += `
                    <tr>
                        <td>#${user.id}</td>
                        <td>
                            <div class="user-cell">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=667eea&color=fff&size=40" alt="${user.name}">
                                <span>${user.name}</span>
                            </div>
                        </td>
                        <td>${user.email}</td>
                        <td>${user.address || ''}</td>
                        <td>
                            <span class="badge ${
                                user.status === 'approved'
                                    ? 'green'
                                    : user.status === 'pending'
                                    ? 'orange'
                                    : 'red'
                            }">
                                ${user.status}
                            </span>
                        </td>
                        <td>${new Date(user.created_at).toLocaleDateString()}</td>
                        <td><span class="badge blue">${user.pet_count || 0} pets</span></td>
                        <td>
                            ${
                                user.status === 'pending'
                                    ? `
                                <button class="btn-icon btn-approve" onclick="updateUserStatus(${user.id}, 'approved')" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-icon btn-reject" onclick="updateUserStatus(${user.id}, 'rejected')" title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            `
                                    : ''
                            }
                            <button class="btn-icon btn-view" onclick="viewUserDetails(${user.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="deleteUser(${user.id}, '${user.name}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            container.innerHTML = html;
        } else {
            container.innerHTML =
                '<p style="text-align:center;padding:40px;color:#6b7280;">No users found</p>';
        }
    } catch (err) {
        console.error('Error loading users:', err);
    }
}

function viewUserDetails(userId) {
    showNotification('User details view coming soon!', 'info');
}

async function deleteUser(userId, userName) {
    if (
        !confirm(
            `Are you sure you want to delete user "${userName}"? This will also delete all their pets and records.`
        )
    ) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('user_id', userId);

        const res = await fetch('admin_api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            showNotification('User deleted successfully', 'success');
            loadAllUsers();
            loadAdminStats();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (err) {
        showNotification('Failed to delete user', 'error');
    }
}

async function updateUserStatus(userId, newStatus) {
    const actionText = newStatus === 'approved' ? 'approve' : 'reject';

    if (!confirm(`Are you sure you want to ${actionText} this user?`)) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'update_user_status');
        formData.append('user_id', userId);
        formData.append('status', newStatus);

        const res = await fetch('admin_api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            showNotification(`User ${actionText}d successfully`, 'success');
            loadAllUsers();
            loadAdminStats();
        } else {
            showNotification(
                'Error: ' + (data.message || 'Failed to update user'),
                'error'
            );
        }
    } catch (err) {
        console.error(err);
        showNotification('Failed to update user status', 'error');
    }
}

// ========================================
// ALL PETS SECTION
// ========================================
async function loadAllPets() {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    mainContent.innerHTML = `
        <div class="admin-header">
            <h1>All Pets</h1>
            <p>View all pets registered in the system</p>
        </div>
        <div class="admin-section">
            <div class="section-header-flex">
                <h2><i class="fas fa-paw"></i> Registered Pets</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="petSearch" placeholder="Search pets...">
                </div>
            </div>
            <div id="petsGridContainer">
                <p style="text-align:center;padding:40px;">Loading pets...</p>
            </div>
        </div>
    `;

    fetchAllPets();
}

async function fetchAllPets() {
    try {
        const res = await fetch('admin_api.php?action=get_all_pets');
        const data = await res.json();

        const container = document.getElementById('petsGridContainer');
        if (!container) return;

        if (data.status === 'success' && data.pets && data.pets.length > 0) {
            container.innerHTML = '<div class="pets-grid-admin"></div>';
            const grid = container.querySelector('.pets-grid-admin');

            data.pets.forEach(pet => {
                const imgSrc =
                    pet.imageurl && pet.imageurl.trim()
                        ? pet.imageurl
                        : 'https://via.placeholder.com/300x200?text=No+Image';

                const card = document.createElement('div');
                card.className = 'pet-card-admin';
                card.innerHTML = `
                    <div class="pet-image-admin" style="background-image:url('${imgSrc}')">
                        <button class="btn-delete-overlay"
                                onclick="deletePet(${pet.id}, '${pet.name}')"
                                title="Delete Pet">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="pet-details-admin">
                        <h3>${pet.name}</h3>
                        <p class="pet-owner"><i class="fas fa-user"></i> ${pet.owner_name}</p>
                        <p class="pet-info">${pet.species} • ${pet.breed || 'Mixed'}</p>
                        <div class="pet-meta">
                            <span class="badge green">${pet.age || 'N/A'} years</span>
                            <span class="badge blue">${pet.gender || 'N/A'}</span>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        } else {
            container.innerHTML =
                '<p style="text-align:center;padding:40px;color:#6b7280;">No pets found</p>';
        }
    } catch (err) {
        console.error('Error loading pets:', err);
    }
}

async function deletePet(petId, petName) {
    if (
        !confirm(
            `Are you sure you want to delete "${petName}"? This will also delete all vaccination records for this pet.`
        )
    ) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete_pet');
        formData.append('pet_id', petId);

        const res = await fetch('admin_api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            showNotification('Pet deleted successfully', 'success');
            fetchAllPets();
            loadAdminStats();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (err) {
        showNotification('Failed to delete pet', 'error');
    }
}

// ========================================
// ALL INCIDENTS SECTION
// ========================================
async function loadAllIncidents() {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    mainContent.innerHTML = `
        <div class="admin-header">
            <h1>All Incidents</h1>
            <p>Monitor and manage all reported incidents</p>
        </div>
        <div class="admin-section">
            <div class="section-header-flex">
                <h2><i class="fas fa-exclamation-triangle"></i> Incident Reports</h2>
                <select id="statusFilter" onchange="filterIncidents()" class="filter-select">
                    <option value="">All Status</option>
                    <option value="Open">Open</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <div id="incidentsContainer">
                <p style="text-align:center;padding:40px;">Loading incidents...</p>
            </div>
        </div>
    `;

    fetchAllIncidents();
}

async function fetchAllIncidents() {
    try {
        const res = await fetch('admin_api.php?action=get_all_incidents');
        const data = await res.json();

        const container = document.getElementById('incidentsContainer');
        if (!container) return;

        if (data.status === 'success' && data.incidents && data.incidents.length > 0) {
            let html = '<div class="incidents-list">';

            data.incidents.forEach(incident => {
                const statusClass =
                    incident.status === 'Resolved' || incident.status === 'Closed'
                        ? 'success'
                        : incident.status === 'Open' || incident.status === 'Pending'
                        ? 'warning'
                        : 'info';

                const severityClass =
                    incident.severity === 'Critical' || incident.severity === 'High'
                        ? 'danger'
                        : incident.severity === 'Medium'
                        ? 'warning'
                        : 'info';

                html += `
                    <div class="incident-card-admin" data-status="${incident.status}">
                        <div class="incident-header-admin">
                            <div>
                                <h3>${incident.incident_type}</h3>
                                <p class="incident-user">
                                    <i class="fas fa-user"></i>
                                    Reported by: ${incident.reporter_name}
                                </p>
                            </div>
                            <div class="incident-badges">
                                <span class="badge ${statusClass}">${incident.status}</span>
                                <span class="badge ${severityClass}">${incident.severity}</span>
                            </div>
                        </div>
                        <p class="incident-description">${incident.description}</p>
                        <div class="incident-footer">
                            <span class="incident-date">
                                <i class="fas fa-calendar"></i>
                                ${new Date(incident.incident_date).toLocaleString()}
                            </span>
                            ${
                                incident.location
                                    ? `<span><i class="fas fa-map-marker-alt"></i> ${incident.location}</span>`
                                    : ''
                            }
                        </div>
                        <div class="incident-actions">
                            <button class="btn-update-status"
                                    onclick="updateIncidentStatus(${incident.id}, '${incident.status}')">
                                <i class="fas fa-edit"></i> Update Status
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        } else {
            container.innerHTML =
                '<p style="text-align:center;padding:40px;color:#6b7280;">No incidents found</p>';
        }
    } catch (err) {
        console.error('Error loading incidents:', err);
    }
}

function updateIncidentStatus(incidentId, currentStatus) {
    const modal = document.createElement('div');
    modal.className = 'status-modal';
    modal.innerHTML = `
        <div class="status-modal-content">
            <div class="status-modal-header">
                <h3><i class="fas fa-edit"></i> Update Incident Status</h3>
                <button class="status-modal-close"
                        onclick="this.closest('.status-modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="status-modal-body">
                <label>Select New Status:</label>
                <select id="newStatus" class="status-select">
                    <option value="Open" ${currentStatus === 'Open' ? 'selected' : ''}>Open</option>
                    <option value="Pending" ${currentStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                    <option value="In Progress" ${
                        currentStatus === 'In Progress' ? 'selected' : ''
                    }>In Progress</option>
                    <option value="Resolved" ${
                        currentStatus === 'Resolved' ? 'selected' : ''
                    }>Resolved</option>
                    <option value="Closed" ${
                        currentStatus === 'Closed' ? 'selected' : ''
                    }>Closed</option>
                </select>

                <label style="margin-top:15px;">Notes (Optional):</label>
                <textarea id="statusNotes" rows="3"
                          class="status-textarea"
                          placeholder="Add any notes about this status update..."></textarea>

                <button class="btn-save-status" onclick="saveIncidentStatus(${incidentId})">
                    <i class="fas fa-save"></i> Save Status
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

async function saveIncidentStatus(incidentId) {
    const newStatus = document.getElementById('newStatus').value;
    const notes = document.getElementById('statusNotes').value;

    try {
        const formData = new FormData();
        formData.append('action', 'update_incident_status');
        formData.append('incident_id', incidentId);
        formData.append('status', newStatus);
        formData.append('notes', notes);

        const res = await fetch('admin_api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.status === 'success') {
            showNotification('Incident status updated successfully', 'success');
            const modal = document.querySelector('.status-modal');
            if (modal) modal.remove();
            fetchAllIncidents();
            loadAdminStats();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (err) {
        showNotification('Failed to update incident status', 'error');
    }
}

function filterIncidents() {
    const select = document.getElementById('statusFilter');
    if (!select) return;

    const value = select.value;
    const cards = document.querySelectorAll('.incident-card-admin');

    cards.forEach(card => {
        const status = card.getAttribute('data-status');
        if (!value || status === value) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// ========================================
// VACCINATIONS SECTION
// ========================================
function loadAllVaccinations() {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    mainContent.innerHTML = `
        <div class="admin-header">
            <h1>Vaccination Management</h1>
            <p>Monitor vaccination records across all pets</p>
        </div>

        <div class="admin-stats-row">
            <div class="mini-stat-card green">
                <i class="fas fa-check-circle"></i>
                <div>
                    <h3 id="vaccinatedPets">0</h3>
                    <p>Vaccinated Pets</p>
                </div>
            </div>
            <div class="mini-stat-card orange">
                <i class="fas fa-clock"></i>
                <div>
                    <h3 id="dueSoon">0</h3>
                    <p>Due Soon</p>
                </div>
            </div>
            <div class="mini-stat-card red">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <h3 id="overdue">0</h3>
                    <p>Overdue</p>
                </div>
            </div>
        </div>

        <div class="admin-section">
            <div class="section-header-flex">
                <h2><i class="fas fa-syringe"></i> All Vaccination Records</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="vaccSearch" placeholder="Search vaccinations...">
                </div>
            </div>
            <div id="vaccinationsContainer">
                <p style="text-align:center;padding:40px;">Loading vaccinations...</p>
            </div>
        </div>
    `;

    fetchAllVaccinations();
}

async function fetchAllVaccinations() {
    try {
        const res = await fetch('admin_api.php?action=get_all_vaccinations');
        const data = await res.json();

        const container = document.getElementById('vaccinationsContainer');
        if (!container) return;

        if (data.status !== 'success') {
            container.innerHTML =
                '<p style="text-align:center;padding:40px;color:#6b7280;">Failed to load vaccinations</p>';
            return;
        }

        const stats = data.stats || {};
        const vEl = document.getElementById('vaccinatedPets');
        const dEl = document.getElementById('dueSoon');
        const oEl = document.getElementById('overdue');

        if (vEl) vEl.textContent = stats.vaccinated_pets || 0;
        if (dEl) dEl.textContent = stats.due_soon || 0;
        if (oEl) oEl.textContent = stats.overdue || 0;

        if (!data.vaccinations || data.vaccinations.length === 0) {
            container.innerHTML =
                '<p style="text-align:center;padding:40px;color:#6b7280;">No vaccination records found</p>';
            return;
        }

        let html = `
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Owner</th>
                            <th>Vaccine</th>
                            <th>Date Given</th>
                            <th>Next Due</th>
                            <th>Status</th>
                            <th>Warning</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        data.vaccinations.forEach(vacc => {
            const status = vacc.is_overdue
                ? 'Overdue'
                : vacc.is_due_soon
                ? 'Due Soon'
                : 'Up to Date';

            const statusClass = vacc.is_overdue
                ? 'danger'
                : vacc.is_due_soon
                ? 'warning'
                : 'success';

            const warningLabel =
                vacc.warning_status === 'Warning Sent' ? 'Warning Sent' : 'None';

            const warningClass =
                vacc.warning_status === 'Warning Sent' ? 'orange' : 'gray';

            html += `
                <tr>
                    <td>
                        <div class="user-cell">
                            <i class="fas fa-paw" style="color:#667eea;font-size:20px;"></i>
                            <span>${vacc.pet_name}</span>
                        </div>
                    </td>
                    <td>${vacc.owner_name}</td>
                    <td><strong>${vacc.vaccine_name}</strong></td>
                    <td>${new Date(vacc.date_given).toLocaleDateString()}</td>
                    <td>${
                        vacc.next_due_date
                            ? new Date(vacc.next_due_date).toLocaleDateString()
                            : 'N/A'
                    }</td>
                    <td><span class="badge ${statusClass}">${status}</span></td>
                    <td><span class="badge ${warningClass}">${warningLabel}</span></td>
                    <td>
                        <button class="btn-warning"
                                onclick="sendVaccinationWarning(${vacc.id})"
                                ${
                                    vacc.warning_status === 'Warning Sent'
                                        ? 'disabled'
                                        : ''
                                }>
                            Send Warning
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        container.innerHTML = html;
    } catch (err) {
        console.error('Error loading vaccinations:', err);
        document.getElementById('vaccinationsContainer').innerHTML =
            '<p style="text-align:center;padding:40px;color:#6b7280;">Error loading vaccinations</p>';
    }
}

async function sendVaccinationWarning(vaccId) {
    const note = prompt('Enter warning note (optional):');
    if (note === null) return;

    try {
        const formData = new FormData();
        formData.append('action', 'send_warning');
        formData.append('vacc_id', vaccId);
        formData.append('note', note);

        const res = await fetch('admin_api.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        if (data.status === 'success') {
            alert('Warning sent successfully');
            fetchAllVaccinations();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (err) {
        console.error(err);
        alert('Failed to send warning');
    }
}

// ========================================
// REPORTS SECTION
// ========================================
async function loadReports() {
    const mainContent = document.getElementById('adminMainContent');
    if (!mainContent) return;

    mainContent.innerHTML = `
        <div class="admin-header">
            <h1>System Reports</h1>
            <p>View analytics and generate reports</p>
        </div>

        <div class="reports-grid">
            <div class="report-card">
                <div class="report-icon blue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Registration Trends</h3>
                <p>Pet and user registration over time</p>
                <button class="btn-report" onclick="viewReport('registrations')">
                    <i class="fas fa-eye"></i> View Report
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon green">
                    <i class="fas fa-syringe"></i>
                </div>
                <h3>Vaccination Report</h3>
                <p>Vaccination compliance and schedules</p>
                <button class="btn-report" onclick="viewReport('vaccinations')">
                    <i class="fas fa-eye"></i> View Report
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon orange">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Incidents Report</h3>
                <p>Incident trends and statistics</p>
                <button class="btn-report" onclick="viewReport('incidents')">
                    <i class="fas fa-eye"></i> View Report
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon purple">
                    <i class="fas fa-download"></i>
                </div>
                <h3>Export Data</h3>
                <p>Download system data as CSV</p>
                <button class="btn-report" onclick="exportData()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <div id="reportDetailContainer"></div>
    `;
}

async function viewReport(type) {
    const container = document.getElementById('reportDetailContainer');
    if (!container) return;

    try {
        const res = await fetch(`admin_api.php?action=get_report&type=${type}`);
        const data = await res.json();

        if (data.status === 'success') {
            container.innerHTML = `
                <div class="admin-section" style="margin-top:30px;">
                    <h2><i class="fas fa-chart-bar"></i> ${
                        type.charAt(0).toUpperCase() + type.slice(1)
                    } Report</h2>
                    <div class="chart-container-report">
                        <canvas id="reportChart"></canvas>
                    </div>
                </div>
            `;

            renderReportChart(type, data.chart_data);
        }
    } catch (err) {
        console.error('Error loading report:', err);
        showNotification('Error loading report', 'error');
    }
}

function renderReportChart(type, chartData) {
    const canvas = document.getElementById('reportChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: type === 'registrations' ? 'line' : 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: chartData.label,
                    data: chartData.data,
                    backgroundColor:
                        type === 'registrations'
                            ? 'rgba(102, 126, 234, 0.2)'
                            : type === 'vaccinations'
                            ? 'rgba(16, 185, 129, 0.2)'
                            : 'rgba(245, 158, 11, 0.2)',
                    borderColor:
                        type === 'registrations'
                            ? '#667eea'
                            : type === 'vaccinations'
                            ? '#10b981'
                            : '#f59e0b',
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function exportData() {
    showNotification('Exporting data...', 'info');
    window.open('admin_api.php?action=export_data', '_blank');
}

// ========================================
// SEARCH FUNCTIONALITY
// ========================================
document.addEventListener('input', function (e) {
    if (e.target.id === 'userSearch') {
        searchTable(e.target.value, 'usersTableContainer');
    } else if (e.target.id === 'petSearch') {
        searchPets(e.target.value);
    } else if (e.target.id === 'vaccSearch') {
        searchTable(e.target.value, 'vaccinationsContainer');
    }
});

function searchTable(query, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const rows = container.querySelectorAll('tbody tr');
    query = (query || '').toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

function searchPets(query) {
    const cards = document.querySelectorAll('.pet-card-admin');
    query = (query || '').toLowerCase();

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? '' : 'none';
    });
}
