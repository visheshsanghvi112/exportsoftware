<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and log errors
if ($conn->connect_error) {
    logError("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
} else {
    logInfo("Connected successfully to the database.");
}

// Function to log error messages
function logError($message) {
    $logFile = __DIR__ . '/logs/error.log';
    $currentDate = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$currentDate] ERROR: $message" . PHP_EOL, FILE_APPEND);
}

// Function to log informational messages
function logInfo($message) {
    $logFile = __DIR__ . '/logs/info.log';
    $currentDate = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$currentDate] INFO: $message" . PHP_EOL, FILE_APPEND);
}

// Function to close the connection (optional, but good practice)
function closeConnection($conn) {
    if ($conn->close()) {
        logInfo("Database connection closed successfully.");
    } else {
        logError("Failed to close the database connection.");
    }
}
?>
