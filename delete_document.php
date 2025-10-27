<?php
// This file is now primarily a redirect handler if accessed directly,
// as the actual delete logic is moved to handle_doc_action.php via AJAX.
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("User not logged in."));
    exit();
}

// Redirect to dashboard, as deletion is now handled via AJAX on handle_doc_action.php
header("Location: dashboard.php");
exit();
?>