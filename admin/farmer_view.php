<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

$id = intval($_GET['id']);

$user = $conn->query("SELECT * FROM users WHERE user_id=$id")->fetch_assoc();

$latestAddressRow = $conn->query("SELECT address FROM sales WHERE user_id=$id ORDER BY id DESC LIMIT 1")->fetch_assoc();
$latestSaleAddress = $latestAddressRow['address'] ?? null;

$sales = $conn->query("
SELECT sales.*, paddy_types.name AS paddy
FROM sales
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
WHERE sales.user_id=$id
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

.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:10px 16px;
    border-radius:999px;
    background:#1e5631;
    color:#ffffff;
    text-decoration:none;
    font-weight:700;
    transition:transform 0.18s ease, background 0.18s ease;
}

.btn:hover{
    transform:translateY(-1px);
    background:#295c35;
}

.btn-secondary{
    background:#6c757d;
}

.btn-secondary:hover{
    background:#5a6268;
}

/* ================= SALES TABLE ================= */
table.sales-table {
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    margin-top:16px;
}

table.sales-table th,
table.sales-table td {
    padding:12px 14px;
    text-align:left;
    border-bottom:1px solid #e9ecef;
    vertical-align:middle;
}

table.sales-table th {
    background:#f8f9fb;
    color:#25313c;
    font-weight:700;
    letter-spacing:0.02em;
}

table.sales-table td {
    word-break:break-word;
}

table.sales-table th:nth-child(1),
table.sales-table td:nth-child(1) {
    width:42%;
}

table.sales-table th:nth-child(2),
table.sales-table td:nth-child(2) {
    width:18%;
}

table.sales-table th:nth-child(3),
table.sales-table td:nth-child(3) {
    width:20%;
}

table.sales-table th:nth-child(4),
table.sales-table td:nth-child(4) {
    width:20%;
}

@media (max-width: 760px) {
    .container {
        margin:80px 16px;
    }
    table.sales-table,
    thead,
    tbody,
    th,
    td,
    tr {
        display:block;
    }
    table.sales-table th {
        display:none;
    }
    table.sales-table td {
        padding:12px 10px;
        border:none;
        border-bottom:1px solid #e9ecef;
        position:relative;
    }
    table.sales-table td:before {
        content:attr(data-label);
        display:block;
        font-weight:600;
        margin-bottom:6px;
        color:#455a64;
    }
}

</style>

</head>

<body>
<!-- NAV -->
<div class="navbar">
    <div class="logo">🌾 RiceLink Admin</div>

    <div class="nav-links">
        
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

<div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
    <h2>👨‍🌾 Farmer Details</h2>
    <a href="farmers.php" class="btn btn-secondary">← Back</a>
</div>

<p><b>Name:</b> <?= $user['name'] ?></p>
<p><b>Email:</b> <?= $user['email'] ?></p>
<p><b>Address:</b> <?= !empty($user['address']) ? $user['address'] : (!empty($latestSaleAddress) ? $latestSaleAddress : 'Not Added') ?></p>

</div>

<!-- SALES -->
<div class="card">

<h2>📦 Harvest Records</h2>

<table class="sales-table">
    <thead>
        <tr>
            <th>🌾 Paddy</th>
            <th>⚖️ Qty</th>
            <th>💰 Total</th>
            <th>📌 Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while($s = $sales->fetch_assoc()): ?>
        <tr>
            <td data-label="Paddy"><?= $s['paddy'] ?></td>
            <td data-label="Qty"><?= $s['quantity'] ?> kg</td>
            <td data-label="Total">Rs. <?= $s['total'] ?></td>
            <td data-label="Status"><span class="badge"><?= $s['status'] ?></span></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>

</div>

</body>
</html>