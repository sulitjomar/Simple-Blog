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

    $postId = $_POST['id'];
    $userId = $_SESSION['userid'];

    // Check if the user is the author of the post
    $checkAuthorSql = "SELECT user_id FROM posts WHERE id = ?";
    $checkAuthorStmt = $conn->prepare($checkAuthorSql);
    $checkAuthorStmt->bind_param("i", $postId);
    $checkAuthorStmt->execute();
    $checkAuthorStmt->bind_result($postAuthorId);
    $checkAuthorStmt->fetch();
    $checkAuthorStmt->close();

    if ($postAuthorId !== $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: User is not the author of the post']);
        exit;
    }

    // Delete related comments first
    $deleteCommentsSql = "DELETE FROM comments WHERE post_id = ?";
    $deleteCommentsStmt = $conn->prepare($deleteCommentsSql);
    $deleteCommentsStmt->bind_param("i", $postId);
    $deleteCommentsStmt->execute();
    $deleteCommentsStmt->close();

    // Proceed with deleting the post
    $deletePostSql = "DELETE FROM posts WHERE id = ?";
    $deletePostStmt = $conn->prepare($deletePostSql);
    $deletePostStmt->bind_param("i", $postId);

    if ($deletePostStmt->execute()) {
        $response = ['success' => true, 'message' => 'Post and related comments deleted successfully'];
    } else {
        $response = ['success' => false, 'message' => 'Error deleting post'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

$conn->close();

?>
