<?php
/**
 * Database Connection for Electric Bill Tracker
 */

$host = "localhost";
$user = "root";
$pass = "";
$db = "electric_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Uncomment line below for debugging
// echo "Connected Successfully to Electric Bill Tracker Database!";
?>
