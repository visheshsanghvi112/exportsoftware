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

// Check if the user is admin
$sql = "SELECT is_admin FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $is_admin);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$is_admin) {
    echo "You do not have permission to manage users.";
    exit();
}

// Fetch users from the database
$sql = "SELECT id, username, email, is_admin FROM users";
$result = mysqli_query($conn, $sql);
if ($result) {
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $users = [];
}

// Handle form submission to add new user or assign admin role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete') {
            $userId = $_POST['user_id'];

            // Prevent deletion of the currently logged-in admin
            if ($userId == $_SESSION['user_id']) {
                echo "You cannot delete yourself!";
                exit();
            }

            // Delete user from the database
            $deleteSql = "DELETE FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header('Location: manage_users.php');
            exit();
        } elseif ($_POST['action'] === 'update_password') {
            $userId = $_POST['user_id'];
            $newPassword = $_POST['new_password'];

            // Update user password in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header('Location: manage_users.php');
            exit();
        } elseif ($_POST['action'] === 'assign_admin') {
            $userId = $_POST['user_id'];

            // Assign admin role to the user
            $updateSql = "UPDATE users SET is_admin = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header('Location: manage_users.php');
            exit();
        }
    } elseif (isset($_POST['new_username'])) {
        $newUsername = $_POST['new_username'];
        $newEmail = $_POST['new_email'];
        $newPassword = $_POST['new_password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insertSql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)";
        $stmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($stmt, "sss", $newUsername, $newEmail, $hashedPassword);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header('Location: manage_users.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        p {
            color: #333;
            font-size: 1.1em;
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

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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
                <li><a href="add_user.php">Add User</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Manage Users</h2>
        
        <!-- Form to add a new user -->
        <form method="POST" action="">
            <h3>Add New User</h3>
            <label for="new_username">Username:</label>
            <input type="text" id="new_username" name="new_username" required><br><br>
            <label for="new_email">Email:</label>
            <input type="email" id="new_email" name="new_email" required><br><br>
            <label for="new_password">Password:</label>
            <input type="password" id="new_password" name="new_password" required><br><br>
            <button type="submit">Add User</button>
        </form>
        
        <!-- User table -->
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                            <td>
                                <!-- Form to update user password -->
                                <form method="POST" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="update_password">
                                    <label for="new_password_<?php echo $user['id']; ?>">New Password:</label>
                                    <input type="password" id="new_password_<?php echo $user['id']; ?>" name="new_password" required>
                                    <button type="submit">Update Password</button>
                                </form>

                                <!-- Form to delete user (only if admin) -->
                                <?php if ($is_admin): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit">Delete</button>
                                    </form>
                                <?php endif; ?>

                                <!-- Form to assign admin role (only if current user is admin) -->
                                <?php if ($is_admin && !$user['is_admin']): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="assign_admin">
                                        <button type="submit">Assign Admin</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found</td>
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
