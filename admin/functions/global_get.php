<?php
/**
 * 🌍 GLOBAL DATA CONTROLLER — FINAL VERSION
 * Fetches system data (cutting jobs, job assignments, inventory, etc.)
 * Supports single-record retrieval for edit modals.
 */

include(__DIR__ . "/../config/db_connection.php");
header('Content-Type: application/json; charset=utf-8');

$type = $_GET['type'] ?? null;
$id   = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$type) {
  echo json_encode(['error' => 'Missing type parameter']);
  exit;
}

$data = null;

switch ($type) {

  // 🪵 INVENTORY ITEMS
  case 'inventory':
    $sql = $id
      ? "SELECT * FROM inventory_items WHERE inventory_id = $id"
      : "SELECT * FROM inventory_items ORDER BY name ASC";
    break;

  // 🚚 DELIVERIES
  case 'deliveries':
    $sql = "
      SELECT d.*, s.name AS supplier_name, i.name AS item_name, i.type AS item_type
      FROM deliveries d
      LEFT JOIN suppliers s ON s.supplier_id = d.supplier_id
      LEFT JOIN inventory_items i ON i.inventory_id = d.inventory_id
      ORDER BY d.delivery_date DESC
    ";
    break;

  // 🏗️ CUTTING JOBS — FIXED
  case 'cutting_jobs':
    $sql = "
      SELECT 
        cj.job_id, cj.job_name, cj.size, cj.quantity, cj.status, cj.due_date, cj.created_at,
        i.name AS inventory_name,
        ja.worker_id, w.full_name AS worker_name, ja.status AS assignment_status
      FROM cutting_jobs cj
      LEFT JOIN inventory_items i ON i.inventory_id = cj.inventory_id
      LEFT JOIN job_assignments ja ON ja.job_id = cj.job_id
      LEFT JOIN workers w ON w.worker_id = ja.worker_id
    ";
    if ($id) {
      $sql .= " WHERE cj.job_id = $id";
    }
    $sql .= " ORDER BY cj.created_at DESC";
    break;

  // 🧰 JOB ASSIGNMENTS
  case 'job_assignments':
    $sql = "
      SELECT 
        ja.assignment_id, ja.job_id, ja.worker_id, ja.assigned_date, ja.status,
        cj.job_name, cj.due_date,
        w.full_name AS worker_name
      FROM job_assignments ja
      LEFT JOIN cutting_jobs cj ON cj.job_id = ja.job_id
      LEFT JOIN workers w ON w.worker_id = ja.worker_id
    ";
    if ($id) {
      $sql .= " WHERE ja.job_id = $id";
    }
    $sql .= " ORDER BY ja.assigned_date DESC";
    break;

  // 👷 WORKERS
  case 'workers':
    $sql = $id
      ? "SELECT * FROM workers WHERE worker_id = $id"
      : "SELECT * FROM workers ORDER BY full_name ASC";
    break;

  // 🏢 SUPPLIERS
  case 'suppliers':
    $sql = $id
      ? "SELECT * FROM suppliers WHERE supplier_id = $id"
      : "SELECT * FROM suppliers ORDER BY name ASC";
    break;

  // 📦 STOCK MOVEMENTS
  case 'stock_movements':
    $sql = "
      SELECT sm.*, i.name AS inventory_name, i.unit AS inventory_unit
      FROM stock_movements sm
      LEFT JOIN inventory_items i ON i.inventory_id = sm.inventory_id
      ORDER BY sm.created_at DESC
    ";
    break;

  // 👨‍💼 USERS
  case 'users':
    $sql = "SELECT id, username, role, created_at FROM users ORDER BY id ASC";
    break;

  default:
    echo json_encode(['error' => 'Invalid type parameter']);
    exit;
}

// 🔍 Execute Query
try {
  $result = $conn->query($sql);
  if ($result === false) {
    throw new Exception($conn->error);
  }

  if ($id) {
    // If ID specified, return single row instead of array
    $data = $result->fetch_assoc() ?: [];
  } else {
    $data = $result->fetch_all(MYSQLI_ASSOC);
  }

  echo json_encode($data ?: []);
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
