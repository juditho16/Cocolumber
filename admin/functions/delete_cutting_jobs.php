<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid job ID.']);
  exit;
}

try {
  // Delete related assignment (foreign key CASCADE also handles this, but for safety)
  $conn->query("DELETE FROM job_assignments WHERE job_id = $id");

  // Delete the job itself
  $conn->query("DELETE FROM cutting_jobs WHERE job_id = $id");

  if ($conn->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Cutting job deleted successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Job not found or already deleted.']);
  }
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
