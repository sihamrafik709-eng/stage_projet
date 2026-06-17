<?php
session_start();
include("../config/db.php");

$message = "";
$username = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['username'];

        header("Location: ../dashboard/index.php");
        exit();
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
    :root {
        --bg-page: #eef1f6;
        --surface: #ffffff;
        --navy-900: #16213e;
        --navy-700: #2c3e67;
        --gold-500: #b8902a;
        --slate-700: #334155;
        --slate-400: #94a3b8;
        --red-600: #c0392b;
        --red-050: #fdecea;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-page);
        padding: 24px;
    }

    .login-shell {
        width: 100%;
        max-width: 920px;
        min-height: 540px;
        display: flex;
        background: var(--surface);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(22, 33, 62, 0.16);
        overflow: hidden;
        animation: rise 0.6s ease;
    }

    @keyframes rise {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }


    .brand-panel {
        position: relative;
        flex: 0 0 42%;
        background: linear-gradient(165deg, var(--navy-900) 0%, var(--navy-700) 100%);
        padding: 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: #ffffff;
        overflow: hidden;
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
        pointer-events: none;
    }

    .brand-seal {
        position: relative;
        z-index: 1;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 1.5px solid var(--gold-500);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 24px;
    }

    .brand-seal::after {
        content: "";
        position: absolute;
        inset: -8px;
        border: 1px solid rgba(184, 144, 42, 0.4);
        border-radius: 50%;
    }

    .brand-title {
        position: relative;
        z-index: 1;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.01em;
        margin: 0 0 12px;
        line-height: 1.3;
    }

    .brand-tagline {
        position: relative;
        z-index: 1;
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.7);
        margin: 0 0 28px;
        max-width: 320px;
    }

    .brand-divider {
        position: relative;
        z-index: 1;
        width: 40px;
        height: 2px;
        background: var(--gold-500);
        margin-bottom: 24px;
    }

    .brand-features {
        position: relative;
        z-index: 1;
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
    }

    .brand-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .brand-features li::before {
        content: "✓";
        color: var(--gold-500);
        font-weight: 700;
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
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--gold-500);
        margin-bottom: 8px;
    }

    .title {
        font-size: 28px;
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
        margin: 0 0 12px;
    }

    .page-meta {
        font-size: 14px;
        color: var(--slate-400);
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
        background: var(--red-050);
        border-color: var(--red-600);
        color: var(--red-600);
    }

    .field-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--slate-700);
        margin-bottom: 8px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 11px 14px;
        font-size: 15px;
        color: var(--navy-900);
        border: 1.5px solid #dde2ec;
        border-radius: 8px;
        margin-bottom: 20px;
        transition: border-color 0.15s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: var(--navy-700);
    }

    .btn-primary {
        width: 100%;
        background: var(--navy-700);
        color: #ffffff;
        border: none;
        padding: 13px 22px;
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
        margin-top: 20px;
        font-size: 12px;
        color: var(--slate-400);
        text-align: center;
    }

    @media (max-width: 760px) {
        .login-shell {
            flex-direction: column;
            min-height: 0;
        }

        .brand-panel {
            flex: none;
            padding: 32px 28px;
        }

        .brand-tagline,
        .brand-features {
            display: none;
        }

        .form-panel {
            padding: 36px 28px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .login-shell {
            animation: none;
        }
    }
</style>
</head>
<body>

<div class="login-shell">

    <div class="brand-panel">
        <div class="brand-seal">🎓</div>
        <h1 class="brand-title">Système de Gestion des Étudiants</h1>
        <p class="brand-tagline">A unified platform for managing students, teachers, subjects, and grades.</p>
        <div class="brand-divider"></div>
        <ul class="brand-features">
            <li>Real-time grade tracking</li>
            <li>Simplified class management</li>
            <li>Secure, role-based access</li>
        </ul>
    </div>

    <div class="form-panel">
        <div class="form-panel-inner">

            <span class="page-eyebrow">School Records</span>
            <h1 class="title">Sign In</h1>
            <hr class="title-rule">
            <div class="page-meta">Enter your credentials to access your dashboard.</div>

            <?php if (!empty($message)) { ?>
                <div class="message error" id="msg">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <label class="field-label" for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    value="<?php echo htmlspecialchars($username); ?>"
                    required
                    autofocus
                >

                <label class="field-label" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

                <button type="submit" name="login" class="btn-primary">
                    Sign In
                </button>

            </form>

            <div class="form-footnote">Authorized personnel only.</div>

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