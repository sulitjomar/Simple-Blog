<?php

include('../includes/db_config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['userid'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized: User not logged in']);
        exit;
    }

    $userId = $_SESSION['userid']; // Retrieve user_id from the session

    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert the post with the associated user_id
    $sql = "INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $userId);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Post created successfully'];
    } else {
        $response = ['success' => false, 'message' => 'Error creating post: ' . $stmt->error];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

$conn->close();

?>
