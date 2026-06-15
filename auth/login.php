<?php
session_start();
include("../config/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>


*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(-45deg,#4facfe,#00f2fe,#43e97b,#38f9d7);
    background-size:400% 400%;
    animation:bg 12s ease infinite;
    overflow:hidden;
}

@keyframes bg{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.particles{
    position:fixed;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:0;
    overflow:hidden;
}

.particles span{
    position:absolute;
    width:6px;
    height:6px;
    background:white;
    border-radius:50%;
    opacity:0.5;
    animation:float linear infinite;
}

@keyframes float{
    from{transform:translateY(100vh);}
    to{transform:translateY(-10vh);}
}

.login-box{
    width:500px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    position:relative;
    z-index:2;
    animation:show 0.7s ease;
}

@keyframes show{
    from{transform:translateY(-30px);opacity:0;}
    to{transform:translateY(0);opacity:1;}
}

.login-box h1{
    text-align:center;
    margin-bottom:10px;
    color:#333;
}

.login-box p{
    text-align:center;
    margin-bottom:20px;
    color:#777;
}

.login-box input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
}

.login-box input:focus{
    border-color:#4facfe;
    box-shadow:0 0 10px rgba(79,172,254,0.3);
    outline:none;
}

.login-box button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#4facfe;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.login-box button:hover{
    transform:scale(1.03);
    background:#3a8dde;
}

.error{
    background:#ffe5e5;
    color:red;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
}

</style>
</head>

<body>

<div class="particles"></div>

<div class="login-box">

    <h1>🎓 Système  Gestion des Étudiants </h1>
    <p>Welcome Back</p>

<?php
if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['username'];

        header("Location: ../dashboard/index.php");
        exit();

    } else {
        echo '<div class="error">Wrong username or password</div>';
    }
}
?>

<form method="POST">

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

</form>

</div>

<script>
for(let i=0;i<50;i++){
    let span = document.createElement("span");
    span.style.left = Math.random()*100 + "vw";
    span.style.animationDuration = (4 + Math.random()*6) + "s";
    span.style.opacity = Math.random();
    span.style.position = "absolute";

    document.querySelector(".particles").appendChild(span);
}
</script>

</body>
</html>