<?php
include("../config/db_connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mahayag Lumber - Official Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background-color: #164e3f;
        }
        .navbar-brand {
            font-weight: 600;
            color: #fff !important;
        }
        .hero {
            background: url('pictures/banner.jpg') center/cover no-repeat;
            height: 350px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-shadow: 0 2px 6px rgba(0,0,0,0.5);
        }
        .hero h1 {
            font-weight: 700;
        }
        .footer {
            background: #164e3f;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>

<body>

<!-- 🔷 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="bi bi-tree-fill me-2"></i> Mahayag Lumber</a>
    <a class="btn btn-light btn-sm" href="admin.php"><i class="bi bi-box-arrow-in-right me-1"></i> Admin Login</a>
  </div>
</nav>

<!-- 🪵 HERO SECTION -->
<div class="hero text-center">
    <h1>Quality Lumber Products</h1>
    <p>Gemelina, Mahogany, and more — ready for your construction needs.</p>
</div>

<!-- 🪓 PRODUCT DISPLAY -->
<div class="container my-5">
    <h3 class="fw-semibold mb-4 text-center">Available Lumber Stocks</h3>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Size</th>
                    <th scope="col">Quantity Available</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT name, type, size, quantity FROM inventory ORDER BY type, name");
                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['type']) ?></td>
                            <td><?= htmlspecialchars($row['size']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?> pcs</td>
                        </tr>
                    <?php endwhile;
                else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No available lumber stocks at the moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="text-center text-muted mt-3">
        Visit our Mahayag branch to order and schedule your cutting in person.
    </p>
</div>

<!-- 🌳 FOOTER -->
<div class="footer">
    <p class="mb-0 small">&copy; <?= date('Y') ?> Mahayag Lumber | All Rights Reserved.</p>
</div>

</body>
</html>
