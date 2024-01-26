<?php

// Start the session
session_start();

// Check if the request is a GET request and the action is 'logout'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session
    session_destroy();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logout successful']);
} else {
    // Send an error response for invalid requests
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid request']);
}

?>