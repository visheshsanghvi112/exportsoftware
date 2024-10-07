<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session cookie securely
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        true, // Secure flag - only send cookie over HTTPS
        true  // HttpOnly flag - prevent JavaScript access
    );
}

// Destroy the session
session_destroy();

// Generate a new session ID (defensive measure)
session_start();
session_regenerate_id(true); // Generate a new session ID to prevent session fixation

// Ensure the session is clean by reinitializing it
$_SESSION = [];

// Redirect to the login page
header('Location: login.php');
exit();
?>
