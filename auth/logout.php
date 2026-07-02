<?php
session_start();

$_SESSION = [];
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logout</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    *{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#eef1f6;
    padding:20px;
}

.card{
    width:100%;
    max-width:380px;
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(22,33,62,.08);
    padding:36px 32px;
    text-align:center;
    animation:pop .5s ease;
}

@keyframes pop{
    from{
        transform:scale(.94);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

.status-seal{
    width:56px;
    height:56px;
    margin:0 auto 20px;
    border-radius:50%;
    border:1.5px solid #b8902a;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:700;
    color:#b8902a;
}

.page-eyebrow{
    display:block;
    font-size:12px;
    font-weight:700;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:#b8902a;
    margin-bottom:8px;
}

.title{
    font-size:24px;
    font-weight:800;
    letter-spacing:-.01em;
    color:#16213e;
    margin:0 0 10px;
}

.title-rule{
    width:56px;
    height:3px;
    background:#b8902a;
    border:none;
    margin:0 auto 14px;
}

.page-meta{
    font-size:14px;
    color:#94a3b8;
    margin-bottom:26px;
    line-height:1.5;
}

.btn-primary{
    width:100%;
    background:#2c3e67;
    color:#ffffff;
    border:none;
    padding:12px 22px;
    font-size:14px;
    font-weight:600;
    border-radius:8px;
    cursor:pointer;
    transition:background .15s ease;
}

.btn-primary:hover{
    background:#16213e;
}

.form-footnote{
    margin-top:16px;
    font-size:12px;
    color:#94a3b8;
}

@media (prefers-reduced-motion:reduce){
    .card{
        animation:none;
    }
}
</style>
</head>

<body>

<div class="card">

    <div class="status-seal">&#10003;</div>

    <span class="page-eyebrow">Dossiers scolaires</span>
    <h1 class="title">Déconnecté</h1>
    <hr class="title-rule">
    <div class="page-meta">Vous avez été déconnecté de votre session en toute sécurité.</div>

    <button onclick="goLogin()" class="btn-primary">Aller à la connexion</button>

    <div class="form-footnote" id="countdown">Redirection dans 3s…</div>

</div>

<script src="../assets/js/script.js"></script>

<script>

function goLogin(){
    window.location.href = "login.php";
}

let secondsLeft = 3;
const countdownEl = document.getElementById("countdown");

const interval = setInterval(() => {
    secondsLeft--;

    if (secondsLeft > 0) {
        countdownEl.textContent = "Redirecting in " + secondsLeft + "s…";
    } else {
        clearInterval(interval);
        goLogin();
    }
}, 1000);

</script>

</body>
</html>