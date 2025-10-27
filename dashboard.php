<?php
session_start();
require 'includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("You need to login first."));
    exit();
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_initial = strtoupper(substr($user_name, 0, 1)); // Get first letter for avatar
$user_email = $_SESSION['user_email'] ?? '';
$user_created_at = $_SESSION['user_created_at'] ?? ''; // From handle_login.php

// Handle new document creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_document'])) {
    $new_doc_title = "Untitled"; // Default title
    $stmt = $conn->prepare("INSERT INTO documents (user_id, title, content) VALUES (?, ?, '')");
    $stmt->bind_param("is", $user_id, $new_doc_title);
    if ($stmt->execute()) {
        header("Location: dashboard.php?id=" . $stmt->insert_id);
        exit();
    } else {
        $dashboard_error = "Error creating new document: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch documents for the user, excluding deleted and archived ones, with favorites first
$documents = [];
$stmt_docs = $conn->prepare("SELECT id, title, favorite FROM documents WHERE user_id = ? AND deleted = 0 AND archived = 0 ORDER BY favorite DESC, updated_at DESC");
$stmt_docs->bind_param("i", $user_id);
$stmt_docs->execute();
$result_docs = $stmt_docs->get_result();
while ($row = $result_docs->fetch_assoc()) {
    $documents[] = $row;
}
$stmt_docs->close();

// Fetch current document content if an 'id' is specified
$current_doc_id = null;
$current_doc_title = "";
$current_doc_content = "";
if (isset($_GET['id'])) {
    $current_doc_id = intval($_GET['id']);
    // Select from `documents` table using `id`, also check `deleted = 0` and `archived = 0`
    $stmt_curr_doc = $conn->prepare("SELECT title, content FROM documents WHERE id = ? AND user_id = ? AND deleted = 0 AND archived = 0");
    $stmt_curr_doc->bind_param("ii", $current_doc_id, $user_id);
    $stmt_curr_doc->execute();
    $result_curr_doc = $stmt_curr_doc->get_result();
    if ($doc_data = $result_curr_doc->fetch_assoc()) {
        $current_doc_title = $doc_data['title'];
        $current_doc_content = $doc_data['content'];
    } else {
        $current_doc_id = null; // Reset if not found or doesn't belong to user or is deleted/archived
        if (isset($_GET['id'])) { // If an ID was specified but not found
             $dashboard_error = "Document not found or you don't have permission to view it.";
        }
    }
    $stmt_curr_doc->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($current_doc_title ?: 'Dashboard'); ?> - NotionClone</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">
                    <?php echo htmlspecialchars($user_initial); ?>
                </div>
                <span class="user-name-display"><?php echo htmlspecialchars($user_name); ?></span>
                <div class="user-menu-dropdown" id="userMenuDropdown">
                    <a href="#" class="personal-area-link" id="openPersonalAreaModalBtn"><i class="fa-solid fa-user"></i> Personal Area</a>
                    <a href="#" class="dark-mode-toggle"><i class="fa-solid fa-moon"></i> Toggle Dark Mode</a>
                    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </div>

            <form method="POST" action="dashboard.php">
                <button type="submit" name="create_document" class="btn-new-page"><i class="fa-solid fa-plus"></i> New Page</button>
            </form>

            <nav class="document-list">
                <h4>Your Documents</h4>
                <ul>
                    <?php if (empty($documents)): ?>
                        <li>No documents yet.</li>
                    <?php else: ?>
                        <?php foreach ($documents as $doc): ?>
                            <li class="<?php echo ($current_doc_id === $doc['id']) ? 'active' : ''; ?>" data-doc-id="<?php echo $doc['id']; ?>">
                                <a href="dashboard.php?id=<?php echo $doc['id']; ?>">
                                    <?php if ($doc['favorite'] == 1): ?>
                                        <i class="fa-solid fa-star favorited-icon"></i>
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($doc['title']); ?></span>
                                </a>
                                <div class="doc-actions">
                                    <button class="doc-action-btn three-dots-btn">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <div class="doc-action-menu">
                                        <a href="#" class="action-archive" data-id="<?php echo $doc['id']; ?>"><i class="fa-solid fa-box-archive"></i> Archive</a>
                                        <a href="#" class="action-favorite" data-id="<?php echo $doc['id']; ?>" data-favorited="<?php echo $doc['favorite']; ?>">
                                            <?php if ($doc['favorite'] == 1): ?>
                                                <i class="fa-solid fa-star"></i> Unfavorite
                                            <?php else: ?>
                                                <i class="fa-regular fa-star"></i> Favorite
                                            <?php endif; ?>
                                        </a>
                                        <a href="#" class="action-delete" data-id="<?php echo $doc['id']; ?>"><i class="fa-solid fa-trash-can"></i> Delete</a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="sidebar-bottom-actions">
                <button class="btn-sidebar-modal" id="openArchiveModal"><i class="fa-solid fa-box-archive"></i> Archived</button>
                <button class="btn-sidebar-modal" id="openTrashModal"><i class="fa-solid fa-trash-can"></i> Trash</button>
            </div>
        </aside>

        <main class="main-content">
            <div id="ajax-message-container"></div>
            <?php if (isset($dashboard_error)): ?>
                <p class="error-message" id="dashboard-message"><?php echo htmlspecialchars($dashboard_error); ?></p>
            <?php endif; ?>

            <?php if ($current_doc_id !== null): ?>
                <div class="document-tools">
                    <button id="downloadPdfBtn" class="btn-download-pdf">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span class="hover-text">Download as PDF</span>
                    </button>
                </div>
                <form id="document-form" method="POST" action="save_document.php">
                    <input type="hidden" name="id" value="<?php echo $current_doc_id; ?>">
                    <input type="text" name="doc_title" id="doc_title" class="doc-title-input" value="<?php echo htmlspecialchars($current_doc_title); ?>" placeholder="Untitled">
                    <textarea name="doc_content" id="doc_content" class="doc-content-editor" placeholder="Start writing your document here..."><?php echo htmlspecialchars($current_doc_content); ?></textarea>
                </form>
            <?php elseif (!empty($documents)): ?>
                 <p class="placeholder-text">Select a document from the sidebar to view or edit, or create a new one.</p>
            <?php else: ?>
                <p class="placeholder-text">Create your first document using the "New Page" button!</p>
            <?php endif; ?>
        </main>
    </div>

    <div id="personalAreaModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal-id="personalAreaModal">&times;</span>
            <h3>Personal Area</h3>
            <div class="user-details">
                <p><strong>Username:</strong> <span id="modalUsername"><?php echo htmlspecialchars($user_name); ?></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"><?php echo htmlspecialchars($user_email); ?></span></p>
                <p><strong>Account Created:</strong> <span id="modalCreatedAt"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($user_created_at))); ?></span></p>
            </div>
        </div>
    </div>

    <div id="archiveModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal-id="archiveModal">&times;</span>
            <h3>Archived Documents</h3>
            <ul id="archivedDocList" class="modal-doc-list">
                </ul>
            <p id="noArchivedDocs" class="no-docs-message" style="display: none;">No archived documents.</p>
        </div>
    </div>

    <div id="trashModal" class="modal">
        <div class="modal-content">
            <span class="close-button" data-modal-id="trashModal">&times;</span>
            <h3>Trash</h3>
            <ul id="trashDocList" class="modal-doc-list">
                </ul>
            <p id="noTrashDocs" class="no-docs-message" style="display: none;">Trash is empty.</p>
        </div>
    </div>

    <script src="js/dashboard_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
</body>
</html>