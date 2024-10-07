<?php
// routes.php

// Get the current path
$request = $_SERVER['REQUEST_URI'];

// Remove query string if it exists
$request = explode('?', $request, 2)[0];

// Define routes
switch ($request) {
    case '/':
   

     

     

    case '/login':
    case '/login.php':
        require __DIR__ . '/login.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}
