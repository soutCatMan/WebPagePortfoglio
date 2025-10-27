<?php
session_start();
require 'includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.', 'documents' => []]);
    exit();
}

$user_id = $_SESSION['user_id'];
$type = $_GET['type'] ?? ''; // 'archived' or 'deleted'
$documents = [];

if ($type === 'archived') {
    $stmt = $conn->prepare("SELECT id, title FROM documents WHERE user_id = ? AND archived = 1 ORDER BY updated_at DESC");
    $stmt->bind_param("i", $user_id);
} elseif ($type === 'deleted') {
    $stmt = $conn->prepare("SELECT id, title FROM documents WHERE user_id = ? AND deleted = 1 ORDER BY updated_at DESC");
    $stmt->bind_param("i", $user_id);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid document type.', 'documents' => []]);
    $conn->close();
    exit();
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    echo json_encode(['success' => true, 'documents' => $documents]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database query failed: ' . $stmt->error, 'documents' => []]);
}

$stmt->close();
$conn->close();
?>