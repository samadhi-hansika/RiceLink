<?php
session_start();
include '../config/db.php';

define('BASE_URL', 'http://localhost/ricelink/');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ".BASE_URL."includes/index.php");
    exit();
}

$email = strtolower(trim($_POST['email']));
$password = $_POST['password'];

if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
}

if ($_SESSION['attempt'] >= 15) {
    echo "<script>alert('Too many attempts. Try again after 5 minutes.');window.location='".BASE_URL."includes/index.php';</script>";
    exit();
}

$stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    if (password_verify($password, $row['password'])) {

        $_SESSION['attempt'] = 0;
        session_regenerate_id(true);

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == "admin") {
            header("Location: ".BASE_URL."admin/dashboard.php");
        } else {
            header("Location: ".BASE_URL."farmer/dashboard.php");
        }
        exit();

    } else {
        $_SESSION['attempt']++;
        echo "<script>alert('Wrong password!');window.location='".BASE_URL."includes/index.php';</script>";
        exit();
    }

} else {
    $_SESSION['attempt']++;
    echo "<script>alert('User not found!');window.location='".BASE_URL."includes/index.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>