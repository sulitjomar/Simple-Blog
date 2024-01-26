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

include('../includes/db_config.php');

$sql = "SELECT comments.*, users.username AS commenter_username
        FROM comments
        LEFT JOIN users ON comments.user_id = users.id
        ORDER BY comments.id DESC";

$result = $conn->query($sql);

$comments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['current_user_id'] = $currentUserInfo['userid'];
        $row['current_user_username'] = $currentUserInfo['username'];
        $comments[] = $row;
    }
    $response = ['success' => true, 'comments' => $comments];
} else {
    $response = ['success' => false, 'message' => 'No comments found'];
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();

?>
