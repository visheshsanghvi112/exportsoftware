<?php
require_once 'auth_check.php'; // Start the session
require_once 'database.php';
require_once 'fpdf/fpdf.php'; // Update the path to FPDF according to your setup

// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission and generate PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Validate dates
    if (!empty($startDate) && !empty($endDate)) {
        // Fetch data from the database based on the selected date range
        $sql = "SELECT id, supplier_id, buyer_id, consignee_id, invoice_no, invoice_date, manufacturing_date, 
                       expiry_date, batch_no, carriage, place_of_receipt, port_of_loading, port_of_discharge, 
                       final_destination, terms_of_delivery, product_id, total_amount, igst_amount, 
                       container_length, container_width, container_height, container_weight_net, 
                       container_weight_gross, container_other, bank_name 
                FROM export_data 
                WHERE invoice_date BETWEEN ? AND ? 
                ORDER BY invoice_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Create PDF
        $pdf = new FPDF('L', 'mm', 'A4'); // Set to landscape orientation
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, 'Export Bills Report', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'From: ' . htmlspecialchars($startDate) . ' To: ' . htmlspecialchars($endDate), 0, 1, 'C');
        $pdf->Ln(10);

        // Header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(200, 220, 255);
        $headers = [
            'ID', 'Supplier ID', 'Buyer ID', 'Consignee ID', 'Invoice No', 'Inv Date', 'Mfg Date', 'Exp Date',
            'Batch No', 'Carriage', 'Place of Receipt', 'Port of Loading', 'Port of Discharge',
            'Final Destination', 'Terms of Delivery', 'Product ID', 'Total Amount', 'IGST Amount',
            'Container Length', 'Container Width', 'Container Height', 'Container Weight Net', 
            'Container Weight Gross', 'Container Other', 'Bank Name'
        ];
        $widths = [10, 30, 30, 30, 30, 30, 25, 25, 25, 25, 30, 30, 30, 30, 30, 25, 30, 30, 30, 30, 30, 30, 30, 30, 30];
        
        foreach ($headers as $key => $header) {
            $pdf->Cell($widths[$key], 10, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Data
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell($widths[0], 10, $row['id'], 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $row['supplier_id'], 1, 0, 'C', true);
            $pdf->Cell($widths[2], 10, $row['buyer_id'], 1, 0, 'C', true);
            $pdf->Cell($widths[3], 10, $row['consignee_id'], 1, 0, 'C', true);
            $pdf->Cell($widths[4], 10, $row['invoice_no'], 1, 0, 'C', true);
            $pdf->Cell($widths[5], 10, $row['invoice_date'], 1, 0, 'C', true);
            $pdf->Cell($widths[6], 10, $row['manufacturing_date'], 1, 0, 'C', true);
            $pdf->Cell($widths[7], 10, $row['expiry_date'], 1, 0, 'C', true);
            $pdf->Cell($widths[8], 10, $row['batch_no'], 1, 0, 'C', true);
            $pdf->Cell($widths[9], 10, $row['carriage'], 1, 0, 'C', true);
            $pdf->Cell($widths[10], 10, $row['place_of_receipt'], 1, 0, 'L', true);
            $pdf->Cell($widths[11], 10, $row['port_of_loading'], 1, 0, 'L', true);
            $pdf->Cell($widths[12], 10, $row['port_of_discharge'], 1, 0, 'L', true);
            $pdf->Cell($widths[13], 10, $row['final_destination'], 1, 0, 'L', true);
            $pdf->Cell($widths[14], 10, $row['terms_of_delivery'], 1, 0, 'L', true);
            $pdf->Cell($widths[15], 10, $row['product_id'], 1, 0, 'C', true);
            $pdf->Cell($widths[16], 10, $row['total_amount'], 1, 0, 'R', true);
            $pdf->Cell($widths[17], 10, $row['igst_amount'], 1, 0, 'R', true);
            $pdf->Cell($widths[18], 10, $row['container_length'], 1, 0, 'R', true);
            $pdf->Cell($widths[19], 10, $row['container_width'], 1, 0, 'R', true);
            $pdf->Cell($widths[20], 10, $row['container_height'], 1, 0, 'R', true);
            $pdf->Cell($widths[21], 10, $row['container_weight_net'], 1, 0, 'R', true);
            $pdf->Cell($widths[22], 10, $row['container_weight_gross'], 1, 0, 'R', true);
            $pdf->Cell($widths[23], 10, $row['container_other'], 1, 0, 'L', true);
            $pdf->Cell($widths[24], 10, $row['bank_name'], 1, 0, 'L', true);
            $pdf->Ln();
        }

        // Output PDF
        $pdf->Output('D', 'Export_Bills_Detail_Report.pdf');
        exit();
    } else {
        $error = 'Please select both start and end dates.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* General Styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 10px 0 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ffeb3b;
        }

        .container {
            flex: 1;
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        input[type="text"], input[type="submit"] {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #444;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="display_exportbills.php">View Bills</a></li>
                <li><a href="create_buyer.php">Create Buyer</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Select Date Range</h2>
        <form action="" method="POST">
            <label for="start_date">Start Date:</label>
            <input type="text" id="start_date" name="start_date" class="datepicker" required>

            <label for="end_date">End Date:</label>
            <input type="text" id="end_date" name="end_date" class="datepicker" required>

            <input type="submit" value="Generate PDF">
            <?php if (!empty($error)) : ?>
                <div class="error"><?= $error; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
        });
    </script>
</body>
</html>
