<?php
include(__DIR__ . '/../config/db_connection.php');
header('Content-Type: application/json');

$id = $_POST['inventory_id'] ?? null;
$name = $_POST['name'] ?? null;
$type = $_POST['type'] ?? null;
$size = $_POST['size'] ?? null;
$quantity = $_POST['quantity'] ?? 0;
$unit = $_POST['unit'] ?? null;
$status = $_POST['status'] ?? 'In Stock';
$remarks = $_POST['remarks'] ?? '';

if (!$id || !$name) {
  echo json_encode(['status' => 'error', 'message' => 'Missing item ID or name.']);
  exit;
}

$stmt = $conn->prepare("UPDATE inventory_items SET name=?, type=?, size=?, quantity=?, unit=?, status=?, updated_at=NOW() WHERE inventory_id=?");
$stmt->bind_param("sssissi", $name, $type, $size, $quantity, $unit, $status, $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
  echo json_encode(['status' => 'success', 'message' => 'Item updated successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
}
$conn->close();
?>
