<?php
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

// Update the export bill
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
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
    $product_name = $_POST['product_name'];
    $total_amount = $_POST['total_amount'];
    $igst_amount = $_POST['igst_amount'];
    $container_length = $_POST['container_length'];
    $container_width = $_POST['container_width'];
    $container_height = $_POST['container_height'];
    $container_other = $_POST['container_other'];
    $bank_name = $_POST['bank_name'];

    $sql = "UPDATE exportbill SET
                name = ?, manufacturing_date = ?, expiry_date = ?, batch_no = ?,
                supplier_id = ?, buyer_id = ?, consignee_id = ?, invoice_no = ?,
                invoice_date = ?, carriage = ?, place_of_receipt = ?, port_of_loading = ?,
                port_of_discharge = ?, final_destination = ?, terms_of_delivery = ?,
                product_id = ?, product_name = ?, total_amount = ?, igst_amount = ?,
                container_length = ?, container_width = ?, container_height = ?,
                container_other = ?, bank_name = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiiissssssssssdddiiiss", $name, $manufacturing_date, $expiry_date, $batch_no, $supplier_id, $buyer_id, $consignee_id, $invoice_no, $invoice_date, $carriage, $place_of_receipt, $port_of_loading, $port_of_discharge, $final_destination, $terms_of_delivery, $product_id, $product_name, $total_amount, $igst_amount, $container_length, $container_width, $container_height, $container_other, $bank_name, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
