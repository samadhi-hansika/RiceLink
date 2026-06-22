<?php
session_start();
include '../config/db.php';

$user = $_SESSION['user_id'];

$type = intval($_POST['type']);
$qty = floatval($_POST['qty']);
$address = trim($_POST['address']);

/* PRICE */
$stmt = $conn->prepare("SELECT price FROM paddy_types WHERE id=?");
$stmt->bind_param("i", $type);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

$price = $data['price'];
$total = $qty * $price;

/* INSERT */
$stmt = $conn->prepare("
INSERT INTO sales(user_id, paddy_type_id, quantity, price, total, address)
VALUES(?,?,?,?,?,?)
");

$stmt->bind_param("iiddds", $user, $type, $qty, $price, $total, $address);
$stmt->execute();

header("Location: my_sales.php");
exit();
?>