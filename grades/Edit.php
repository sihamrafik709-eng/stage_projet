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

if (isset($_POST['save'])) {
    $student_id = $_POST['student_id'] ?? '';
    $subject    = trim($_POST['subject'] ?? '');
    $score      = $_POST['score'] ?? '';

    if ($student_id === '' || $subject === '' || $score === '') {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($score) || $score < 0 || $score > 20) {
        $error = "La note doit être un nombre entre 0 et 20.";
    } else {
        $stmt = $conn->prepare("UPDATE grades SET student_id = ?, subject = ?, score = ? WHERE id = ?");
        $stmt->bind_param("isdi", $student_id, $subject, $score, $id);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Une erreur est survenue lors de la mise à jour.";
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT * FROM grades WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$grade = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$grade) {
    header("Location: list.php");
    exit();
}

$students = $conn->query("SELECT * FROM students ORDER BY first_name");
$subjects = ["Math", "Francais", "Histoire", "Sciences", "Anglais"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier une note — SMS Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    body {
    background: #eef1f6;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
}

.container {
    max-width: 520px;
    margin: 56px auto;
    padding: 0 20px;
}

.page-header {
    margin-bottom: 28px;
}

.page-eyebrow {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #b8902a;
    margin-bottom: 8px;
}

.title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.01em;
    color: #16213e;
    margin: 0 0 10px;
}

.title-rule {
    width: 56px;
    height: 3px;
    background: #b8902a;
    border: none;
    margin: 0 0 12px;
}

.page-meta {
    font-size: 14px;
    color: #94a3b8;
}

.card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
    padding: 28px;
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

.message.success {
    background: #eafbf0;
    border-color: #15803d;
    color: #0f5c2c;
}

.field-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #334155;
    margin-bottom: 8px;
}

input[type="number"],
select {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 14px;
    font-size: 15px;
    color: #16213e;
    border: 1.5px solid #dde2ec;
    border-radius: 8px;
    margin-bottom: 22px;
    background: #fff;
    transition: border-color 0.15s ease;
    appearance: auto;
}

input[type="number"]:focus,
select:focus {
    outline: none;
    border-color: #2c3e67;
}

.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.btn-primary {
    background: #15803d;
    color: #ffffff;
    border: none;
    padding: 11px 22px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s ease;
}

.btn-primary:hover {
    background: #0f5c2c;
}

.btn-back {
    font-size: 14px;
    font-weight: 600;
    color: #94a3b8;
    text-decoration: none;
}

.btn-back:hover {
    color: #2c3e67;
}
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Gestion des notes</span>
        <h1 class="title">Modifier une note</h1>
        <hr class="title-rule">
        <div class="page-meta">Mettre à jour les informations de la note de l'étudiant.</div>
    </div>

    <div class="card">

        <?php if (!empty($error)) { ?>
            <div class="message error" id="msg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="student_id">Étudiant</label>
            <select id="student_id" name="student_id" required>
                <option value="">Sélectionnez un étudiant</option>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <option value="<?php echo (int)$row['id']; ?>"
                        <?php echo ((int)$row['id'] === (int)$grade['student_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label class="field-label" for="subject">Matière</label>
            <select id="subject" name="subject" required>
                <option value="">Sélectionnez une matière</option>
                <?php foreach ($subjects as $subj): ?>
                    <option value="<?php echo htmlspecialchars($subj); ?>"
                        <?php echo ($grade['subject'] === $subj) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subj); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="field-label" for="score">Note (sur 20)</label>
            <input
                type="number"
                id="score"
                name="score"
                min="0"
                max="20"
                step="0.25"
                value="<?php echo htmlspecialchars($grade['score']); ?>"
                required
            >

            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Retour à la liste</a>
                <button type="submit" name="save" class="btn-primary">Mettre à jour la note</button>
            </div>

        </form>

    </div>

</div>

<script>
setTimeout(function () {
    let msg = document.getElementById("msg");
    if (msg) {
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 3000);
</script>

</body>
<?php include("../includes/navbar.php");?>
</html>