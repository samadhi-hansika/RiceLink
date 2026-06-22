<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../index.php");
    exit();
}

/* STATS */
$totalSales = $conn->query("SELECT COUNT(*) AS c FROM sales")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) AS c FROM sales WHERE status='Pending'")->fetch_assoc()['c'];
$approved = $conn->query("SELECT COUNT(*) AS c FROM sales WHERE status='Approved'")->fetch_assoc()['c'];
$paid = $conn->query("SELECT COUNT(*) AS c FROM sales WHERE status='Paid'")->fetch_assoc()['c'];

/* LATEST SALES (FIXED JOIN) */
$res = $conn->query("
SELECT 
sales.id,
sales.quantity,
sales.total,
sales.status,
users.name AS farmer_name,
users.email,
paddy_types.name AS paddy_name
FROM sales
LEFT JOIN users ON sales.user_id = users.user_id
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
ORDER BY sales.id DESC
LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - RiceLink</title>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= LAYOUT ================= */
.container{
    max-width:1200px;
    margin:120px auto;
    padding:20px;
}

/* ================= CARDS ================= */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    text-align:center;
}

.card h3{
    color:#1e5631;
}

.card p{
    font-size:22px;
    font-weight:bold;
}

/* ================= TABLE ================= */
.table-card{
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

th{
    background:#1e5631;
    color:white;
    padding:10px;
    font-size:14px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:14px;
}

/* ================= STATUS ================= */
.status{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.Pending{background:#fff3cd;color:#856404;}
.Approved{background:#d4edda;color:#155724;}
.Paid{background:#cce5ff;color:#004085;}

h2{
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

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🌾 RiceLink Admin</div>

    <div class="nav-links">
        <a href="../index.php">
            <i class="fa-solid fa-house icon-home"></i> Home
        </a>
        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>
        <a href="farmers.php">
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

<h2>📊 Admin Dashboard</h2>

<!-- STATS -->
<div class="cards">

    <div class="card">
        <h3>Total Sales</h3>
        <p><?= $totalSales ?></p>
    </div>

    <div class="card">
        <h3>Pending</h3>
        <p><?= $pending ?></p>
    </div>

    <div class="card">
        <h3>Approved</h3>
        <p><?= $approved ?></p>
    </div>

    <div class="card">
        <h3>Paid</h3>
        <p><?= $paid ?></p>
    </div>

</div>

<!-- LATEST SALES -->
<div class="table-card">

<h2>📦 Latest Sales</h2>

<table>

<tr>
    <th>ID</th>
    <th>Farmer</th>
    <th>Email</th>
    <th>Paddy</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Status</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>

<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['farmer_name'] ?></td>
    <td><?= $r['email'] ?></td>
    <td><?= $r['paddy_name'] ?></td>
    <td><?= $r['quantity'] ?> kg</td>
    <td>Rs. <?= number_format($r['total'],2) ?></td>
    <td>
        <span class="status <?= $r['status'] ?>">
            <?= $r['status'] ?>
        </span>
    </td>
</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>