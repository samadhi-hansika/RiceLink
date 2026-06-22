<?php
session_start();
include '../config/db.php';

/* ================= AUTH ================= */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

$query = "
SELECT 
sales.*,
users.name AS farmer_name,
users.email AS farmer_email,
paddy_types.name AS paddy_name
FROM sales
LEFT JOIN users ON sales.user_id = users.user_id
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
ORDER BY sales.id DESC
";

$res = $conn->query($query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Sales - RiceLink</title>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= LAYOUT ================= */
.container{
    max-width:1200px;
    margin:120px auto;
    padding:20px;
}

/* ================= CARD ================= */
.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
    overflow:hidden;
}

th{
    background:#1e5631;
    color:white;
    padding:12px;
    font-size:14px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:14px;
}

/* ================= STATUS ================= */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.Pending{background:#fff3cd;color:#856404;}
.Approved{background:#d4edda;color:#155724;}
.Rejected{background:#f8d7da;color:#721c24;}
.Paid{background:#cce5ff;color:#004085;}

/* ================= ACTION BUTTONS ================= */
.btn{
    padding:5px 10px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:12px;
}

.approve{background:#28a745;}
.reject{background:#dc3545;}
.pay{background:#007bff;}

.btn:hover{
    opacity:0.85;
}

/* ================= HEADER ================= */
h2{
    color:#1e5631;
    margin-bottom:5px;
}

p{
    color:#666;
}

.navbar a{
    text-decoration:none;
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

<div class="navbar">
    <div class="logo">🌾 RiceLink Admin</div>

    <div class="nav-links">
        <a href="../index.php">
            <i class="fa-solid fa-house icon-home"></i> Home
        </a>
        <a href="dashboard.php">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>
        <a href="farmers.php">
            <i class="fa-solid fa-users icon-users"></i> Farmers
        </a>
        <a href="sales.php" class="active">
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


<!-- CONTENT -->
<div class="container">

<div class="card">

<h2>📦 All Harvest Sales</h2>
<p>Manage farmer sales, approve harvests and control payments</p>

<table>

<tr>
    <th>Farmer</th>
    <th>Email</th>
    <th>Paddy</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>

<tr>

<td><?= $r['farmer_name'] ?? 'Unknown' ?></td>
<td><?= $r['farmer_email'] ?? 'No Email' ?></td>
<td><?= $r['paddy_name'] ?? 'N/A' ?></td>
<td><?= $r['quantity'] ?? 0 ?> kg</td>
<td>Rs. <?= number_format($r['total'] ?? 0,2) ?></td>

<td>
    <span class="badge <?= $r['status'] ?>">
        <?= $r['status'] ?>
    </span>
</td>

<td>

<a class="btn approve"
href="update_status.php?id=<?= $r['id'] ?>&status=Approved">
Approve
</a>

<a class="btn reject"
href="update_status.php?id=<?= $r['id'] ?>&status=Rejected">
Reject
</a>

<a class="btn pay"
href="update_status.php?id=<?= $r['id'] ?>&status=Paid">
Paid
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>