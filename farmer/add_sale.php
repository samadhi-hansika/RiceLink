<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$res = $conn->query("SELECT * FROM paddy_types");


?>

<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Add Sale - RiceLink</title>

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
    max-width:900px;
    margin:120px auto;
    padding:20px;
}

.form-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

label{
    font-weight:600;
    margin-top:10px;
    display:block;
}

input,select,textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    border:1px solid #ddd;
    border-radius:8px;
}

.price-box{
    background:#e8f5e9;
    padding:10px;
    margin-top:10px;
    border-radius:8px;
    color:#1e5631;
    font-weight:bold;
}

button{
    margin-top:15px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1e5631;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#2f7d32;
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
        <a href="add_sale.php" class="active">➕ Add Sale</a>
        <a href="my_sales.php" >📄 My Sales</a>
        <a href="../auth/logout.php">🚪 Logout</a>
    </div>
</div>

<!-- FORM -->
<div class="page-container">

<div class="form-card">

<h2>🌾 අස්වැන්න විකුණන්න</h2>
<p>ඔබගේ අස්වැන්න විස්තර ඇතුළත් කරන්න</p>

<form action="save_sales.php" method="POST">
    <!-- PADDY -->
<label>🌾 වී වර්ගය</label>
<select name="type" id="paddy" required>
    <option value="">වී වර්ගය තෝරන්න</option>

    <?php while($r = $res->fetch_assoc()): ?>
        <option value="<?= $r['id'] ?>" data-price="<?= $r['price'] ?>">
            <?= $r['name'] ?>
        </option>
    <?php endwhile; ?>
</select>

<!-- PRICE -->
<div class="price-box">
    💰 Price per kg: Rs. <span id="price">0</span>
</div>

<!-- QTY -->
<label>⚖️ ප්‍රමාණය (Kg)</label>
<input type="number" id="qty" name="qty" required>

<!-- TOTAL -->
<div class="price-box">
    🧮 Total: Rs. <span id="total">0</span>
</div>

<!-- ADDRESS (NEW) -->
<label>📍 Address / ගොවි බිම ලිපිනය</label>
<textarea name="address" rows="3" placeholder="උදා: Kurunegala, Rideegama, Field No 12" required></textarea>

<button type="submit">
    🚀 Submit Sale
</button>

</form>

</div>

</div>

<!-- SCRIPT -->
<script>
let price = 0;

document.getElementById("paddy").addEventListener("change", function(){
    price = this.options[this.selectedIndex].dataset.price || 0;
    document.getElementById("price").innerText = price;
    calculate();
});

document.getElementById("qty").addEventListener("input", calculate);

function calculate(){
    let qty = document.getElementById("qty").value || 0;
    let total = qty * price;
    document.getElementById("total").innerText = total.toFixed(2);
}
</script>

</body>
</html>