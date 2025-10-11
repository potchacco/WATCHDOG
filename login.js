document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.querySelector('.login-btn');
    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');
    
    function validateField(field) {
        const inputGroup = field.closest('.input-group');
        const value = field.value.trim();
        
        clearFieldError(inputGroup);
        
        if (!value) {
            showFieldError(inputGroup, 'This field is required');
            return false;
        }
        
        if (field.type === 'email' && !isValidEmail(value)) {
            showFieldError(inputGroup, 'Please enter a valid email address');
            return false;
        }
        
        inputGroup.classList.add('success');
        return true;
    }
    
    function clearFieldError(inputGroup) {
        const existingError = inputGroup.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        inputGroup.classList.remove('error', 'success');
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showFieldError(inputGroup, message) {
        inputGroup.classList.add('error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        inputGroup.appendChild(errorDiv);
    }
    
    function showFormError(message) {
        const existingError = document.querySelector('.form-error');
        if (existingError) {
            existingError.remove();
        }
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        loginForm.insertBefore(errorDiv, loginForm.firstChild);
    }
    
    if (emailInput && passwordInput) {
        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('blur', function() {
                validateField(input);
            });
            
            input.addEventListener('input', function() {
                const inputGroup = input.closest('.input-group');
                if (inputGroup.classList.contains('error')) {
                    validateField(input);
                }
            });
        });
    }
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const isEmailValid = validateField(emailInput);
            const isPasswordValid = validateField(passwordInput);
            
            if (!isEmailValid || !isPasswordValid) {
                return;
            }
            
            loginBtn.disabled = true;
            loginBtn.textContent = 'Logging in...';
            
            try {
                const formData = new FormData(loginForm);
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const responseText = await response.text();
                
                if (responseText.includes('Account does not exist')) {
                    showFormError('This account does not exist. Please check your email or register.');
                } else if (responseText.includes('Incorrect password')) {
                    showFormError('Incorrect password. Please try again.');
                } else if (responseText.includes('Database error')) {
                    showFormError('A system error occurred. Please try again later.');
                } else if (responseText.includes('dashboard.php')) {
                    window.location.href = 'dashboard.php';
                    return;
                } else {
                    showFormError('An unexpected error occurred. Please try again.');
                }
            } catch (error) {
                showFormError('Connection error. Please check your internet connection and try again.');
            } finally {
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login';
            }
        });
    }
});
    
    
    // Social login buttons
    document.querySelector('.facebook-btn').addEventListener('click', function() {
        showToast('Facebook login coming soon', 'info');
    });
    
    document.querySelector('.google-btn').addEventListener('click', function() {
        showToast('Google login coming soon', 'info');
    });
    
    // Other links
    document.querySelector('.forgot-link').addEventListener('click', function(e) {
        e.preventDefault();
        showToast('Password reset sent to your email', 'success');
    });
    
    document.querySelector('.signup-link').addEventListener('click', function(e) {
        e.preventDefault();
        showToast('Registration page coming soon', 'info');
    });
    
    // Simple toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Demo credentials - click paw icons
    document.querySelector('.paw-1').addEventListener('click', function() {
        usernameInput.value = 'admin@petcontrolx.gov.ph';
        passwordInput.value = 'admin123';
        showToast('Demo credentials loaded', 'success');
    });
    
    console.log('PetControlX Simple Login Ready!');

