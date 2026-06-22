<?php
session_start();
include '../config/db.php';

/* AUTH CHECK */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['status'])){

    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $allowed = ['Pending','Approved','Rejected','Paid'];

    if(in_array($status, $allowed)){

        $stmt = $conn->prepare("UPDATE sales SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}

header("Location: dashboard.php");
exit();
?>