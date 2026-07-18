<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user_id'];

/* ================= MONTHLY DATA ================= */
$chartData = [];
$months = [];

$q = "
SELECT 
    DATE_FORMAT(created_at,'%Y-%m') as month,
    SUM(total) as total
FROM sales
WHERE user_id = '$user'
GROUP BY month
ORDER BY month
";

$res = $conn->query($q);

while($row = $res->fetch_assoc()){
    $months[] = $row['month'];
    $chartData[] = $row['total'];
}

/* ================= NOTIFICATIONS ================= */
$noti = $conn->query("
SELECT id,status,total,address 
FROM sales 
WHERE user_id='$user' 
AND (status='Approved' OR status='Paid')
ORDER BY id DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Farmer Dashboard - RiceLink</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

/* NAV ICON COLORS */
.icon-home{ color:#4CAF50; }
.icon-chart{ color:#2196F3; }
.icon-bell{ color:#FF9800; }
.icon-logout{ color:#f44336; }

.nav-links a:hover{
    background:#fff;
    color:#1e5631;
}

.nav-links a.active{
    background:#fff;
    color:#1e5631;
}

/* PAGE */
.container{
    max-width:1100px;
    margin:120px auto;
    padding:20px;
}

/* CARDS */
.card{
    background:white;
    padding:20px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

/* NOTIFICATIONS */
.noti{
    padding:12px;
    border-bottom:1px solid #eee;
}

.noti:last-child{
    border:none;
}

.approved{ color:#28a745; font-weight:bold; }
.paid{ color:#007bff; font-weight:bold; }

/* ADDRESS BOX */
.address{
    font-size:13px;
    color:#555;
}

/* BUTTON */
button{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    background:#1e5631;
    color:white;
    cursor:pointer;
}

button:hover{
    background:#2f7d32;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">🌾 RiceLink - Farmer</div>

    <div class="nav-links"> 

        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>

        <a href="add_sale.php">
            ➕ Add Sale
        </a>

        <a href="my_sales.php">
            📄 My Sales
        </a>

        <a href="../auth/logout.php">
            <i class="fa-solid fa-right-from-bracket icon-logout"></i> Logout
        </a>
    </div>
</nav>

<div class="container">

<!-- ================= CHART ================= -->
<div class="card">
    <h3><i class="fa fa-chart-line icon-chart"></i> Monthly Earnings</h3>
    <canvas id="myChart"></canvas>
</div>

<!-- ================= NOTIFICATIONS ================= -->
<div class="card">
    <h3><i class="fa fa-bell icon-bell"></i> Notifications</h3>

    <?php if($noti->num_rows > 0): ?>
        <?php while($n = $noti->fetch_assoc()): ?>

        <div class="noti">

            <?php if($n['status'] == "Approved"): ?>
                ✅ Your sale #<?= $n['id'] ?> has been 
                <span class="approved">Approved</span>
            <?php elseif($n['status'] == "Paid"): ?>
                💰 Payment received for sale #<?= $n['id'] ?> 
                (<b>Rs. <?= number_format($n['total'],2) ?></b>)
            <?php endif; ?>

            <!-- ADDRESS -->
            <?php if(!empty($n['address'])): ?>
                <div class="address">
                    📍 <?= htmlspecialchars($n['address']) ?>
                </div>
            <?php endif; ?>

        </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>No notifications yet</p>
    <?php endif; ?>

</div>

<!-- ================= QUICK ACTION ================= -->
<div class="card">
    <h3>Quick Actions</h3>

    <a href="add_sale.php">
        <button>➕ Add Harvest</button>
    </a>

    <a href="my_sales.php">
        <button>📄 View Sales</button>
    </a>
</div>

</div>

<!-- ================= CHART SCRIPT ================= -->
<script>

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Earnings (Rs)',
            data: <?= json_encode($chartData) ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive:true
    }
});

</script>

</body>
</html>