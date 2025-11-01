<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report_' . $type . '_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');

    if ($type == 'inventory') {
        fputcsv($output, ['ID', 'Name', 'Type', 'Size', 'Quantity']);
        $res = mysqli_query($conn, "SELECT * FROM inventory");
    } elseif ($type == 'cutting') {
        fputcsv($output, ['ID', 'Type', 'Size', 'Quantity', 'Worker', 'Status']);
        $res = mysqli_query($conn, "SELECT * FROM cutting_jobs");
    } else {
        fputcsv($output, ['Supplier', 'Item', 'Quantity', 'Delivered Date']);
        $res = mysqli_query($conn, "SELECT * FROM deliveries");
    }

    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>
