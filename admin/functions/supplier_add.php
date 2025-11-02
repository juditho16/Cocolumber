<?php
include_once(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $contact = trim($_POST['contact_person'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $email = trim($_POST['email_or_phone'] ?? '');

  if ($name === '' || $contact === '' || $address === '' || $email === '') {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_person, address, email_or_phone) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $contact, $address, $email);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Supplier added successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add supplier.']);
  }

  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
