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

// Query to retrieve data from export_data table
$sql = "SELECT id, supplier_id, buyer_id, invoice_no, invoice_date, total_amount FROM export_data LIMIT 10"; // Modify as per your requirement
$result = $conn->query($sql);

// Initialize table rows with data
$tableRows = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tableRows .= "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['supplier_id']}</td>
                <td>{$row['buyer_id']}</td>
                <td>{$row['invoice_no']}</td>
                <td>{$row['invoice_date']}</td>
                <td>{$row['total_amount']}</td>
            </tr>";
    }
} else {
    $tableRows = "<tr><td colspan='6'>No records found</td></tr>";
}

// Close the connection
closeConnection($conn);

// Include Composer's autoloader for Dompdf
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize dompdf class
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// HTML content with dynamic data
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export Data PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            color: #4a4a4a;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Export Data</h1>
        <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
    </div>
    <h3>Export Data Table</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Supplier ID</th>
            <th>Buyer ID</th>
            <th>Invoice No</th>
            <th>Invoice Date</th>
            <th>Total Amount</th>
        </tr>
        ' . $tableRows . '
    </table>
</body>
</html>
';

// Load HTML content into DOMPDF
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to browser
$dompdf->stream("export_data.pdf", array("Attachment" => false));

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

// Function to close the connection (optional)
function closeConnection($conn) {
    if ($conn->close()) {
        logInfo("Database connection closed successfully.");
    } else {
        logError("Failed to close the database connection.");
    }
}
?>
