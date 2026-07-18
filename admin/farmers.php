<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

$res = $conn->query("
SELECT 
u.user_id,
u.name,
u.email,
(
 SELECT s.address 
 FROM sales s 
 WHERE s.user_id = u.user_id 
 ORDER BY s.id DESC LIMIT 1
) AS last_address,
(
 SELECT p.name 
 FROM sales s 
 JOIN paddy_types p ON s.paddy_type_id = p.id 
 WHERE s.user_id = u.user_id 
 ORDER BY s.id DESC LIMIT 1
) AS last_paddy,
(
 SELECT status 
 FROM sales s 
 WHERE s.user_id = u.user_id 
 ORDER BY s.id DESC LIMIT 1
) AS last_status
FROM users u
WHERE u.role='farmer'
ORDER BY u.user_id DESC
");
?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Farmers - Admin</title>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

.container{
    max-width:1100px;
    margin:120px auto;
    padding:20px;
}

.card{
    background:#fff;
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
    color:#fff;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* button */
.btn{
    padding:6px 10px;
    background:#1e5631;
    color:white;
    text-decoration:none;
    border-radius:6px;
}

/* badges */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.pending{background:#fff3cd;color:#856404;}
.approved{background:#d4edda;color:#155724;}
.rejected{background:#f8d7da;color:#721c24;}
.paid{background:#cce5ff;color:#004085;}

/* header */
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

<div class="navbar">
    <div class="logo">🌾 RiceLink Admin</div>

    <div class="nav-links">
        
        <a href="dashboard.php">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>
        <a href="farmers.php" class="active">
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

<div class="card">

<h2>👨‍🌾 Farmers List</h2>

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Address</th>
    <th>Last Paddy</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>

<tr>
    <td><?= $r['user_id'] ?></td>
    <td><?= $r['name'] ?></td>
    <td><?= $r['email'] ?></td>
    <td><?= $r['last_address'] ?? '-' ?></td>
    <td><?= $r['last_paddy'] ?></td>

    <td>
        <?php
            $st = strtolower($r['last_status']);

            if($st=="approved") echo "<span class='badge approved'>Approved</span>";
            elseif($st=="rejected") echo "<span class='badge rejected'>Rejected</span>";
            elseif($st=="paid") echo "<span class='badge paid'>Paid</span>";
            else echo "<span class='badge pending'>No Data</span>";
        ?>
    </td>

    <td>
        <a href="farmer_view.php?id=<?= $r['user_id'] ?>" class="btn">View</a>
    </td>
</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>