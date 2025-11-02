<?php
include_once(__DIR__ . "/../config/db_connection.php");
header("Content-Type: application/json");

$id = intval($_POST['id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM workers WHERE worker_id=?");
$stmt->bind_param("i", $id);
echo $stmt->execute()
  ? json_encode(['status'=>'success','message'=>'Worker deleted successfully.'])
  : json_encode(['status'=>'error','message'=>'Failed to delete worker.']);
$stmt->close();
