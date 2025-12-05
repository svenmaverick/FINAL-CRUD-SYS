<?php
/**
 * Database Configuration for Electric Bill Tracker
 * Using PDO for secure database connections
 */

// Database Configuration - For Laragon/XAMPP local development
$host = 'localhost';
$dbname = 'electric_db';
$username = 'root';
$password = '';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Database doesn't exist - try to create it
    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        
        // Create users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            street VARCHAR(255),
            barangay VARCHAR(100),
            municipality VARCHAR(100),
            province VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Create bills table with customer_name column
        $pdo->exec("CREATE TABLE IF NOT EXISTS bills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            customer_name VARCHAR(200) NOT NULL,
            billing_month VARCHAR(20) NOT NULL,
            consumption_kwh DECIMAL(10,2) NOT NULL,
            total_cost DECIMAL(10,2) NOT NULL,
            due_date DATE NOT NULL,
            status ENUM('Paid', 'Unpaid', 'Overdue') DEFAULT 'Unpaid',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Create meter_readings table
        $pdo->exec("CREATE TABLE IF NOT EXISTS meter_readings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            previous_reading DECIMAL(10,2) DEFAULT 0,
            current_reading DECIMAL(10,2) DEFAULT 0,
            rate_per_kwh DECIMAL(10,4) DEFAULT 0,
            reading_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Create indexes for better performance
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_bills_user_id ON bills(user_id)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_meter_user_id ON meter_readings(user_id)");
        
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
    } catch(PDOException $e2) {
        // Return JSON error for API calls
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e2->getMessage()]);
            exit;
        }
        die("Database Error: " . $e2->getMessage());
    }
}
?>
