<?php
session_start(); // Start the session

// Include the database connection
require_once 'database.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Initialize variables for the form
$user = ['username' => '', 'email' => ''];
$error = '';

// Check if 'id' is set in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Prepare and execute the query to fetch user data
    $sql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $error = "User not found.";
    }
    $stmt->close();
} else {
    $error = "Invalid user ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        /* Include your styles here */
        body, h1, p {
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
        }
        header h1 {
            margin-bottom: 10px;
            font-size: 2.5em;
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
        }
        nav ul li a:hover {
            background-color: #575757;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #f1f1f1;
            padding: 15px 0;
            text-align: center;
            color: #555;
            font-size: 0.9em;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit User</h1>
        <nav>
            <ul>
                <li><a href="export_form.php">Create Export</a></li>
                <li><a href="exportbill.php">View Export Bills</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="update_user.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userId); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank if not changing">

            <button type="submit">Update User</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Export Billing Software. All rights reserved.</p>
    </footer>
</body>
</html>
