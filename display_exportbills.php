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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Bills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 0.75rem;
        }
        th {
            background: linear-gradient(135deg, #add8e6, #87ceeb); /* Light blue gradient */
            color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        tbody tr:nth-child(even) {
            background-color: #e9f5fe;
        }
        tbody tr:hover {
            background-color: #cfe2f3;
            transition: background-color 0.3s ease-in-out;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .delete-btn, .edit-btn {
            background-color: #007bff;
            border: none;
            color: #ffffff;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s ease-in-out;
        }
        .delete-btn:hover, .edit-btn:hover {
            background-color: #0056b3;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        /* Header Styles */
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
        
        /* Footer Styles */
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

        /* Responsive Adjustments */
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <nav>
            <ul>
            <li><a href="welcome.php">Home</a></li>
            <li><a href="input.php">Create Export</a></li>
                <li><a href="display_exportbills.php">View Export Bills</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container wow fadeIn" data-wow-duration="2s">
        <h1 class="text-center mb-4">Export Bills</h1>

        <!-- Search Bar -->
        <div class="search-bar text-center">
            <input type="text" id="searchInput" class="form-control w-50 mx-auto" placeholder="Search records...">
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

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

        // Handle deletion
        if (isset($_GET['delete_id'])) {
            $id = $_GET['delete_id'];
            $sql = "DELETE FROM export_data WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Record deleted successfully</div>";
            } else {
                echo "<div class='alert alert-danger'>Error deleting record: " . $stmt->error . "</div>";
            }
            $stmt->close();
        }

        // Fetch data from export_data table
        $sql = "SELECT * FROM export_data";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier ID</th>
                        <th>Buyer ID</th>
                        <th>Consignee ID</th>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Manufacturing Date</th>
                        <th>Expiry Date</th>
                        <th>Batch No</th>
                        <th>Carriage</th>
                        <th>Place of Receipt</th>
                        <th>Port of Loading</th>
                        <th>Port of Discharge</th>
                        <th>Final Destination</th>
                        <th>Terms of Delivery</th>
                        <th>Product ID</th>
                        <th>Total Amount</th>
                        <th>IGST Amount</th>
                        <th>Container Length</th>
                        <th>Container Width</th>
                        <th>Container Height</th>
                        <th>Container Weight Net</th>
                        <th>Container Weight Gross</th>
                        <th>Container Other</th>
                        <th>Bank Name</th>
                        <th>IFSC Code</th>
                        <th>GSTIN</th>
                        <th>VAT/TIN</th>
                        <th>IEC Code</th>
                        <th>Bank Account No</th>
                        <th>End User Code</th>
                        <th>Destination</th>
                        <th>Drug License No</th>
                        <th>Actions</th>
                    </tr>
                  </thead>";
            echo "<tbody id='tableBody'>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["id"]."</td>
                        <td>".$row["supplier_id"]."</td>
                        <td>".$row["buyer_id"]."</td>
                        <td>".$row["consignee_id"]."</td>
                        <td>".$row["invoice_no"]."</td>
                        <td>".$row["invoice_date"]."</td>
                        <td>".$row["manufacturing_date"]."</td>
                        <td>".$row["expiry_date"]."</td>
                        <td>".$row["batch_no"]."</td>
                        <td>".$row["carriage"]."</td>
                        <td>".$row["place_of_receipt"]."</td>
                        <td>".$row["port_of_loading"]."</td>
                        <td>".$row["port_of_discharge"]."</td>
                        <td>".$row["final_destination"]."</td>
                        <td>".$row["terms_of_delivery"]."</td>
                        <td>".$row["product_id"]."</td>
                        <td>".$row["total_amount"]."</td>
                        <td>".$row["igst_amount"]."</td>
                        <td>".$row["container_length"]."</td>
                        <td>".$row["container_width"]."</td>
                        <td>".$row["container_height"]."</td>
                        <td>".$row["container_weight_net"]."</td>
                        <td>".$row["container_weight_gross"]."</td>
                        <td>".$row["container_other"]."</td>
                        <td>".$row["bank_name"]."</td>
                        <td>".$row["ifsc_code"]."</td>
                        <td>".$row["gstin"]."</td>
                        <td>".$row["vat_tin"]."</td>
                        <td>".$row["iec_code"]."</td>
                        <td>".$row["bank_account_no"]."</td>
                        <td>".$row["end_user_code"]."</td>
                        <td>".$row["destination"]."</td>
                        <td>".$row["drug_license_no"]."</td>
                        <td class='actions'>
                            <a href='display_exportbills.php?delete_id=".$row["id"]."' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                            <a href='edit_exportbill.php?id=".$row["id"]."' class='edit-btn'>Edit</a>
                        </td>
                      </tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning'>No results found</div>";
        }

        $conn->close();
        ?>

        <script>
            // Show loading spinner when the page is loading
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('loadingSpinner').style.display = 'none';
            });

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var rows = document.querySelectorAll('#tableBody tr');
                rows.forEach(function(row) {
                    var cells = row.getElementsByTagName('td');
                    var match = Array.from(cells).some(function(cell) {
                        return cell.textContent.toLowerCase().includes(filter);
                    });
                    row.style.display = match ? '' : 'none';
                });
            });
        </script>
    </div>

    <footer>
        <p>&copy; 2024 Export Software. All rights reserved.</p>
    </footer>
</body>
</html>
