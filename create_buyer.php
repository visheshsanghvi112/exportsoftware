<?php

require_once 'auth_check.php'; // Start the session
// PHP script to handle form submission and add a new buyer to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data with checks for existence
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $bank_id = $_POST['bank_id'] ?? '';
    $bank_acc_no = $_POST['bank_acc_no'] ?? ''; // New field for bank account number
    $gst_number = $_POST['gst_number'] ?? '';
    $iec_number = $_POST['iec_number'] ?? '';
    $drug_licence_number = $_POST['drug_licence_number'] ?? '';
    $vat_tin_number = $_POST['vat_tin_number'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $city_id = $_POST['city_id'] ?? '';
    $state_id = $_POST['state_id'] ?? '';
    $country_id = $_POST['country_id'] ?? '';

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

        // Validate foreign keys
        $valid_city = $pdo->prepare("SELECT id FROM cities WHERE id = ?");
        $valid_city->execute([$city_id]);
        if (!$valid_city->fetch()) {
            throw new Exception("Invalid city ID");
        }

        $valid_state = $pdo->prepare("SELECT id FROM states WHERE id = ?");
        $valid_state->execute([$state_id]);
        if (!$valid_state->fetch()) {
            throw new Exception("Invalid state ID");
        }

        $valid_country = $pdo->prepare("SELECT id FROM countries WHERE id = ?");
        $valid_country->execute([$country_id]);
        if (!$valid_country->fetch()) {
            throw new Exception("Invalid country ID");
        }

        // Prepare and execute the insert query
        $stmt = $pdo->prepare("
            INSERT INTO buyers (
                name, address, bank_id, bank_acc_no, gst_number, iec_number, 
                drug_licence_number, vat_tin_number, pincode, city_id, state_id, country_id
            ) VALUES (
                :name, :address, :bank_id, :bank_acc_no, :gst_number, :iec_number, 
                :drug_licence_number, :vat_tin_number, :pincode, :city_id, :state_id, :country_id
            )
        ");
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':bank_id' => $bank_id,
            ':bank_acc_no' => $bank_acc_no,
            ':gst_number' => $gst_number,
            ':iec_number' => $iec_number,
            ':drug_licence_number' => $drug_licence_number,
            ':vat_tin_number' => $vat_tin_number,
            ':pincode' => $pincode,
            ':city_id' => $city_id,
            ':state_id' => $state_id,
            ':country_id' => $country_id,
        ]);

        $success_message = "Buyer created successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch data for dropdowns
try {
    $dsn = 'mysql:host=localhost;dbname=exportsoftware';
    $username = 'root';
    $password = '';
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $banks_stmt = $pdo->query("SELECT id, bank_name FROM banks");
    $banks = $banks_stmt->fetchAll();

    $cities_stmt = $pdo->query("SELECT id, city FROM cities");
    $cities = $cities_stmt->fetchAll();

    $states_stmt = $pdo->query("SELECT id, name FROM states");
    $states = $states_stmt->fetchAll();

    $countries_stmt = $pdo->query("SELECT id, name FROM countries");
    $countries = $countries_stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Buyer</title>
    <style>
        /* Reset some default styles */
        body, h1, h2, p {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
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
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            animation: fadeIn 1s ease-in;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0056b3;
            font-size: 2.5rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #0056b3;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 85, 255, 0.25);
        }

        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #003d7a;
            border-color: #003d7a;
        }

        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .alert-success {
            background-color: #4caf50;
        }

        .alert-danger {
            background-color: #f44336;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
            }

            nav ul li a {
                padding: 10px 15px;
            }
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
                <li><a href="add_user.php">Add User</a></li> <!-- Link to add a new user -->
                <li><a href="logout.php" id="logout-link">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Create New Buyer</h1>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="bank_id">Bank:</label>
                <select id="bank_id" name="bank_id" class="form-control" required>
                    <option value="">Select Bank</option>
                    <?php foreach ($banks as $bank): ?>
                        <option value="<?php echo htmlspecialchars($bank['id']); ?>">
                            <?php echo htmlspecialchars($bank['bank_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="bank_acc_no">Bank Account Number:</label>
                <input type="text" id="bank_acc_no" name="bank_acc_no" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gst_number">GST Number:</label>
                <input type="text" id="gst_number" name="gst_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="iec_number">IEC Number:</label>
                <input type="text" id="iec_number" name="iec_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="drug_licence_number">Drug Licence Number:</label>
                <input type="text" id="drug_licence_number" name="drug_licence_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="vat_tin_number">VAT TIN Number:</label>
                <input type="text" id="vat_tin_number" name="vat_tin_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="pincode">Pincode:</label>
                <input type="text" id="pincode" name="pincode" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="city_id">City:</label>
                <select id="city_id" name="city_id" class="form-control" required>
                    <option value="">Select City</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city['id']); ?>">
                            <?php echo htmlspecialchars($city['city']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="state_id">State:</label>
                <select id="state_id" name="state_id" class="form-control" required>
                    <option value="">Select State</option>
                    <?php foreach ($states as $state): ?>
                        <option value="<?php echo htmlspecialchars($state['id']); ?>">
                            <?php echo htmlspecialchars($state['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="country_id">Country:</label>
                <select id="country_id" name="country_id" class="form-control" required>
                    <option value="">Select Country</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo htmlspecialchars($country['id']); ?>">
                            <?php echo htmlspecialchars($country['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Buyer</button>
        </form>
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
