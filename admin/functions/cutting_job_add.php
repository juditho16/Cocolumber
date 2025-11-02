<?php
include_once(__DIR__ . '/../config/db_connection.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['job_name'] ?? '');
  $inventory_id = intval($_POST['inventory_id'] ?? 0);
  $target = intval($_POST['target_quantity'] ?? 0);
  $due = $_POST['due_date'] ?? '';

  if ($name === '' || !$inventory_id || !$target || !$due) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO cutting_jobs (job_name, inventory_id, target_quantity, due_date, status) VALUES (?, ?, ?, ?, 'Pending')");
  $stmt->bind_param("siis", $name, $inventory_id, $target, $due);

  if ($stmt->execute())
    echo json_encode(['status' => 'success', 'message' => 'Cutting job added successfully.']);
  else
    echo json_encode(['status' => 'error', 'message' => 'Failed to add cutting job.']);

  $stmt->close();
  $conn->close();
}
?>
