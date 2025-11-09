document.addEventListener('DOMContentLoaded', function() {
    const petRegistrationModal = document.getElementById('petRegistrationModal');
    const registerPetBtn = document.getElementById('registerPetBtn');
    const petRegistrationForm = document.getElementById('petRegistrationForm');
    const petsGrid = document.getElementById('petsGrid');
    const imagePreview = document.getElementById('imagePreview');
    const petImageInput = document.getElementById('petImage');

    // ===== Show modal =====
    if (registerPetBtn) {
        registerPetBtn.addEventListener('click', () => {
            petRegistrationModal.classList.add('active');
        });
    }

    // ===== Close modal =====
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => petRegistrationModal.classList.remove('active'));
    });

    // ===== Image preview =====
if (petImageInput && imagePreview) {
    // Make preview clickable
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


    // ===== Pet registration form =====
if (petRegistrationForm) {
    petRegistrationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(petRegistrationForm);

        try {
            const res = await fetch('pets.php', { method: 'POST', body: formData });
            
            // Get the response as text first to see what we're getting
            const text = await res.text();
            console.log('Raw response:', text);
            
            // Try to parse it as JSON
            const data = JSON.parse(text);
            console.log('Parsed data:', data);

            if (data.status === 'success') {
                alert('Pet registered successfully!');
                petRegistrationForm.reset();
                imagePreview.style.backgroundImage = '';
                imagePreview.innerHTML = `<i class="fas fa-camera"></i><span>Click to upload image</span>`;
                petRegistrationModal.classList.remove('active');
                loadPets();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (err) {
            console.error('Full error:', err);
            console.error('Error message:', err.message);
            alert('Error: ' + err.message + ' - Check browser console (F12) for details');
        }
    });
}



    // ===== Load pets =====
    async function loadPets() {
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
                        : 'default_pet.png';
                    const petDiv = document.createElement('div');
                    petDiv.className = 'pet-card';
                    petDiv.innerHTML = `
                        <div class="pet-image" style="background-image: url('${imgSrc}');"></div>
                        <div class="pet-details">
                            <h3>${pet.name}</h3>
                            <p>${pet.species} - ${pet.breed || 'Unknown'}</p>
                            <p>Age: ${pet.age || 'N/A'} | Gender: ${pet.gender || 'N/A'}</p>
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

    // Initial load
    loadPets();
});



