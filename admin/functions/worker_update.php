<?php
include_once(__DIR__ . "/../config/db_connection.php");
header("Content-Type: application/json");

$id = intval($_POST['worker_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$age = intval($_POST['age'] ?? 0);
$address = trim($_POST['address'] ?? '');
$contract = trim($_POST['contract_no'] ?? '');
$status = $_POST['status'] ?? 'Active';

$stmt = $conn->prepare("UPDATE workers SET full_name=?, age=?, address=?, contract_no=?, status=? WHERE worker_id=?");
$stmt->bind_param("sisssi", $name, $age, $address, $contract, $status, $id);
echo $stmt->execute()
  ? json_encode(['status'=>'success','message'=>'Worker updated successfully.'])
  : json_encode(['status'=>'error','message'=>'Failed to update worker.']);
$stmt->close();
