<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user_id'];

$type = intval($_POST['type']);
$qty = floatval($_POST['qty']);
$address = trim($_POST['address']);
$address = $_POST['address'];

/* GET PRICE */
$q = $conn->query("SELECT price FROM paddy_types WHERE id='$type'");
$data = $q->fetch_assoc();
$price = $data['price'];

$total = $qty * $price;

/* INSERT */
$conn->query("
INSERT INTO sales(user_id, paddy_type_id, quantity, price, total, address)
VALUES('$user','$type','$qty','$price','$total','$address')
");

/* REDIRECT */
header("Location: my_sales.php");
exit();
?>