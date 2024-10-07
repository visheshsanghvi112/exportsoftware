<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // Set domain if needed
    'secure' => true, // Ensure HTTPS
    'httponly' => true, // Prevent JavaScript access
    'samesite' => 'Strict' // Prevent CSRF
]);
session_start(); // Start the session to manage login state

// Include the database connection
require_once 'database.php'; // Adjust path if needed

// Initialize variables for error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['remember_me']);

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashedPassword);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                $_SESSION['username'] = $username;

                if ($rememberMe) {
                    // Set cookie to remember user for 30 days
                    setcookie('username', $username, time() + (30 * 24 * 60 * 60), "/", "", true, true);
                }

                // Redirect to welcome.php
                header('Location: welcome.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }

        $stmt->close();
    }
    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
            max-width: 500px;
            padding: 2em;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
            border-radius: 25px;
            background-color: #171717;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5), 
                        0 0 25px rgba(255, 0, 0, 0.3);
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 2em;
            border-radius: 25px;
            transition: .4s ease-in-out;
        }

        .form:hover {
            transform: scale(1.03);
            border: 1px solid #444;
        }

        #heading {
            text-align: center;
            margin: 0;
            color: #fff;
            font-size: 2em;
        }

        .field {
            display: flex;
            align-items: center;
            gap: 0.5em;
            border-radius: 25px;
            padding: 0.6em;
            border: none;
            background-color: #1f1f1f;
            box-shadow: inset 2px 5px 10px rgba(0, 0, 0, 0.5);
        }

        .input-icon {
            height: 1.5em;
            width: 1.5em;
            fill: #fff;
        }

        .input-field {
            background: none;
            border: none;
            width: 100%;
            color: #d3d3d3;
            padding: 0.5em;
            font-size: 1em;
        }

        .input-field:focus {
            border: 2px solid #ff0000;
            outline: none;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5em;
            margin: 1em 0;
            color: #d3d3d3;
        }

        .btn {
            display: flex;
            justify-content: center;
            gap: 1em;
            margin-top: 1.5em;
        }

        .button1, .button2, .button3 {
            padding: 0.75em 1.5em;
            border-radius: 5px;
            border: none;
            outline: none;
            transition: .4s ease-in-out;
            background-color: #252525;
            color: #fff;
            font-size: 1em;
            font-weight: bold;
        }

        .button1:hover, .button2:hover, .button3:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .button3 {
            margin-top: 1em;
            background-color: #252525;
        }

        .button3:hover {
            background-color: #f00;
        }

        .error, .success {
            font-size: 0.9em;
            padding: 0.5em;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .company-name {
            text-align: center;
            color: #fff;
            font-size: 2.5em;
            margin-bottom: 1em;
            font-family: 'Great Vibes', cursive;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 1em;
            }

            .form {
                padding: 1em;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on the username input field when the page loads
            const usernameInput = document.querySelector('input[name="username"]');
            usernameInput.focus();

            // Handle the Enter key for form navigation
            const formElements = document.querySelectorAll('.input-field, .button1');
            formElements.forEach((element, index) => {
                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Prevent form submission
                        if (index < formElements.length - 1) {
                            formElements[index + 1].focus();
                        } else {
                            formElements[index].click();
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="company-name">Export Software</div>
        <form class="form" action="login.php" method="post">
            <p id="heading">Login</p>
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.924 0 1.467.708 1.467 1.895 0 1.187-.556 1.913-1.48 1.913-.879 0-1.443-.716-1.443-1.901z"/>
                </svg>
                <input class="input-field" type="text" name="username" placeholder="Username" autofocus>
            </div>
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.516 10.667a3.5 3.5 0 1 1-5.033 0 3.5 3.5 0 0 1 5.033 0zM1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8z"/>
                </svg>
                <input class="input-field" type="password" name="password" placeholder="Password">
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="remember_me" id="remember_me">
                <label for="remember_me">Remember Me</label>
            </div>
            <div class="btn">
                <button class="button1" type="submit" name="login">Login</button>
                <button class="button2" type="button" onclick="window.location.href='signup.php'">Register</button>
            </div>
        </form>
        <button class="button3" onclick="window.location.href='forgot-password.php'">Forgot Password</button>
    </div>
</body>
</html>
