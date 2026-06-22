<?php
session_start();
include '../config/db.php';

$email = strtolower(trim($_POST['email']));
$password = $_POST['password'];

if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
}

if ($_SESSION['attempt'] >= 5) {
    die("Too many attempts. Try again later.");
}

$stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    if (password_verify($password, $row['password'])) {

        $_SESSION['attempt'] = 0;
        session_regenerate_id(true);

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../farmer/dashboard.php");
        }
        exit();

    } else {
        $_SESSION['attempt']++;
        echo "<script>alert('Wrong password');window.location='../index.php';</script>";
    }

} else {
    $_SESSION['attempt']++;
    echo "<script>alert('User not found');window.location='../index.php';</script>";
}
?>