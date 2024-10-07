<?php
require_once 'auth_check.php'; // Start the session
require 'vendor/autoload.php'; // Include Composer's autoloader
use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = $_POST['name'] ?? '';
    $manufacturing_date = $_POST['manufacturing_date'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $batch_no = $_POST['batch_no'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';
    $buyer_id = $_POST['buyer_id'] ?? '';
    $consignee_id = $_POST['consignee_id'] ?? '';
    $invoice_no = $_POST['invoice_no'] ?? '';
    $invoice_date = $_POST['invoice_date'] ?? '';
    $carriage = $_POST['carriage'] ?? '';
    $place_of_receipt = $_POST['place_of_receipt'] ?? '';
    $port_of_loading = $_POST['port_of_loading'] ?? '';
    $port_of_discharge = $_POST['port_of_discharge'] ?? '';
    $final_destination = $_POST['final_destination'] ?? '';
    $terms_of_delivery = $_POST['terms_of_delivery'] ?? '';
    $product_id = $_POST['product_id'] ?? '';
    $hsn_code = $_POST['hsn_code'] ?? '';
    $marks_and_nos = $_POST['marks_and_nos'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $price_per_unit = $_POST['price_per_unit'] ?? '';
    $total_unit_price = $_POST['total_unit_price'] ?? '';
    $total_amount = $_POST['total_amount'] ?? '';
    $igst_amount = $_POST['igst_amount'] ?? '';
    $container_length = $_POST['container_length'] ?? '';
    $container_width = $_POST['container_width'] ?? '';
    $container_height = $_POST['container_height'] ?? '';
    $container_weight_net = $_POST['container_weight_net'] ?? '';
    $container_weight_gross = $_POST['container_weight_gross'] ?? '';
    $container_other = $_POST['container_other'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $ifsc_code = $_POST['ifsc_code'] ?? '';
    $gstin = $_POST['gstin'] ?? '';
    $vat_tin = $_POST['vat_tin'] ?? '';
    $iec_code = $_POST['iec_code'] ?? '';
    $bank_account_no = $_POST['bank_account_no'] ?? '';
    $end_user_code = $_POST['end_user_code'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $drug_license_no = $_POST['drug_license_no'] ?? '';

    // Database connection and data insertion
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
        $stmt = $pdo->prepare("INSERT INTO export_data (supplier_id, buyer_id, consignee_id, invoice_no, invoice_date, 
        manufacturing_date, expiry_date, batch_no, carriage, place_of_receipt, 
        port_of_loading, port_of_discharge, final_destination, terms_of_delivery, 
        product_id, total_amount, igst_amount, container_length, container_width, 
        container_height, container_weight_net, container_weight_gross, container_other, 
        bank_name, ifsc_code, gstin, vat_tin, iec_code, bank_account_no, 
        end_user_code, destination, drug_license_no, hsn_code, marks_and_nos, 
        quantity, price_per_unit, total_unit_price, name) VALUES (
        :supplier_id, :buyer_id, :consignee_id, :invoice_no, :invoice_date, 
        :manufacturing_date, :expiry_date, :batch_no, :carriage, :place_of_receipt, 
        :port_of_loading, :port_of_discharge, :final_destination, :terms_of_delivery, 
        :product_id, :total_amount, :igst_amount, :container_length, :container_width, 
        :container_height, :container_weight_net, :container_weight_gross, :container_other, 
        :bank_name, :ifsc_code, :gstin, :vat_tin, :iec_code, :bank_account_no, 
        :end_user_code, :destination, :drug_license_no, :hsn_code, :marks_and_nos, 
        :quantity, :price_per_unit, :total_unit_price, :name)");
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
            ':name' => $name,
        ]);

        // Generate PDF bill
        $dompdf = new Dompdf();
        $html = '<h1>Invoice Bill</h1>';
        $html .= '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>';
        $html .= '<p><strong>Invoice Number:</strong> ' . htmlspecialchars($invoice_no) . '</p>';
        $html .= '<p><strong>Invoice Date:</strong> ' . htmlspecialchars($invoice_date) . '</p>';
        $html .= '<p><strong>Manufacturing Date:</strong> ' . htmlspecialchars($manufacturing_date) . '</p>';
        $html .= '<p><strong>Expiry Date:</strong> ' . htmlspecialchars($expiry_date) . '</p>';
        $html .= '<p><strong>Batch No:</strong> ' . htmlspecialchars($batch_no) . '</p>';
        $html .= '<p><strong>Carriage:</strong> ' . htmlspecialchars($carriage) . '</p>';
        $html .= '<p><strong>Place of Receipt:</strong> ' . htmlspecialchars($place_of_receipt) . '</p>';
        $html .= '<p><strong>Port of Loading:</strong> ' . htmlspecialchars($port_of_loading) . '</p>';
        $html .= '<p><strong>Port of Discharge:</strong> ' . htmlspecialchars($port_of_discharge) . '</p>';
        $html .= '<p><strong>Final Destination:</strong> ' . htmlspecialchars($final_destination) . '</p>';
        $html .= '<p><strong>Terms of Delivery:</strong> ' . htmlspecialchars($terms_of_delivery) . '</p>';
        $html .= '<p><strong>Product ID:</strong> ' . htmlspecialchars($product_id) . '</p>';
        $html .= '<p><strong>HSN Code:</strong> ' . htmlspecialchars($hsn_code) . '</p>';
        $html .= '<p><strong>Marks and Nos:</strong> ' . htmlspecialchars($marks_and_nos) . '</p>';
        $html .= '<p><strong>Quantity:</strong> ' . htmlspecialchars($quantity) . '</p>';
        $html .= '<p><strong>Price per Unit:</strong> ' . htmlspecialchars($price_per_unit) . '</p>';
        $html .= '<p><strong>Total Unit Price:</strong> ' . htmlspecialchars($total_unit_price) . '</p>';
        $html .= '<p><strong>Total Amount:</strong> ' . htmlspecialchars($total_amount) . '</p>';
        $html .= '<p><strong>IGST Amount:</strong> ' . htmlspecialchars($igst_amount) . '</p>';
        $html .= '<p><strong>Container Length:</strong> ' . htmlspecialchars($container_length) . '</p>';
        $html .= '<p><strong>Container Width:</strong> ' . htmlspecialchars($container_width) . '</p>';
        $html .= '<p><strong>Container Height:</strong> ' . htmlspecialchars($container_height) . '</p>';
        $html .= '<p><strong>Container Weight (Net):</strong> ' . htmlspecialchars($container_weight_net) . '</p>';
        $html .= '<p><strong>Container Weight (Gross):</strong> ' . htmlspecialchars($container_weight_gross) . '</p>';
        $html .= '<p><strong>Container Other:</strong> ' . htmlspecialchars($container_other) . '</p>';
        $html .= '<p><strong>Bank Name:</strong> ' . htmlspecialchars($bank_name) . '</p>';
        $html .= '<p><strong>IFSC Code:</strong> ' . htmlspecialchars($ifsc_code) . '</p>';
        $html .= '<p><strong>GSTIN:</strong> ' . htmlspecialchars($gstin) . '</p>';
        $html .= '<p><strong>VAT TIN:</strong> ' . htmlspecialchars($vat_tin) . '</p>';
        $html .= '<p><strong>IEC Code:</strong> ' . htmlspecialchars($iec_code) . '</p>';
        $html .= '<p><strong>Bank Account No:</strong> ' . htmlspecialchars($bank_account_no) . '</p>';
        $html .= '<p><strong>End User Code:</strong> ' . htmlspecialchars($end_user_code) . '</p>';
        $html .= '<p><strong>Destination:</strong> ' . htmlspecialchars($destination) . '</p>';
        $html .= '<p><strong>Drug License No:</strong> ' . htmlspecialchars($drug_license_no) . '</p>';
        $html .= '<p>Thank you for your business!</p>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF to browser
        $dompdf->stream('invoice_' . $invoice_no . '.pdf', ['Attachment' => true]);
        
        $success_message = "Bill generated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error inserting data: " . $e->getMessage();
    }
}

// Fetch product names
try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch bank names
    $stmt = $pdo->query("SELECT * FROM banks");
    $banks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch buyer names
    $stmt = $pdo->query("SELECT * FROM buyers");
    $buyers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching data: " . $e->getMessage();
}
?>
<!-- Your HTML form -->
<form method="post" id="export-form">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" name="name" id="name" required>
    </div>
    <div class="form-group">
        <label for="manufacturing_date">Manufacturing Date:</label>
        <input type="date" class="form-control" name="manufacturing_date" id="manufacturing_date" required>
    </div>
    <div class="form-group">
        <label for="expiry_date">Expiry Date:</label>
        <input type="date" class="form-control" name="expiry_date" id="expiry_date" required>
    </div>
    <div class="form-group">
        <label for="batch_no">Batch No:</label>
        <input type="text" class="form-control" name="batch_no" id="batch_no" required>
    </div>
    <div class="form-group">
        <label for="invoice_no">Invoice No:</label>
        <input type="text" class="form-control" name="invoice_no" id="invoice_no" required>
    </div>
    <div class="form-group">
        <label for="invoice_date">Invoice Date:</label>
        <input type="date" class="form-control" name="invoice_date" id="invoice_date" required>
    </div>
    <div class="form-group">
        <label for="supplier_id">Supplier ID:</label>
        <input type="text" class="form-control" name="supplier_id" id="supplier_id" required>
    </div>
    <div class="form-group">
        <label for="buyer_id">Buyer ID:</label>
        <input type="text" class="form-control" name="buyer_id" id="buyer_id" required>
    </div>
    <div class="form-group">
        <label for="consignee_id">Consignee ID:</label>
        <input type="text" class="form-control" name="consignee_id" id="consignee_id" required>
    </div>
    <div class="form-group">
        <label for="carriage">Carriage:</label>
        <input type="text" class="form-control" name="carriage" id="carriage" required>
    </div>
    <div class="form-group">
        <label for="place_of_receipt">Place of Receipt:</label>
        <input type="text" class="form-control" name="place_of_receipt" id="place_of_receipt" required>
    </div>
    <div class="form-group">
        <label for="port_of_loading">Port of Loading:</label>
        <input type="text" class="form-control" name="port_of_loading" id="port_of_loading" required>
    </div>
    <div class="form-group">
        <label for="port_of_discharge">Port of Discharge:</label>
        <input type="text" class="form-control" name="port_of_discharge" id="port_of_discharge" required>
    </div>
    <div class="form-group">
        <label for="final_destination">Final Destination:</label>
        <input type="text" class="form-control" name="final_destination" id="final_destination" required>
    </div>
    <div class="form-group">
        <label for="terms_of_delivery">Terms of Delivery:</label>
        <input type="text" class="form-control" name="terms_of_delivery" id="terms_of_delivery" required>
    </div>
    <div class="form-group">
        <label for="product_id">Product ID:</label>
        <input type="text" class="form-control" name="product_id" id="product_id" required>
    </div>
    <div class="form-group">
        <label for="hsn_code">HSN Code:</label>
        <input type="text" class="form-control" name="hsn_code" id="hsn_code" required>
    </div>
    <div class="form-group">
        <label for="marks_and_nos">Marks and Nos:</label>
        <input type="text" class="form-control" name="marks_and_nos" id="marks_and_nos" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" class="form-control" name="quantity" id="quantity" required>
    </div>
    <div class="form-group">
        <label for="price_per_unit">Price per Unit:</label>
        <input type="number" class="form-control" name="price_per_unit" id="price_per_unit" required>
    </div>
    <div class="form-group">
        <label for="total_unit_price">Total Unit Price:</label>
        <input type="number" class="form-control" name="total_unit_price" id="total_unit_price" required>
    </div>
    <div class="form-group">
        <label for="total_amount">Total Amount:</label>
        <input type="number" class="form-control" name="total_amount" id="total_amount" required>
    </div>
    <div class="form-group">
        <label for="igst_amount">IGST Amount:</label>
        <input type="number" class="form-control" name="igst_amount" id="igst_amount" required>
    </div>
    <div class="form-group">
        <label for="container_length">Container Length:</label>
        <input type="number" class="form-control" name="container_length" id="container_length" required>
    </div>
    <div class="form-group">
        <label for="container_width">Container Width:</label>
        <input type="number" class="form-control" name="container_width" id="container_width" required>
    </div>
    <div class="form-group">
        <label for="container_height">Container Height:</label>
        <input type="number" class="form-control" name="container_height" id="container_height" required>
    </div>
    <div class="form-group">
        <label for="container_weight_net">Container Weight (Net):</label>
        <input type="number" class="form-control" name="container_weight_net" id="container_weight_net" required>
    </div>
    <div class="form-group">
        <label for="container_weight_gross">Container Weight (Gross):</label>
        <input type="number" class="form-control" name="container_weight_gross" id="container_weight_gross" required>
    </div>
    <div class="form-group">
        <label for="container_other">Container Other:</label>
        <input type="text" class="form-control" name="container_other" id="container_other">
    </div>
    <div class="form-group">
        <label for="bank_name">Bank Name:</label>
        <select class="form-control" name="bank_name" id="bank_name" required>
            <option value="">Select Bank</option>
            <?php foreach ($banks as $bank): ?>
                <option value="<?php echo htmlspecialchars($bank['name']); ?>"><?php echo htmlspecialchars($bank['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="ifsc_code">IFSC Code:</label>
        <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" required>
    </div>
    <div class="form-group">
        <label for="gstin">GSTIN:</label>
        <input type="text" class="form-control" name="gstin" id="gstin">
    </div>
    <div class="form-group">
        <label for="vat_tin">VAT TIN:</label>
        <input type="text" class="form-control" name="vat_tin" id="vat_tin">
    </div>
    <div class="form-group">
        <label for="iec_code">IEC Code:</label>
        <input type="text" class="form-control" name="iec_code" id="iec_code">
    </div>
    <div class="form-group">
        <label for="bank_account_no">Bank Account No:</label>
        <input type="text" class="form-control" name="bank_account_no" id="bank_account_no" required>
    </div>
    <div class="form-group">
        <label for="end_user_code">End User Code:</label>
        <input type="text" class="form-control" name="end_user_code" id="end_user_code">
    </div>
    <div class="form-group">
        <label for="destination">Destination:</label>
        <input type="text" class="form-control" name="destination" id="destination" required>
    </div>
    <div class="form-group">
        <label for="drug_license_no">Drug License No:</label>
        <input type="text" class="form-control" name="drug_license_no" id="drug_license_no">
    </div>
    <button type="submit" class="btn btn-primary">Generate Bill</button>
</form>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php elseif (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

</body>
</html>
