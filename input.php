<?php

require_once 'auth_check.php'; // Start the session
// PHP script to handle form submission and populate dropdowns
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $manufacturing_date = isset($_POST['manufacturing_date']) ? $_POST['manufacturing_date'] : '';
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
    $batch_no = isset($_POST['batch_no']) ? $_POST['batch_no'] : '';
    $supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : '';
    $buyer_id = isset($_POST['buyer_id']) ? $_POST['buyer_id'] : '';
    $consignee_id = isset($_POST['consignee_id']) ? $_POST['consignee_id'] : '';
    $invoice_no = isset($_POST['invoice_no']) ? $_POST['invoice_no'] : '';
    $invoice_date = isset($_POST['invoice_date']) ? $_POST['invoice_date'] : '';
    $carriage = isset($_POST['carriage']) ? $_POST['carriage'] : '';
    $place_of_receipt = isset($_POST['place_of_receipt']) ? $_POST['place_of_receipt'] : '';
    $port_of_loading = isset($_POST['port_of_loading']) ? $_POST['port_of_loading'] : '';
    $port_of_discharge = isset($_POST['port_of_discharge']) ? $_POST['port_of_discharge'] : '';
    $final_destination = isset($_POST['final_destination']) ? $_POST['final_destination'] : '';
    $terms_of_delivery = isset($_POST['terms_of_delivery']) ? $_POST['terms_of_delivery'] : '';
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $hsn_code = isset($_POST['hsn_code']) ? $_POST['hsn_code'] : '';
    $marks_and_nos = isset($_POST['marks_and_nos']) ? $_POST['marks_and_nos'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $price_per_unit = isset($_POST['price_per_unit']) ? $_POST['price_per_unit'] : '';
    $total_unit_price = isset($_POST['total_unit_price']) ? $_POST['total_unit_price'] : '';
    $total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : '';
    $igst_amount = isset($_POST['igst_amount']) ? $_POST['igst_amount'] : '';
    $container_length = isset($_POST['container_length']) ? $_POST['container_length'] : '';
    $container_width = isset($_POST['container_width']) ? $_POST['container_width'] : '';
    $container_height = isset($_POST['container_height']) ? $_POST['container_height'] : '';
    $container_weight_net = isset($_POST['container_weight_net']) ? $_POST['container_weight_net'] : '';
    $container_weight_gross = isset($_POST['container_weight_gross']) ? $_POST['container_weight_gross'] : '';
    $container_other = isset($_POST['container_other']) ? $_POST['container_other'] : '';
    $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
    $ifsc_code = isset($_POST['ifsc_code']) ? $_POST['ifsc_code'] : '';
    $gstin = isset($_POST['gstin']) ? $_POST['gstin'] : '';
    $vat_tin = isset($_POST['vat_tin']) ? $_POST['vat_tin'] : '';
    $iec_code = isset($_POST['iec_code']) ? $_POST['iec_code'] : '';
    $bank_account_no = isset($_POST['bank_account_no']) ? $_POST['bank_account_no'] : '';
    $end_user_code = isset($_POST['end_user_code']) ? $_POST['end_user_code'] : '';
    $destination = isset($_POST['destination']) ? $_POST['destination'] : '';
    $drug_license_no = isset($_POST['drug_license_no']) ? $_POST['drug_license_no'] : '';


    // Database connection
    try {
        $dsn = 'mysql:host=localhost;dbname=exportsoftware';
        $username = 'root';
        $password = '';
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Prepare and execute the insert query
        $stmt = $pdo->prepare("
    INSERT INTO export_data (
        supplier_id, buyer_id, consignee_id, invoice_no, invoice_date, 
        manufacturing_date, expiry_date, batch_no, carriage, place_of_receipt, 
        port_of_loading, port_of_discharge, final_destination, terms_of_delivery, 
        product_id, total_amount, igst_amount, container_length, container_width, 
        container_height, container_weight_net, container_weight_gross, container_other, 
        bank_name, ifsc_code, gstin, vat_tin, iec_code, bank_account_no, 
        end_user_code, destination, drug_license_no, hsn_code, marks_and_nos, 
        quantity, price_per_unit, total_unit_price
    ) VALUES (
        :supplier_id, :buyer_id, :consignee_id, :invoice_no, :invoice_date, 
        :manufacturing_date, :expiry_date, :batch_no, :carriage, :place_of_receipt, 
        :port_of_loading, :port_of_discharge, :final_destination, :terms_of_delivery, 
        :product_id, :total_amount, :igst_amount, :container_length, :container_width, 
        :container_height, :container_weight_net, :container_weight_gross, :container_other, 
        :bank_name, :ifsc_code, :gstin, :vat_tin, :iec_code, :bank_account_no, 
        :end_user_code, :destination, :drug_license_no, :hsn_code, :marks_and_nos, 
        :quantity, :price_per_unit, :total_unit_price
    )
");
$stmt->execute([
    ':supplier_id' => $supplier_id,
    ':buyer_id' => $buyer_id,
    ':consignee_id' => $consignee_id,
    ':invoice_no' => $invoice_no,
    ':invoice_date' => $invoice_date,
    ':manufacturing_date' => $manufacturing_date,
    ':expiry_date' => $expiry_date,
    ':batch_no' => $batch_no,
    ':carriage' => $carriage,
    ':place_of_receipt' => $place_of_receipt,
    ':port_of_loading' => $port_of_loading,
    ':port_of_discharge' => $port_of_discharge,
    ':final_destination' => $final_destination,
    ':terms_of_delivery' => $terms_of_delivery,
    ':product_id' => $product_id,
    ':total_amount' => $total_amount,
    ':igst_amount' => $igst_amount,
    ':container_length' => $container_length,
    ':container_width' => $container_width,
    ':container_height' => $container_height,
    ':container_weight_net' => $container_weight_net,
    ':container_weight_gross' => $container_weight_gross,
    ':container_other' => $container_other,
    ':bank_name' => $bank_name,
    ':ifsc_code' => $ifsc_code,
    ':gstin' => $gstin,
    ':vat_tin' => $vat_tin,
    ':iec_code' => $iec_code,
    ':bank_account_no' => $bank_account_no,
    ':end_user_code' => $end_user_code,
    ':destination' => $destination,
    ':drug_license_no' => $drug_license_no,
    ':hsn_code' => $hsn_code,
    ':marks_and_nos' => $marks_and_nos,
    ':quantity' => $quantity,
    ':price_per_unit' => $price_per_unit,
    ':total_unit_price' => $total_unit_price,
]);

        $success_message = "Data submitted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch product names, bank names, and buyer details from the database
try {
    $dsn = 'mysql:host=localhost;dbname=exportsoftware';
    $username = 'root';
    $password = '';
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $products_stmt = $pdo->query("SELECT id, brand_name FROM products");
    $products = $products_stmt->fetchAll();

    $suppliers_stmt = $pdo->query("SELECT id, name FROM suppliers");
     $suppliers = $suppliers_stmt->fetchAll();


    $banks_stmt = $pdo->query("SELECT id, bank_name FROM banks");
    $banks = $banks_stmt->fetchAll();

    $buyers_stmt = $pdo->query("SELECT id, name, bank_id, gst_number, iec_number, drug_licence_number, vat_tin_number FROM buyers");
    $buyers = $buyers_stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Form</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --background-color: #ecf0f1;
            --form-background: #ffffff;
            --text-color: #333333;
            --input-border: #bdc3c7;
            --input-focus: #3498db;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        form {
            background-color: var(--form-background);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            transition: box-shadow 0.3s ease-in-out;
        }

        form:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--input-focus);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 12 12'%3E%3Cpath d='M10.293 3.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 011.414-1.414L6 7.586l4.293-4.293z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .btn {
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            padding: 14px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            width: 100%;
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .success-message,
        .error-message {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            font-weight: 600;
            padding: 12px;
            border-radius: 6px;
        }

        .success-message {
            color: var(--success-color);
            background-color: rgba(46, 204, 113, 0.1);
        }

        .error-message {
            color: var(--error-color);
            background-color: rgba(231, 76, 60, 0.1);
        }

        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .btn {
                padding: 12px 20px;
            }
        }
    </style>

</head>
<body>
    <h1>Welcome to Export Bill</h1>
 
    <form id="export-form" method="POST" action="">
        <!-- Existing fields -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name"  >
        </div>
        <div class="form-group">
            <label for="manufacturing_date">Manufacturing Date:</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date"  >
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date"  >
        </div>
        <div class="form-group">
            <label for="batch_no">Batch Number:</label>
            <input type="text" id="batch_no" name="batch_no"  >
        </div>
        <div class="form-group">
            <label for="supplier_id">Supplier:</label>
            <select id="supplier_id" name="supplier_id">
    <?php foreach ($suppliers as $supplier): ?>
        <option value="<?php echo htmlspecialchars($supplier['id']); ?>">
            <?php echo htmlspecialchars($supplier['name']); ?>
        </option>
    <?php endforeach; ?>
</select>

        </div>
        <div class="form-group">
            <label for="buyer_id">Buyer:</label>
            <select id="buyer_id" name="buyer_id"  >
                <?php foreach ($buyers as $buyer): ?>
                    <option value="<?php echo htmlspecialchars($buyer['id']); ?>">
                        <?php echo htmlspecialchars($buyer['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="consignee_id">Consignee:</label>
            <select id="consignee_id" name="consignee_id"  >
                <!-- Populate options dynamically -->
            </select>
        </div>
        <div class="form-group">
        <label for="invoice_no">Invoice Number:</label>
        <input type="text" id="invoice_no" name="invoice_no" required>
    </div>
        <div class="form-group">
            <label for="invoice_date">Invoice Date:</label>
            <input type="date" id="invoice_date" name="invoice_date"  >
        </div>
        <div class="form-group">
            <label for="carriage">Carriage:</label>
            <input type="text" id="carriage" name="carriage"  >
        </div>
        <div class="form-group">
            <label for="place_of_receipt">Place of Receipt:</label>
            <input type="text" id="place_of_receipt" name="place_of_receipt"  >
        </div>
        <div class="form-group">
            <label for="port_of_loading">Port of Loading:</label>
            <input type="text" id="port_of_loading" name="port_of_loading"  >
        </div>
        <div class="form-group">
            <label for="port_of_discharge">Port of Discharge:</label>
            <input type="text" id="port_of_discharge" name="port_of_discharge"  >
        </div>
        <div class="form-group">
            <label for="final_destination">Final Destination:</label>
            <input type="text" id="final_destination" name="final_destination"  >
        </div>
        <div class="form-group">
            <label for="terms_of_delivery">Terms of Delivery:</label>
            <input type="text" id="terms_of_delivery" name="terms_of_delivery"  >
        </div>
        <div class="form-group">
            <label for="product_id">Product:</label>
            <select id="product_id" name="product_id"  >
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo htmlspecialchars($product['id']); ?>">
                        <?php echo htmlspecialchars($product['brand_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
    <label for="hsn_code">HSN Code:</label>
    <input type="text" id="hsn_code" name="hsn_code">
</div>
<div class="form-group">
    <label for="marks_and_nos">Marks & Nos:</label>
    <input type="text" id="marks_and_nos" name="marks_and_nos">
</div>
<div class="form-group">
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity">
</div>
<div class="form-group">
    <label for="price_per_unit">Price Per Unit (Dollar):</label>
    <input type="number" id="price_per_unit" name="price_per_unit" step="0.01">
</div>
<div class="form-group">
    <label for="total_unit_price">Total Unit Price (Dollar):</label>
    <input type="number" id="total_unit_price" name="total_unit_price" step="0.01">
</div>

        <div class="form-group">
            <label for="total_amount">Total Amount:</label>
            <input type="number" id="total_amount" name="total_amount" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="igst_amount">IGST Amount:</label>
            <input type="number" id="igst_amount" name="igst_amount" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_length">Container Length:</label>
            <input type="number" id="container_length" name="container_length" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_width">Container Width:</label>
            <input type="number" id="container_width" name="container_width" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_height">Container Height:</label>
            <input type="number" id="container_height" name="container_height" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_weight_net">Container Net Weight:</label>
            <input type="number" id="container_weight_net" name="container_weight_net" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_weight_gross">Container Gross Weight:</label>
            <input type="number" id="container_weight_gross" name="container_weight_gross" step="0.01"  >
        </div>
        <div class="form-group">
            <label for="container_other">Container Other:</label>
            <input type="text" id="container_other" name="container_other">
        </div>
        <!-- Additional bank details fields -->
        <div class="form-group">
            <label for="bank_name">Bank Name:</label>
            <input type="text" id="bank_name" name="bank_name">
        </div>
        <div class="form-group">
            <label for="ifsc_code">IFSC Code:</label>
            <input type="text" id="ifsc_code" name="ifsc_code">
        </div>
        <div class="form-group">
            <label for="gstin">GSTIN:</label>
            <input type="text" id="gstin" name="gstin">
        </div>
        <div class="form-group">
            <label for="vat_tin">VAT TIN:</label>
            <input type="text" id="vat_tin" name="vat_tin">
        </div>
        <div class="form-group">
            <label for="iec_code">IEC Code:</label>
            <input type="text" id="iec_code" name="iec_code">
        </div>
        <div class="form-group">
            <label for="bank_account_no">Bank Account Number:</label>
            <input type="text" id="bank_account_no" name="bank_account_no">
        </div>
        <div class="form-group">
            <label for="end_user_code">End User Code:</label>
            <input type="text" id="end_user_code" name="end_user_code">
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination">
        </div>
        <div class="form-group">
            <label for="drug_license_no">Drug License Number:</label>
            <input type="text" id="drug_license_no" name="drug_license_no">
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Submit</button>
        </div>
        <button type="submit" class="btn">Generate PDF</button>
    </form>
    <div id="message-container">
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
    </div>
    <script>
        // JavaScript to handle buyer selection and auto-fill bank details
        document.addEventListener('DOMContentLoaded', function() {
            const buyerSelect = document.getElementById('buyer_id');
            const bankNameInput = document.getElementById('bank_name');
            const ifscCodeInput = document.getElementById('ifsc_code');
            const gstinInput = document.getElementById('gstin');
            const vatTinInput = document.getElementById('vat_tin');
            const iecCodeInput = document.getElementById('iec_code');
            const bankAccountNoInput = document.getElementById('bank_account_no');
            const endUserCodeInput = document.getElementById('end_user_code');
            const destinationInput = document.getElementById('destination');
            const drugLicenseNoInput = document.getElementById('drug_license_no');
            
            const buyers = <?php echo json_encode($buyers); ?>;
            const banks = <?php echo json_encode($banks); ?>;

            buyerSelect.addEventListener('change', function() {
                const selectedBuyerId = parseInt(buyerSelect.value);
                const selectedBuyer = buyers.find(buyer => buyer.id === selectedBuyerId);

                if (selectedBuyer) {
                    const selectedBank = banks.find(bank => bank.id === parseInt(selectedBuyer.bank_id));

                    if (selectedBank) {
                        bankNameInput.value = selectedBank.bank_name;
                        ifscCodeInput.value = selectedBank.ifsc_code;
                    } else {
                        bankNameInput.value = '';
                        ifscCodeInput.value = '';
                    }

                    gstinInput.value = selectedBuyer.gst_number || '';
                    vatTinInput.value = selectedBuyer.vat_tin_number || '';
                    iecCodeInput.value = selectedBuyer.iec_number || '';
                    bankAccountNoInput.value = ''; // This would typically be filled from another source
                    endUserCodeInput.value = ''; // This would typically be filled from another source
                    destinationInput.value = ''; // This would typically be filled from another source
                    drugLicenseNoInput.value = selectedBuyer.drug_licence_number || '';
                } else {
                    bankNameInput.value = '';
                    ifscCodeInput.value = '';
                    gstinInput.value = '';
                    vatTinInput.value = '';
                    iecCodeInput.value = '';
                    bankAccountNoInput.value = '';
                    endUserCodeInput.value = '';
                    destinationInput.value = '';
                    drugLicenseNoInput.value = '';
                }
            });
        });

        
    </script>
 <script>
        function confirmSubmission() {
            var invoiceNo = document.getElementById("invoice_no").value;
            // Check if the invoice number is not empty
            if (invoiceNo === "") {
                alert("Please enter a valid Invoice Number.");
                return false; // Prevent form submission
            }

            // Confirm with the user
            var confirmMsg = "Are you sure you want to generate the PDF for Invoice Number: " + invoiceNo + "?";
            return confirm(confirmMsg); // Return true or false based on user confirmation
        }
    </script>
</body>
</html>
