<?php
session_start();
require 'includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.', 'documents' => []]);
    exit();
}

$user_id = $_SESSION['user_id'];
$documents = [];

// Fetch documents for the user, excluding deleted and archived ones, with favorites first
$stmt_docs = $conn->prepare("SELECT id, title, favorite FROM documents WHERE user_id = ? AND deleted = 0 AND archived = 0 ORDER BY favorite DESC, updated_at DESC");
$stmt_docs->bind_param("i", $user_id);
$stmt_docs->execute();
$result_docs = $stmt_docs->get_result();
while ($row = $result_docs->fetch_assoc()) {
    $documents[] = $row;
}
$stmt_docs->close();
$conn->close();

echo json_encode(['success' => true, 'documents' => $documents]);
?>