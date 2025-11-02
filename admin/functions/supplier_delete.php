<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
  exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid supplier ID.']);
  exit;
}

$stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'Supplier deleted successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Failed to delete supplier.']);
}

$stmt->close();
$conn->close();
?>
