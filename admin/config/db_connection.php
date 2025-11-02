<?php
/**
 * 🧩 Database Connection + Global Constants Loader
 * Ensures ROOT_PATH, BASE_URL, and other constants are always available.
 */

// ✅ Load global constants FIRST (before any DB or include logic)
include_once(__DIR__ . '/constants.php');

// 🔹 Database credentials
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP password
$dbname = "cocolumber";

// 🔹 Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// 🔹 Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 🔹 Set charset for proper UTF-8 support
mysqli_set_charset($conn, 'utf8mb4');
