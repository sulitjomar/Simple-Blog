<?php

include('../includes/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['comment_id']; // Assuming you're sending comment_id in your AJAX request
    $updatedComment = $_POST['updated_comment'];

    // Check if the user is allowed to update the comment (you can add additional checks)
    // For example, you may want to check if the user is the author of the comment

    // Proceed with updating the comment
    $updateSql = "UPDATE comments SET comment = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $updatedComment, $commentId);

    if ($updateStmt->execute()) {
        $response = ['success' => true, 'message' => 'Comment updated successfully'];
    } else {
        $response = ['success' => false, 'message' => 'Error updating comment'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

$conn->close();

?>
