<?php
session_start();
include '../config/db.php';

/* ================= AUTH CHECK ================= */
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin"){
    header("Location: ../index.php");
    exit();
}

/* ================= UPDATE PRICE ================= */
$res = $conn->query("SELECT * FROM paddy_types ORDER BY id ASC");

    if(isset($_POST['update'])){

    $id = intval($_POST['id']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("UPDATE paddy_types SET price=? WHERE id=?");
    $stmt->bind_param("di", $price, $id);
    $stmt->execute();

    header("Location: price_update.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Admin Price Update - RiceLink</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* PAGE */
.container{
    max-width:1000px;
    margin:120px auto;
    padding:20px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* TITLE */
h2{
    color:#1e5631;
    margin-bottom:5px;
}

p{
    color:#666;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#1e5631;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* INPUT */
input{
    padding:10px;
    width:120px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
}

input:focus{
    border-color:#1e5631;
}

/* BUTTON */
button{
    padding:10px 15px;
    border:none;
    background:#1e5631;
    color:white;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#2e7d32;
}

/* BADGE */
.badge{
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    background:#e8f5e9;
    color:#1e5631;
    font-weight:600;
}

.navbar a{
    text-decoration:none;
}
/* ================= NAVBAR ================= */
.navbar{
    width:100%;
    background:#1e5631;
    padding:18px 60px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:fixed;
    top:0;
    z-index:1000;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.logo{
    color:white;
    font-size:26px;
    font-weight:bold;
}

/* LINKS */
.nav-links{
    display:flex;
    gap:25px;
}

.nav-links a{
    color:white;
    text-decoration:none;
    font-size:15px;
    display:flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:8px;
    transition:0.3s;
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
        <a href="sales.php">
            <i class="fa-solid fa-chart-line icon-sales"></i> Sales
        </a>
        <a href="price_update.php" class="active">
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

<h2>📊 Paddy Price Update Panel</h2>
<p>Admin can update live market prices for farmers</p>

<table>

<tr>
    <th>🌾 Paddy Type</th>
    <th>💰 Current Price</th>
    <th>✏️ Update Price</th>
</tr>

<?php while($row = $res->fetch_assoc()): ?>

<tr>

<td>
    🌾 <?= htmlspecialchars($row['name']) ?>
</td>

<td>
    <span class="badge">
        Rs. <?= number_format($row['price'],2) ?> / kg
    </span>
</td>

<td>

<form method="POST">

    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <input type="number"
           step="0.01"
           name="price"
           value="<?= $row['price'] ?>"
           required>

    <button type="submit" name="update">
        Update
    </button>

</form>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>
</html>