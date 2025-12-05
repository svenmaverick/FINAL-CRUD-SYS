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
    <title>Electric Bill Tracker - Bills History</title>
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
                    <a href="bills.php" class="nav-item active">
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
                        <h1 class="page-title">Bill History</h1>
                        <div class="welcome-section">
                            <h2 class="welcome-text">Your Bills</h2>
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

            <!-- Bills Content -->
            <div class="bills-content">
                <!-- Add New Bill Record Card -->
                <div class="add-bill-card">
                    <h3 class="card-title">Add New Bill Record</h3>
                    
                    <form id="addBillForm" class="add-bill-form">
                        <div class="form-row five-cols">
                            <div class="form-group">
                                <label class="form-label">Customer Name</label>
                                <input type="text" id="customerName" class="form-input" placeholder="Full Name" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Billing Month</label>
                                <div class="select-wrapper">
                                    <select id="billingMonth" class="form-select" required>
                                        <option value="">Select Month</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                    <i class="bi bi-chevron-down select-icon"></i>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Consumption (kWh)</label>
                                <input type="number" id="consumptionKwh" class="form-input" placeholder="kWh" step="0.01" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Total Cost(₱)</label>
                                <input type="number" id="totalCost" class="form-input" placeholder="₱0.00" step="0.01" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Due Date</label>
                                <input type="date" id="dueDate" class="form-input" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary btn-save-bill">Save Bill</button>
                    </form>
                </div>

                <!-- Historical Bills Card -->
                <div class="history-card">
                    <div class="history-header">
                        <h3 class="history-title">Historical Bills</h3>
                        <div class="history-actions">
                            <!-- Search Box -->
                            <div class="search-box">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="searchInput" class="search-input" placeholder="Search by name..." oninput="searchBills()">
                            </div>
                            <button class="action-btn" onclick="printBills()" title="Print Bills">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button class="action-btn" onclick="toggleExpandTable()" title="Expand Table">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-container" id="billsTableContainer">
                        <table class="bills-table" id="billsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Billing Month</th>
                                    <th>Consumption (kWh)</th>
                                    <th>Total Cost (₱)</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="billsTableBody">
                                <!-- Bills will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Bill Modal -->
    <div class="modal" id="editBillModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Bill Record</h3>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editBillForm" class="modal-form">
                <input type="hidden" id="editBillId">
                <div class="form-group">
                    <label class="form-label">Customer Name</label>
                    <input type="text" id="editCustomerName" class="form-input" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Billing Month</label>
                    <select id="editBillingMonth" class="form-select" required>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Consumption (kWh)</label>
                    <input type="number" id="editConsumption" class="form-input" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Total Cost (₱)</label>
                    <input type="number" id="editTotalCost" class="form-input" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" id="editDueDate" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="editStatus" class="form-select" required>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expanded Table Modal -->
    <div class="modal expand-modal" id="expandModal">
        <div class="modal-content expanded-content">
            <div class="modal-header">
                <h3>Historical Bills - Expanded View</h3>
                <button class="modal-close" onclick="closeExpandModal()">&times;</button>
            </div>
            <!-- Search Box for Expanded View -->
            <div class="expanded-search-container">
                <div class="search-box expanded-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="expandedSearchInput" class="search-input" placeholder="Search by name..." oninput="searchExpandedBills()">
                </div>
            </div>
            <div class="expanded-table-container" id="expandedTableContainer">
                <!-- Expanded table will be cloned here -->
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-primary" onclick="printExpandedBills()">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button type="button" class="btn-secondary" onclick="downloadAsImage()">
                    <i class="bi bi-image"></i> Download as Image
                </button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            loadBills();
        });
        
        document.getElementById('addBillForm').addEventListener('submit', handleAddBill);
        document.getElementById('editBillForm').addEventListener('submit', handleEditBill);
    </script>
</body>
</html>

