<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $low_threshold = $_POST['low_threshold'];

    $query = "INSERT INTO inventory (name, type, size, quantity, low_threshold)
              VALUES ('$name', '$type', '$size', '$quantity', '$low_threshold')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "New inventory item added successfully!";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
    }

    header("Location: ../pages/inventory.php");
    exit();
}
?>
