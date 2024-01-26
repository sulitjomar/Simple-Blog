<?php

// Start or resume the session
session_start();

function getCurrentUserInfo() {
    // Return user information or false if not logged in
    if (isset($_SESSION['username']) && isset($_SESSION['userid'])) {
        return [
            'username' => $_SESSION['username'],
            'userid' => $_SESSION['userid']
        ];
    } else {
        return false;
    }
}

// Get current user information
$currentUserInfo = getCurrentUserInfo();

if (!$currentUserInfo) {
    // Handle the case where the user is not logged in
    $response = ['success' => false, 'message' => 'User not logged in'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution
}

// Get the username of the logged-in user
$currentUserUsername = $_SESSION['username'];
$currentUserId = $_SESSION['userid'];

include('../includes/db_config.php');

$sql = "SELECT posts.*, users.username
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id
        ORDER BY posts.id DESC";

$result = $conn->query($sql);

$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['current_user_username'] = $currentUserUsername;
        $row['current_user_id'] = $currentUserId;
        $posts[] = $row;
    }
}

$response = ['success' => true, 'posts' => $posts];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();

?>