<?php
session_start();
include("../config/db.php");

$message = "";
$username = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['student_id'] = $user['student_id'];

            if ($user['role'] == "student") {
                
            header("Location: ../etudiant/index.php");
        } else {
            header("Location: ../dashboard/index.php");
        }
        exit();
        } else {
            $message = "Wrong username or password.";
        }

    } else {
        $message = "Wrong username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef1f6;
    padding: 24px;
    font-family: Arial, sans-serif;
}

.login-shell {
    width: 100%;
    max-width: 920px;
    min-height: 540px;
    display: flex;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
    overflow: hidden;
}

.brand-panel {
    flex: 0 0 42%;
    background: linear-gradient(165deg, #16213e, #2c3e67);
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: #ffffff;
    position: relative;
}

.brand-panel::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        to bottom,
        rgba(184, 144, 42, 0.08) 0px,
        rgba(184, 144, 42, 0.08) 1px,
        transparent 1px,
        transparent 34px
    );
}

.brand-seal {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 2px solid #b8902a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 24px;
    position: relative;
}

.brand-title {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 12px;
}

.brand-tagline {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 28px;
    max-width: 320px;
}

.brand-divider {
    width: 40px;
    height: 2px;
    background: #b8902a;
    margin-bottom: 24px;
}

.brand-features {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 13px;
}

.brand-features li {
    margin-bottom: 12px;
    position: relative;
    padding-left: 18px;
}

.brand-features li::before {
    content: "✓";
    color: #b8902a;
    position: absolute;
    left: 0;
}

.form-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 44px;
}

.form-panel-inner {
    width: 100%;
    max-width: 320px;
}

.page-eyebrow {
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 2px;
    color: #b8902a;
    margin-bottom: 8px;
    display: block;
}

.title {
    font-size: 28px;
    font-weight: bold;
    color: #16213e;
    margin: 0 0 10px;
}

.title-rule {
    width: 56px;
    height: 3px;
   
    border: none;
    margin-bottom: 12px;
}

.page-meta {
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 24px;
}

.message {
    font-size: 14px;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 18px;
    border: 1px solid transparent;
}

.message.error {
    background: #fdecea;
    border-color: #c0392b;
    color: #c0392b;
}

.field-label {
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    color: #334155;
    display: block;
    margin-bottom: 8px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 11px 14px;
    font-size: 15px;
    border: 1.5px solid #dde2ec;
    border-radius: 8px;
    margin-bottom: 20px;
}

input:focus {
    outline: none;
    border-color: #2c3e67;
}

.btn-primary {
    width: 100%;
    background: #2c3e67;
    color: #fff;
    border: none;
    padding: 13px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
}

.btn-primary:hover {
    background: #16213e;
}

.form-footnote {
    margin-top: 20px;
    font-size: 12px;
    color: #94a3b8;
    text-align: center;
}

@media (max-width: 760px) {
    .login-shell {
        flex-direction: column;
    }

    .brand-panel {
        padding: 32px 28px;
    }

    .form-panel {
        padding: 36px 28px;
    }
}
</style>
</head>
<body>

<div class="login-shell">

    <div class="brand-panel">
        <div class="brand-seal">🎓</div>
        <h1 class="brand-title">Système de Gestion des Étudiants</h1>
        <p class="brand-tagline">Une plateforme unifiée pour gérer les étudiants, les enseignants, les matières et les notes.</p>
        <div class="brand-divider"></div>
        <ul class="brand-features">
            <li>Suivi des notes en temps réel</li>
            <li>Gestion simplifiée des classes</li>
            <li>Accès sécurisé basé sur les rôles</li>
        </ul>
    </div>

    <div class="form-panel">
        <div class="form-panel-inner">

            <span class="page-eyebrow">Dossiers scolaires</span>
            <h1 class="title">Se connecter</h1>
            <hr class="title-rule">
            <div class="page-meta">Saisissez vos identifiants pour accéder à votre tableau de bord.</div>

            <?php if (!empty($message)) { ?>
                <div class="message error" id="msg">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <label class="field-label" for="username">Nom d'utilisateur</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    value="<?php echo htmlspecialchars($username); ?>"
                    required
                    autofocus
                >

                <label class="field-label" for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

                <button type="submit" name="login" class="btn-primary">
                    Se connecter
                </button>

            </form>

            <div class="form-footnote">Réservé au personnel autorisé</div>

        </div>
    </div>

</div>

<script src="../assets/js/script.js"></script>

<script>
setTimeout(function(){
    let msg = document.getElementById("msg");

    if(msg){
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";

        setTimeout(() => {
            msg.remove();
        }, 500);
    }
}, 3000);
</script>

</body>
</html>