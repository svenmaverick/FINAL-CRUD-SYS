<?php
/**
 * Electric Bill Tracker - Main Entry Point
 * This file serves as an API router or can redirect to the frontend
 */

include 'db_connect.php';

// Get the action from request
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'users':
        // Display all users
        $result = $conn->query("SELECT id, first_name, last_name, email, phone, street, barangay, municipality, province, created_at FROM users ORDER BY created_at DESC");
        
        echo "<h2>Registered Users</h2>";
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
        echo "<tr style='background-color: #333; color: white;'>";
        echo "<th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Registered</th>";
        echo "</tr>";
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                $address = htmlspecialchars($row['street'] . ', ' . $row['barangay'] . ', ' . $row['municipality'] . ', ' . $row['province']);
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $fullName . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone'] ?? 'N/A') . "</td>";
                echo "<td>" . $address . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align: center;'>No users found</td></tr>";
        }
        echo "</table>";
        break;
        
    case 'bills':
        // Display all bills
        $result = $conn->query("
            SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
            FROM bills b 
            LEFT JOIN users u ON b.user_id = u.id 
            ORDER BY b.created_at DESC
        ");
        
        echo "<h2>Bills History</h2>";
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
        echo "<tr style='background-color: #333; color: white;'>";
        echo "<th>ID</th><th>User</th><th>Billing Month</th><th>Consumption (kWh)</th><th>Total Cost (₱)</th><th>Due Date</th><th>Status</th>";
        echo "</tr>";
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusColor = $row['status'] == 'Paid' ? '#4CAF50' : ($row['status'] == 'Overdue' ? '#F44336' : '#FF9800');
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name'] ?? 'Unknown') . "</td>";
                echo "<td>" . htmlspecialchars($row['billing_month']) . "</td>";
                echo "<td>" . number_format($row['consumption_kwh'], 2) . "</td>";
                echo "<td>₱" . number_format($row['total_cost'], 2) . "</td>";
                echo "<td>" . $row['due_date'] . "</td>";
                echo "<td style='background-color: {$statusColor}; color: white; text-align: center;'>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>No bills found</td></tr>";
        }
        echo "</table>";
        break;
        
    case 'meter':
        // Display meter readings
        $result = $conn->query("
            SELECT m.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
            FROM meter_readings m 
            LEFT JOIN users u ON m.user_id = u.id 
            ORDER BY m.reading_date DESC
        ");
        
        echo "<h2>Meter Readings</h2>";
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
        echo "<tr style='background-color: #333; color: white;'>";
        echo "<th>ID</th><th>User</th><th>Previous Reading</th><th>Current Reading</th><th>Consumption</th><th>Rate/kWh</th><th>Date</th>";
        echo "</tr>";
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $consumption = $row['current_reading'] - $row['previous_reading'];
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name'] ?? 'Unknown') . "</td>";
                echo "<td>" . number_format($row['previous_reading'], 2) . " kWh</td>";
                echo "<td>" . number_format($row['current_reading'], 2) . " kWh</td>";
                echo "<td>" . number_format($consumption, 2) . " kWh</td>";
                echo "<td>₱" . number_format($row['rate_per_kwh'], 4) . "</td>";
                echo "<td>" . $row['reading_date'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>No meter readings found</td></tr>";
        }
        echo "</table>";
        break;
        
    default:
        // Home page - Dashboard summary
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Electric Bill Tracker - Admin Panel</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: 'Inter', Arial, sans-serif; background: #CACACA; min-height: 100vh; padding: 30px; }
                .container { max-width: 1200px; margin: 0 auto; }
                h1 { color: #333; margin-bottom: 30px; font-size: 36px; }
                .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
                .stat-card { background: #F3F3F3; padding: 25px; border-radius: 15px; box-shadow: inset 1px -2px 2px rgba(0,0,0,0.08); }
                .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
                .stat-card .value { font-size: 36px; font-weight: 700; color: #333; }
                .nav-links { display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 30px; }
                .nav-links a { display: inline-block; padding: 15px 30px; background: #1D1D1D; color: white; text-decoration: none; border-radius: 10px; font-weight: 600; transition: transform 0.2s; }
                .nav-links a:hover { transform: translateY(-3px); }
                .nav-links a.secondary { background: #6C6C6C; }
                .message { padding: 15px; background: #4CAF50; color: white; border-radius: 8px; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>⚡ Electric Bill Tracker - Admin Panel</h1>
                
                <div class="message">✓ Database Connected Successfully!</div>
                
                <?php
                // Get statistics
                $usersCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'] ?? 0;
                $billsCount = $conn->query("SELECT COUNT(*) as count FROM bills")->fetch_assoc()['count'] ?? 0;
                $totalConsumption = $conn->query("SELECT SUM(consumption_kwh) as total FROM bills")->fetch_assoc()['total'] ?? 0;
                $totalRevenue = $conn->query("SELECT SUM(total_cost) as total FROM bills")->fetch_assoc()['total'] ?? 0;
                ?>
                
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <div class="value"><?php echo $usersCount; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Bills</h3>
                        <div class="value"><?php echo $billsCount; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Consumption</h3>
                        <div class="value"><?php echo number_format($totalConsumption, 0); ?> kWh</div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Revenue</h3>
                        <div class="value">₱<?php echo number_format($totalRevenue, 2); ?></div>
                    </div>
                </div>
                
                <h2 style="margin-bottom: 15px; color: #333;">View Data Tables</h2>
                <div class="nav-links">
                    <a href="?action=users">👤 View Users</a>
                    <a href="?action=bills">📄 View Bills</a>
                    <a href="?action=meter">⚡ View Meter Readings</a>
                </div>
                
                <h2 style="margin-bottom: 15px; color: #333;">Go to Main Application</h2>
                <div class="nav-links">
                    <a href="../index.php" class="secondary">🏠 Open CRUD System</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
}

$conn->close();
?>
