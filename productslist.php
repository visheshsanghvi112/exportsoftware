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

// Initialize search query and sorting options
$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$sort_column = isset($_POST['sort_column']) ? mysqli_real_escape_string($conn, $_POST['sort_column']) : 'brand_name';
$sort_order = isset($_POST['sort_order']) ? mysqli_real_escape_string($conn, $_POST['sort_order']) : 'ASC';

// Validate sort column and order
$allowed_columns = ['brand_name', 'composition', 'type', 'packing', 'case_qty', 'manufacturing_date', 'expiry_date', 'batch_no', 'hsn_code', 'rate', 'currency', 'gst_rate'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'brand_name';
}
$sort_order = ($sort_order === 'DESC') ? 'DESC' : 'ASC';

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo '<p class="message">Product deleted successfully.</p>';
    } else {
        echo '<p class="message">Error deleting product.</p>';
    }
}

// Fetch products from the database with search and sorting functionality
$sql = "SELECT id, brand_name, composition, type, packing, case_qty, manufacturing_date, expiry_date, batch_no, hsn_code, rate, currency, gst_rate 
        FROM products 
        WHERE brand_name LIKE '%$search%' 
           OR composition LIKE '%$search%' 
           OR type LIKE '%$search%' 
           OR packing LIKE '%$search%' 
           OR batch_no LIKE '%$search%' 
           OR hsn_code LIKE '%$search%'
        ORDER BY $sort_column $sort_order";
$result = mysqli_query($conn, $sql);

if ($result) {
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $products = [];
    // Optionally, add error handling here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        form select {
            padding: 10px;
            width: 100%;
            max-width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 10px;
            font-size: 16px;
            background-color: #f9f9f9;
            color: #333;
            cursor: pointer;
        }

        form select option {
            padding: 10px;
            background-color: #fff;
            color: #333;
        }

        form select:hover {
            border-color: #007bff;
        }

        form select:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
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

        .message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }

        .edit-btn, .delete-btn {
            color: #007bff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s, color 0.3s;
        }

        .edit-btn {
            background-color: #e7f0ff;
        }

        .edit-btn:hover {
            background-color: #c6e0ff;
        }

        .delete-btn {
            color: #dc3545;
            background-color: #f8d7da;
        }

        .delete-btn:hover {
            background-color: #f5c6cb;
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
        <h2>Product List</h2>

        <form method="post" action="">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="sort_column">
                <option value="brand_name" <?php if ($sort_column == 'brand_name') echo 'selected'; ?>>Brand Name</option>
                <option value="composition" <?php if ($sort_column == 'composition') echo 'selected'; ?>>Composition</option>
                <option value="type" <?php if ($sort_column == 'type') echo 'selected'; ?>>Type</option>
                <option value="packing" <?php if ($sort_column == 'packing') echo 'selected'; ?>>Packing</option>
                <option value="case_qty" <?php if ($sort_column == 'case_qty') echo 'selected'; ?>>Case Quantity</option>
                <option value="manufacturing_date" <?php if ($sort_column == 'manufacturing_date') echo 'selected'; ?>>Manufacturing Date</option>
                <option value="expiry_date" <?php if ($sort_column == 'expiry_date') echo 'selected'; ?>>Expiry Date</option>
                <option value="batch_no" <?php if ($sort_column == 'batch_no') echo 'selected'; ?>>Batch No</option>
                <option value="hsn_code" <?php if ($sort_column == 'hsn_code') echo 'selected'; ?>>HSN Code</option>
                <option value="rate" <?php if ($sort_column == 'rate') echo 'selected'; ?>>Rate</option>
                <option value="currency" <?php if ($sort_column == 'currency') echo 'selected'; ?>>Currency</option>
                <option value="gst_rate" <?php if ($sort_column == 'gst_rate') echo 'selected'; ?>>GST Rate</option>
            </select>
            <select name="sort_order">
                <option value="ASC" <?php if ($sort_order == 'ASC') echo 'selected'; ?>>Ascending</option>
                <option value="DESC" <?php if ($sort_order == 'DESC') echo 'selected'; ?>>Descending</option>
            </select>
            <input type="submit" value="Search and Sort">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Brand Name</th>
                    <th>Composition</th>
                    <th>Type</th>
                    <th>Packing</th>
                    <th>Case Quantity</th>
                    <th>Manufacturing Date</th>
                    <th>Expiry Date</th>
                    <th>Batch No</th>
                    <th>HSN Code</th>
                    <th>Rate</th>
                    <th>Currency</th>
                    <th>GST Rate</th>
                    <th>Action</th> <!-- New column for action buttons -->
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['composition']); ?></td>
                            <td><?php echo htmlspecialchars($product['type']); ?></td>
                            <td><?php echo htmlspecialchars($product['packing']); ?></td>
                            <td><?php echo htmlspecialchars($product['case_qty']); ?></td>
                            <td><?php echo htmlspecialchars($product['manufacturing_date']); ?></td>
                            <td><?php echo htmlspecialchars($product['expiry_date']); ?></td>
                            <td><?php echo htmlspecialchars($product['batch_no']); ?></td>
                            <td><?php echo htmlspecialchars($product['hsn_code']); ?></td>
                            <td><?php echo htmlspecialchars($product['rate']); ?></td>
                            <td><?php echo htmlspecialchars($product['currency']); ?></td>
                            <td><?php echo htmlspecialchars($product['gst_rate']); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="edit-btn">Edit</a> 
                                <a href="?delete_id=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td> <!-- Edit and Delete links -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13">No products found</td>
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
