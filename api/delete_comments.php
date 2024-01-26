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

    $commentId = $_POST['comment_id'];
    $userId = $_SESSION['userid'];

    // Check if the user is the author of the comment
    $checkAuthorSql = "SELECT user_id FROM comments WHERE id = ?";
    $checkAuthorStmt = $conn->prepare($checkAuthorSql);
    $checkAuthorStmt->bind_param("i", $commentId);
    $checkAuthorStmt->execute();
    $checkAuthorStmt->bind_result($commentAuthorId);
    $checkAuthorStmt->fetch();
    $checkAuthorStmt->close();

    if ($commentAuthorId !== $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: User is not the author of the comment']);
        exit;
    }

    // Proceed with deleting the comment
    $deleteSql = "DELETE FROM comments WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $commentId);

    if ($deleteStmt->execute()) {
        $response = ['success' => true, 'message' => 'Comment deleted successfully'];
    } else {
        $response = ['success' => false, 'message' => 'Error deleting comment'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

$conn->close();

?>
