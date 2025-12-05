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
    <title>Electric Bill Tracker - Forgot Password</title>
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

        <!-- Right Side - Forgot Password Form -->
        <div class="auth-form-wrapper">
            <div class="auth-form-card">
                <h2 class="form-title">Forgot Password</h2>
                <p style="text-align: center; margin-bottom: 30px; color: var(--text-secondary);">
                    Enter your full name to reset your password.
                </p>
                
                <form id="forgotPasswordForm" class="auth-form">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="text" id="forgotFullName" name="fullname" placeholder="Full Name" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary btn-login">Reset Password</button>
                </form>
                
                <p class="auth-redirect">
                    Remember your password? <a href="index.php">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal" id="resetPasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reset Your Password</h3>
                <button class="modal-close" onclick="closeResetModal()">&times;</button>
            </div>
            <form id="resetPasswordForm" class="modal-form" onsubmit="handleResetPassword(event)">
                <input type="hidden" id="resetUserId">
                <input type="hidden" id="resetEmail">
                
                <p style="margin-bottom: 20px; color: var(--text-secondary);">
                    Email found! Please enter your new password.
                </p>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" id="newPassword" class="form-input" placeholder="Enter new password" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm new password" required minlength="6">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeResetModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/app.js"></script>
    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', handleForgotPassword);
    </script>
</body>
</html>

