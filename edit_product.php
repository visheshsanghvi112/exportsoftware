<?php
 
require_once 'database.php';
require_once 'auth_check.php'; // Start the session

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Get product ID from query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
if ($product_id) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
} else {
    $product = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update product details
    $brand_name = $_POST['brand_name'];
    $composition = $_POST['composition'];
    $type = $_POST['type'];
    $packing = $_POST['packing'];
    $case_qty = $_POST['case_qty'];
    $manufacturing_date = $_POST['manufacturing_date'];
    $expiry_date = $_POST['expiry_date'];
    $batch_no = $_POST['batch_no'];
    $hsn_code = $_POST['hsn_code'];
    $rate = $_POST['rate'];
    $currency = $_POST['currency'];
    $gst_rate = $_POST['gst_rate'];

    $sql = "UPDATE products SET brand_name = ?, composition = ?, type = ?, packing = ?, case_qty = ?, manufacturing_date = ?, expiry_date = ?, batch_no = ?, hsn_code = ?, rate = ?, currency = ?, gst_rate = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisssssdii", $brand_name, $composition, $type, $packing, $case_qty, $manufacturing_date, $expiry_date, $batch_no, $hsn_code, $rate, $currency, $gst_rate, $product_id);
    if ($stmt->execute()) {
        $success_message = "Product updated successfully!";
        // Redirect with a short delay to ensure the message is shown
        echo "<script>
            alert('$success_message');
            window.location.href = 'welcome.php';
        </script>";
        exit();
    } else {
        $error_message = "Failed to update product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body, h1, h2, p {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
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

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="date"], input[type="email"] {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="input.php">Create Export</a></li>
                <li><a href="display_exportbills.php">View Export Bills</a></li>
                <li><a href="add_user.php">Add User</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Edit Product</h2>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="brand_name">Brand Name:</label>
            <input type="text" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($product['brand_name'] ?? ''); ?>" >
            <label for="composition">Composition:</label>
            <input type="text" id="composition" name="composition" value="<?php echo htmlspecialchars($product['composition'] ?? ''); ?>" >
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($product['type'] ?? ''); ?>" >
            <label for="packing">Packing:</label>
            <input type="text" id="packing" name="packing" value="<?php echo htmlspecialchars($product['packing'] ?? ''); ?>" >
            <label for="case_qty">Case Quantity:</label>
            <input type="number" id="case_qty" name="case_qty" value="<?php echo htmlspecialchars($product['case_qty'] ?? ''); ?>" >
            <label for="manufacturing_date">Manufacturing Date:</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" value="<?php echo htmlspecialchars($product['manufacturing_date'] ?? ''); ?>" >
            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($product['expiry_date'] ?? ''); ?>" >
            <label for="batch_no">Batch No:</label>
            <input type="text" id="batch_no" name="batch_no" value="<?php echo htmlspecialchars($product['batch_no'] ?? ''); ?>" >
            <label for="hsn_code">HSN Code:</label>
            <input type="text" id="hsn_code" name="hsn_code" value="<?php echo htmlspecialchars($product['hsn_code'] ?? ''); ?>" >
            <label for="rate">Rate:</label>
            <input type="number" step="0.01" id="rate" name="rate" value="<?php echo htmlspecialchars($product['rate'] ?? ''); ?>" >
            <label for="currency">Currency:</label>
            <input type="text" id="currency" name="currency" value="<?php echo htmlspecialchars($product['currency'] ?? ''); ?>" >
            <label for="gst_rate">GST Rate:</label>
            <input type="number" step="0.01" id="gst_rate" name="gst_rate" value="<?php echo htmlspecialchars($product['gst_rate'] ?? ''); ?>" >
            <input type="submit" value="Update Product">
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Export Billing Software. All rights reserved.</p>
    </footer>
</body>
</html>
