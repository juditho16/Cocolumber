<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];

        $query = "INSERT INTO suppliers (name, contact, address)
                  VALUES ('$name', '$contact', '$address')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Supplier added successfully!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }

    } elseif ($action == 'delivery') {
        $supplier_id = $_POST['supplier_id'];
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];

        $query = "INSERT INTO deliveries (supplier_id, item, quantity, delivered_at)
                  VALUES ('$supplier_id', '$item', '$quantity', NOW())";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Delivery recorded successfully!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }
    }

    header("Location: ../pages/suppliers.php");
    exit();
}
?>
