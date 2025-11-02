<?php
include(__DIR__ . '/../config/db_connection.php');
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if (!$id) {
  echo json_encode(['status' => 'error', 'message' => 'Missing item ID.']);
  exit;
}

$stmt = $conn->prepare("DELETE FROM inventory_items WHERE inventory_id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
  echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Failed to delete item.']);
}
$conn->close();
?>
