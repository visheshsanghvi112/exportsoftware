<?php
// Include Composer autoloader
require 'vendor/autoload.php';

// Use the Dompdf namespace
use Dompdf\Dompdf;

// Database connection parameters
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invoice_no'])) {
    // Get the invoice ID from the form
    $invoiceId = intval($_POST['invoice_no']); // Convert to integer for security

    // Fetch a specific bill data (modify the WHERE clause as necessary)
    $sql = "SELECT * FROM export_data WHERE invoice_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoiceId); // Bind the invoice ID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare HTML content for PDF
    $html = '<h1>Export Invoice</h1>';
    $html .= '<h2>Invoice No: ' . htmlspecialchars($invoiceId) . '</h2>';
    $html .= '<h3>Invoice Details</h3>';
    $html .= '<p><strong>Invoice Date:</strong> ' . date("Y-m-d") . '</p>'; // Current date, or use the actual invoice date
    $html .= '<hr>';

    // Check if there is data
    if ($result->num_rows > 0) {
        // Output data of the fetched row
        $row = $result->fetch_assoc();

        // Build invoice details
        $html .= '<h3>Export Details</h3>';
        $html .= '<p><strong>Supplier ID:</strong> ' . htmlspecialchars($row["supplier_id"]) . '</p>';
        $html .= '<p><strong>Buyer ID:</strong> ' . htmlspecialchars($row["buyer_id"]) . '</p>';
        $html .= '<p><strong>Consignee ID:</strong> ' . htmlspecialchars($row["consignee_id"]) . '</p>';
        $html .= '<p><strong>Invoice No:</strong> ' . htmlspecialchars($row["invoice_no"]) . '</p>';
        $html .= '<p><strong>Invoice Date:</strong> ' . htmlspecialchars($row["invoice_date"]) . '</p>';
        $html .= '<p><strong>Manufacturing Date:</strong> ' . htmlspecialchars($row["manufacturing_date"]) . '</p>';
        $html .= '<p><strong>Expiry Date:</strong> ' . htmlspecialchars($row["expiry_date"]) . '</p>';
        $html .= '<p><strong>Batch No:</strong> ' . htmlspecialchars($row["batch_no"]) . '</p>';
        $html .= '<p><strong>Carriage:</strong> ' . htmlspecialchars($row["carriage"]) . '</p>';
        $html .= '<p><strong>Place of Receipt:</strong> ' . htmlspecialchars($row["place_of_receipt"]) . '</p>';
        $html .= '<p><strong>Port of Loading:</strong> ' . htmlspecialchars($row["port_of_loading"]) . '</p>';
        $html .= '<p><strong>Port of Discharge:</strong> ' . htmlspecialchars($row["port_of_discharge"]) . '</p>';
        $html .= '<p><strong>Final Destination:</strong> ' . htmlspecialchars($row["final_destination"]) . '</p>';
        $html .= '<p><strong>Terms of Delivery:</strong> ' . htmlspecialchars($row["terms_of_delivery"]) . '</p>';
        $html .= '<p><strong>Product ID:</strong> ' . htmlspecialchars($row["product_id"]) . '</p>';
        $html .= '<p><strong>Total Amount:</strong> ' . htmlspecialchars($row["total_amount"]) . '</p>';
        $html .= '<p><strong>IGST Amount:</strong> ' . htmlspecialchars($row["igst_amount"]) . '</p>';
        $html .= '<p><strong>Container Length:</strong> ' . htmlspecialchars($row["container_length"]) . '</p>';
        $html .= '<p><strong>Container Width:</strong> ' . htmlspecialchars($row["container_width"]) . '</p>';
        $html .= '<p><strong>Container Height:</strong> ' . htmlspecialchars($row["container_height"]) . '</p>';
        $html .= '<p><strong>Net Weight:</strong> ' . htmlspecialchars($row["container_weight_net"]) . '</p>';
        $html .= '<p><strong>Gross Weight:</strong> ' . htmlspecialchars($row["container_weight_gross"]) . '</p>';
        $html .= '<p><strong>Other Container Details:</strong> ' . htmlspecialchars($row["container_other"]) . '</p>';
        $html .= '<p><strong>Bank Name:</strong> ' . htmlspecialchars($row["bank_name"]) . '</p>';
        $html .= '<p><strong>IFSC Code:</strong> ' . htmlspecialchars($row["ifsc_code"]) . '</p>';
        $html .= '<p><strong>GSTIN:</strong> ' . htmlspecialchars($row["gstin"]) . '</p>';
        $html .= '<p><strong>VAT/TIN:</strong> ' . htmlspecialchars($row["vat_tin"]) . '</p>';
        $html .= '<p><strong>IEC Code:</strong> ' . htmlspecialchars($row["iec_code"]) . '</p>';
        $html .= '<p><strong>Bank Account No:</strong> ' . htmlspecialchars($row["bank_account_no"]) . '</p>';
        $html .= '<p><strong>End User Code:</strong> ' . htmlspecialchars($row["end_user_code"]) . '</p>';
        $html .= '<p><strong>Destination:</strong> ' . htmlspecialchars($row["destination"]) . '</p>';
        $html .= '<p><strong>Drug License No:</strong> ' . htmlspecialchars($row["drug_license_no"]) . '</p>';
        $html .= '<p><strong>HSN Code:</strong> ' . htmlspecialchars($row["hsn_code"]) . '</p>';
        $html .= '<p><strong>Marks and Nos:</strong> ' . htmlspecialchars($row["marks_and_nos"]) . '</p>';
        $html .= '<p><strong>Quantity:</strong> ' . htmlspecialchars($row["quantity"]) . '</p>';
        $html .= '<p><strong>Price Per Unit:</strong> ' . number_format((float)$row["price_per_unit"], 2) . '</p>';
        $html .= '<p><strong>Total Unit Price:</strong> ' . number_format((float)$row["total_unit_price"], 2) . '</p>';
    } else {
        $html .= '<p>No invoice found.</p>';
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Create an instance of the Dompdf class
    $dompdf = new Dompdf();

    // Load the HTML content into Dompdf
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream("export_invoice_$invoiceId.pdf", array("Attachment" => false));
} else {
    // If the form is not submitted, redirect back to the form or show an error message
    echo "Please submit the invoice ID.";
}
?>
