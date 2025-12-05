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
    <title>Electric Bill Tracker - Usage</title>
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
                    <a href="usage.php" class="nav-item active">
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
                        <h1 class="page-title">Usage</h1>
                        <div class="welcome-section">
                            <h2 class="welcome-text">Consumption Trends Analysis</h2>
                            <p class="welcome-subtitle">Manage and view your past electric consumption and costs.</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Usage Content -->
            <div class="usage-content">
                <!-- Chart Card -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Monthly Consumption History (kWh)</h3>
                        <p class="chart-subtitle">Visuals: Bar Chart</p>
                    </div>
                    
                    <div class="chart-container">
                        <canvas id="consumptionChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="toast" class="toast"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            loadUsageChart();
        });
    </script>
</body>
</html>

