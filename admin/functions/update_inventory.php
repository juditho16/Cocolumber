<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $mode = $_POST['mode']; // set | in | out

    if ($mode == 'in') {
        $query = "UPDATE inventory SET quantity = quantity + $quantity WHERE id = $id";
    } elseif ($mode == 'out') {
        $query = "UPDATE inventory SET quantity = GREATEST(quantity - $quantity, 0) WHERE id = $id";
    } else {
        $query = "UPDATE inventory SET quantity = $quantity WHERE id = $id";
    }

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Inventory updated successfully!";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
    }

    header("Location: ../pages/inventory.php");
    exit();
}
?>
