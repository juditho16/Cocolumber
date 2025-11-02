<?php
include(__DIR__ . '/../config/db_connection.php');
header('Content-Type: application/json');

$name = $_POST['name'] ?? null;
$type = $_POST['type'] ?? null;
$size = $_POST['size'] ?? null;
$quantity = $_POST['quantity'] ?? 0;
$unit = $_POST['unit'] ?? null;
$status = $_POST['status'] ?? 'In Stock';
$remarks = $_POST['remarks'] ?? '';

if (!$name || !$type) {
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO inventory_items (name, type, size, quantity, unit, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("sssiss", $name, $type, $size, $quantity, $unit, $status);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
  echo json_encode(['status' => 'success', 'message' => 'Item added successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Failed to add item.']);
}
$conn->close();
?>
