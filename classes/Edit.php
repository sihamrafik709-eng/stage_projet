<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die("Class not found.");
}

$error = '';

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);

    if ($name === '') {
        $error = "Class name cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating class.";
        }
    }
}

$displayName = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']))
    ? $_POST['name']
    : $row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier une classe </title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
   body {
    background: #eef1f6;
}

.container {
    max-width: 480px;
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

.id-tag {
    display: inline-block;
    font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
    font-size: 12px;
    font-weight: 700;
    color: #16213e;
    padding: 2px 8px;
    border-radius: 6px;
}

.card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
    padding: 28px;
}

.alert {
    background: #fdecea;
    border: 1px solid #c0392b;
    color: #c0392b;
    font-size: 14px;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 18px;
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

input[type="text"] {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 14px;
    font-size: 15px;
    color: #16213e;
    border: 1.5px solid #dde2ec;
    border-radius: 8px;
    margin-bottom: 22px;
    transition: border-color 0.15s ease;
}

input[type="text"]:focus {
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
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Modifier une classe</h1>
        <hr class="title-rule">
        <div class="page-meta">Modifier la classe <span class="id-tag"><?php echo $id; ?></span></div>
    </div>

    <div class="card">

        <?php if ($error !== '') { ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="name">Nom de la classe</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?php echo htmlspecialchars($displayName); ?>"
                required
                autofocus
            >

            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Retour à la liste</a>
                <button type="submit" name="update" class="btn-primary">
                    Mettre à jour la classe
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>