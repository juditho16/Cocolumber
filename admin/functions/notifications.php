<?php
include("../config/db_connection.php");
header('Content-Type: application/json');

$res = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 20");
$data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

echo json_encode($data);
?>
