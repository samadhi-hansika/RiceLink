<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user_id'];

/* ================= GET SALES ================= */
$query = "
SELECT 
    sales.*,
    paddy_types.name AS paddy_name
FROM sales
LEFT JOIN paddy_types 
    ON sales.paddy_type_id = paddy_types.id
WHERE sales.user_id = ?
ORDER BY sales.id DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>මගේ විකුණුම් - RiceLink</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

.page-container{
    max-width:1100px;
    margin:120px auto;
    padding:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

h2{
    color:#1e5631;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
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

/* STATUS */
.status{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{ background:#fff3cd; color:#856404; }
.approved{ background:#d4edda; color:#155724; }
.rejected{ background:#f8d7da; color:#721c24; }
.paid{ background:#cce5ff; color:#004085; }

/* ADDRESS */
.address{
    font-size:12px;
    color:#555;
}

/* EMPTY */
.empty{
    padding:30px;
    text-align:center;
    color:#777;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🌾 RiceLink - Farmer</div>

    <div class="nav-links"> 
        <a href="dashboard.php">
            <i class="fa-solid fa-chart-line icon-chart"></i> Dashboard
        </a>        
        <a href="add_sale.php">➕ Add Sale</a>
        <a href="my_sales.php" class="active">📄 My Sales</a>
        <a href="../auth/logout.php">🚪 Logout</a>
    </div>
</div>

<div class="page-container">

<div class="card">

<h2>📄 මගේ විකුණුම්</h2>
<p>ඔබ දමා ඇති අස්වැන්න විස්තර</p>

<table>

<tr>
    <th>ID</th>
    <th>🌾 වී වර්ගය</th>
    <th>⚖️ Qty</th>
    <th>💰 Total</th>
    <th>📍 Address</th>
    <th>📅 Date</th>
    <th>Status</th>
</tr>

<?php if($res->num_rows > 0): ?>

<?php while($r = $res->fetch_assoc()): ?>

<tr>

<td><?= $r['id'] ?></td>

<td><?= htmlspecialchars($r['paddy_name']) ?></td>

<td><?= htmlspecialchars($r['quantity']) ?> kg</td>

<td>Rs. <?= number_format($r['total'],2) ?></td>

<td>
    <?php if(!empty($r['address'])): ?>
        <div class="address">
            📍 <?= htmlspecialchars($r['address']) ?>
        </div>
    <?php else: ?>
        -
    <?php endif; ?>
</td>

<td>
    <?= isset($r['created_at']) ? date("Y-m-d", strtotime($r['created_at'])) : "-" ?>
</td>

<td>
<?php
$status = strtolower($r['status']);

if($status == "approved"){
    echo "<span class='status approved'>Approved</span>";
}
elseif($status == "rejected"){
    echo "<span class='status rejected'>Rejected</span>";
}
elseif($status == "paid"){
    echo "<span class='status paid'>Paid</span>";
}
else{
    echo "<span class='status pending'>Pending</span>";
}
?>
</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="7" class="empty">
🌾 තවම විකුණුම් නැත <br><br>
<a href="add_sale.php">➕ අලුත් විකුණුමක් දමන්න</a>
</td>
</tr>

<?php endif; ?>

</table>

</div>

</div>

</body>
</html>