<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json');

$job_id        = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
$worker_id     = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
$assigned_date = $_POST['assigned_date'] ?? date('Y-m-d');

if (!$job_id || !$worker_id) {
  echo json_encode(['status' => 'error', 'message' => 'Missing job or worker.']);
  exit;
}

try {
  // Prevent duplicate assignment
  $check = $conn->prepare("SELECT job_id FROM job_assignments WHERE job_id = ?");
  $check->bind_param("i", $job_id);
  $check->execute();
  $result = $check->get_result();
  if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Job is already assigned.']);
    exit;
  }

  // Insert new assignment
  $stmt = $conn->prepare("
    INSERT INTO job_assignments (job_id, worker_id, status, assigned_date)
    VALUES (?, ?, 'Assigned', ?)
  ");
  $stmt->bind_param("iis", $job_id, $worker_id, $assigned_date);
  $stmt->execute();

  // Also update cutting job status to "Pending"
  $conn->query("UPDATE cutting_jobs SET status = 'Pending', updated_at = NOW() WHERE job_id = $job_id");

  echo json_encode(['status' => 'success', 'message' => 'Job assigned successfully.']);
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
