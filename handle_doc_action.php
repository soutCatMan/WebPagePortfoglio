<?php
session_start();
require 'includes/db_connect.php';

header('Content-Type: application/json'); // Respond with JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['action'])) {
    $doc_id = intval($_POST['id']);
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];
    $response = ['success' => false, 'message' => 'Invalid action.'];

    switch ($action) {
        case 'delete':
            // Soft delete: sets deleted = 1, also ensure not archived or favorited in main view
            $stmt = $conn->prepare("UPDATE documents SET deleted = 1, archived = 0, favorite = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $doc_id, $user_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = ['success' => true, 'message' => 'Document moved to trash successfully!'];
                } else {
                    $response = ['success' => false, 'message' => 'Document not found or already in trash.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Error moving document to trash: ' . $stmt->error];
            }
            $stmt->close();
            break;

        case 'archive':
            // Archive: sets archived = 1, also ensure not deleted or favorited in main view
            $stmt = $conn->prepare("UPDATE documents SET archived = 1, deleted = 0, favorite = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $doc_id, $user_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = ['success' => true, 'message' => 'Document archived successfully!'];
                } else {
                    $response = ['success' => false, 'message' => 'Document not found or already archived.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Error archiving document: ' . $stmt->error];
            }
            $stmt->close();
            break;

        case 'favorite':
            // Toggle favorite: sets favorite = state, only if not deleted or archived
            $state = isset($_POST['state']) && $_POST['state'] === '1' ? 1 : 0; // 1 for favorite, 0 for unfavorite
            $stmt = $conn->prepare("UPDATE documents SET favorite = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ? AND deleted = 0 AND archived = 0");
            $stmt->bind_param("iii", $state, $doc_id, $user_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $action_word = ($state == 1) ? 'favorited' : 'unfavorited';
                    $response = ['success' => true, 'message' => "Document {$action_word} successfully!", 'new_favorite_state' => $state];
                } else {
                    $response = ['success' => false, 'message' => 'Document not found, already in trash/archive, or no change.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Error changing favorite status: ' . $stmt->error];
            }
            $stmt->close();
            break;

        default:
            // Handled by initial response
            break;
    }

    echo json_encode($response);
    $conn->close();
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
    $conn->close();
    exit();
}