<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json');

$job_id    = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
$job_name  = trim($_POST['job_name'] ?? '');
$worker_id = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
$status    = trim($_POST['status'] ?? '');
$due_date  = trim($_POST['due_date'] ?? '');

if (!$job_id || !$job_name || !$worker_id || !$status || !$due_date) {
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
  exit;
}

try {
  // ✅ Update cutting job details
  $stmt = $conn->prepare("UPDATE cutting_jobs SET job_name = ?, due_date = ?, status = ?, updated_at = NOW() WHERE job_id = ?");
  $stmt->bind_param("sssi", $job_name, $due_date, $status, $job_id);
  $stmt->execute();
  $stmt->close();

  // ✅ Update or insert into job_assignments
  $existing = $conn->query("SELECT assignment_id FROM job_assignments WHERE job_id = $job_id");
  if ($existing->num_rows > 0) {
    $stmt2 = $conn->prepare("
      UPDATE job_assignments
      SET worker_id = ?, status = ?, assigned_date = NOW()
      WHERE job_id = ?
    ");
    $stmt2->bind_param("isi", $worker_id, $status, $job_id);
  } else {
    $stmt2 = $conn->prepare("
      INSERT INTO job_assignments (job_id, worker_id, status, assigned_date)
      VALUES (?, ?, ?, NOW())
    ");
    $stmt2->bind_param("iis", $job_id, $worker_id, $status);
  }
  $stmt2->execute();
  $stmt2->close();

  echo json_encode(['status' => 'success', 'message' => 'Job updated successfully.']);
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
