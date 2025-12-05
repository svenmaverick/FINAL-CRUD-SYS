-- Electric Bill Tracker Database Schema
-- Run this SQL file to set up the database

CREATE DATABASE IF NOT EXISTS electric_db;
USE electric_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
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
);

-- Bills Table
CREATE TABLE IF NOT EXISTS bills (
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
);

-- Add customer_name column if it doesn't exist (for existing databases)
-- ALTER TABLE bills ADD COLUMN customer_name VARCHAR(200) NOT NULL DEFAULT '' AFTER user_id;

-- Meter Readings Table
CREATE TABLE IF NOT EXISTS meter_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    previous_reading DECIMAL(10,2) DEFAULT 0,
    current_reading DECIMAL(10,2) DEFAULT 0,
    rate_per_kwh DECIMAL(10,4) DEFAULT 0,
    reading_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_bills_user_id ON bills(user_id);
CREATE INDEX idx_bills_billing_month ON bills(billing_month);
CREATE INDEX idx_meter_readings_user_id ON meter_readings(user_id);

