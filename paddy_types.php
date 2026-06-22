<?php
include 'config/db.php';

$res = $conn->query("SELECT name, price FROM paddy_types;");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Current Paddy Prices - RiceLink</title>

<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

.container{
    max-width:1000px;
    margin:120px auto;
    padding:20px;
}

h1{
    text-align:center;
    color:#1e5631;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-top:30px;
}

.card{
    background:white;
    padding:20px;
    border-radius:14px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    text-align:center;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.price{
    font-size:26px;
    font-weight:bold;
    color:#2e7d32;
    margin-top:10px;
}

.icon{
    font-size:40px;
    color:#1e5631;
}

.note{
    text-align:center;
    margin-top:20px;
    color:gray;
}

/* 🎨 ICON COLORS */
.nav-links a:nth-child(1) i{  /* Home */
    color:#4CAF50;
}

.nav-links a:nth-child(2) i{  /* Paddy */
    color:#FF9800;
}

.nav-links a:nth-child(3) i{  /* About */
    color:#2196F3;
}

.nav-links a:nth-child(4) i{  /* Services */
    color:#9C27B0;
}

.nav-links a:nth-child(5) i{  /* Contact */
    color:#F44336;
}

/* HOVER EFFECT */
.nav-links a:hover{
    background:white;
    color:#1e5631;
}

.nav-links a:hover i{
    color:#1e5631; /* icon changes with text */
}

.nav-links a.active{
    background:#ffffff;
    color:#1e5631;
    font-weight:600;
}

/* keep icon visible in active state */
.nav-links a.active i{
    color:#1e5631 !important;
}
</style>

</head>

<body>
    <nav class="navbar">
    <div class="logo">🌾 RiceLink</div>

    <div class="nav-links">
        <a href="index.php"><i class="fa fa-home"></i> Home</a>
        <a href="paddy_types.php" class="active"><i class="fa fa-seedling"></i> Paddy Prices</a>
        <a href="../index.php#about"><i class="fa fa-circle-info"></i> About</a>
        <a href="../index.php#services"><i class="fa fa-briefcase"></i> Services</a>
        <a href="../index.php#contact"><i class="fa fa-phone"></i> Contact</a>
    </div>
</nav>

<div class="container">

<h1>🌾 Current Paddy Prices</h1>

<div class="grid">

<?php while($r = $res->fetch_assoc()): ?>

<div class="card">

    <div class="icon">
        <i class="fa-solid fa-seedling"></i>
    </div>

    <h2><?= $r['name'] ?></h2>

    <div class="price">
        Rs. <?= $r['price'] ?> / Kg
    </div>

</div>

<?php endwhile; ?>

</div>

<div class="note">
📢 Prices may change daily based on market conditions
</div>

</div>

</body>
</html>