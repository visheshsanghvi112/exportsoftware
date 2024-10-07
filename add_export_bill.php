<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Bill Form</title>
</head>
<body>
    <h1>Export Bill Form</h1>
    <form action="process_export_bill.php" method="post">
        <label for="supplier_id">Supplier ID:</label>
        <input type="number" id="supplier_id" name="supplier_id"><br><br>
        
        <label for="buyer_id">Buyer ID:</label>
        <input type="number" id="buyer_id" name="buyer_id"><br><br>
        
        <label for="consignee_id">Consignee ID:</label>
        <input type="number" id="consignee_id" name="consignee_id"><br><br>
        
        <label for="invoice_no">Invoice No:</label>
        <input type="text" id="invoice_no" name="invoice_no" required><br><br>
        
        <label for="invoice_date">Invoice Date:</label>
        <input type="date" id="invoice_date" name="invoice_date"><br><br>
        
        <label for="carriage">Carriage:</label>
        <select id="carriage" name="carriage">
            <option value="sea">Sea</option>
            <option value="air">Air</option>
        </select><br><br>
        
        <label for="place_of_receipt">Place of Receipt:</label>
        <input type="text" id="place_of_receipt" name="place_of_receipt"><br><br>
        
        <label for="port_of_loading">Port of Loading:</label>
        <input type="text" id="port_of_loading" name="port_of_loading"><br><br>
        
        <label for="port_of_discharge">Port of Discharge:</label>
        <input type="text" id="port_of_discharge" name="port_of_discharge"><br><br>
        
        <label for="final_destination">Final Destination:</label>
        <input type="text" id="final_destination" name="final_destination"><br><br>
        
        <label for="terms_of_delivery">Terms of Delivery:</label>
        <textarea id="terms_of_delivery" name="terms_of_delivery"></textarea><br><br>
        
        <label for="total_amount">Total Amount:</label>
        <input type="number" step="0.01" id="total_amount" name="total_amount"><br><br>
        
        <label for="igst_amount">IGST Amount:</label>
        <input type="number" step="0.01" id="igst_amount" name="igst_amount"><br><br>
        
        <label for="container_length">Container Length:</label>
        <input type="number" step="0.01" id="container_length" name="container_length"><br><br>
        
        <label for="container_width">Container Width:</label>
        <input type="number" step="0.01" id="container_width" name="container_width"><br><br>
        
        <label for="container_height">Container Height:</label>
        <input type="number" step="0.01" id="container_height" name="container_height"><br><br>
        
        <label for="container_other">Container Other:</label>
        <textarea id="container_other" name="container_other"></textarea><br><br>
        
        <label for="bank_name">Bank Name:</label>
        <input type="text" id="bank_name" name="bank_name"><br><br>
        
        <label for="ifsc_code">IFSC Code:</label>
        <input type="text" id="ifsc_code" name="ifsc_code"><br><br>
        
        <label for="gstin">GSTIN:</label>
        <input type="text" id="gstin" name="gstin"><br><br>
        
        <label for="vat_tin">VAT TIN:</label>
        <input type="text" id="vat_tin" name="vat_tin"><br><br>
        
        <label for="declaration">Declaration:</label>
        <textarea id="declaration" name="declaration"></textarea><br><br>
        
        <label for="shipment_advice_no">Shipment Advice No:</label>
        <input type="text" id="shipment_advice_no" name="shipment_advice_no"><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
