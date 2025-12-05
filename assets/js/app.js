/**
 * ELECTRIC BILL TRACKER - Main JavaScript File
 * Handles all client-side functionality including:
 * - Authentication (Login/Register)
 * - Dashboard calculations
 * - Bills management
 * - Usage charts
 * - Account management
 * - Theme switching (Dark/Light mode)
 */

// ============================================
// CONFIGURATION & GLOBALS
// ============================================

const API_BASE = 'api/';

// Store user data in localStorage for demo purposes
// In production, this would be handled by PHP sessions
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;

// Global variable to store loaded bills (fixes "Bill not found" issue)
let loadedBills = [];

// ============================================
// THEME MANAGEMENT
// ============================================

/**
 * Initialize theme from localStorage or default to light
 */
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);
}

/**
 * Set the theme
 * @param {string} theme - 'light' or 'dark'
 */
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Update toggle icon
    const themeToggle = document.getElementById('themeToggle');
    
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill';
                themeToggle.title = 'Toggle Light Mode';
            } else {
                icon.className = 'bi bi-moon-fill';
                themeToggle.title = 'Toggle Dark Mode';
            }
        }
    }
}

/**
 * Toggle between light and dark themes
 */
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

// Initialize theme toggle event listeners
document.addEventListener('DOMContentLoaded', function() {
    initTheme();
    
    const themeToggle = document.getElementById('themeToggle');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
});

// ============================================
// TOAST NOTIFICATIONS
// ============================================

/**
 * Show a toast notification
 * @param {string} message - Message to display
 * @param {string} type - 'success', 'error', or 'warning'
 */
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// ============================================
// AUTHENTICATION
// ============================================

/**
 * Check if user is authenticated
 * Redirect to login if not on auth pages
 */
function checkAuth() {
    currentUser = JSON.parse(localStorage.getItem('currentUser'));
    
    if (!currentUser) {
        window.location.href = 'index.php';
        return false;
    }
    
    // Update username display
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
        userNameElement.textContent = currentUser.first_name || 'User';
    }
    
    return true;
}

/**
 * Handle login form submission
 * @param {Event} e - Form submit event
 */
async function handleLogin(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch(`${API_BASE}auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'login', email, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            localStorage.setItem('currentUser', JSON.stringify(data.user));
            showToast('Login successful!', 'success');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1000);
        } else {
            showToast(data.message || 'Login failed', 'error');
        }
    } catch (error) {
        // Log error for debugging
        console.error('API Error:', error);
        
        // Fallback for demo without PHP backend
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const user = users.find(u => u.email === email && u.password === password);
        
        if (user) {
            localStorage.setItem('currentUser', JSON.stringify(user));
            showToast('Login successful (using local storage). Check console for API errors.', 'warning');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1000);
        } else {
            showToast('Invalid email or password', 'error');
        }
    }
}

/**
 * Handle registration form submission
 * @param {Event} e - Form submit event
 */
async function handleRegister(e) {
    e.preventDefault();
    
    const userData = {
        first_name: document.getElementById('firstName').value,
        last_name: document.getElementById('lastName').value,
        email: document.getElementById('regEmail').value,
        password: document.getElementById('regPassword').value,
        street: document.getElementById('street').value,
        barangay: document.getElementById('barangay').value,
        municipality: document.getElementById('municipality').value,
        province: document.getElementById('province').value
    };
    
    try {
        const response = await fetch(`${API_BASE}auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'register', ...userData })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Account created successfully!', 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast(data.message || 'Registration failed', 'error');
        }
    } catch (error) {
        // Log error for debugging
        console.error('API Error:', error);
        
        // Fallback for demo without PHP backend
        const users = JSON.parse(localStorage.getItem('users')) || [];
        
        // Check if email exists
        if (users.find(u => u.email === userData.email)) {
            showToast('Email already registered', 'error');
            return;
        }
        
        // Add user ID
        userData.id = Date.now();
        users.push(userData);
        localStorage.setItem('users', JSON.stringify(users));
        
        showToast('Account created in local storage (API unavailable). Check console for details.', 'warning');
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 1500);
    }
}

/**
 * Handle forgot password form submission
 * @param {Event} e - Form submit event
 */
async function handleForgotPassword(e) {
    e.preventDefault();
    
    const fullName = document.getElementById('forgotFullName').value.trim();
    
    if (!fullName) {
        showToast('Please enter your full name', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'forgot_password', full_name: fullName })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showResetPasswordModal(data.user_id, data.email);
        } else {
            showToast(data.message || 'User not found', 'error');
        }
    } catch (error) {
        // Fallback - check if name exists in localStorage
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const searchName = fullName.toLowerCase();
        
        // Find user by full name (first_name + last_name)
        const user = users.find(u => {
            const userFullName = `${u.first_name} ${u.last_name}`.toLowerCase();
            return userFullName === searchName || 
                   u.first_name.toLowerCase() === searchName ||
                   userFullName.includes(searchName);
        });
        
        if (user) {
            // For demo purposes, show the reset modal
            showResetPasswordModal(user.id, user.email);
        } else {
            showToast('User not found. Please check your full name.', 'error');
        }
    }
}

/**
 * Show reset password modal (for demo)
 */
function showResetPasswordModal(userId, email) {
    const modal = document.getElementById('resetPasswordModal');
    if (modal) {
        document.getElementById('resetUserId').value = userId;
        document.getElementById('resetEmail').value = email;
        modal.classList.add('active');
    }
}

/**
 * Close reset password modal
 */
function closeResetModal() {
    const modal = document.getElementById('resetPasswordModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

/**
 * Handle password reset
 * @param {Event} e - Form submit event
 */
async function handleResetPassword(e) {
    e.preventDefault();
    
    const userId = parseInt(document.getElementById('resetUserId').value);
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        showToast('Passwords do not match', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showToast('Password must be at least 6 characters', 'error');
        return;
    }
    
    try {
        // Try to update password via API
        const response = await fetch(`${API_BASE}auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                action: 'reset_password', 
                user_id: userId, 
                new_password: newPassword 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Password reset successfully! Please login.', 'success');
            closeResetModal();
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast(data.message || 'Failed to reset password', 'error');
        }
    } catch (error) {
        // Fallback - Update password in localStorage
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.id === userId);
        
        if (userIndex !== -1) {
            users[userIndex].password = newPassword;
            localStorage.setItem('users', JSON.stringify(users));
            
            showToast('Password reset successfully! Please login.', 'success');
            closeResetModal();
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        } else {
            showToast('User not found', 'error');
        }
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    localStorage.removeItem('currentUser');
    showToast('Logged out successfully', 'success');
    setTimeout(() => {
        window.location.href = 'index.php';
    }, 1000);
}

/**
 * Toggle password visibility
 * @param {string} inputId - ID of the password input
 */
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling?.querySelector('i') || 
                 input.parentElement.querySelector('.toggle-password i');
    
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) icon.className = 'bi bi-eye-slash-fill';
    } else {
        input.type = 'password';
        if (icon) icon.className = 'bi bi-eye-fill';
    }
}

// ============================================
// DASHBOARD FUNCTIONS
// ============================================

/**
 * Load dashboard data
 */
async function loadDashboardData() {
    if (!currentUser) return;
    
    try {
        const response = await fetch(`${API_BASE}meter.php?user_id=${currentUser.id}`);
        const data = await response.json();
        
        if (data.success && data.reading) {
            updateDashboardDisplay(data.reading);
        }
    } catch (error) {
        // Fallback for demo without PHP backend
        const readings = JSON.parse(localStorage.getItem('meterReadings')) || {};
        const userReading = readings[currentUser.id];
        
        if (userReading) {
            updateDashboardDisplay(userReading);
        }
    }
}

/**
 * Update dashboard display with reading data
 * @param {object} reading - Meter reading data
 */
function updateDashboardDisplay(reading) {
    const prevReading = parseFloat(reading.previous_reading) || 0;
    const currReading = parseFloat(reading.current_reading) || 0;
    const ratePerKwh = parseFloat(reading.rate_per_kwh) || 0;
    const consumption = currReading - prevReading;
    const estimatedCost = consumption * ratePerKwh;
    
    // Update stat cards
    document.getElementById('prevReading').textContent = prevReading.toFixed(0);
    document.getElementById('currReading').textContent = currReading.toFixed(0);
    document.getElementById('monthlyConsumption').textContent = consumption.toFixed(0);
    document.getElementById('ratePerKwh').textContent = ratePerKwh.toFixed(2);
    
    // Update breakdown
    document.getElementById('breakdownKwh').textContent = consumption.toFixed(0);
    document.getElementById('breakdownCost').textContent = estimatedCost.toFixed(2);
    document.getElementById('breakdownKwh2').textContent = consumption.toFixed(0);
    
    // Update input fields
    document.getElementById('inputPrevReading').value = prevReading || '';
    document.getElementById('inputCurrReading').value = currReading || '';
}

/**
 * Calculate bill based on readings
 * Formula: 
 * - Usage = Current Reading - Previous Reading
 * - Cost per kWh = Current Bill / Current kWh used
 * - Estimated Bill = Usage * Cost per kWh
 */
function calculateBill() {
    const prevReading = parseFloat(document.getElementById('inputPrevReading').value) || 0;
    const currReading = parseFloat(document.getElementById('inputCurrReading').value) || 0;
    
    if (currReading < prevReading) {
        showToast('Current reading must be greater than previous reading', 'error');
        return;
    }
    
    const usage = currReading - prevReading;
    
    // Get bills to calculate rate
    let ratePerKwh = 0;
    if (loadedBills.length > 0) {
        const recentBill = loadedBills[loadedBills.length - 1];
        if (recentBill.consumption_kwh > 0) {
            ratePerKwh = recentBill.total_cost / recentBill.consumption_kwh;
        }
    }
    
    // Default rate if no bills exist (typical Philippine rate)
    if (ratePerKwh === 0) {
        ratePerKwh = 11.50; // Default rate per kWh
    }
    
    const estimatedCost = usage * ratePerKwh;
    
    // Update display
    document.getElementById('calculatedUsage').textContent = usage.toFixed(2);
    document.getElementById('prevReading').textContent = prevReading.toFixed(0);
    document.getElementById('currReading').textContent = currReading.toFixed(0);
    document.getElementById('monthlyConsumption').textContent = usage.toFixed(0);
    document.getElementById('ratePerKwh').textContent = ratePerKwh.toFixed(2);
    
    // Update breakdown
    document.getElementById('breakdownKwh').textContent = usage.toFixed(0);
    document.getElementById('breakdownCost').textContent = estimatedCost.toFixed(2);
    document.getElementById('breakdownKwh2').textContent = usage.toFixed(0);
    
    // Save meter reading
    saveMeterReading(prevReading, currReading, ratePerKwh);
    
    showToast('Bill calculated and meter updated!', 'success');
}

/**
 * Save meter reading to storage/database
 */
async function saveMeterReading(prevReading, currReading, ratePerKwh) {
    const reading = {
        user_id: currentUser.id,
        previous_reading: prevReading,
        current_reading: currReading,
        rate_per_kwh: ratePerKwh,
        reading_date: new Date().toISOString()
    };
    
    try {
        await fetch(`${API_BASE}meter.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(reading)
        });
    } catch (error) {
        // Log error for debugging
        console.error('API Error saving meter reading:', error);
        
        // Fallback for demo
        const readings = JSON.parse(localStorage.getItem('meterReadings')) || {};
        readings[currentUser.id] = reading;
        localStorage.setItem('meterReadings', JSON.stringify(readings));
    }
}

// ============================================
// BILLS MANAGEMENT
// ============================================

/**
 * Load bills history
 */
async function loadBills() {
    if (!currentUser) return;
    
    try {
        const response = await fetch(`${API_BASE}bills.php?user_id=${currentUser.id}`);
        const data = await response.json();
        
        if (data.success) {
            loadedBills = data.bills; // Store globally
            renderBillsTable(data.bills);
        }
    } catch (error) {
        // Fallback for demo
        const bills = JSON.parse(localStorage.getItem('bills')) || [];
        const userBills = bills.filter(b => b.user_id === currentUser.id);
        loadedBills = userBills; // Store globally
        renderBillsTable(userBills);
    }
}

/**
 * Render bills to table
 * @param {array} bills - Array of bill objects
 */
function renderBillsTable(bills) {
    const tbody = document.getElementById('billsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (bills.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    No bills found. Add your first bill record above.
                </td>
            </tr>
        `;
        return;
    }
    
    bills.forEach(bill => {
        const dueDate = new Date(bill.due_date);
        const formattedDate = `${(dueDate.getMonth() + 1).toString().padStart(2, '0')}-${dueDate.getDate().toString().padStart(2, '0')}-${dueDate.getFullYear().toString().slice(-2)}`;
        
        const statusClass = bill.status.toLowerCase();
        const customerName = bill.customer_name || 'N/A';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${customerName}</td>
            <td>${bill.billing_month}</td>
            <td>${parseFloat(bill.consumption_kwh).toFixed(2)}</td>
            <td>₱${parseFloat(bill.total_cost).toFixed(2)}</td>
            <td>${formattedDate}</td>
            <td><span class="status-badge status-${statusClass}">${bill.status}</span></td>
            <td>
                <div class="table-actions">
                    <button class="btn-edit" onclick="openEditModal(${bill.id})">Edit</button>
                    <button class="btn-delete" onclick="deleteBill(${bill.id})">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

/**
 * Handle add bill form submission
 * @param {Event} e - Form submit event
 */
async function handleAddBill(e) {
    e.preventDefault();
    
    const billData = {
        user_id: currentUser.id,
        customer_name: document.getElementById('customerName').value.trim(),
        billing_month: document.getElementById('billingMonth').value,
        consumption_kwh: parseFloat(document.getElementById('consumptionKwh').value),
        total_cost: parseFloat(document.getElementById('totalCost').value),
        due_date: document.getElementById('dueDate').value,
        status: 'Unpaid'
    };
    
    if (!billData.customer_name || !billData.billing_month || !billData.consumption_kwh || !billData.total_cost || !billData.due_date) {
        showToast('Please fill all fields', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}bills.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(billData)
        });
        
        // Check if response is ok
        if (!response.ok) {
            const errorText = await response.text();
            console.error('API Response Error:', response.status, errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Bill added successfully!', 'success');
            document.getElementById('addBillForm').reset();
            loadBills();
        } else {
            showToast(data.message || 'Failed to add bill', 'error');
            console.error('API returned error:', data);
        }
    } catch (error) {
        // Log detailed error for debugging
        console.error('API Error Details:', {
            error: error.message,
            url: `${API_BASE}bills.php`,
            data: billData
        });
        
        // Show error to user
        showToast(`API Error: ${error.message}. Check console (F12) for details.`, 'error');
        
        // Fallback for demo - but warn user
        const bills = JSON.parse(localStorage.getItem('bills')) || [];
        billData.id = Date.now();
        bills.push(billData);
        localStorage.setItem('bills', JSON.stringify(bills));
        
        showToast('Bill saved to local storage only (not in database).', 'warning');
        document.getElementById('addBillForm').reset();
        loadBills();
    }
}

/**
 * Open edit bill modal - FIXED to use loadedBills
 * @param {number} billId - ID of the bill to edit
 */
function openEditModal(billId) {
    // First try to find in loadedBills (from API)
    let bill = loadedBills.find(b => b.id == billId);
    
    // Fallback to localStorage if not found
    if (!bill) {
        const bills = JSON.parse(localStorage.getItem('bills')) || [];
        bill = bills.find(b => b.id == billId);
    }
    
    if (!bill) {
        showToast('Bill not found', 'error');
        return;
    }
    
    document.getElementById('editBillId').value = bill.id;
    document.getElementById('editCustomerName').value = bill.customer_name || '';
    document.getElementById('editBillingMonth').value = bill.billing_month;
    document.getElementById('editConsumption').value = bill.consumption_kwh;
    document.getElementById('editTotalCost').value = bill.total_cost;
    document.getElementById('editDueDate').value = bill.due_date;
    document.getElementById('editStatus').value = bill.status;
    
    document.getElementById('editBillModal').classList.add('active');
}

/**
 * Close edit modal
 */
function closeEditModal() {
    document.getElementById('editBillModal').classList.remove('active');
}

/**
 * Handle edit bill form submission
 * @param {Event} e - Form submit event
 */
async function handleEditBill(e) {
    e.preventDefault();
    
    const billId = parseInt(document.getElementById('editBillId').value);
    const billData = {
        id: billId,
        customer_name: document.getElementById('editCustomerName').value.trim(),
        billing_month: document.getElementById('editBillingMonth').value,
        consumption_kwh: parseFloat(document.getElementById('editConsumption').value),
        total_cost: parseFloat(document.getElementById('editTotalCost').value),
        due_date: document.getElementById('editDueDate').value,
        status: document.getElementById('editStatus').value
    };
    
    try {
        const response = await fetch(`${API_BASE}bills.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(billData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Bill updated successfully!', 'success');
            closeEditModal();
            loadBills();
        } else {
            showToast(data.message || 'Failed to update bill', 'error');
        }
    } catch (error) {
        // Fallback for demo
        const bills = JSON.parse(localStorage.getItem('bills')) || [];
        const index = bills.findIndex(b => b.id === billId);
        
        if (index !== -1) {
            bills[index] = { ...bills[index], ...billData };
            localStorage.setItem('bills', JSON.stringify(bills));
            
            showToast('Bill updated successfully!', 'success');
            closeEditModal();
            loadBills();
        }
    }
}

/**
 * Delete a bill
 * @param {number} billId - ID of the bill to delete
 */
async function deleteBill(billId) {
    if (!confirm('Are you sure you want to delete this bill?')) return;
    
    try {
        const response = await fetch(`${API_BASE}bills.php?id=${billId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Bill deleted successfully!', 'success');
            loadBills();
        } else {
            showToast(data.message || 'Failed to delete bill', 'error');
        }
    } catch (error) {
        // Fallback for demo
        let bills = JSON.parse(localStorage.getItem('bills')) || [];
        bills = bills.filter(b => b.id !== billId);
        localStorage.setItem('bills', JSON.stringify(bills));
        
        showToast('Bill deleted successfully!', 'success');
        loadBills();
    }
}

/**
 * Search bills by customer name
 * Filters records based on typed keyword with partial matching
 */
function searchBills() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    
    if (!searchTerm) {
        // If search is empty, show all bills
        renderBillsTable(loadedBills);
        return;
    }
    
    // Filter bills by customer name (partial matching)
    const filteredBills = loadedBills.filter(bill => {
        const customerName = (bill.customer_name || '').toLowerCase();
        return customerName.includes(searchTerm);
    });
    
    // Render filtered results
    renderBillsTable(filteredBills);
}

/**
 * Print bills table
 */
function printBills() {
    window.print();
}

/**
 * Toggle expanded table view
 */
function toggleExpandTable() {
    const modal = document.getElementById('expandModal');
    const expandedContainer = document.getElementById('expandedTableContainer');
    
    if (modal && expandedContainer) {
        // Render expanded table with all bills
        renderExpandedBillsTable(loadedBills);
        
        // Clear search input
        const searchInput = document.getElementById('expandedSearchInput');
        if (searchInput) {
            searchInput.value = '';
        }
        
        modal.classList.add('active');
    }
}

/**
 * Render bills to expanded table
 * @param {array} bills - Array of bill objects
 */
function renderExpandedBillsTable(bills) {
    const container = document.getElementById('expandedTableContainer');
    if (!container) return;
    
    let tableHTML = `
        <table class="bills-table" id="expandedBillsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Billing Month</th>
                    <th>Consumption (kWh)</th>
                    <th>Total Cost (₱)</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    if (bills.length === 0) {
        tableHTML += `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    No bills found matching your search.
                </td>
            </tr>
        `;
    } else {
        bills.forEach(bill => {
            const dueDate = new Date(bill.due_date);
            const formattedDate = `${(dueDate.getMonth() + 1).toString().padStart(2, '0')}-${dueDate.getDate().toString().padStart(2, '0')}-${dueDate.getFullYear().toString().slice(-2)}`;
            
            const statusClass = bill.status.toLowerCase();
            const customerName = bill.customer_name || 'N/A';
            
            tableHTML += `
                <tr>
                    <td>${customerName}</td>
                    <td>${bill.billing_month}</td>
                    <td>${parseFloat(bill.consumption_kwh).toFixed(2)}</td>
                    <td>₱${parseFloat(bill.total_cost).toFixed(2)}</td>
                    <td>${formattedDate}</td>
                    <td><span class="status-badge status-${statusClass}">${bill.status}</span></td>
                </tr>
            `;
        });
    }
    
    tableHTML += '</tbody></table>';
    container.innerHTML = tableHTML;
}

/**
 * Search bills in expanded view by customer name
 * Filters records based on typed keyword with partial matching
 */
function searchExpandedBills() {
    const searchTerm = document.getElementById('expandedSearchInput').value.toLowerCase().trim();
    
    if (!searchTerm) {
        // If search is empty, show all bills
        renderExpandedBillsTable(loadedBills);
        return;
    }
    
    // Filter bills by customer name (partial matching)
    const filteredBills = loadedBills.filter(bill => {
        const customerName = (bill.customer_name || '').toLowerCase();
        return customerName.includes(searchTerm);
    });
    
    // Render filtered results
    renderExpandedBillsTable(filteredBills);
}

/**
 * Close expand modal
 */
function closeExpandModal() {
    document.getElementById('expandModal').classList.remove('active');
}

/**
 * Print expanded bills
 */
function printExpandedBills() {
    const content = document.getElementById('expandedTableContainer').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Historical Bills - Electric Bill Tracker</title>
            <style>
                body { font-family: 'Inter', Arial, sans-serif; padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
                th { background: #333; color: white; }
                h1 { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h1>Historical Bills - Electric Bill Tracker</h1>
            ${content}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

/**
 * Download table as image
 */
async function downloadAsImage() {
    const container = document.getElementById('expandedTableContainer');
    
    if (typeof html2canvas !== 'undefined') {
        try {
            const canvas = await html2canvas(container, {
                backgroundColor: '#ffffff',
                scale: 2
            });
            
            const link = document.createElement('a');
            link.download = 'historical-bills.png';
            link.href = canvas.toDataURL();
            link.click();
            
            showToast('Image downloaded successfully!', 'success');
        } catch (error) {
            showToast('Failed to generate image', 'error');
        }
    } else {
        showToast('Image export not available', 'error');
    }
}

// ============================================
// USAGE CHART
// ============================================

/**
 * Load and render usage chart
 */
function loadUsageChart() {
    if (!currentUser) return;
    
    // Use loadedBills if available, otherwise get from localStorage
    let userBills = loadedBills.length > 0 ? loadedBills : [];
    
    if (userBills.length === 0) {
        const bills = JSON.parse(localStorage.getItem('bills')) || [];
        userBills = bills.filter(b => b.user_id === currentUser.id);
    }
    
    // Organize data by month
    const monthOrder = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    
    const monthData = {};
    userBills.forEach(bill => {
        if (!monthData[bill.billing_month]) {
            monthData[bill.billing_month] = 0;
        }
        monthData[bill.billing_month] += parseFloat(bill.consumption_kwh);
    });
    
    // Prepare chart data
    const labels = monthOrder.filter(month => monthData[month]);
    const data = labels.map(month => monthData[month] || 0);
    
    // If no data, show sample data
    if (labels.length === 0) {
        labels.push('January', 'February', 'March', 'April', 'May', 'June');
        data.push(0, 0, 0, 0, 0, 0);
    }
    
    // Get chart canvas
    const ctx = document.getElementById('consumptionChart');
    if (!ctx) return;
    
    // Check if chart exists and destroy it
    if (window.usageChart) {
        window.usageChart.destroy();
    }
    
    // Determine colors based on theme
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#ffffff' : '#000000';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    
    // Create chart
    window.usageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Consumption (kWh)',
                data: data,
                backgroundColor: isDark ? 'rgba(100, 100, 100, 0.8)' : 'rgba(108, 108, 108, 0.8)',
                borderColor: isDark ? 'rgba(150, 150, 150, 1)' : 'rgba(80, 80, 80, 1)',
                borderWidth: 1,
                borderRadius: 5,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: textColor,
                        font: {
                            family: "'Inter', sans-serif",
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? '#333' : '#fff',
                    titleColor: textColor,
                    bodyColor: textColor,
                    borderColor: gridColor,
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor,
                        font: {
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor,
                        font: {
                            family: "'Inter', sans-serif"
                        },
                        callback: function(value) {
                            return value + ' kWh';
                        }
                    }
                }
            }
        }
    });
}

// Re-render chart when theme changes
const originalToggleTheme = toggleTheme;
toggleTheme = function() {
    originalToggleTheme();
    setTimeout(() => {
        if (window.usageChart) {
            loadUsageChart();
        }
    }, 100);
};

// ============================================
// ACCOUNT MANAGEMENT
// ============================================

/**
 * Load account data
 */
function loadAccountData() {
    if (!currentUser) return;
    
    // Personal Information
    document.getElementById('fullName').value = 
        `${currentUser.first_name || ''} ${currentUser.last_name || ''}`.trim();
    document.getElementById('accountEmail').value = currentUser.email || '';
    document.getElementById('phoneNumber').value = currentUser.phone || '';
    
    // Billing Address
    document.getElementById('streetAddress').value = currentUser.street || '';
    document.getElementById('barangayAddress').value = currentUser.barangay || '';
    document.getElementById('municipalityAddress').value = currentUser.municipality || '';
    document.getElementById('provinceAddress').value = currentUser.province || '';
}

/**
 * Toggle personal info edit mode
 */
function toggleEditPersonal() {
    const inputs = ['fullName', 'accountEmail', 'phoneNumber'];
    const editBtn = document.getElementById('editPersonalBtn');
    const saveBtn = document.getElementById('savePersonalBtn');
    
    const isEditing = editBtn.textContent === 'Cancel';
    
    inputs.forEach(id => {
        document.getElementById(id).disabled = isEditing;
    });
    
    if (isEditing) {
        editBtn.textContent = 'Edit';
        saveBtn.style.display = 'none';
        loadAccountData(); // Reset values
    } else {
        editBtn.textContent = 'Cancel';
        saveBtn.style.display = 'block';
    }
}

/**
 * Save personal information
 */
async function savePersonalInfo() {
    const fullName = document.getElementById('fullName').value.trim();
    const nameParts = fullName.split(' ');
    const firstName = nameParts[0] || '';
    const lastName = nameParts.slice(1).join(' ') || '';
    
    const userData = {
        ...currentUser,
        first_name: firstName,
        last_name: lastName,
        email: document.getElementById('accountEmail').value,
        phone: document.getElementById('phoneNumber').value
    };
    
    try {
        const response = await fetch(`${API_BASE}users.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentUser = userData;
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            showToast('Personal information updated!', 'success');
            toggleEditPersonal();
        } else {
            showToast(data.message || 'Failed to update', 'error');
        }
    } catch (error) {
        // Fallback for demo
        currentUser = userData;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        
        // Update users array
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const index = users.findIndex(u => u.id === currentUser.id);
        if (index !== -1) {
            users[index] = currentUser;
            localStorage.setItem('users', JSON.stringify(users));
        }
        
        showToast('Personal information updated!', 'success');
        toggleEditPersonal();
    }
}

/**
 * Toggle billing address edit mode
 */
function toggleEditBilling() {
    const inputs = ['streetAddress', 'barangayAddress', 'municipalityAddress', 'provinceAddress'];
    const editBtn = document.getElementById('editBillingBtn');
    const saveBtn = document.getElementById('saveBillingBtn');
    
    const isEditing = editBtn.textContent === 'Cancel';
    
    inputs.forEach(id => {
        document.getElementById(id).disabled = isEditing;
    });
    
    if (isEditing) {
        editBtn.textContent = 'Edit';
        saveBtn.style.display = 'none';
        loadAccountData(); // Reset values
    } else {
        editBtn.textContent = 'Cancel';
        saveBtn.style.display = 'block';
    }
}

/**
 * Save billing address
 */
async function saveBillingAddress() {
    const userData = {
        ...currentUser,
        street: document.getElementById('streetAddress').value,
        barangay: document.getElementById('barangayAddress').value,
        municipality: document.getElementById('municipalityAddress').value,
        province: document.getElementById('provinceAddress').value
    };
    
    try {
        const response = await fetch(`${API_BASE}users.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentUser = userData;
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            showToast('Billing address updated!', 'success');
            toggleEditBilling();
        } else {
            showToast(data.message || 'Failed to update', 'error');
        }
    } catch (error) {
        // Fallback for demo
        currentUser = userData;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        
        // Update users array
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const index = users.findIndex(u => u.id === currentUser.id);
        if (index !== -1) {
            users[index] = currentUser;
            localStorage.setItem('users', JSON.stringify(users));
        }
        
        showToast('Billing address updated!', 'success');
        toggleEditBilling();
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Format currency
 * @param {number} amount - Amount to format
 * @returns {string} Formatted currency string
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

/**
 * Format date
 * @param {string} dateString - Date string to format
 * @returns {string} Formatted date string (MM-DD-YY)
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const year = date.getFullYear().toString().slice(-2);
    return `${month}-${day}-${year}`;
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});


