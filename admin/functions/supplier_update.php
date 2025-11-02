<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['supplier_id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $contact = trim($_POST['contact_person'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $email = trim($_POST['email_or_phone'] ?? '');

  if ($id <= 0 || $name === '' || $contact === '' || $address === '' || $email === '') {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
  }

  $stmt = $conn->prepare("UPDATE suppliers SET name=?, contact_person=?, address=?, email_or_phone=? WHERE supplier_id=?");
  $stmt->bind_param("ssssi", $name, $contact, $address, $email, $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Supplier updated successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update supplier.']);
  }

  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
