<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        $query = "INSERT INTO workers (name, phone, role) VALUES ('$name', '$phone', '$role')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Worker added successfully!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }

    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM workers WHERE id = '$id'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Worker deleted successfully!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }
    }

    header("Location: ../pages/workers.php");
    exit();
}
?>
