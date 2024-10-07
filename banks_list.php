<?php
require_once 'auth_check.php';
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='alert error'>Connection failed: " . $conn->connect_error . "</div>");
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM banks WHERE id=$id")) {
        echo "<div class='alert success'>Record deleted successfully.</div>";
    } else {
        echo "<div class='alert error'>Error deleting record: " . $conn->error . "</div>";
    }
}

// Handle update request
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $location = $conn->real_escape_string($_POST['location']);
    $established_year = intval($_POST['established_year']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($conn->query("UPDATE banks SET name='$name', location='$location', established_year=$established_year, is_active=$is_active WHERE id=$id")) {
        echo "<div class='alert success'>Record updated successfully.</div>";
    } else {
        echo "<div class='alert error'>Error updating record: " . $conn->error . "</div>";
    }
}

// Handle add request
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $location = $conn->real_escape_string($_POST['location']);
    $established_year = intval($_POST['established_year']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($conn->query("INSERT INTO banks (name, location, established_year, is_active) VALUES ('$name', '$location', $established_year, $is_active)")) {
        echo "<div class='alert success'>Record added successfully.</div>";
    } else {
        echo "<div class='alert error'>Error adding record: " . $conn->error . "</div>";
    }
}

// Retrieve records
$sql = "SELECT * FROM banks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banks List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #e9ecef;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a {
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            color: #fff;
            transition: background 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .actions .edit {
            background: #28a745;
        }
        .actions .edit:hover {
            background: #218838;
        }
        .actions .delete {
            background: #dc3545;
        }
        .actions .delete:hover {
            background: #c82333;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .form-container h2 {
            margin-top: 0;
            color: #333;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="checkbox"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .form-container input[type="submit"]:hover {
            background: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        .alert.success {
            background-color: #28a745;
            color: #fff;
        }
        .alert.error {
            background-color: #dc3545;
            color: #fff;
        }
        .form-container .form-group {
            margin-bottom: 15px;
        }
        .form-container .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        .btn-add {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            display: block;
            text-align: center;
            transition: background 0.3s;
        }
        .btn-add:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Banks List</h1>

        <a href="?action=add" class="btn-add">Add New Bank</a>
        
        <!-- Display table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Established Year</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['established_year']); ?></td>
                    <td><?php echo $row['is_active'] ? 'Yes' : 'No'; ?></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $row['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fas fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php
        // Edit form
        if (isset($_GET['edit'])) {
            $id = intval($_GET['edit']);
            $result = $conn->query("SELECT * FROM banks WHERE id=$id");
            $row = $result->fetch_assoc();
        ?>
        <div class="form-container">
            <h2>Edit Record</h2>
            <form action="" method="POST" id="editForm">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($row['location']); ?>">
                </div>
                <div class="form-group">
                    <label for="established_year">Established Year:</label>
                    <input type="number" id="established_year" name="established_year" value="<?php echo htmlspecialchars($row['established_year']); ?>">
                </div>
                <div class="form-group">
                    <label for="is_active">Active:</label>
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo $row['is_active'] ? 'checked' : ''; ?>>
                </div>
                <input type="submit" name="update" value="Update">
            </form>
        </div>
        <?php
        }
        ?>

        <?php
        // Add form
        if (isset($_GET['action']) && $_GET['action'] == 'add') {
        ?>
        <div class="form-container">
            <h2>Add New Bank</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location">
                </div>
                <div class="form-group">
                    <label for="established_year">Established Year:</label>
                    <input type="number" id="established_year" name="established_year">
                </div>
                <div class="form-group">
                    <label for="is_active">Active:</label>
                    <input type="checkbox" id="is_active" name="is_active" value="1">
                </div>
                <input type="submit" name="add" value="Add Bank">
            </form>
        </div>
        <?php
        }
        ?>

        <!-- JavaScript for form validation and alert messages -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('editForm');
                if (form) {
                    form.addEventListener('submit', function(event) {
                        const nameInput = form.querySelector('input[name="name"]');
                        if (nameInput.value.trim() === '') {
                            showAlert('Name field cannot be empty.', 'error');
                            event.preventDefault();
                        }
                    });
                }
            });

            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert ${type}`;
                alertDiv.textContent = message;
                document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
                setTimeout(() => alertDiv.remove(), 3000);
            }
        </script>
    </div>
</body>
</html>
