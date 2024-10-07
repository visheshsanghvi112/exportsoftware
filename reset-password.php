<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

require_once 'database.php';

$error = '';
$success = '';

// Check if the identifier is provided
if (!isset($_GET['identifier']) || empty(trim($_GET['identifier']))) {
    $error = 'Invalid request.';
} else {
    $identifier = trim($_GET['identifier']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
        $newPassword = trim($_POST['new_password']);

        if (empty($newPassword)) {
            $error = 'New password is required.';
        } else {
            // Prepare the query
            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $query = "UPDATE users SET password = ? WHERE email = ?";
            } else {
                $query = "UPDATE users SET password = ? WHERE username = ?";
            }

            $stmt = $conn->prepare($query);

            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt->bind_param("ss", $hashedPassword, $identifier);
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt->bind_param("ss", $hashedPassword, $identifier);
            }

            if ($stmt->execute()) {
                $success = 'Password successfully updated.';
            } else {
                $error = 'Error updating password. Please try again.';
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
    <title>Reset Password</title>
    <style>
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
            
            font-family: 'Dancing Script', cursive;
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

        .btn {
            display: flex;
            justify-content: center;
            gap: 1em;
            margin-top: 1.5em;
        }

        .button1 {
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

        .button1:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
            font-family: 'Dancing Script', cursive;
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
</head>
<body>
    <div class="container">
        <div class="company-name">Export Software</div>
        <form class="form" action="reset-password.php?identifier=<?php echo urlencode($_GET['identifier']); ?>" method="post">
            <p id="heading">Reset Password</p>
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.516 10.667a3.5 3.5 0 1 1-5.033 0 3.5 3.5 0 0 1 5.033 0zM1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8z"/>
                </svg>
                <input class="input-field" type="password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="btn">
                <button class="button1" type="submit" name="reset_password">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
