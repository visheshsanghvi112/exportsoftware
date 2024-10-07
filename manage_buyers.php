<?php
 
require_once 'auth_check.php'; // Start the session

// Include the database connection
require_once 'database.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Fetch buyers from the database
$sql = "SELECT * FROM buyers";
$result = mysqli_query($conn, $sql);

if ($result) {
    $buyers = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $buyers = [];
    // Optionally, add error handling here
}

// Handle form submission to delete buyer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $buyerId = $_POST['buyer_id'];
        
        // Delete buyer from the database
        $deleteSql = "DELETE FROM buyers WHERE id = $buyerId";
        $deleteResult = mysqli_query($conn, $deleteSql);
        
        if ($deleteResult) {
            // Redirect to manage_buyers.php to refresh buyer list after deletion
            header('Location: manage_buyers.php');
            exit();
        } else {
            // Handle error, e.g., display error message
            echo "Error deleting buyer: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Buyers</title>
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

        /* Container styling */
        .container {
            width: 90%;
            max-width: 12000px;
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

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        /* Form styling */
        form {
            margin-bottom: 20px;
        }

        button[type="submit"], button[type="button"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #0056b3;
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

        /* Responsive adjustments */
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
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="input.php">Create Export</a></li>
                <li><a href="display_exportbills.php">View Export Bills</a></li>
                <li><a href="manage_users.php">Manage Users</a></li> <!-- Link to manage users -->
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Manage Buyers</h2>

        <!-- Buyers table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Bank</th>
                    <th>Bank Account No.</th>
                    <th>GST Number</th>
                    <th>IEC Number</th>
                    <th>Drug Licence Number</th>
                    <th>VAT TIN Number</th>
                    <th>Pincode</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($buyers) > 0): ?>
                    <?php foreach ($buyers as $buyer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buyer['id']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['name']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['address']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['bank_id']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['bank_acc_no']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['gst_number']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['iec_number']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['drug_licence_number']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['vat_tin_number']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['pincode']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['city_id']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['state_id']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['country_id']); ?></td>
                            <td>
                                <!-- Form to delete buyer -->
                                <form method="POST" action="">
                                    <input type="hidden" name="buyer_id" value="<?php echo $buyer['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="14">No buyers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2024 Export Billing Software. All rights reserved.</p>
    </footer>
</body>
</html>
