<?php
include("admin/config/db_connection.php");
session_start();

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ Simple prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Plain-text password match (matches your DB)
        if ($password === $user['password']) {
            // Store session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // ✅ Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin/pages/dashboard.php");
            } else {
                header("Location: admin/pages/dashboard.php"); // same dashboard for staff for now
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Account not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Mahayag Lumber</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f5e9;
            font-family: "Segoe UI", sans-serif;
        }
        .login-container {
            max-width: 420px;
            margin: 5% auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-login {
            background-color: #164e3f;
            color: #fff;
        }
        .btn-login:hover {
            background-color: #0f3d31;
        }
    </style>
</head>
<body>
<div class="container login-container">
    <div class="card p-4">
        <div class="text-center mb-4">
            <img src="admin/pictures/logo.jpg" alt="Logo" width="70" class="mb-2">
            <h4 class="fw-semibold text-dark">Mahayag Lumber Admin</h4>
            <p class="text-muted small">Sign in to manage inventory and operations</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Usersawname</label>
                <input required type="text" name="username" class="form-control" placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input required type="password" name="password" class="form-control" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-login w-100 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>
    </div>

    <p class="text-center text-muted small mt-3">
        &copy; <?= date('Y') ?> Mahayag Lumber | Admin Portal
    </p>
</div>
</body>
</html>
