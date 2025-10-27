<?php
session_start();
require 'includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $doc_id = intval($_GET['id']);
    $type = $_GET['type']; // 'archived' or 'deleted' - used for reference but not in query
    $user_id = $_SESSION['user_id'];

    $response = ['success' => false, 'message' => 'Invalid restore type.'];

    // For restoring, set both 'archived' and 'deleted' flags to 0
    // and set 'favorite' to 0 (can be re-favorited later if needed)
    // Restore also updates updated_at, moving it to top of regular list
    $stmt = $conn->prepare("UPDATE documents SET archived = 0, deleted = 0, favorite = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $doc_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => 'Document restored successfully!', 'doc_id' => $doc_id];
        } else {
            $response = ['success' => false, 'message' => 'Document not found or already restored.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Error restoring document: ' . $stmt->error];
    }
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
    $conn->close();
    exit();
}