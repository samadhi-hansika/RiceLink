<?php
include '../config/db.php';

$name = trim($_POST['name']);
$email = strtolower(trim($_POST['email']));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES (?,?,?,'farmer')");
$stmt->bind_param("sss", $name, $email, $password);
$stmt->execute();

header("Location: ../index.php");
exit();
?>