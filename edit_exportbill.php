<?php
require_once 'auth_check.php'; // Start the session
// Start output buffering
ob_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $manufacturing_date = $_POST['manufacturing_date'];
        $expiry_date = $_POST['expiry_date'];
        $batch_no = $_POST['batch_no'];
        $supplier_id = $_POST['supplier_id'];
        $buyer_id = $_POST['buyer_id'];
        $consignee_id = $_POST['consignee_id'];
        $invoice_no = $_POST['invoice_no'];
        $invoice_date = $_POST['invoice_date'];
        $carriage = $_POST['carriage'];
        $place_of_receipt = $_POST['place_of_receipt'];
        $port_of_loading = $_POST['port_of_loading'];
        $port_of_discharge = $_POST['port_of_discharge'];
        $final_destination = $_POST['final_destination'];
        $terms_of_delivery = $_POST['terms_of_delivery'];
        $product_id = $_POST['product_id'];
        $total_amount = $_POST['total_amount'];
        $igst_amount = $_POST['igst_amount'];
        $container_length = $_POST['container_length'];
        $container_width = $_POST['container_width'];
        $container_height = $_POST['container_height'];
        $container_other = $_POST['container_other'];
        $bank_name = $_POST['bank_name'];

        $sql = "UPDATE export_data SET manufacturing_date=?, expiry_date=?, batch_no=?, supplier_id=?, buyer_id=?, consignee_id=?, invoice_no=?, invoice_date=?, carriage=?, place_of_receipt=?, port_of_loading=?, port_of_discharge=?, final_destination=?, terms_of_delivery=?, product_id=?, total_amount=?, igst_amount=?, container_length=?, container_width=?, container_height=?, container_other=?, bank_name=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssssssssi", $manufacturing_date, $expiry_date, $batch_no, $supplier_id, $buyer_id, $consignee_id, $invoice_no, $invoice_date, $carriage, $place_of_receipt, $port_of_loading, $port_of_discharge, $final_destination, $terms_of_delivery, $product_id, $total_amount, $igst_amount, $container_length, $container_width, $container_height, $container_other, $bank_name, $id);

        if ($stmt->execute()) {
            header("Location: display_exportbills.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error updating record: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>No ID provided</div>";
    }
}

// Fetch record for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM export_data WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Record not found</div>";
        exit();
    }
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>No ID provided</div>";
    exit();
}

$conn->close();

// End output buffering and flush
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Export Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 40px;
            max-width: 1200px;
        }
        .card {
            border-radius: 15px;
            border: 1px solid #d6d9db;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: 1px solid #0056b3;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .row {
            margin-bottom: 15px;
        }
        .alert {
            border-radius: 10px;
            font-size: 1rem;
        }
        header {
            background: linear-gradient(to right, #EA8D8D, #A890FE);
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2);
            z-index: -1;
            filter: blur(10px);
        }
        h1 {
            margin-bottom: 10px;
            font-size: 2.5em;
            font-weight: bold;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            background-color: #333;
            border-radius: 5px;
        }
        nav ul li {
            margin: 0;
        }
        nav ul li a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s, transform 0.2s;
        }
        nav ul li a:hover {
            background-color: #575757;
            transform: scale(1.05);
        }
        footer {
            background-color: #f1f1f1;
            padding: 15px 0;
            text-align: center;
            color: #555;
            font-size: 0.9em;
            border-top: 1px solid #ddd;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        footer p {
            margin: 0;
        }
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
            }
            nav ul li a {
                padding: 10px 15px;
            }
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Export Bill</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="input.php">Create Export Bill</a></li>
                <li><a href="display_exportbills.php">View Bills</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Edit Export Bill
            </div>
            <div class="card-body">
                <form method="POST" action="edit_exportbill.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                    <div class="row">
                        <?php
                        $fields = [
                            'supplier_id' => 'Supplier ID',
                            'buyer_id' => 'Buyer ID',
                            'consignee_id' => 'Consignee ID',
                            'invoice_no' => 'Invoice No',
                            'invoice_date' => 'Invoice Date',
                            'manufacturing_date' => 'Manufacturing Date',
                            'expiry_date' => 'Expiry Date',
                            'batch_no' => 'Batch No',
                            'carriage' => 'Carriage',
                            'place_of_receipt' => 'Place of Receipt',
                            'port_of_loading' => 'Port of Loading',
                            'port_of_discharge' => 'Port of Discharge',
                            'final_destination' => 'Final Destination',
                            'terms_of_delivery' => 'Terms of Delivery',
                            'product_id' => 'Product ID',
                            'total_amount' => 'Total Amount',
                            'igst_amount' => 'IGST Amount',
                            'container_length' => 'Container Length',
                            'container_width' => 'Container Width',
                            'container_height' => 'Container Height',
                            'container_other' => 'Container Other',
                            'bank_name' => 'Bank Name'
                        ];

                        foreach ($fields as $field => $label) {
                            $type = (strpos($field, 'date') !== false) ? 'date' : 'text';
                            echo "<div class='col-md-6 mb-3'>
                                <label for='$field' class='form-label'>$label</label>
                                <input type='$type' class='form-control' id='$field' name='$field' value='" . htmlspecialchars($row[$field]) . "'>
                            </div>";
                        }
                        ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Export Software. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
