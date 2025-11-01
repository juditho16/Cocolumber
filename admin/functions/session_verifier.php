<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_path', '/'); // ensures session is valid across all folders
    session_start();
}

// ✅ Check if any logged-in user exists (admin or staff)
if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    header("Location: ../../admin.php");
    exit();
}
?>
