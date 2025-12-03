<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Digital Pet Registration and Tracking Platform</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="shortcut icon" href="project-logo.png" type="image/x-icon">
  <!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">


</head>
<body>
  <body>
  <!-- Enhanced Navbar -->
<nav class="navbar">
  <div class="container nav-container">
    <div class="nav-logo">
      <!-- <img src="project-logo.png" width="110px" alt="" class="project-logo"> -->
      <span class="logo-text"><i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i></span>
    </div>
    <input type="checkbox" id="nav-toggle" aria-label="Open menu" />
    <label for="nav-toggle" class="nav-toggle-label">
      <span></span><span></span><span></span>
    </label>
    <div class="nav-menu">
      <a href="#home" class="nav-link active">Home</a>
      <a href="#services" class="nav-link">Services</a>  
      <a href="#benefits" class="nav-link">About</a>
      <a href="#howto" class="nav-link">How to Use</a>
      <a href="#contact" class="nav-link">Contact</a>
      <div class="auth-buttons">
        <button class="auth-btn login-btn" id="loginBtn">
           Login
        </button>
        <button class="auth-btn register-btn" id="registerBtn">
          <i class="fas fa-user-plus"></i> Register
        </button>
      </div>
    </div>
  </div>
</nav>

  <!-- Login Modal - UNCHANGED -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Login <i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i></h2>
        <button type="button" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form id="loginForm" novalidate>
          <!-- NEW: Login As Selector -->
          <div class="form-group">
            <label for="loginRole">Login As</label>
            <div class="input-group">
              <select id="loginRole" name="role" required>
                <option value="resident">👤 Resident (Pet Owner)</option>
                <option value="admin">🛡️ Admin (Barangay Official)</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="loginEmail">Email Address</label>
            <div class="input-group">
              <input type="email" id="loginEmail" name="email" placeholder="✉️ Enter your email address" required>
            </div>
          </div>
          <div class="form-group">
            <label for="loginPassword">Password</label>
            <div class="password-input-group">
              <input type="password" id="loginPassword" name="password" placeholder="🔒 Enter your password" required>
              <button type="button" class="password-toggle">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>     
          <div class="form-message-container"></div>     
          <button type="submit" name="login" class="btn btn-primary btn-block">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>
        </form>
        
        <a href="#" id="forgot-link">forgot password?</a><hr>
        <p id="login-via">or login via</p>
        
        <div class="login-options">
          <div class="options-holder">
            <a href="facebook-login.php"><button type="button" class="option-btn"><i class="fa-brands fa-facebook fa-xl"></i> Log in with facebook</button></a>
            <a href="google-login.php"><button type="button" class="option-btn"><i class="fa-brands fa-google fa-xl"></i> Log in with Google</button></a>
          </div>
        </div>
        
        <div class="modal-footer">
          <p>Don't have an account? <a href="#" class="switch-to-register">Register here</a></p>
        </div>
      </div>
    </div>
  </div>


  <!-- Register Modal - UNCHANGED -->
  <div id="registerModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Create Account 🎉</h2>
        <button type="button" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form id="registerForm" novalidate>
          <div class="form-group">
            <label for="registerName">Full Name</label>
            <div class="input-group">
              <input type="text" id="registerName" name="name" placeholder="✉️ Enter your full name" required>
            </div>
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <div class="input-group">
              <input type="email" id="registerEmail" name="email" placeholder="✉️ Enter your email" required>
            </div>
            <div class="form-group">
  <label for="address">Complete Address / Location *</label>
  <input type="text" id="address" name="address"
         placeholder="e.g., Purok 3, Sitio Riverside, Barangay San Roque" required>
</div>

          </div>
          <div class="form-group">
            <label for="registerPassword">Password</label>
            <div class="password-input-group">
              <input type="password" id="registerPassword" name="password" placeholder="🔒 Create a password" required
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
              <button type="button" class="password-toggle">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <div class="password-input-group">
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="🔒 Confirm your password" required>
              <button type="button" class="password-toggle">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-message-container"></div>
          <button type="submit" name="register" class="btn btn-primary btn-block">
            <i class="fas fa-user-plus"></i> Create Account
          </button>
        </form><hr>
        <p id="login-via">or login via</p>
        <div class="login-options">
          <div class="options-holder">
            <a href=""><button type="button" class="option-btn"><i class="fa-brands fa-facebook fa-xl"></i> Log in with facebook</button></a>
            <a href=""><button type="button" class="option-btn"><i class="fa-brands fa-google fa-xl"></i> Log in with Google</button></a>
          </div>
        </div>
        <div class="modal-footer">
          <p>Already have an account? <a href="#" class="switch-to-login">Login here</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- NEW Enhanced Hero Section with Centered Dog Image -->
  <section class="hero" id="home">
    <div class="hero-background">
      <div class="bg-gradient"></div>
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
      </div>
    </div>

    <div class="container hero-container">
      <!-- Left Side Content -->
      <div class="hero-left">
        <div class="hero-badge">
          <i class="fas fa-shield-alt"></i>
          <span>Government Certified Platform</span>
        </div>
        
        <h1 class="hero-title">
          Digital Pet
          <span class="highlight">Registration</span>
          & Tracking Platform
        </h1>
        
        <p class="hero-subtitle">
          Empowering communities with digital pet management, real-time tracking, and comprehensive care solutions.
        </p>

        <div class="hero-stats-left">
          <div class="stat-item">
            <div class="stat-icon"><i class="fas fa-paw"></i></div>
            <div class="stat-content">
              <div class="stat-number">1,247+</div>
              <div class="stat-label">Registered Pets</div>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon"><i class="fas fa-syringe"></i></div>
            <div class="stat-content">
              <div class="stat-number">98.5%</div>
              <div class="stat-label">Vaccination Rate</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Center - Large Dog Image -->
      <div class="hero-center">
        <div class="dog-container">
          <div class="dog-image-wrapper">
            <!-- Replace with your high-quality dog image -->
            <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80" 
                 alt="Happy Dog" 
                 class="hero-dog-image" />
            <div class="dog-glow"></div>
          </div>
          
          <div class="floating-badge badge-1">
            <i class="fas fa-heartbeat"></i>
            <span>Vaccine Monitored</span>
          </div>
          <div class="floating-badge badge-2">
            <i class="fas fa-map-marker-alt"></i>
            <span>GPS Tracked</span>
          </div>
          <div class="floating-badge badge-3">
            <i class="fas fa-user-md"></i>
            <span>Vet Connected</span>
          </div>
        </div>
      </div>

      <!-- Right Side Content -->
      <div class="hero-right">
        <div class="quick-actions">
          <h3>Quick Actions</h3>
          
          <button class="action-card" id="registerPetBtn">
            <div class="action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
              <i class="fas fa-plus-circle"></i>
            </div>
            <div class="action-content">
              <h4>Register Pet</h4>
              <p>Add new pet to system</p>
            </div>
            <i class="fas fa-arrow-right action-arrow"></i>
          </button>

          <button class="action-card" id="emergencyHotlineBtn">
            <div class="action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="action-content">
              <h4>Emergency Report</h4>
              <p>Report incidents 24/7</p>
            </div>
            <i class="fas fa-arrow-right action-arrow"></i>
          </button>

          <button class="action-card" id="viewDashboardBtn">
            <div class="action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="action-content">
              <h4>Dashboard</h4>
              <p>View analytics & stats</p>
            </div>
            <i class="fas fa-arrow-right action-arrow"></i>
          </button>
        </div>

        <div class="trust-indicators">
          <div class="trust-item">
            <i class="fas fa-clock"></i>
            <span>24/7 Support</span>
          </div>
          <div class="trust-item">
            <i class="fas fa-lock"></i>
            <span>Secure Data</span>
          </div>
        </div>
                <div class="hero-features-left">
          <div class="feature-badge">
            <i class="fas fa-check-circle"></i> GDPR Compliant
          </div>
          <div class="feature-badge">
            <i class="fas fa-mobile-alt"></i> Mobile Ready
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- ENHANCED SERVICES SECTION -->
  <section class="services-premium" id="services">
    <div class="container">
      <div class="section-header-premium">
        <span class="badge-premium">Our Services</span>
        <h2 class="section-title-premium">Complete Pet Care Solutions</h2>
        <p class="section-desc-premium">Professional services designed to keep your pets healthy, safe, and happy</p>
      </div>

      <div class="services-grid-premium">
        <div class="service-circle-card">
          <div class="service-icon-circle">
            <i class="fas fa-id-card-alt"></i>
          </div>
          <h3>Digital Registration</h3>
          <p>Quick and secure pet registration with official ID cards for instant verification</p>
          <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="service-circle-card">
          <div class="service-icon-circle">
            <i class="fas fa-syringe"></i>
          </div>
          <h3>Vaccination Tracking</h3>
          <p>Smart reminders and complete health records to keep your pet's vaccinations up to date</p>
          <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="service-circle-card">
          <div class="service-icon-circle">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>24/7 Emergency</h3>
          <p>Round-the-clock emergency response system for immediate assistance when you need it most</p>
          <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <button class="btn-premium-outline">View All Services</button>
    </div>
  </section>

  <!-- ENHANCED BENEFITS SECTION (Dog with glasses style) -->
  <section class="benefits-premium" id="benefits">
    <div class="container">
      <div class="section-header-premium">
        <h2 class="section-title-premium">Benefits Of Using Digital Pet Registration and Tracking Platform</h2>
        <p class="section-desc-premium">Discover why thousands of pet owners trust our platform for their pet care needs</p>
      </div>

      <div class="benefits-layout">
        <div class="benefits-left">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-user-md"></i>
            </div>
            <div class="benefit-content">
              <h4>Aenean Allegra</h4>
              <p>Professional veterinary network at your fingertips. Connect with licensed vets instantly.</p>
            </div>
          </div>

          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-dna"></i>
            </div>
            <div class="benefit-content">
              <h4>Praesent</h4>
              <p>Advanced health tracking and genetic insights to understand your pet better.</p>
            </div>
          </div>
        </div>

        <div class="benefits-center">
          <div class="benefits-image-wrapper">
            <!-- Replace with your dog with glasses image -->
            <img src="https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=600&q=80" 
                 alt="Happy Dog" 
                 class="benefits-main-image" />
            <div class="benefits-bg-circle"></div>
          </div>
        </div>

        <div class="benefits-right">
          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-pills"></i>
            </div>
            <div class="benefit-content">
              <h4>Morbi sodas</h4>
              <p>Automated medication reminders and prescription management system.</p>
            </div>
          </div>

          <div class="benefit-item">
            <div class="benefit-icon">
              <i class="fas fa-heartbeat"></i>
            </div>
            <div class="benefit-content">
              <h4>Vestibulum</h4>
              <p>Real-time health monitoring and emergency alert system for peace of mind.</p>
            </div>
          </div>
        </div>
      </div>

      <button class="btn-premium-outline">Learn More</button>
    </div>
  </section>

  <!-- ENHANCED HOW TO USE SECTION (Cat style) -->
  <section class="howto-premium" id="howto">
    <div class="container">
      <div class="howto-layout">
        <div class="howto-left">
          <div class="howto-image-wrapper">
            <!-- Replace with your cat image -->
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=600&q=80" 
                 alt="Cat" 
                 class="howto-main-image" />
            <div class="howto-bg-circle"></div>
          </div>
        </div>

        <div class="howto-right">
          <div class="section-header-premium text-left">
            <h2 class="section-title-premium">How To Use</h2>
            <p class="section-desc-premium">Getting started with Digital Pet Registration and Tracking Platform is easy. Follow these simple steps to begin protecting your pet.</p>
          </div>

          <div class="howto-steps">
            <div class="howto-step">
              <div class="step-number">01</div>
              <div class="step-content">
                <h4>Create Your Account</h4>
                <p>Sign up in seconds using email or social login. Verify your identity and you're ready to go.</p>
              </div>
            </div>

            <div class="howto-step">
              <div class="step-number">02</div>
              <div class="step-content">
                <h4>Register Your Pet</h4>
                <p>Add your pet's details, upload photos, and get instant digital ID for tracking.</p>
              </div>
            </div>

            <div class="howto-step">
              <div class="step-number">03</div>
              <div class="step-content">
                <h4>Set Up Health Profile</h4>
                <p>Input vaccination records, medical history, and set automated reminders for upcoming appointments.</p>
              </div>
            </div>

            <div class="howto-step">
              <div class="step-number">04</div>
              <div class="step-content">
                <h4>Monitor & Manage</h4>
                <p>Access your dashboard anytime to track health, schedule vet visits, and connect with services.</p>
              </div>
            </div>
          </div>
          <button class="btn-premium">Get Started Now</button>
        </div>
      </div>
    </div>
  </section>

    <!-- PREMIUM CONTACT SECTION -->
  <section class="contact-premium" id="contact">
    <div class="container">
      <div class="section-header-premium">
        <span class="badge-premium">Get In Touch</span>
        <h2 class="section-title-premium">Contact Us</h2>
        <p class="section-desc-premium">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
      </div>

      <div class="contact-layout">
        <!-- Left Side - Contact Info -->
        <div class="contact-info-side">
          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="contact-card-content">
              <h4>Visit Us</h4>
              <p>123 Pet Street, Barangay Center<br>Manila, Philippines 1000</p>
            </div>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="contact-card-content">
              <h4>Call Us</h4>
              <p>+63 917 123 4567<br>Mon-Fri 9AM-6PM</p>
            </div>
          </div>

          <div class="contact-card">
            <div class="contact-card-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-card-content">
              <h4>Email Us</h4>
              <p>support@watchdog.com<br>info@watchdog.com</p>
            </div>
          </div>

          <!-- <div class="contact-social">
            <h4>Follow Us</h4>
            <div class="contact-social-links">
              <a href="#"><i class="fab fa-facebook"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
          </div> -->
        </div>

        <!-- Right Side - Contact Form -->
        <div class="contact-form-side">
          <form class="contact-form" id="contactForm">
            <div class="form-row">
              <div class="form-group-contact">
                <label for="contactName">Your Name</label>
                <input type="text" id="contactName" name="name" placeholder="Enter your name" required>
              </div>
              <div class="form-group-contact">
                <label for="contactEmail">Email Address</label>
                <input type="email" id="contactEmail" name="email" placeholder="Enter your email" required>
              </div>
            </div>

            <div class="form-group-contact">
              <label for="contactSubject">Subject</label>
              <input type="text" id="contactSubject" name="subject" placeholder="What's this about?" required>
            </div>

            <div class="form-group-contact">
              <label for="contactMessage">Message</label>
              <textarea id="contactMessage" name="message" rows="5" placeholder="Tell us more..." required></textarea>
            </div>

            <button type="submit" class="btn-premium">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ENHANCED FOOTER -->
  <footer class="footer-premium">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand-premium">
          <div class="footer-logo">
            <i class="fas fa-paw"></i>
            <span>THE PAW ADVISOR</span>
          </div>
          <p>Your trusted partner in pet care and management. Join thousands of happy pet owners today.</p>
        </div>

        <div class="footer-nav-premium">
          <a href="#home">Home</a>
          <a href="#about">About Us</a>
          <a href="#services">Services</a>
          <a href="#howto">How To Use</a>
          <a href="#benefits">Benefits</a>
          <a href="#blog">Blog</a>
          <a href="#contact">Contact Us</a>
        </div>
      </div>

      <div class="footer-contact-premium">
        <div class="contact-item-premium">
          <div class="contact-icon-premium">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="contact-info-premium">
            <h5>Address</h5>
            <p>123 Pet Street<br>Manila, Philippines</p>
          </div>
        </div>

        <div class="contact-item-premium">
          <div class="contact-icon-premium">
            <i class="fas fa-phone-alt"></i>
          </div>
          <div class="contact-info-premium">
            <h5>Phone</h5>
            <p>+1 234 567 890<br>Mon-Fri 9am-6pm</p>
          </div>
        </div>

        <div class="contact-item-premium">
          <div class="contact-icon-premium">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="contact-info-premium">
            <h5>Email</h5>
            <p>support@watchdog.com<br>info@watchdog.com</p>
          </div>
        </div>
      </div>

      <div class="footer-bottom-premium">
        <p>Copyright © 2025 <span>The Paw Advisor</span>. All rights reserved.</p>
      </div>
    </div>
  </footer>


  <script src="app.js"></script>
  <script>
    // Initialize modals
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM loaded, initializing modals...');
      const modals = document.querySelectorAll('.modal');
      modals.forEach(modal => {
        console.log('Found modal:', modal.id);
      });
    });
  </script>
</body>
</html>
