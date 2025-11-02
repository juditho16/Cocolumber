<?php
include_once(__DIR__ . "/../config/db_connection.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Invalid request.']); exit;
}

$name = trim($_POST['name'] ?? '');
$age = intval($_POST['age'] ?? 0);
$address = trim($_POST['address'] ?? '');
$contract = trim($_POST['contract_no'] ?? '');

if (!$name || !$age || !$address || !$contract) {
  echo json_encode(['status'=>'error','message'=>'All fields are required.']); exit;
}

$stmt = $conn->prepare("INSERT INTO workers (full_name, age, address, contract_no, status) VALUES (?, ?, ?, ?, 'Active')");
$stmt->bind_param("siss", $name, $age, $address, $contract);
echo $stmt->execute()
  ? json_encode(['status'=>'success','message'=>'Worker added successfully.'])
  : json_encode(['status'=>'error','message'=>'Failed to add worker.']);
$stmt->close();
