<?php

include('../includes/db_config.php');

// Function to authenticate user and start a session upon successful login
function authenticate($conn, $username, $password) {
    // Prepare and execute a parameterized query to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Start a session and store user information
        session_start();
        $row = $result->fetch_assoc();
        
        $_SESSION['username'] = $username;
        $_SESSION['userid'] = $row['id'];

        return true;
    } else {
        return false;
    }
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user credentials from the request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password (use a secure hashing algorithm in a real-world scenario)
    $hashedPassword = md5($password);

    // Authenticate the user and start a session upon successful login
    $authenticated = authenticate($conn, $username, $hashedPassword);

    // Send JSON response
    header('Content-Type: application/json');

    if ($authenticated) {
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }

    // Close the database connection
    $conn->close();
} else {
    // Send an error response for non-POST requests
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method Not Allowed']);
}

?>
