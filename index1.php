<?php
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Login - Global Hospitals</title>
  
  <link href="https://fonts.googleapis.com/css?family=Manrope:400,600,700|Space+Grotesk:700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
  
  <style>
    :root {
      --brand: #0f9d8a;
      --brand-deep: #0c7f70;
      --text: #2c3e50;
      --light-bg: #f8f9fa;
      --border: #e1e8ed;
      --success: #27ae60;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Manrope", sans-serif;
      color: var(--text);
      line-height: 1.6;
      background: linear-gradient(135deg, var(--brand) 0%, #0d8a7a 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navigation */
    .navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 1rem 0;
    }

    .navbar-brand {
      font-family: "Space Grotesk", sans-serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--brand) !important;
    }

    .navbar-nav .nav-link {
      color: var(--text) !important;
      margin-left: 2rem;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: var(--brand) !important;
    }

    /* Main Container */
    .login-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      margin-top: 60px;
    }

    .login-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      max-width: 1000px;
      width: 100%;
      align-items: center;
    }

    /* Left Section - Welcome Message */
    .welcome-section {
      color: white;
      padding: 2rem;
    }

    .welcome-section h1 {
      font-family: "Space Grotesk", sans-serif;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      line-height: 1.2;
    }

    .welcome-section p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 1.5rem;
      line-height: 1.8;
    }

    .feature-list {
      list-style: none;
      margin: 2rem 0;
    }

    .feature-list li {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      font-size: 1rem;
    }

    .feature-list i {
      font-size: 1.5rem;
      margin-right: 1rem;
      color: #4ecdc4;
    }

    /* Right Section - Login Form */
    .login-card {
      background: white;
      border-radius: 16px;
      padding: 3rem;
      box-shadow: 0 14px 34px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-card h2 {
      font-family: "Space Grotesk", sans-serif;
      font-size: 1.8rem;
      color: var(--text);
      margin-bottom: 0.5rem;
      text-align: center;
    }

    .login-card .subtitle {
      text-align: center;
      color: #95a5a6;
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }

    .form-group-custom {
      margin-bottom: 1.5rem;
    }

    .form-group-custom label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text);
      font-size: 0.95rem;
    }

    .form-group-custom input {
      width: 100%;
      padding: 0.85rem 1rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      font-family: "Manrope", sans-serif;
    }

    .form-group-custom input:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 3px rgba(15, 157, 138, 0.1);
    }

    .form-group-custom input::placeholder {
      color: #bdc3c7;
    }

    .btn-login {
      width: 100%;
      padding: 0.95rem;
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(15, 157, 138, 0.3);
    }

    .login-footer {
      text-align: center;
      margin-top: 1.5rem;
      color: #95a5a6;
      font-size: 0.9rem;
    }

    .login-footer a {
      color: var(--brand);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .login-footer a:hover {
      color: var(--brand-deep);
    }

    .back-button {
      display: inline-block;
      margin-bottom: 1rem;
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: opacity 0.3s ease;
    }

    .back-button:hover {
      opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .login-wrapper {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .welcome-section h1 {
        font-size: 2rem;
      }

      .welcome-section {
        padding: 1rem;
      }

      .login-card {
        padding: 2rem;
      }

      .login-container {
        margin-top: 80px;
        padding: 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fa fa-hospital-o"></i> Global Hospitals
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="services.html">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Login Section -->
  <div class="login-container">
    <div class="login-wrapper">
      <!-- Welcome Section -->
      <div class="welcome-section">
        <a href="index.php" class="back-button"><i class="fa fa-arrow-left"></i> Back to Home</a>
        
        <h1>Welcome to Your Healthcare Journey</h1>
        <p>Access your medical records, appointments, and prescriptions all in one place.</p>
        
        <ul class="feature-list">
          <li>
            <i class="fa fa-calendar-check-o"></i>
            <span><strong>Book Appointments</strong> - Schedule with our expert doctors</span>
          </li>
          <li>
            <i class="fa fa-file-medical-alt" style="font-family: Arial;"></i>
            <span><strong>View Prescriptions</strong> - Access your medical records anytime</span>
          </li>
          <li>
            <i class="fa fa-history"></i>
            <span><strong>Appointment History</strong> - Track your medical visits</span>
          </li>
          <li>
            <i class="fa fa-lock"></i>
            <span><strong>Secure & Safe</strong> - Your data is protected</span>
          </li>
        </ul>
      </div>

      <!-- Login Form -->
      <div class="login-card">
        <h2>Patient Login</h2>
        <p class="subtitle">Sign in to your account</p>
        
        <form method="POST" action="func.php">
          <div class="form-group-custom">
            <label for="email"><i class="fa fa-envelope"></i> Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required/>
          </div>

          <div class="form-group-custom">
            <label for="password"><i class="fa fa-lock"></i> Password</label>
            <input type="password" id="password" name="password2" placeholder="Enter your password" required/>
          </div>

          <button type="submit" name="patsub" class="btn-login">
            <i class="fa fa-sign-in"></i> Sign In
          </button>
        </form>

        <div class="login-footer">
          <p>Don''t have an account? <a href="index.php">Register here</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
