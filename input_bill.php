<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Input Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        form {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label, input {
            display: block;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Enter Bill Details</h2>

<form action="generate_bill.php" method="POST">
    <!-- Supplier Info -->
    <label for="supplier_name">Supplier Name:</label>
    <input type="text" id="supplier_name" name="supplier_name" required>

    <label for="supplier_address">Supplier Address:</label>
    <input type="text" id="supplier_address" name="supplier_address" required>

    <!-- Recipient Info -->
    <label for="recipient_name">Recipient Name:</label>
    <input type="text" id="recipient_name" name="recipient_name" required>

    <label for="recipient_address">Recipient Address:</label>
    <input type="text" id="recipient_address" name="recipient_address" required>

    <!-- Invoice Details -->
    <label for="invoice_no">Invoice Number:</label>
    <input type="text" id="invoice_no" name="invoice_no" required>

    <label for="invoice_date">Invoice Date:</label>
    <input type="date" id="invoice_date" name="invoice_date" required>

    <!-- Product Info -->
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id" required>

    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>

    <label for="price_per_unit">Price Per Unit:</label>
    <input type="number" id="price_per_unit" name="price_per_unit" step="0.01" required>

    <!-- Bank Info -->
    <label for="bank_name">Bank Name:</label>
    <input type="text" id="bank_name" name="bank_name" required>

    <label for="account_number">Account Number:</label>
    <input type="text" id="account_number" name="account_number" required>

    <label for="ifsc_code">IFSC Code:</label>
    <input type="text" id="ifsc_code" name="ifsc_code" required>

    <label for="swift_code">SWIFT Code:</label>
    <input type="text" id="swift_code" name="swift_code" required>

    <input type="submit" value="Generate Bill">
</form>

</body>
</html>
