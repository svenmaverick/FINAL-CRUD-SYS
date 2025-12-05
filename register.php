<?php
// Start session for PHP-side features
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Note: Authentication redirect is handled by JavaScript
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Bill Tracker - Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="auth-page">
    <!-- Theme Toggle -->
    <div class="theme-toggle-container">
        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
            <i class="bi bi-moon-fill"></i>
        </button>
    </div>

    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="brand-logo">
                <img src="assets/images/logo.svg" alt="Electric Bill Tracker Logo" class="logo-img">
                <h1 class="brand-title">ELECTRIC BILL<br> TRACKER</h1>
            </div>
            <div class="brand-tagline">
                <h2>Take Control of Your Electric Bills.</h2>
                <p>Track consumption, predict costs, and save energy with smart analytics.</p>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="auth-form-wrapper">
            <div class="auth-form-card register-card">
                <h2 class="form-title">Create your Account</h2>
                
                <form id="registerForm" class="auth-form">
                    <div class="form-row">
                        <div class="form-group half">
                            <div class="input-wrapper small">
                                <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="form-group half">
                            <div class="input-wrapper small">
                                <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper small">
                            <input type="email" id="regEmail" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper small">
                            <input type="password" id="regPassword" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper small">
                            <input type="text" id="street" name="street" placeholder="Street/House #" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <div class="input-wrapper small">
                                <input type="text" id="barangay" name="barangay" placeholder="Barangay" required>
                            </div>
                        </div>
                        <div class="form-group half">
                            <div class="input-wrapper small">
                                <input type="text" id="municipality" name="municipality" placeholder="Municipality" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper small">
                            <input type="text" id="province" name="province" placeholder="Province" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary btn-login">Create</button>
                </form>
                
                <p class="auth-redirect">
                    Already have an account? <a href="index.php">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/app.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', handleRegister);
    </script>
</body>
</html>

