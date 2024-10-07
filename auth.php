<?php
require_once 'database.php';

function authenticateUser($username, $password, $conn) {
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        return password_verify($password, $hashedPassword);
    } else {
        return false;
    }
}
?>
