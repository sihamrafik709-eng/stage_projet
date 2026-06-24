<?php include("../includes/navbar.php"); ?>
<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = (int) ($_GET['id'] ?? 0);

if ($id === 0) {
    header("Location: list.php");
    exit();
}

$error = "";

// --- Mise à jour ---
if (isset($_POST['save'])) {
    $student_id = $_POST['student_id'] ?? '';
    $date       = $_POST['date'] ?? '';
    $status     = $_POST['status'] ?? '';

    if ($student_id === '' || $date === '' || $status === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("UPDATE attendance SET student_id = ?, date = ?, status = ? WHERE id = ?");
        $stmt->bind_param("issi", $student_id, $date, $status, $id);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Une erreur est survenue lors de la mise à jour.";
        }
        $stmt->close();
    }
}

// --- Récupération de la présence existante ---
$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$attendance = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$attendance) {
    header("Location: list.php");
    exit();
}

$students = $conn->query("SELECT * FROM students ORDER BY first_name");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier une présence — SMS Admin</title>

<style>
    :root{
        --navy:#0f1b3c;
        --navy-light:#16213e;
        --gold:#c9a44c;
        --gold-light:#e3c878;
        --forest:#2e7d4f;
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
        background:#fdf1e7;
        color:#c9821f;
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

    .btn-row{
        display:flex;
        gap:10px;
        margin-top:24px;
    }

    button, .btn-cancel{
        flex:1;
        padding:12px;
        border:none;
        border-radius:8px;
        font-size:14.5px;
        font-weight:700;
        letter-spacing:0.3px;
        cursor:pointer;
        text-align:center;
        text-decoration:none;
        font-family:inherit;
    }

    button{
        background:var(--navy-light);
        color:#fff;
        transition:background .2s ease;
    }

    button:hover{
        background:var(--navy);
    }

    .btn-cancel{
        background:#f1f1f1;
        color:var(--text-muted);
    }

    .btn-cancel:hover{
        background:#e5e5e5;
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
</style>
</head>

<body>

<div class="container">

    <div class="header-icon">✏️</div>
    <h2>Modifier une présence</h2>
    <p class="subtitle">Mettre à jour le statut de présence de l'étudiant</p>

    <?php if ($error): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">

        <label for="student_id">Étudiant</label>
        <select id="student_id" name="student_id" required>
            <?php while ($row = $students->fetch_assoc()): ?>
                <option value="<?php echo (int)$row['id']; ?>"
                    <?php echo ((int)$row['id'] === (int)$attendance['student_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" required
            value="<?php echo htmlspecialchars($attendance['date']); ?>">

        <label for="status">Statut</label>
        <select id="status" name="status" required>
            <option value="present" <?php echo $attendance['status'] === 'present' ? 'selected' : ''; ?>>Présent</option>
            <option value="absent" <?php echo $attendance['status'] === 'absent' ? 'selected' : ''; ?>>Absent</option>
        </select>

        <div class="btn-row">
            <a href="list.php" class="btn-cancel">Annuler</a>
            <button type="submit" name="save">Enregistrer</button>
        </div>

    </form>

</div>

</body>
</html>