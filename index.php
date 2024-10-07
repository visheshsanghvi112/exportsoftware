<?php
// Start the session securely
session_start([
    'cookie_lifetime' => 0, // Session cookie expires when the browser is closed
    'cookie_secure' => true, // Only send cookies over HTTPS
    'cookie_httponly' => true, // Prevent JavaScript access to cookies
    'cookie_samesite' => 'Strict' // Prevent CSRF attacks
]);

// Optionally, you can include a CSRF token generation here
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure CSRF token
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Export Billing Software</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
    body {
        background-image: url('6269876.jpg'); /* Use the correct path to your image */
        background-size: cover;
        background-position: center;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
        overflow: hidden;
    }

    .container {
        text-align: center;
        background: rgba(255, 255, 255, 0.9); /* Slightly more opaque for better contrast */
        padding: 40px 20px; /* Added padding for better spacing */
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Increased shadow for depth */
        position: relative;
        animation: fadeIn 1s ease-in-out;
        max-width: 600px; /* Limit max width for better readability */
    }

    .welcome-message {
        margin-bottom: 25px;
        font-size: 2.5rem;
        color: #007bff;
        font-weight: bold; /* Added bold for emphasis */
    }

    .lead {
        margin-bottom: 25px;
        font-size: 1.5rem;
        color: #555;
        line-height: 1.6; /* Improved line height for readability */
    }

    .btn {
        margin: 15px;
        padding: 12px 24px;
        font-size: 1.2rem;
        border-radius: 30px;
        border: none; /* Removed default border */
        color: white; /* Set text color to white for better contrast */
        cursor: pointer; /* Pointer cursor for buttons */
        transition: transform 0.3s, background-color 0.3s;
    }

    .btn-primary {
        background-color: #007bff; /* Primary button color */
    }

    .btn-secondary {
        background-color: #6c757d; /* Secondary button color */
    }

    .btn-primary:hover {
        transform: scale(1.05);
        background-color: #0056b3;
    }

    .btn-secondary:hover {
        transform: scale(1.05);
        background-color: #5a6268;
    }

    .video-container {
        margin: 30px 0;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* Increased shadow for depth */
    }

    .video-container video {
        width: 100%;
        max-width: 500px;
        height: auto;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Darker overlay for better visibility */
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .video-container:hover .overlay {
        opacity: 1;
    }

    .attribution {
        margin-top: 30px;
        font-size: 0.9rem;
        color: #777;
        line-height: 1.5; /* Improved line height for readability */
    }

    .attribution a {
        color: #007bff;
        text-decoration: none;
    }

    .attribution a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    button.style.backgroundColor = '#28a745';
                    button.style.color = '#fff';
                    setTimeout(() => {
                        button.style.backgroundColor = '';
                        button.style.color = '';
                    }, 300);
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="welcome-message">Welcome to Export Billing Software</h1>
        <p class="lead">Please log in or register to continue.</p>
        
        <div class="video-container">
            <video autoplay loop muted playsinline>
                <source src="assets/img/gif.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="overlay">Enjoy the Experience</div>
        </div>

        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="signup.php" class="btn btn-secondary">Register</a>

        <!-- Optional: Add CSRF Token as a hidden input field for forms -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    </div>
</body>
</html>
