<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Community Pet Registration & Control System</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container nav-container">
      <div class="nav-logo">
        <!-- <img src="images/registration-card.png" alt="Logo" class="logo-img" /> -->
        <span class="logo-text">WATCHD<i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i>G</span></span>
      </div>
      <input type="checkbox" id="nav-toggle" aria-label="Open menu" />
      <label for="nav-toggle" class="nav-toggle-label">
        <span></span><span></span><span></span>
      </label>
      <div class="nav-menu">
        <a href="#home" class="nav-link">Home</a>
        <a href="#services" class="nav-link">Services</a>  
        <a href="#about" class="nav-link">About</a>
        <a href="#stats" class="nav-link">Impact</a>
        <a href="#contact" class="nav-link">Contact</a>
        <div class="auth-buttons">
          <button class="auth-btn login-btn" id="loginBtn">Login</button>
          <button class="auth-btn register-btn" id="registerBtn">Register</button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Login Modal -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Login to WATCHD<i class="fa-solid fa-paw fa-rotate-by" style="color: #0d0de6; --fa-rotate-angle: 30deg;"></i>G</h2>
        <button type="button" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form id="loginForm" novalidate>
          <div class="form-group">
            <label for="loginEmail">Email Address</label>
            <div class="input-group">
              <!-- <i class="fas fa-envelope input-icon"></i> -->
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
              <!-- <i class="fas fa-lock input-icon"></i> -->
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

  <!-- Register Modal -->
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
              <!-- <i class="fas fa-user input-icon"></i> -->
            </div>
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <div class="input-group">
              <input type="email" id="registerEmail" name="email" placeholder="✉️ Enter your email" required>
              <!-- <i class="fas fa-envelope input-icon"></i> -->
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
              <!-- <i class="fas fa-lock input-icon"></i> -->
            </div>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <div class="password-input-group">
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="🔒 Confirm your password" required>
              <button type="button" class="password-toggle">
                <i class="fas fa-eye"></i>
              </button>
              <!-- <i class="fas fa-lock input-icon"></i> -->
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

  <section class="hero" id="home">
    <div class="container hero-container">
      <div class="hero-left">
        <!-- <span class="hero-badge">🛡️ Trusted Government Platform</span> -->
        <h1 class="hero-title">
          Community Pet<br />
          <span class="highlight">Registration</span><br />
          & Control System
        </h1>
        <p class="hero-subtitle">
          Empowering barangays with a digital platform for pet registration, real-time tracking, vaccination management, and community safety.
        </p>

        <div class="hero-stats">
          <div class="stat-card">
            <div class="stat-number" data-target="1247">564</div>
            <div class="stat-label">Registered Pets</div>
          </div>
          <div class="stat-card">
            <div class="stat-number" data-target="98.5">99%</div>
            <div class="stat-label">Success Rate</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Support</div>
          </div>
          <!-- <div class="stat-card">
            <div class="stat-number">50+</div>
            <div class="stat-label">Barangays</div>
          </div> -->
        </div>

        <div class="hero-buttons">
          <button class="btn btn-primary" id="registerPetBtn">
            Register New Pet <span>🐕</span>
          </button>
          <button class="btn btn-secondary" id="emergencyHotlineBtn">
            Report Emergency <span>🚨</span>
          </button>
          <button class="btn btn-outline" id="viewDashboardBtn">View Dashboard <span>📊</span></button>
        </div>

        <div class="hero-trust">
          <span>✅ Government Certified</span>
          <span>🔒 GDPR Compliant</span>
          <!-- <span>⚡ Real-time Updates</span> -->
          <span>📱 Mobile Optimized</span>
        </div>
      </div>

      <div class="hero-right">
        <img src="images/hero-registration.png" alt="Registration Interface" class="hero-main-img" />
        <div class="hero-panels">
          <div class="hero-panel">
            <img src="images/vaccination-system.png" alt="Vaccination" />
            <span>Vaccination Tracking</span>
          </div>
          <div class="hero-panel">
            <img src="images/incident-report.png" alt="Incident" />
            <span>Emergency Reports</span>
          </div>
          <div class="hero-panel">
            <img src="images/emergency-response.png" alt="Emergency Response" />
            <span>Response Dashboard</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="services" id="services">
    <div class="container section-container">

      <header class="section-header">
        <span class="section-badge">⚡ Comprehensive Solutions</span>
        <h2 class="section-title">Complete Pet Management Ecosystem</h2>
        <p class="section-description">Everything your barangay needs to manage, track, and protect pets through one integrated government platform.</p>
      </header>

      <div class="services-grid">
        <article class="service-card blue">
          <img src="images/registration-card.png" alt="Digital Pet Registration" />
          <h3>Digital Pet Registration</h3>
          <p>Official ID cards, QR codes, biometric data & database integration.</p>
          <ul>
            <li>✓ Official Government ID Cards</li>
            <li>✓ Secure QR Code Generation</li>
            <li>✓ Real-time Sync</li>
          </ul>
        </article>

        <article class="service-card green">
          <img src="images/vaccination-system.png" alt="Vaccination Management" />
          <h3>Vaccination Management</h3>
          <p>Smart reminders, analytics, and veterinary partnerships.</p>
          <ul>
            <li>✓ Smart Reminders</li>
            <li>✓ Health Analytics</li>
            <li>✓ Vet Clinic Integration</li>
          </ul>
        </article>

        <article class="service-card orange">
          <img src="images/incident-report.png" alt="Incident Management" />
          <h3>Incident Management</h3>
          <p>GPS tracking, photo evidence, priority classification & dispatch.</p>
          <ul>
            <li>✓ GPS Location Tracking</li>
            <li>✓ Photo & Video Evidence</li>
            <li>✓ Priority Classification</li>
          </ul>
        </article>

        <article class="service-card red">
          <img src="images/emergency-response.png" alt="Emergency Response" />
          <h3>Emergency Response</h3>
          <p>24/7 hotline, rapid response, real-time community coordination.</p>
          <ul>
            <li>✓ 24/7 Emergency Hotline</li>
            <li>✓ Rapid Response Team</li>
            <li>✓ Community Alerts</li>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <section class="stats" id="stats">
    <div class="container stats-container">
      <div class="stats-content">
        <header>
          <span class="section-badge">📊 Community Impact</span>
          <h2>Making a Real Difference</h2>
          <p>Transforming pet care safety for millions of pets and families nationwide.</p>
        </header>
        <div class="stats-grid">
          <div class="stat-card blue">
            <div class="stat-icon">🐕</div>
            <div class="stat-num" data-target="1247">0</div>
            <div class="stat-label">Registered Pets</div>
          </div>
          <div class="stat-card green">
            <div class="stat-icon">💉</div>
            <div class="stat-num" data-target="89">0</div>
            <div class="stat-label">Vaccinated This Month</div>
          </div>
          <div class="stat-card orange">
            <div class="stat-icon">✅</div>
            <div class="stat-num" data-target="156">0</div>
            <div class="stat-label">Cases Resolved</div>
          </div>
          <!-- <div class="stat-card red">
            <div class="stat-icon">🏢</div>
            <div class="stat-num" data-target="50">0</div>
            <div class="stat-label">Partner Barangays</div>
          </div> -->
          <div class="stat-card blue">
            <div class="stat-icon">👮</div>
            <div class="stat-num" data-target="12">0</div>
            <div class="stat-label">Active Officers</div>
          </div>
          <!-- <div class="stat-card green">
            <div class="stat-icon">⭐</div>
            <div class="stat-num" data-target="47">0</div>
            <div class="stat-label">Satisfaction Rating</div>
          </div> -->
        </div>
      </div>
      <aside class="stats-side">
        <img src="images/community-pets.png" alt="Community Analytics" class="stats-main-img" />
        <div class="stats-highlights">
          <div>
            <span>📈</span>
            <div>
              <b>+285%</b>
              <div>Registration Growth</div>
            </div>
          </div>
          <div>
            <span>🎯</span>
            <div>
              <b>99.2%</b>
              <div>System Uptime</div>
            </div>
          </div>
          <div>
            <span>⚡</span>
            <div>
              <b>&lt; 2 min</b>
              <div>Avg Response</div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </section>

  <section class="about" id="about">
    <div class="container about-container">
      <div class="about-text">
        <span class="section-badge">💡 Why Choose PetControlX</span>
        <h2>Leading Innovation in Community Pet Management</h2>
        <p>Government-approved technology with real-time intelligence and multi-platform integration.</p>
        <ul>
          <li>📊 Real-time analytics and dashboards</li>
          <li>🌐 Seamless cloud and mobile integration</li>
        </ul>
      </div>
      <figure class="about-image">
        <img src="images/community-stats.png" alt="Community Pets" />
      </figure>
    </div>
  </section>

  <section class="cta" id="contact">
    <div class="container cta-container">
      <div class="cta-text">
        <span class="section-badge white">🚀 Ready to Get Started?</span>
        <h2 id="text-black">Protect Your Community with PetControlX</h2>
        <p>Join thousands of responsible pet owners and officials using our platform to ensure safe and healthy communities.</p>
        <div class="cta-buttons">
          <button class="btn btn-primary" id="registerPetBtn" id>Register Your Pet Now 🐕</button>
          <button class="btn btn-secondary" id="emergencyHotlineBtn">Schedule a Demo 📅</button>
          <button class="btn btn-outline" id="viewDashboardBtn">Emergency Hotline 🚨</button>
        </div>
      </div>
      <div class="cta-image">
        <img src="images/hero-registration.png" alt="PET Registration" />
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container footer-container">
      <div class="footer-main">
        <div class="footer-brand">
          <!-- <img src="images/registration-card.png" alt="Logo" />
          <span class="footer-logo-text">PetControl<span class="footer-logo-accent">X</span></span> -->
          <p>The country's leading digital pet registration and management platform, trusted by government units nationwide.</p>
        </div>
        <div class="footer-links">
          <div>
            <h4>Platform Services</h4>
            <ul>
              <li><a href="#">Pet Registration System</a></li>
              <li><a href="#">Vaccination Management</a></li>
              <li><a href="#">Incident Reporting</a></li>
              <li><a href="#">Emergency Response</a></li>
              <li><a href="#">Analytics Dashboard</a></li>
            </ul>
          </div>
          <div>
            <h4>Government Partners</h4>
            <ul>
              <li><a href="#">DILG Partnership</a></li>
              <li><a href="#">DOH Collaboration</a></li>
              <li><a href="#">LGU Integration</a></li>
              <li><a href="#">Barangay Network</a></li>
              <li><a href="#">Training Programs</a></li>
            </ul>
          </div>
          <div>
            <h4>Support & Resources</h4>
            <ul>
              <li><a href="#">Help Documentation</a></li>
              <li><a href="#">Video Tutorials</a></li>
              <li><a href="#">Community Forums</a></li>
              <li><a href="#">Technical Support</a></li>
              <li><a href="#">System Status</a></li>
            </ul>
          </div>
          <div>
            <h4>Emergency Contact</h4>
            <ul>
              <li>📞 (02) 8-PET-HELP</li>
              <li>📞 0917-123-PETS</li>
              <li>Immediate response for animal emergencies</li>
              <li>Metro Manila • Cebu • Davao</li>
            </ul>
            <div class="footer-social">
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">© 2025 PetControlX | DICT Certified | ISO 27001 | GDPR Compliant | 99.9% Uptime</div>
    </div>
  </footer>

      </div>
    </div>
  </nav>

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