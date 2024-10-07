<?php
// config.php

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'exportsoftware');

// App settings
define('APP_NAME', 'Export Billing Software');
define('APP_URL', 'http://localhost/exportsoftware'); // Update with your actual URL

// Path to logs
define('LOG_PATH', __DIR__ . '/logs'); // Ensure the 'logs' directory exists

// Initialize the database connection
function getDatabaseConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error, 3, LOG_PATH . '/error.log');
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Close the database connection
function closeDatabaseConnection($conn) {
    $conn->close();
}
?>
