<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

/* Farmer info */
$user = $conn->query("SELECT * FROM users WHERE user_id='$id'")->fetch_assoc();

/* Sales */
$sales = $conn->query("
SELECT sales.*, paddy_types.name AS paddy
FROM sales
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
WHERE sales.user_id='$id'
ORDER BY sales.id DESC
");
?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Farmer Details</title>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.container{
    max-width:1000px;
    margin:120px auto;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    margin-bottom:20px;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    background:#e8f5e9;
    color:#1e5631;
}

/* ================= ICON COLORS ================= */
.icon-home{ color:#4CAF50; }
.icon-price{ color:#FF9800; }
.icon-users{ color:#2196F3; }
.icon-sales{ color:#9C27B0; }
.icon-logout{ color:#f44336; }

/* ================= HOVER ================= */
.nav-links a:hover{
    background:#fff;
    color:#1e5631;
}

/* ================= ACTIVE PAGE ================= */
.nav-links a.active{
    background:#ffffff;
    color:#1e5631;
    font-weight:600;
}

/* keep icon visible on active */
.nav-links a.active i{
    color:#1e5631 !important;
}

/* ================= LOGOUT SPECIAL ================= */
.logout-btn{
    background:rgba(255,255,255,0.1);
}

.logout-btn:hover{
    background:#fff;
    color:#f44336;
}

</style>

</head>

<body>
<!-- NAV -->
<div class="navbar">
    <div class="logo">🌾 RiceLink Admin</div>

    <div class="nav-links">
        <a href="../index.php">
            <i class="fa-solid fa-house icon-home"></i> Home
        </a>
        <a href="dashboard.php">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>
        <a href="farmers.php" class="active">
            <i class="fa-solid fa-users icon-users"></i> Farmers
        </a>
        <a href="sales.php">
            <i class="fa-solid fa-chart-line icon-sales"></i> Sales
        </a>
        <a href="price_update.php">
            <i class="fa-solid fa-money-bill icon-price"></i> Prices
        </a>
        <a href="../auth/logout.php">
            <i class="fa-solid fa-right-from-bracket icon-logout"></i> Logout
        </a>
    </div>
</div>


<div class="container">

<!-- FARMER INFO -->
<div class="card">

<h2>👨‍🌾 Farmer Details</h2>

<p><b>Name:</b> <?= $user['name'] ?></p>
<p><b>Email:</b> <?= $user['email'] ?></p>
<p><b>Address:</b> <?= !empty($user['address']) ? $user['address'] : 'Not Added' ?></p>

</div>

<!-- SALES -->
<div class="card">

<h2>📦 Harvest Records</h2>

<table width="100%">

<tr>
    <th>🌾 Paddy</th>
    <th>⚖️ Qty</th>
    <th>💰 Total</th>
    <th>📌 Status</th>
</tr>

<?php while($s = $sales->fetch_assoc()): ?>

<tr>
    <td><?= $s['paddy'] ?></td>
    <td><?= $s['quantity'] ?> kg</td>
    <td>Rs. <?= $s['total'] ?></td>
    <td><span class="badge"><?= $s['status'] ?></span></td>
</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>