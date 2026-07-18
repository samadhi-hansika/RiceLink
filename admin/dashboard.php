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

/* ALL SALES (FIXED JOIN) */
$res = $conn->query("
SELECT 
sales.*,
users.name AS farmer_name,
users.email,
sales.address AS address,
paddy_types.name AS paddy_name
FROM sales
LEFT JOIN users ON sales.user_id = users.user_id
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
ORDER BY sales.id DESC
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

body{
    margin:0;
    padding:0;
    background:#e9f0ea;
    font-family:'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color:#243a2f;
}

/* ================= NAVBAR ================= */
.navbar{
    position:sticky;
    top:0;
    z-index:100;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1rem;
    padding:18px 32px;
    background:linear-gradient(90deg, #164222 0%, #24603e 100%);
    box-shadow:0 18px 40px rgba(0,0,0,0.08);
}

.logo{
    display:flex;
    align-items:center;
    gap:0.75rem;
    color:#f4fbf4;
    font-size:1.1rem;
    font-weight:700;
}

.nav-links{
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:0.75rem;
}

.nav-links a{
    display:inline-flex;
    align-items:center;
    gap:0.5rem;
    padding:10px 16px;
    border-radius:999px;
    text-decoration:none;
    color:rgba(255,255,255,0.9);
    background:rgba(255,255,255,0.08);
    transition:all 0.18s ease;
    font-size:0.95rem;
}

.nav-links a:hover{
    background:rgba(255,255,255,0.18);
    color:#ffffff;
}

.nav-links a.active{
    background:#ffffff;
    color:#1e5631;
    font-weight:700;
    box-shadow:0 18px 35px rgba(15,57,29,0.18);
}

.nav-links a.active i{
    color:#1e5631 !important;
}

.container{
    max-width:1600px;
    margin:114px auto 40px;
    padding:0 36px 24px;
}

/* ================= CARDS ================= */
.cards{
    display:grid;
    grid-template-columns:repeat(4,minmax(220px,1fr));
    gap:24px;
}

.card{
    background:#ffffff;
    padding:28px 22px;
    border-radius:22px;
    box-shadow:0 18px 36px rgba(25, 64, 41, 0.08);
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
    background:#ffffff;
    padding:24px;
    border-radius:24px;
    box-shadow:0 24px 55px rgba(28, 62, 46, 0.08);
    overflow-x:auto;
}

.table-card h2{
    margin-bottom:8px;
    font-size:28px;
    letter-spacing:0.015em;
}

.table-card p{
    margin:0;
    color:#556b5f;
    font-size:0.98rem;
}

.table-card table{
    width:100%;
    min-width:1480px;
    border-collapse:separate;
    border-spacing:0;
    margin-top:24px;
    background:#ffffff;
    table-layout:fixed;
}

.table-card thead th{
    background:#1e5631;
    color:#ffffff;
    padding:18px 20px;
    font-size:0.95rem;
    text-align:left;
    letter-spacing:0.01em;
    border-bottom:2px solid rgba(255,255,255,0.15);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.table-card thead th:nth-child(1){width:60px;}
.table-card thead th:nth-child(2){width:220px;}
.table-card thead th:nth-child(3){width:260px;}
.table-card thead th:nth-child(4){width:160px;}
.table-card thead th:nth-child(5){width:100px;}
.table-card thead th:nth-child(6){width:110px;}
.table-card thead th:nth-child(7){width:120px;}
.table-card thead th:nth-child(8){width:120px;}
.table-card thead th:nth-child(9){width:110px;}
.table-card thead th:nth-child(10){width:190px;}

.table-card tbody tr{
    transition:background 0.2s ease, transform 0.18s ease;
}

.table-card tbody tr:hover{
    background:rgba(30,86,49,0.05);
}

.table-card td{
    padding:18px 20px;
    text-align:left;
    border-bottom:1px solid #f0f3f5;
    font-size:0.95rem;
    color:#2f3f35;
    vertical-align:middle;
    word-break:break-word;
}

.action-cell{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.status{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:92px;
    padding:8px 14px;
    border-radius:999px;
    font-size:0.84rem;
    font-weight:700;
    letter-spacing:0.01em;
    box-shadow:0 4px 10px rgba(30,86,49,0.08);
}

.Pending{background:#fff4cc;color:#8c6d10;}
.Approved{background:#e8f7e7;color:#1f6d2b;}
.Paid{background:#d9edff;color:#0f4d7a;}

.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:0.45rem;
    min-width:90px;
    padding:10px 14px;
    border-radius:999px;
    border:none;
    color:#ffffff;
    font-size:0.88rem;
    font-weight:700;
    text-decoration:none;
    transition:transform 0.18s ease, opacity 0.18s ease, box-shadow 0.18s ease;
}

.btn:hover{
    transform:translateY(-1px);
    opacity:0.95;
    box-shadow:0 8px 18px rgba(0,0,0,0.1);
}

.action-cell{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.approve{background:#2ca55d;}
.reject{background:#d42f3a;}
.pay{background:#2266cc;}
.receipt{background:#0f5d91;}
.disabled{
    background:#d4d7dc;
    color:#4f5b65 !important;
    cursor:default;
    pointer-events:none;
}
@media (max-width: 760px) {
    .navbar{
        flex-direction:column;
        align-items:flex-start;
        padding:20px;
    }

    .nav-links{
        width:100%;
        justify-content:flex-start;
    }

    .cards{
        grid-template-columns:1fr;
    }

    table{
        min-width:100%;
    }
}

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
        
        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>
        <a href="farmers.php">
            <i class="fa-solid fa-users icon-users"></i> Farmers
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

<h2>📊 Admin Dashboard</h2><br><br>

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

<!-- ALL SALES -->
<div class="table-card">

<h2>📦 All Harvest Sales</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Email</th>
    <th>Address</th>
    <th>Paddy</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while($r = $res->fetch_assoc()): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['email'] ?></td>
    <td><?= $r['address'] ?? '-' ?></td>
    <td><?= $r['paddy_name'] ?></td>
    <td><?= $r['quantity'] ?> kg</td>
    <td>Rs. <?= number_format($r['price'],2) ?></td>
    <td>Rs. <?= number_format($r['total'],2) ?></td>
    <td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
    <td>
        <span class="status <?= $r['status'] ?>">
            <?= $r['status'] ?>
        </span>
    </td>
    <td class="action-cell">
        <?php if($r['status'] == 'Pending'): ?>
            <a class="btn approve" href="update_status.php?id=<?= $r['id'] ?>&status=Approved">
                <i class="fa-solid fa-check"></i> Approve
            </a>
            <a class="btn reject" href="update_status.php?id=<?= $r['id'] ?>&status=Rejected">
                <i class="fa-solid fa-xmark"></i> Reject
            </a>
        <?php elseif($r['status'] == 'Approved'): ?>
            <a class="btn pay" href="update_status.php?id=<?= $r['id'] ?>&status=Paid">
                <i class="fa-solid fa-dollar-sign"></i> Paid
            </a>
        <?php elseif($r['status'] == 'Paid'): ?>
            <a class="btn receipt" href="receipt.php?id=<?= $r['id'] ?>">
                <i class="fa-solid fa-receipt"></i> Receipt
            </a>
        <?php else: ?>
            <a class="btn receipt disabled" href="#">
                <i class="fa-solid fa-ban"></i> No Action
            </a>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>

</div>

</body>
</html>