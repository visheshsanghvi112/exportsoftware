<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Set an error message in the session
    $_SESSION['error'] = 'You need to log in to access this page.';
    header('Location: login.php'); // Redirect to login page
    exit();
}
?>
