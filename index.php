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
    <title>Electric Bill Tracker - Login</title>
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

        <!-- Right Side - Login Form -->
        <div class="auth-form-wrapper">
            <div class="auth-form-card">
                <h2 class="form-title">Log in</h2>
                
                <form id="loginForm" class="auth-form">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>
                    
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                    
                    <button type="submit" class="btn-primary btn-login">Log in</button>
                </form>
                
                <p class="auth-redirect">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/app.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', handleLogin);
    </script>
</body>
</html>

