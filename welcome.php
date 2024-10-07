<?php
require_once 'auth_check.php'; // Start the session
require_once 'database.php'; // Include the database connection
 
// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Personalized greeting based on the time of day
$hour = date('H');
$greeting = ($hour < 12) ? "Good Morning" : (($hour < 18) ? "Good Afternoon" : "Good Evening");

// Retrieve the total number of bills
$sqlTotalBills = "SELECT COUNT(*) AS total_bills FROM export_data"; // Corrected variable name
$resultTotalBills = $conn->query($sqlTotalBills);
$totalBills = $resultTotalBills ? $resultTotalBills->fetch_assoc()['total_bills'] : 0;

// Retrieve recent bills
$sqlRecentBills = "
    SELECT e.id, e.invoice_no, b.name
    FROM export_data e
    LEFT JOIN buyers b ON e.buyer_id = b.id
    ORDER BY e.id DESC
    LIMIT 5";
$resultRecentBills = $conn->query($sqlRecentBills);

$recentBills = [];
if ($resultRecentBills && $resultRecentBills->num_rows > 0) {
    while ($row = $resultRecentBills->fetch_assoc()) {
        $recentBills[] = $row;
    }
}

// Example data for pending bills
$pendingBillsCount = 3; // Replace with actual data retrieval

// Example data for profile picture
$profilePicturePath = 'assets/img/JOKER.jpg'; // Replace with actual path to user's profile picture

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Basic styles for layout */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            padding: 20px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
        }

        .action-card {
            background: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            flex: 1;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .action-card:hover {
            background: #0056b3;
        }

        .statistics {
            display: flex;
            gap: 20px;
        }

        .stat-card {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            flex: 1;
            text-align: center;
        }

        .recent-reports {
            margin: 20px 0;
        }

        .recent-reports h4 {
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .recent-reports table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .recent-reports table, .recent-reports th, .recent-reports td {
            border: 1px solid #ddd;
        }

        .recent-reports th, .recent-reports td {
            padding: 8px;
            text-align: left;
        }

        .recent-reports th {
            background-color: #f4f4f4;
        }

        .recent-reports td {
            background-color: #fff;
        }

        .recent-reports tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .recent-reports .view-all-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .recent-reports .view-all-button:hover {
            background-color: #0056b3;
        }

        .chart-container {
            margin-top: 20px;
        }

        /* Floating calculator styles */
        #calculator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: #333;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display: none; /* Initially hidden */
            color: #fff;
            text-align: center;
            box-sizing: border-box;
        }

        #calculator #close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
        }

        #display {
            width: 100%;
            height: 50px;
            margin-bottom: 15px;
            text-align: right;
            font-size: 24px;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background-color: #444;
            color: #fff;
            box-sizing: border-box;
        }

        #buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        #buttons button {
            width: 100%;
            height: 60px;
            font-size: 20px;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background-color: #555;
            color: #fff;
            transition: background-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        #buttons button:hover {
            background-color: #666;
        }

        #buttons button:active {
            background-color: #777;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.3);
        }

        #buttons .operator {
            background-color: #f39c12;
        }

        #buttons .operator:hover {
            background-color: #e67e22;
        }

        #buttons .operator:active {
            background-color: #d35400;
        }

        #buttons .equals {
            grid-column: span 2;
            background-color: #2ecc71;
        }

        #buttons .equals:hover {
            background-color: #27ae60;
        }

        #buttons .equals:active {
            background-color: #1e8449;
        }

        #buttons .clear {
            grid-column: span 2;
            background-color: #e74c3c;
        }

        #buttons .clear:hover {
            background-color: #c0392b;
        }

        #buttons .clear:active {
            background-color: #a93226;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $greeting . ', ' . htmlspecialchars($username); ?>!</h1>
        <nav>
            <ul>
                <li><a href="input.php">Create Export Bills</a></li>
                <li><a href="display_exportbills.php">View Bills</a></li>
                <li><a href="create_buyer.php">Create Buyer</a></li>
                <li><a href="manage_buyers.php">Manage Buyers</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="profile-card">
            <img src="<?php echo htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture">
            <div>
                <h3><?php echo htmlspecialchars($username); ?></h3>
                <p>Welcome back! Here’s a quick overview of your activities.</p>
            </div>
        </div>
        <div class="quick-actions">
            <div class="action-card">
                <a href="input.php">Create New Export</a>
            </div>
            <div class="action-card">
                <a href="generate_report.php" onclick="showLoading();">Generate Report</a>
            </div>
            <div class="action-card">
                <a href="manage_users.php">Manage Users</a>
            </div>
            <div class="action-card">
                <a href="add_product.php">Add New Product</a>
            </div>
        </div>
        <div class="statistics">
            <div class="stat-card">
                <h4>Total Exports</h4>
                <p><?php echo $totalBills; ?></p>
            </div>
            <div class="stat-card">
                <h4>Pending Bills</h4>
                <p><?php echo $pendingBillsCount; ?></p>
            </div>
        </div>
        <div class="recent-reports">
            <h4>Recent Reports</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>Buyer Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentBills)): ?>
                        <?php foreach ($recentBills as $bill): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bill['id']); ?></td>
                                <td><?php echo htmlspecialchars($bill['invoice_no']); ?></td>
                                <td><?php echo htmlspecialchars($bill['name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No recent reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="display_exportbills.php" class="view-all-button">View All Bills</a>
        </div>
        <div class="chart-container">
            <canvas id="exportChart"></canvas>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Export Billing Software. All rights reserved.</p>
    </footer>
    <div id="loading" style="display:none;">Loading...</div>
    <script>
    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }

    var ctx = document.getElementById('exportChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label: '2024 Sales',
                    data: [12, 19, 3, 5, 2, 3, 9],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: '2023 Sales',
                    data: [8, 15, 4, 7, 5, 8, 11],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    
<!-- Floating Calculator HTML -->
<div id="calculator">
    <button id="close-btn">X</button>
    <input type="text" id="display" disabled>
    <div id="buttons">
        <!-- Number and operator buttons for mouse input -->
        <button onclick="appendNumber(7)">7</button>
        <button onclick="appendNumber(8)">8</button>
        <button onclick="appendNumber(9)">9</button>
        <button onclick="appendOperation('/')" class="operator">/</button>
        <button onclick="appendNumber(4)">4</button>
        <button onclick="appendNumber(5)">5</button>
        <button onclick="appendNumber(6)">6</button>
        <button onclick="appendOperation('*')" class="operator">*</button>
        <button onclick="appendNumber(1)">1</button>
        <button onclick="appendNumber(2)">2</button>
        <button onclick="appendNumber(3)">3</button>
        <button onclick="appendOperation('-')" class="operator">-</button>
        <button onclick="appendNumber(0)">0</button>
        <button onclick="appendOperation('+')" class="operator">+</button>
        <button onclick="calculateResult()" class="equals">=</button>
        <button onclick="clearDisplay()" class="clear">C</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const calculator = document.getElementById('calculator');
        const closeBtn = document.getElementById('close-btn');
        const display = document.getElementById('display');

        /**
         * Toggles the visibility of the calculator
         */
        function toggleCalculator() {
            calculator.style.display = calculator.style.display === 'none' ? 'block' : 'none';
        }

        // Event listener to toggle calculator on F12
        document.addEventListener('keydown', (event) => {
            if (event.key === 'F12') {
                event.preventDefault(); // Prevent default F12 behavior (e.g., opening DevTools)
                toggleCalculator();
            }

            // Handle keyboard input for numbers, operators, and other keys
            handleKeyPress(event);
        });

        closeBtn.addEventListener('click', () => {
            calculator.style.display = 'none';
        });

        /**
         * Appends a number to the display
         * @param {number|string} number - The number or digit to append
         */
        window.appendNumber = function(number) {
            display.value += number;
        };

        /**
         * Appends an operator to the display with spacing
         * @param {string} operation - The operator to append (e.g., '+', '-', '*', '/')
         */
        window.appendOperation = function(operation) {
            display.value += ' ' + operation + ' ';
        };

        /**
         * Evaluates the current expression in the display and shows the result
         */
        window.calculateResult = function() {
            try {
                // Use eval to evaluate the math expression
                const result = eval(display.value);
                display.value = result;
            } catch (e) {
                display.value = 'Error'; // Handle errors, e.g., invalid expressions
            }
        };

        /**
         * Clears the display
         */
        window.clearDisplay = function() {
            display.value = '';
        };

        /**
         * Handles key press events for the calculator
         * @param {KeyboardEvent} event - The key event object
         */
        function handleKeyPress(event) {
            // Check if a number key is pressed (0-9)
            if (!isNaN(event.key)) {
                appendNumber(event.key); // Append the number to the display
            }

            // Check if an operator key is pressed (+, -, *, /)
            if (['+', '-', '*', '/'].includes(event.key)) {
                appendOperation(event.key); // Append the operator
            }

            // Handle 'Enter' key to calculate the result
            if (event.key === 'Enter') {
                calculateResult();
            }

            // Handle 'Escape' key to clear the display
            if (event.key === 'Escape') {
                clearDisplay();
            }

            // Optionally handle other keys like backspace to delete the last character
            if (event.key === 'Backspace') {
                display.value = display.value.slice(0, -1); // Remove the last character
            }
        }
    });
</script>
</body>
</html>
