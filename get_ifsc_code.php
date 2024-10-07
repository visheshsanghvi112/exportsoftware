<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch IFSC code based on bank name
if (isset($_GET['bank_name'])) {
    $bank_name = $_GET['bank_name'];
    $stmt = $conn->prepare("SELECT ifsc_code FROM banks WHERE name = ?");
    $stmt->bind_param("s", $bank_name);
    $stmt->execute();
    $stmt->bind_result($ifsc_code);
    
    if ($stmt->fetch()) {
        echo $ifsc_code;
    } else {
        echo 'Not found';
    }
    
    $stmt->close();
}
$conn->close();
?>
