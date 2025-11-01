<?php
include("../config/db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $type = $_POST['type'];
        $size = $_POST['size'];
        $quantity = $_POST['quantity'];
        $due_date = $_POST['due_date'];
        $worker_id = $_POST['worker_id'];

        $query = "INSERT INTO cutting_jobs (type, size, quantity, due_date, worker_id, status)
                  VALUES ('$type', '$size', '$quantity', '$due_date', '$worker_id', 'Pending')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Cutting job created successfully!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }

    } elseif ($action == 'status') {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $query = "UPDATE cutting_jobs SET status = '$status' WHERE id = '$id'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Job status updated!";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
        }
    }

    header("Location: ../pages/cutting_jobs.php");
    exit();
}
?>
