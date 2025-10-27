<?php
session_start();
require 'includes/db_connect.php';

header('Content-Type: application/json'); // Respond with JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['doc_title']) && isset($_POST['doc_content'])) {
    $doc_id = intval($_POST['id']);
    $doc_title = $_POST['doc_title'];
    $doc_content = $_POST['doc_content'];
    $user_id = $_SESSION['user_id'];

    if (empty($doc_title)) {
        $doc_title = "Untitled";
    }

    // Update `documents` table, set `title`, `content`, `updated_at`. `id` is the PK.
    // Also ensure it's not deleted or archived to allow saving
    $stmt = $conn->prepare("UPDATE documents SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ? AND deleted = 0 AND archived = 0");
    $stmt->bind_param("ssii", $doc_title, $doc_content, $doc_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $message = 'Document saved successfully!';
            $success = true;
        } else {
            // Could be that nothing changed, or doc not found/permission issue/already deleted/archived
            $message = 'Document not found, not editable, or no changes detected.';
            $success = false; // Indicate failure if no rows affected
        }
        echo json_encode(['success' => $success, 'message' => $message, 'new_title' => $doc_title]);
    } else {
        $error_message = 'Error saving document: ' . $stmt->error;
        echo json_encode(['success' => false, 'message' => $error_message]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
    $conn->close();
}
exit();