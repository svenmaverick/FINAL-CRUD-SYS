<?php
// Start session for PHP-side features
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Note: Authentication is primarily handled by JavaScript checkAuth()
// PHP session is used for server-side operations when needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Bill Tracker - Dashboard</title>
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
                    <a href="dashboard.php" class="nav-item active">
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
                    <a href="account.php" class="nav-item">
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
                        <h1 class="page-title">Dashboard</h1>
                        <div class="welcome-section">
                            <h2 class="welcome-text">Welcome, <span id="userName">User</span></h2>
                            <p class="welcome-subtitle">This is your electric bill analytics.</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards Row -->
                <div class="stats-row">
                    <div class="stat-card light">
                        <h3 class="stat-title">Previous Reading</h3>
                        <div class="stat-value-row">
                            <span class="stat-value" id="prevReading">0</span>
                            <span class="stat-unit">kWh</span>
                        </div>
                        <p class="stat-desc">Reading from the start<br>of the billing cycle</p>
                    </div>
                    <div class="stat-card dark">
                        <h3 class="stat-title">Current Reading</h3>
                        <div class="stat-value-row">
                            <span class="stat-value" id="currReading">0</span>
                            <span class="stat-unit">kWh</span>
                        </div>
                        <p class="stat-desc">Latest reading entered by the user</p>
                    </div>
                    <div class="stat-card light">
                        <h3 class="stat-title">Monthly Consumption</h3>
                        <div class="stat-value-row">
                            <span class="stat-value" id="monthlyConsumption">0</span>
                            <span class="stat-unit">kWh</span>
                        </div>
                        <p class="stat-desc">Usage in the span of 1 month<br>(Current - Previous)</p>
                    </div>
                    <div class="stat-card dark">
                        <h3 class="stat-title">Est. Rate per kWh</h3>
                        <div class="stat-value-row">
                            <span class="stat-value" id="ratePerKwh">0</span>
                            <span class="stat-unit">₱/kWh</span>
                        </div>
                        <p class="stat-desc">Latest reading entered by the user</p>
                    </div>
                </div>

                <!-- Calculator and Breakdown Row -->
                <div class="calculator-row">
                    <!-- Estimate Bill Calculator -->
                    <div class="calculator-card">
                        <h3 class="card-title">Estimate Bill &<br>Update Readings</h3>
                        
                        <div class="calculator-form">
                            <div class="form-group">
                                <label class="form-label">Previous Reading (kWh)</label>
                                <input type="number" id="inputPrevReading" class="form-input" placeholder="0" step="0.01">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Current Reading (kWh)</label>
                                <input type="number" id="inputCurrReading" class="form-input" placeholder="0" step="0.01">
                            </div>
                            
                            <button type="button" class="btn-primary btn-calculate" onclick="calculateBill()">
                                Calculate & Update Meter
                            </button>
                            
                            <p class="calculated-usage">Calculated Usage: <span id="calculatedUsage">0</span> kWh</p>
                        </div>
                    </div>

                    <!-- Bill Breakdown -->
                    <div class="breakdown-card">
                        <h3 class="card-title">Current Estimated Bill Breakdown</h3>
                        
                        <div class="breakdown-grid">
                            <div class="breakdown-item">
                                <p class="breakdown-label">kWh Used This Month</p>
                                <p class="breakdown-value"><span id="breakdownKwh">0</span> kWh</p>
                                <p class="breakdown-note">(Previous - > Current)</p>
                            </div>
                            <div class="breakdown-item">
                                <p class="breakdown-label">Estimated Total<br>Cost</p>
                                <p class="breakdown-value">₱<span id="breakdownCost">0</span></p>
                                <p class="breakdown-note">(kWh Used @ Cost per kWh)</p>
                            </div>
                            <div class="breakdown-item">
                                <p class="breakdown-label">kWh Used This Month</p>
                                <p class="breakdown-value"><span id="breakdownKwh2">0</span> kWh</p>
                                <p class="breakdown-note">(Previous - > Current)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="toast" class="toast"></div>

    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            loadDashboardData();
        });
    </script>
</body>
</html>

