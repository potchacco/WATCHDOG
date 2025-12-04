document.addEventListener("DOMContentLoaded", function() {
  console.log("Initializing app functionality...");
  
  // Get all modal elements
  const loginBtn = document.getElementById('loginBtn');
  const registerBtn = document.getElementById('registerBtn');
  const loginModal = document.getElementById('loginModal');
  const registerModal = document.getElementById('registerModal');
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const closeButtons = document.querySelectorAll('.modal-close');
  const messageContainers = document.querySelectorAll('.form-message-container');

  // Get all protected action buttons
  const protectedButtons = [
    document.getElementById('registerPetBtn'),
    document.getElementById('emergencyHotlineBtn'),
    document.getElementById('viewDashboardBtn'),
    document.getElementById('ctaRegisterPetBtn'),
    document.getElementById('ctaScheduleDemoBtn'),
    document.getElementById('ctaEmergencyHotlineBtn')
  ];

  

  // Function to check if user is logged in
  async function checkLoginStatus() {
    try {
      const response = await fetch('check_session.php');
      const data = await response.json();
      return data.loggedIn;
    } catch (error) {
      console.error('Error checking login status:', error);
      return false;
    }
  }

  // Add click event listeners to all protected buttons
  protectedButtons.forEach(button => {
    if (button) {
      button.addEventListener('click', async (e) => {
        e.preventDefault();
        const isLoggedIn = await checkLoginStatus();
        
        if (!isLoggedIn) {
          // Show login modal if user is not logged in
          openModal(loginModal);
        } else {
          // Handle the action based on button ID
          switch(button.id) {
            case 'registerPetBtn':
            case 'ctaRegisterPetBtn':
              window.location.href = 'dashboard.php?action=register_pet';
              break;
            case 'emergencyHotlineBtn':
            case 'ctaEmergencyHotlineBtn':
              window.location.href = 'dashboard.php?action=emergency';
              break;
            case 'ctaScheduleDemoBtn':
              window.location.href = 'dashboard.php?action=schedule_demo';
              break;
            case 'viewDashboardBtn':
              window.location.href = 'dashboard.php';
              break;
          }
        }
      });
    }
  });

  // Form variables
  const loginEmail = document.getElementById('loginEmail');
  const loginPassword = document.getElementById('loginPassword');
  const registerName = document.getElementById('registerName');
  const registerEmail = document.getElementById('registerEmail');
  const registerPassword = document.getElementById('registerPassword');
  const confirmPassword = document.getElementById('confirmPassword');

  // Function to show message in modal
  function showMessage(container, message, type) {
    container.innerHTML = `<div class="form-${type}">${message}</div>`;
    setTimeout(() => {
      container.innerHTML = '';
    }, 5000);
  }
  const switchToRegisterLinks = document.querySelectorAll('.switch-to-register');
  const switchToLoginLinks = document.querySelectorAll('.switch-to-login');
  const passwordToggles = document.querySelectorAll('.password-toggle');
  const navToggleCheckbox = document.getElementById('nav-toggle');
  const navMenu = document.querySelector('.nav-menu');

  console.log("Setting up event listeners...");

  // Modal functions
  function openModal(modal) {
    if (!modal) return;
    
    // First set display to flex
    modal.style.display = 'flex';
    
    // Force a reflow before adding the active class
    modal.offsetHeight;
    
    // Add active class for animation
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Focus first input if it exists
    const firstInput = modal.querySelector('input');
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 300);
    }
  }

  function closeModal(modal) {
    if (!modal) return;
    
    modal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Wait for animation to finish before hiding
    setTimeout(() => {
      if (!modal.classList.contains('active')) {
        modal.style.display = 'none';
        // Reset form if it exists
        const form = modal.querySelector('form');
        if (form) {
          form.reset();
          const messageContainer = form.querySelector('.form-message-container');
          if (messageContainer) {
            messageContainer.innerHTML = '';
          }
        }
      }
    }, 300);
  }

  function closeAllModals() {
    document.querySelectorAll('.modal').forEach(modal => closeModal(modal));
  }

  // Handle form submissions
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const messageContainer = loginForm.querySelector('.form-message-container');
    const submitButton = loginForm.querySelector('button[type="submit"]');
    
    if (!loginEmail.value || !loginPassword.value) {
      showMessage(messageContainer, 'Please fill in all fields', 'error');
      return;
    }

    // Show loading state
    const originalButtonContent = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
    submitButton.style.opacity = '0.7';

    const formData = new FormData(loginForm);
    
    // Start timer for minimum 3 seconds
    const startTime = Date.now();
    
    try {
      const response = await fetch('login.php', {
        method: 'POST',
        body: formData
      });
      
      const data = await response.json(); // Changed to JSON
      
      // Calculate remaining time to reach 3 seconds
      const elapsedTime = Date.now() - startTime;
      const remainingTime = Math.max(0, 3000 - elapsedTime);
      
      // Wait for remaining time before showing result
      await new Promise(resolve => setTimeout(resolve, remainingTime));
      
      if (data.status === 'success') {
        // Success animation
        submitButton.innerHTML = '<i class="fas fa-check-circle"></i> Success!';
        submitButton.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        submitButton.style.opacity = '1';
        
        // Redirect based on role after short delay
        setTimeout(() => {
          window.location.href = data.redirect; // Use the redirect from server
        }, 800);
      } else {
        // Reset button on error
        submitButton.innerHTML = originalButtonContent;
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
        submitButton.style.background = ''; // Reset background
        showMessage(messageContainer, data.message || 'Invalid email or password', 'error');
      }
    } catch (error) {
      // Calculate remaining time even on error
      const elapsedTime = Date.now() - startTime;
      const remainingTime = Math.max(0, 3000 - elapsedTime);
      await new Promise(resolve => setTimeout(resolve, remainingTime));
      
      // Reset button on error
      submitButton.innerHTML = originalButtonContent;
      submitButton.disabled = false;
      submitButton.style.opacity = '1';
      submitButton.style.background = ''; // Reset background
      showMessage(messageContainer, 'An error occurred. Please try again.', 'error');
    }
  });
}


  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const messageContainer = registerForm.querySelector('.form-message-container');
      
      // Validate all fields
      if (!registerName.value || !registerEmail.value || !registerPassword.value || !confirmPassword.value) {
        showMessage(messageContainer, 'Please fill in all fields', 'error');
        return;
      }

      // Validate password match
      if (registerPassword.value !== confirmPassword.value) {
        showMessage(messageContainer, 'Passwords do not match', 'error');
        return;
      }

      // Validate email format
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(registerEmail.value)) {
        showMessage(messageContainer, 'Please enter a valid email address', 'error');
        return;
      }

      const formData = new FormData(registerForm);
      
      try {
        console.log('Submitting registration...');
        const response = await fetch('register.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.text();
        console.log('Registration response:', data);
        
        if (data.trim() === 'success') {
          showMessage(messageContainer, 'Registration successful! You can now login.', 'success');
          setTimeout(() => {
            closeModal(registerModal);
            openModal(loginModal);
          }, 2000);
        } else {
          showMessage(messageContainer, data || 'Registration failed. Please try again.', 'error');
        }
      } catch (error) {
        showMessage(messageContainer, 'An error occurred. Please try again.', 'error');
      }
    });
  }

  // Event Listeners for modals
  if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
      e.preventDefault();
      closeAllModals();
      openModal(loginModal);
    });
  }

  if (registerBtn) {
    registerBtn.addEventListener('click', (e) => {
      e.preventDefault();
      closeAllModals();
      openModal(registerModal);
    });
  }

  // Close button handlers
  closeButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = button.closest('.modal');
      closeModal(modal);
    });
  });

  // Switch between login and register
  switchToRegisterLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      closeModal(loginModal);
      setTimeout(() => openModal(registerModal), 300);
    });
  });

  switchToLoginLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      closeModal(registerModal);
      setTimeout(() => openModal(loginModal), 300);
    });
  });

  // Password toggle functionality
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      const button = e.currentTarget;
      const input = button.closest('.password-input-group').querySelector('input');
      const icon = button.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Close modal when clicking outside
  window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
      closeModal(e.target);
    }
  });

    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeAllModals();
      }
    });

    // Stop propagation on modal content click
    document.querySelectorAll('.modal-content').forEach(content => {
      content.addEventListener('click', e => e.stopPropagation());
    });

  function showFormMessage(form, message, type = 'error') {
    const container = getOrCreateMessageContainer(form);
    container.innerHTML = `<div class="form-${type}">${message}</div>`;
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
      setTimeout(() => {
        container.innerHTML = '';
      }, 5000);
    }
  }

  function showFormError(form, message) {
    showFormMessage(form, message, 'error');
  }

  function showFormSuccess(form, message) {
    showFormMessage(form, message, 'success');
  }
  ;

  // Password visibility toggle
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      const input = toggle.parentElement.querySelector('input');
      const icon = toggle.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Mobile nav toggle
  if (navToggleCheckbox && navMenu) {
    navToggleCheckbox.addEventListener("change", () => {
      navMenu.style.display = navToggleCheckbox.checked ? "flex" : "";
    });
  }

  // Smooth scroll for navigation links
  document.querySelectorAll(".nav-link[href^='#']").forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
      if (navToggleCheckbox) {
        navToggleCheckbox.checked = false;
        navMenu.style.display = "";
      }
    });
  });

  // Animate stat counters
  const stats = document.querySelectorAll(".stat-num[data-target]");
  stats.forEach(stat => {
    const target = parseInt(stat.getAttribute("data-target"));
    let current = 0;
    const step = Math.ceil(target / 75);
    const interval = setInterval(() => {
      current += step;
      if (current >= target) {
        stat.textContent = target;
        clearInterval(interval);
      } else {
        stat.textContent = current;
      }
    }, 20);
  });

  // Button click blur effect
  document.querySelectorAll("button").forEach(button => {
    button.addEventListener("click", () => button.blur());
  });
});

  // Active Navigation Link Handler
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // Remove active class from all links
      navLinks.forEach(nav => nav.classList.remove('active'));
      
      // Add active class to clicked link
      this.classList.add('active');
    });
  });

  // Optional: Update active link on scroll
  window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section[id]');
    
    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.clientHeight;
      
      if (window.pageYOffset >= sectionTop - 100) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${current}`) {
        link.classList.add('active');
      }
    });

      // ============================================
  // SAFE SCROLL ANIMATIONS
  // ============================================
  
  // Add animation classes to elements
  function initAnimations() {
    // Navbar
    setTimeout(() => {
      const navbar = document.querySelector('.navbar');
      if (navbar) navbar.classList.add('is-visible');
    }, 100);

    // Hero sections
    const heroLeft = document.querySelector('.hero-left');
    const heroCenter = document.querySelector('.hero-center');
    const heroRight = document.querySelector('.hero-right');
    
    if (heroLeft) {
      heroLeft.classList.add('will-animate', 'from-left');
    }
    if (heroCenter) {
      heroCenter.classList.add('will-animate', 'from-center');
    }
    if (heroRight) {
      heroRight.classList.add('will-animate', 'from-right');
    }

    // Service cards
    document.querySelectorAll('.service-circle-card').forEach(card => {
      card.classList.add('will-animate');
    });

    // Benefit items
    document.querySelectorAll('.benefits-left .benefit-item').forEach(item => {
      item.classList.add('will-animate', 'from-left');
    });
    
    document.querySelectorAll('.benefits-right .benefit-item').forEach(item => {
      item.classList.add('will-animate', 'from-right');
    });

    const benefitsCenter = document.querySelector('.benefits-center');
    if (benefitsCenter) {
      benefitsCenter.classList.add('will-animate', 'from-center');
    }

    // How to steps
    document.querySelectorAll('.howto-step').forEach(step => {
      step.classList.add('will-animate');
    });

    const howtoImage = document.querySelector('.howto-image-wrapper');
    if (howtoImage) {
      howtoImage.classList.add('will-animate', 'from-left');
    }

    // Contact cards
    document.querySelectorAll('.contact-card').forEach(card => {
      card.classList.add('will-animate');
    });

    // Section headers
    document.querySelectorAll('.section-header-premium').forEach(header => {
      header.classList.add('will-animate');
    });

    // Action cards
    document.querySelectorAll('.action-card').forEach(card => {
      card.classList.add('will-animate');
    });

    // Stat items
    document.querySelectorAll('.stat-item').forEach(stat => {
      stat.classList.add('will-animate');
    });
  }

  // Run on page load
  initAnimations();

  // Intersection Observer
  const animateObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        animateObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });

  // Observe all elements with will-animate class
  setTimeout(() => {
    document.querySelectorAll('.will-animate').forEach(element => {
      // Check if already in viewport
      const rect = element.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        setTimeout(() => element.classList.add('is-visible'), 100);
      } else {
        animateObserver.observe(element);
      }
    });
  }, 100);

  });

