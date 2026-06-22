<?php
session_start();
include '../config/db.php';

$email = trim(strtolower($_POST['email']));
$password = $_POST['password'];

/* =========================
   LOGIN ATTEMPT SECURITY
========================= */
if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
}

if ($_SESSION['attempt'] >= 5) {
    die("Too many attempts. Try again later.");
}

/* =========================
   FETCH USER
========================= */
$stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    $stmt->bind_result($id, $dbpass, $role);
    $stmt->fetch();

    if (password_verify($password, $dbpass)) {

        // reset attempts on success
        $_SESSION['attempt'] = 0;

        // security
        session_regenerate_id(true);

        // session set
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        /* =========================
           ROLE BASED REDIRECT
        ========================= */
        if ($role == "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../farmer/dashboard.php");
        }
        exit();

    } else {

        $_SESSION['attempt']++;

        echo "<script>
        alert('❌ Wrong password');
        window.location.href='../index.php';
        </script>";
        exit();
    }

} else {

    $_SESSION['attempt']++;

    echo "<script>
    alert('❌ User not found');
    window.location.href='../index.php';
    </script>";
    exit();
}

$stmt->close();
$conn->close();
?>