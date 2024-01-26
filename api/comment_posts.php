<?php

include('../includes/db_config.php');

// Assuming you're using $_POST to get the data
$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$comment = $_POST['comment'];

// Validate the data as needed

// Insert the comment into the database
$sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $post_id, $user_id, $comment);

$response = [];

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Comment added successfully';
} else {
    $response['success'] = false;
    $response['message'] = 'Error adding comment: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);

?>
