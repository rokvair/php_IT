<?php
// config.php

// Database connection details
$host = 'localhost';  // Your database host
$dbname = 'laivai';   // Your database name
$username = 'root';    // Your database username
$password = '';        // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage()); // Exit if connection fails
}
?>



