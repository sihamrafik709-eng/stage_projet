<?php include("../includes/navbar.php"); ?>
<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$students = $conn->query("SELECT * FROM students ORDER BY first_name");

$error = "";

if (isset($_POST['save'])) {
    $student_id = $_POST['student_id'] ?? '';
    $subject    = trim($_POST['subject'] ?? '');
    $score      = $_POST['score'] ?? '';

    if ($student_id === '' || $subject === '' || $score === '') {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($score) || $score < 0 || $score > 20) {
        $error = "La note doit être un nombre entre 0 et 20.";
    } else {
        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject, score) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $student_id, $subject, $score);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Une erreur est survenue lors de l'enregistrement.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter une note — SMS Admin</title>

<style>
    :root{
        --navy:#0f1b3c;
        --navy-light:#16213e;
        --gold:#c9a44c;
        --gold-light:#e3c878;
        --danger:#c0392b;
        --bg:#f4f5f7;
        --card-border:#e6e2d6;
        --text-dark:#1c1f2a;
        --text-muted:#6b7280;
    }

    *{box-sizing:border-box;}

    body{
        margin:0;
        font-family:'Segoe UI', Arial, sans-serif;
        background:var(--bg);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:20px;
    }

    .container{
        width:100%;
        max-width:420px;
        background:#fff;
        padding:32px 30px;
        border-radius:14px;
        border:1px solid var(--card-border);
        box-shadow:0 4px 18px rgba(15,27,60,0.08);
        position:relative;
        overflow:hidden;
    }

    .container::before{
        content:"";
        position:absolute;
        top:0; left:0; right:0;
        height:5px;
        background:linear-gradient(90deg, var(--navy), var(--gold));
    }

    .header-icon{
        width:54px;
        height:54px;
        border-radius:12px;
        background:#f1edfb;
        color:#6d4fc7;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:26px;
        margin:6px auto 16px auto;
    }

    h2{
        text-align:center;
        color:var(--navy);
        margin:0 0 4px 0;
        font-size:21px;
    }

    .subtitle{
        text-align:center;
        color:var(--text-muted);
        font-size:13.5px;
        margin-bottom:24px;
    }

    label{
        display:block;
        font-size:13px;
        font-weight:600;
        color:var(--navy);
        margin-bottom:6px;
        margin-top:16px;
    }

    input, select{
        width:100%;
        padding:11px 12px;
        border:1px solid var(--card-border);
        border-radius:8px;
        font-size:14px;
        font-family:inherit;
        background:#fafafa;
        transition:border-color .2s ease;
    }

    input:focus, select:focus{
        outline:none;
        border-color:var(--gold);
        background:#fff;
    }

    button{
        width:100%;
        padding:12px;
        margin-top:24px;
        background:var(--navy-light);
        color:#fff;
        border:none;
        border-radius:8px;
        font-size:14.5px;
        font-weight:700;
        letter-spacing:0.3px;
        cursor:pointer;
        transition:background .2s ease;
    }

    button:hover{
        background:var(--navy);
    }

    .error-box{
        background:#fdecea;
        color:var(--danger);
        border:1px solid #f5c6c0;
        padding:10px 14px;
        border-radius:8px;
        font-size:13.5px;
        margin-bottom:10px;
    }

    .back-link{
        display:block;
        text-align:center;
        margin-top:18px;
        font-size:13px;
        color:var(--text-muted);
        text-decoration:none;
    }

    .back-link:hover{
        color:var(--navy);
    }
</style>
</head>

<body>

<div class="container">

    <div class="header-icon">📈</div>
    <h2>Ajouter une note</h2>
    <p class="subtitle">Enregistrer la note d'un étudiant dans une matière</p>

    <?php if ($error): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">

        <label for="student_id">Étudiant</label>
        <select id="student_id" name="student_id" required>
            <option value="">Sélectionner un étudiant</option>
            <?php while ($row = $students->fetch_assoc()): ?>
                <option value="<?php echo (int)$row['id']; ?>">
                    <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="subject">Matière</label>
        <select id="subject" name="subject" required>
            <option value="">Sélectionner une matière</option>
            <option value="Math">Math</option>
            <option value="Francais">Francais</option>
            <option value="Histoire">Histoire</option>
            <option value="Sciences">Sciences</option>
            <option value="Anglais">Anglais</option>
        </select>

        <label for="score">Note (sur 20)</label>
        <input type="number" id="score" name="score" min="0" max="20" step="0.25" required>

        <button type="submit" name="save">Enregistrer</button>

    </form>

    <a href="list.php" class="back-link">← Retour à la liste des notes</a>

</div>

</body>
</html>