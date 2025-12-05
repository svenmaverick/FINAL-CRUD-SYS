<?php
// Start session for PHP-side features
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Note: Authentication is primarily handled by JavaScript checkAuth()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Bill Tracker - Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="app-page">
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-content">
                <!-- Logo -->
                <div class="sidebar-logo">
                    <img src="assets/images/logo.svg" alt="Logo" class="sidebar-logo-img">
                    <span class="sidebar-brand">ELECTRIC BILL TRACKER</span>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav">
                    <a href="dashboard.php" class="nav-item">
                        <i class="bi bi-bar-chart-fill nav-icon"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="bills.php" class="nav-item">
                        <i class="bi bi-calendar-event nav-icon"></i>
                        <span>Bills History</span>
                    </a>
                    <a href="usage.php" class="nav-item">
                        <i class="bi bi-lightning-fill nav-icon"></i>
                        <span>Usage</span>
                    </a>
                    <a href="account.php" class="nav-item active">
                        <i class="bi bi-person-fill nav-icon"></i>
                        <span>Account</span>
                    </a>
                </nav>

                <!-- Logout -->
                <div class="sidebar-footer">
                    <a href="#" class="nav-item logout-btn" onclick="handleLogout()">
                        <i class="bi bi-box-arrow-left nav-icon"></i>
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-content">
                    <div class="page-info">
                        <h1 class="page-title">Account</h1>
                        <div class="welcome-section">
                            <p class="welcome-subtitle">Manage Account and Billing address.</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Account Content -->
            <div class="account-content">
                <!-- Personal Information Card -->
                <div class="account-card">
                    <h3 class="card-title">Personal Information</h3>
                    
                    <form id="personalInfoForm" class="account-form">
                        <div class="form-group">
                            <input type="text" id="fullName" class="form-input" placeholder="Full Name" disabled>
                        </div>
                        
                        <div class="form-group">
                            <input type="email" id="accountEmail" class="form-input" placeholder="Email Address" disabled>
                        </div>
                        
                        <div class="form-group">
                            <input type="tel" id="phoneNumber" class="form-input" placeholder="Phone Number" disabled>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn-secondary" id="savePersonalBtn" onclick="savePersonalInfo()" style="display: none;">Save</button>
                            <button type="button" class="btn-primary" id="editPersonalBtn" onclick="toggleEditPersonal()">Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Billing Address Card -->
                <div class="account-card">
                    <h3 class="card-title">Billing Address</h3>
                    
                    <form id="billingAddressForm" class="account-form">
                        <div class="form-group">
                            <input type="text" id="streetAddress" class="form-input" placeholder="Street/House #" disabled>
                        </div>
                        
                        <div class="form-row two-cols">
                            <div class="form-group">
                                <input type="text" id="barangayAddress" class="form-input" placeholder="Barangay" disabled>
                            </div>
                            <div class="form-group">
                                <input type="text" id="municipalityAddress" class="form-input" placeholder="Municipality" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="provinceAddress" class="form-input" placeholder="Province" disabled>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn-secondary" id="saveBillingBtn" onclick="saveBillingAddress()" style="display: none;">Save</button>
                            <button type="button" class="btn-primary" id="editBillingBtn" onclick="toggleEditBilling()">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            loadAccountData();
        });
    </script>
</body>
</html>

