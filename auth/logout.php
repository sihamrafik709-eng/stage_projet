<?php
session_start();

// destroy session
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
    :root {
        --bg-page: #eef1f6;
        --surface: #ffffff;
        --navy-900: #16213e;
        --navy-700: #2c3e67;
        --gold-500: #b8902a;
        --slate-700: #334155;
        --slate-400: #94a3b8;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--bg-page);
        padding: 20px;
    }

    .card {
        width: 100%;
        max-width: 380px;
        background: var(--surface);
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
        padding: 36px 32px;
        text-align: center;
        animation: pop 0.5s ease;
    }

    @keyframes pop {
        from { transform: scale(0.94); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .status-seal {
        width: 56px;
        height: 56px;
        margin: 0 auto 20px;
        border-radius: 50%;
        border: 1.5px solid var(--gold-500);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: var(--gold-500);
    }

    .page-eyebrow {
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--gold-500);
        margin-bottom: 8px;
    }

    .title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.01em;
        color: var(--navy-900);
        margin: 0 0 10px;
    }

    .title-rule {
        width: 56px;
        height: 3px;
        background: var(--gold-500);
        border: none;
        margin: 0 auto 14px;
    }

    .page-meta {
        font-size: 14px;
        color: var(--slate-400);
        margin-bottom: 26px;
        line-height: 1.5;
    }

    .btn-primary {
        width: 100%;
        background: var(--navy-700);
        color: #ffffff;
        border: none;
        padding: 12px 22px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-primary:hover {
        background: var(--navy-900);
    }

    .form-footnote {
        margin-top: 16px;
        font-size: 12px;
        color: var(--slate-400);
    }

    @media (prefers-reduced-motion: reduce) {
        .card {
            animation: none;
        }
    }
</style>
</head>

<body>

<div class="card">

    <div class="status-seal">&#10003;</div>

    <span class="page-eyebrow">School Records</span>
    <h1 class="title">Logged Out</h1>
    <hr class="title-rule">
    <div class="page-meta">You have been securely logged out of your session.</div>

    <button onclick="goLogin()" class="btn-primary">Go to Login</button>

    <div class="form-footnote" id="countdown">Redirecting in 3s…</div>

</div>

<script src="../assets/js/script.js"></script>

<script>

function goLogin(){
    window.location.href = "login.php";
}

// countdown + auto redirect
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