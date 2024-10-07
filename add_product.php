<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <style>
        /* Reset some default styles */
        body, h1, h2, p {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        /* General body styling */
        body {
            background-color: #f4f7f6;
            color: #333;
        }

        /* Header styling */
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

        /* Footer Styling */
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

        /* Container styling */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 700px;
            margin: 30px auto;
        }

        .container h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #444;
            text-align: center;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #007BFF;
            outline: none;
        }

        .form-group input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 12px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            font-size: 18px;
            color: #28a745;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
            }

            nav ul li a {
                padding: 10px 15px;
            }

            .container {
                padding: 20px;
                width: 95%;
            }

            .container h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome!</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="input.php">Create Export</a></li>
                <li><a href="display_exportbills.php">View Export Bills</a></li>
                <li><a href="productslist.php">Product List</a></li>
                <li><a href="logout.php" id="logout-link">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Add New Product</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="brand_name">Brand Name:</label>
                <input type="text" id="brand_name" name="brand_name" required>
            </div>
            <div class="form-group">
                <label for="composition">Composition:</label>
                <input type="text" id="composition" name="composition">
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type">
            </div>
            <div class="form-group">
                <label for="packing">Packing:</label>
                <input type="text" id="packing" name="packing">
            </div>
            <div class="form-group">
                <label for="case_qty">Case Quantity:</label>
                <input type="number" id="case_qty" name="case_qty">
            </div>
            <div class="form-group">
                <label for="manufacturing_date">Manufacturing Date:</label>
                <input type="date" id="manufacturing_date" name="manufacturing_date">
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date:</label>
                <input type="date" id="expiry_date" name="expiry_date">
            </div>
            <div class="form-group">
                <label for="batch_no">Batch Number:</label>
                <input type="text" id="batch_no" name="batch_no">
            </div>
            <div class="form-group">
                <label for="hsn_code">HSN Code:</label>
                <input type="text" id="hsn_code" name="hsn_code">
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" id="rate" name="rate">
            </div>
            <div class="form-group">
                <label for="currency">Currency:</label>
                <select id="currency" name="currency">
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                    <option value="INR" selected>INR</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gst_rate">GST Rate:</label>
                <input type="text" id="gst_rate" name="gst_rate">
            </div>
            <div class="form-group">
                <input type="submit" value="Add Product">
            </div>
        </form>

        <?php
         
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
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

            // Database configuration
            $host = 'localhost';
            $dbname = 'exportsoftware';
            $username = 'root';
            $password = '';

            try {
                // Create a new PDO instance
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

                // Set the PDO error mode to exception
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL query to insert a new product
                $sql = "INSERT INTO products (
                    brand_name,
                    composition,
                    type,
                    packing,
                    case_qty,
                    manufacturing_date,
                    expiry_date,
                    batch_no,
                    hsn_code,
                    rate,
                    currency,
                    gst_rate
                ) VALUES (
                    :brand_name,
                    :composition,
                    :type,
                    :packing,
                    :case_qty,
                    :manufacturing_date,
                    :expiry_date,
                    :batch_no,
                    :hsn_code,
                    :rate,
                    :currency,
                    :gst_rate
                )";

                // Prepare the statement
                $stmt = $pdo->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':brand_name', $brand_name);
                $stmt->bindParam(':composition', $composition);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':packing', $packing);
                $stmt->bindParam(':case_qty', $case_qty, PDO::PARAM_INT);
                $stmt->bindParam(':manufacturing_date', $manufacturing_date);
                $stmt->bindParam(':expiry_date', $expiry_date);
                $stmt->bindParam(':batch_no', $batch_no);
                $stmt->bindParam(':hsn_code', $hsn_code);
                $stmt->bindParam(':rate', $rate, PDO::PARAM_STR);
                $stmt->bindParam(':currency', $currency);
                $stmt->bindParam(':gst_rate', $gst_rate, PDO::PARAM_STR);

                // Execute the statement
                $stmt->execute();

                echo '<p class="message">New product added successfully.</p>';

            } catch (PDOException $e) {
                echo '<p class="message">Error: ' . $e->getMessage() . '</p>';
            }

            // Close the connection
            $pdo = null;
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Export Billing Software. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            var confirmLogout = confirm('Are you sure you want to logout?');
            if (!confirmLogout) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
