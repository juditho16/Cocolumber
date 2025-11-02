<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $supplier_id = intval($_POST['supplier_id'] ?? 0);
  $inventory_id = intval($_POST['inventory_id'] ?? 0);
  $quantity = floatval($_POST['quantity'] ?? 0);
  $unit = trim($_POST['unit'] ?? '');
  $delivery_date = $_POST['delivery_date'] ?? date('Y-m-d');
  $remarks = trim($_POST['remarks'] ?? '');

  if ($supplier_id <= 0 || $inventory_id <= 0 || $quantity <= 0 || $unit === '') {
    echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled.']);
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO deliveries (supplier_id, inventory_id, quantity, unit, delivery_date, remarks) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iidsss", $supplier_id, $inventory_id, $quantity, $unit, $delivery_date, $remarks);

  if ($stmt->execute()) {
    // ✅ Optionally update inventory stock
    $update = $conn->prepare("UPDATE inventory_items SET quantity = quantity + ? WHERE inventory_id = ?");
    $update->bind_param("di", $quantity, $inventory_id);
    $update->execute();
    $update->close();

    echo json_encode(['status' => 'success', 'message' => 'Delivery log added successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add delivery log.']);
  }

  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
