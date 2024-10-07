<?php
require_once 'database.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $email = trim($_POST['email']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    // Validate input
    if (empty($username) || empty($password) || empty($email)) {
        $error = 'Username, password, and email are required.';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $error = 'Username already exists. Please choose a different username.';
        } else {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $password, $email, $firstName, $lastName);

            // Execute and check for errors
            if ($stmt->execute()) {
                $success = 'Registration successful. You can now <a href="login.php">login</a>.';
            } else {
                $error = 'Registration failed: ' . $conn->error;
            }

            $stmt->close();
        }
    }
    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        /* Import a calligraphic font */
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap');

        /* Overall body styling to center content */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #8e0e00, #1f1c18);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 500px;
            padding: 2em;
            box-sizing: border-box;
            border-radius: 25px;
            background-color: #171717;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5), 
                        0 0 25px rgba(255, 0, 0, 0.3);
        }

        h2 {
            color: #fff;
            margin-bottom: 1em;
            font-family: 'Great Vibes', cursive;
        }

        .form-group {
            margin-bottom: 1em;
            width: 100%;
        }

        .form-group label {
            display: block;
            color: #fff;
            margin-bottom: 0.5em;
        }

        .form-group input {
            width: 100%;
            padding: 0.75em;
            border-radius: 25px;
            border: none;
            background-color: #1f1f1f;
            color: #d3d3d3;
            box-shadow: inset 2px 5px 10px rgba(0, 0, 0, 0.5);
            font-size: 1em;
        }

        .form-group input:focus {
            border: 2px solid #ff0000;
            outline: none;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 1.5em;
        }

        .btn-group button, .btn-group a {
            padding: 0.75em 1.5em;
            border-radius: 5px;
            border: none;
            outline: none;
            transition: .4s ease-in-out;
            background-color: #252525;
            color: #fff;
            font-size: 1em;
            font-weight: bold;
            margin: 0.5em 0;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-group button:hover, .btn-group a:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .error, .success {
            font-size: 0.9em;
            padding: 0.5em;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            margin-bottom: 1em;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 1em;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            margin-bottom: 1em;
            font-size: 1em;
            transition: opacity 0.5s ease-in-out;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="post" action="signup.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name">
            </div>
            <div class="btn-group">
                <button type="submit">Sign Up</button>
                <a href="login.php">Login</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get success message element
            var successMessage = document.querySelector('.success');

            // Check if the success message exists
            if (successMessage) {
                // Hide the success message after 5 seconds
                setTimeout(function() {
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 500); // Match the CSS transition duration
                }, 5000); // 5 seconds
            }
        });
    </script>
</body>
</html>
