<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RiceLink | Smart Farmer Platform</title>

<link rel="stylesheet" href="/RiceLink/RiceLink/assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f7fb;
    background-image: url('/RiceLink/RiceLink/assets/homeimage.jpg');
    background-size: cover;
    background-position: center;
}


.navbar{
    width:100%;
    background:#1e5631;
    padding:18px 70px;
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
    font-size:28px;
    font-weight:bold;
}

.nav-links{
    display:flex;
    gap:30px;
}

.nav-links a{
    color:white;
    text-decoration:none;
    transition:.3s;
    font-size:15px;
}
.nav-links a i{
    margin-right:6px;
    font-size:14px;
}

.nav-links a{
    display:flex;
    align-items:center;
    gap:6px;
}


.nav-links a:hover{
    background:white;
    color:#1e5631;
}

/* NAV LINKS */
.nav-links a{
    display:flex;
    align-items:center;
    gap:8px;
    color:white;
    text-decoration:none;
    font-weight:500;
}

/* ICON BASE */
.nav-links a i{
    font-size:16px;
    transition:0.3s;
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

/* HERO */
.hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    padding:120px 10%;
    background:linear-gradient(135deg,#eef8f0,#ffffff);
}

.hero-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:100%;
    gap:60px;
}

.hero-left{
    flex:1;
}

.hero-left h1{
    font-size:55px;
    color:#1e5631;
    margin-bottom:20px;
    line-height:1.2;
}

.hero-left p{
    font-size:18px;
    color:#555;
    line-height:1.8;
    max-width:650px;
}

/* LOGIN CARD */
.login-card{
    width:420px;
    background:white;
    padding:40px;
    border-radius:22px;
    box-shadow:0 15px 45px rgba(0,0,0,0.08);
}

.login-card h2{
    text-align:center;
    margin-bottom:25px;
    color:#1e5631;
}

.input-group{
    margin-bottom:18px;
}

.input-group input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
    font-size:15px;
}

.input-group input:focus{
    border-color:#1e5631;
}

.login-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#1e5631;
    color:white;
    cursor:pointer;
    font-size:16px;
}

.login-btn:hover{
    background:#2f7c3d;
}

/* REGISTER LINK */
.register-link{
    margin-top:20px;
    text-align:center;
}

.register-link a{
    color:#1e5631;
    font-weight:600;
    text-decoration:none;
}

/* ABOUT */
.about{
    padding:100px 10%;
    background:white;
}

.about-title{
    text-align:center;
    font-size:40px;
    color:#1e5631;
    margin-bottom:20px;
}

.about-desc{
    text-align:center;
    max-width:900px;
    margin:auto;
    color:#666;
    line-height:1.9;
    font-size:17px;
}

.about-grid{
    margin-top:60px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}

.about-card{
    background:#f8faf8;
    padding:35px;
    border-radius:18px;
    text-align:center;
    transition:.3s;
}

.about-card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 25px rgba(0,0,0,0.06);
}

.about-card h3{
    margin:15px 0;
    color:#1e5631;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.55);
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal-box{
    background:white;
    width:420px;
    padding:35px;
    border-radius:20px;
    position:relative;
}

.close{
    position:absolute;
    right:20px;
    top:15px;
    cursor:pointer;
    font-size:28px;
}

.modal-box h2{
    text-align:center;
    margin-bottom:25px;
    color:#1e5631;
}

/* MOBILE */
@media(max-width:900px){

.hero-container{
    flex-direction:column;
}

.login-card{
    width:100%;
}

.hero-left h1{
    font-size:38px;
}

.navbar{
    padding:15px 25px;
}

.nav-links{
    gap:15px;
}

}
</style>
</head>

<body>

<nav class="navbar">
    <div class="logo">🌾 RiceLink</div>

    <div class="nav-links">
        <a href="/RiceLink/RiceLink/index.php" class="active"><i class="fa fa-home"></i> Home</a>
        <a href="/RiceLink/RiceLink/paddy_types.php"><i class="fa fa-seedling"></i> Paddy Prices</a>
        <a href="/RiceLink/RiceLink/index.php#about"><i class="fa fa-circle-info"></i> About</a>
        <a href="/RiceLink/RiceLink/index.php#services"><i class="fa fa-briefcase"></i> Services</a>
        <a href="/RiceLink/RiceLink/index.php#contact"><i class="fa fa-phone"></i> Contact</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">

<div class="hero-container">

<div class="hero-left">

<h1>Empowering Farmers to Sell Smarter, Faster, and Fairer with RiceLink</h1>

<p>
RiceLink helps farmers connect directly with buyers, manage harvest details,
share locations, set pickup times, and receive fair payments — all in one platform.
</p>

</div>

<div class="login-card">

<h2>Farmer Login / ගොවි ලොගින්</h2>

<form action="auth/login.php" method="POST">

<div class="input-group">
<input type="email" name="email" placeholder="Email / ඊමේල්" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Password / මුරපදය" required>
</div>

<button class="login-btn">Login / ඇතුල් වන්න</button>

</form>

<div class="register-link">
Don't have an account? / ගිණුමක් නැද්ද?

<a href="#" onclick="openRegister()">Register Now / ලියාපදිංචි වන්න</a>
</div>

</div>

</div>

</section>

<!-- ABOUT -->
<section class="about" id="about">
    <div class="about-container">

        <h2>අප ගැන – RiceLink</h2>

        <p>
            RiceLink යනු ගොවීන්ට තම අස්වැන්න මැදිවෙන්නන් (middlemen) නොමැතිව 
            සෘජුවම වෙළඳපොළට විකුණා ගැනීමට උපකාර කරන නවීන ඩිජිටල් පද්ධතියකි.
        </p>

        <div class="about-grid">

            <div class="about-card">
                <h3>🌾 අස්වැන්න විකිණීම</h3>
                <p>
                    ඔබගේ වී වර්ගය, ප්‍රමාණය, මිල සහ ලබාදෙන ස්ථානය පහසුවෙන් ඇතුළත් කර 
                    buyer ලට සෘජුවම ලබා දිය හැක.
                </p>
            </div>

            <div class="about-card">
                <h3>📍 ස්ථාන (Location Tracking)</h3>
                <p>
                    ගොවිබිම් සහ ලබාදෙන ස්ථාන map එක මගින් හඳුනාගැනීමට හැකිය. 
                    Buyer ලට පහසුවෙන් ඔබව සොයාගත හැක.
                </p>
            </div>

            <div class="about-card">
                <h3>💰 සාධාරණ මිල</h3>
                <p>
                    මැදිවෙන්නන් ඉවත් කිරීමෙන් ඔබට වෙළඳපොළට සාධාරණ සහ වැඩි මිලක් ලබා ගත හැක.
                </p>
            </div>

            <div class="about-card">
                <h3>📱 SMS & දැනුම්දීම්</h3>
                <p>
                    ඔබගේ අස්වැන්නට buyer කෙනෙක් interest දක්වන විට SMS හෝ alert මගින් දැනුම්දීම් ලැබේ.
                </p>
            </div>

        </div>

    </div>
</section>

<section class="about" id="services">

    <div class="about-container">

        <h2>Our Services / අපගේ සේවාවන්</h2>

        <p>
            RiceLink platform එක ගොවීන්ට තම අස්වැන්න පහසුවෙන් විකිණීමට සහ කළමනාකරණයට උපකාර කරයි.
        </p>

        <div class="about-grid">

            <div class="about-card">
                <h3>🌾 Harvest Selling</h3>
                <p>
                    ඔබගේ වී අස්වැන්න price, quantity සහ location සමඟ සෘජුවම buyer ලට විකුණන්න.
                </p>
            </div>

            <div class="about-card">
                <h3>📍 Location Support</h3>
                <p>
                    GPS මගින් ඔබගේ ගොවිබිම සහ pickup location පහසුවෙන් share කරන්න.
                </p>
            </div>

            <div class="about-card">
                <h3>💰 Fair Pricing</h3>
                <p>
                    මැදිවෙන්නන් නැති නිසා ඔබට සාධාරණ market price එක ලැබේ.
                </p>
            </div>

            <div class="about-card">
                <h3>📲 Instant Alerts</h3>
                <p>
                    Buyer කෙනෙක් interest දක්වූ විට SMS / notification ලැබේ.
                </p>
            </div>

        </div>

    </div>

</section>

<section class="about" id="contact">

    <div class="about-container">

        <h2>Contact Us / අපව සම්බන්ධ කරගන්න</h2>

        <p>
            ඔබට උදව් අවශ්‍ය නම් අපිව පහසුවෙන් සම්බන්ධ කරගන්න.
        </p>

        <div class="about-grid">

            <div class="about-card">
                <h3>📞 Phone</h3>
                <p>+94 77 123 4567</p>
            </div>

            <div class="about-card">
                <h3>📧 Email</h3>
                <p>support@ricelink.lk</p>
            </div>

            <div class="about-card">
                <h3>📍 Address</h3>
                <p>RiceLink HQ, Colombo, Sri Lanka</p>
            </div>

            <div class="about-card">
                <h3>⏰ Support Hours</h3>
                <p>Mon - Sat (8.00 AM - 6.00 PM)</p>
            </div>

        </div>

    </div>

</section>

<div class="modal" id="registerModal">

<div class="modal-box">

<span class="close" onclick="closeRegister()">&times;</span>

<h2>Register / ලියාපදිංචි වන්න</h2>

<form action="/RiceLink/RiceLink/auth/register.php" method="POST">

<div class="input-group">
<input type="text" name="name" placeholder="Full Name / සම්පූර්ණ නම" required>
</div>

<div class="input-group">
<input type="email" name="email" placeholder="Email / ඊමේල්" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Password / මුරපදය" required>
</div>

<button class="login-btn">Create Account / ගිණුම සාදන්න</button>

</form>

</div>

</div>

<script>
function openRegister(){
    document.getElementById("registerModal").style.display="flex";
}

function closeRegister(){
    document.getElementById("registerModal").style.display="none";
}

window.onclick=function(e){
    let modal=document.getElementById("registerModal");

    if(e.target==modal){
        modal.style.display="none";
    }
}
</script>

</body>
</html> 