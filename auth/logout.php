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

<style>

body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial;
    background:linear-gradient(135deg,#ff4d4d,#ff9966);
    overflow:hidden;
}

/* card */
.box{
    background:white;
    padding:40px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    animation:pop 0.6s ease;
    position:relative;
    z-index:2;
}

@keyframes pop{
    from{
        transform:scale(0.7);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

h1{
    color:#333;
}

p{
    color:#777;
    margin-bottom:20px;
}

button{
    padding:12px 20px;
    border:none;
    border-radius:10px;
    background:#ff4d4d;
    color:white;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
    background:#e60000;
}

/* particles */
.particle{
    position:absolute;
    width:8px;
    height:8px;
    background:white;
    border-radius:50%;
    animation:move 6s linear infinite;
    opacity:0.5;
}

@keyframes move{
    from{
        transform:translateY(100vh);
    }
    to{
        transform:translateY(-10vh);
    }
}

</style>
</head>

<body>

<div class="box">
    <h1>👋 Logged out</h1>
    <p>You have been successfully logged out</p>

    <button onclick="goLogin()">Go to Login</button>
</div>

<script>

// redirect button
function goLogin(){
    window.location.href = "login.php";
}

// particles
for(let i=0;i<40;i++){
    let p = document.createElement("div");
    p.classList.add("particle");
    p.style.left = Math.random()*100 + "vw";
    p.style.animationDuration = (3 + Math.random()*5) + "s";
    p.style.opacity = Math.random();

    document.body.appendChild(p);
}

</script>

<?php
// safety redirect after 3 sec
echo "<script>
setTimeout(()=> {
    window.location.href='login.php';
}, 3000);
</script>";
?>

</body>
</html>