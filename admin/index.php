<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_path', '/'); // allows session across all folders
    session_start();
}

require_once __DIR__ . "/config/db_connection.php";
require_once __DIR__ . "/functions/session_verifier.php";

// ✅ Guard admin session
if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    header("Location: ../admin.php");
    exit;
}

// ✅ Include layout header (already has sidebar + topbar)
include __DIR__ . "/partials/header.php";

// ✅ Determine which page to load
$page = $_GET['page'] ?? 'dashboard';
$pagePath = __DIR__ . "/pages/" . basename($page) . ".php";

// ✅ Load corresponding page if it exists
if (file_exists($pagePath)) {
    include $pagePath;
} else {
    echo "<div class='p-4'>
            <div class='alert alert-danger'>
                <strong>Error:</strong> Page <code>{$page}</code> not found at <code>{$pagePath}</code>.
            </div>
          </div>";
}

// ✅ Include footer (closes HTML tags + JS)
include __DIR__ . "/partials/footer.php";
?>
