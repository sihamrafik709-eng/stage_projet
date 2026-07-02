<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$message = "";
$type = "";
$name = "";

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if ($name === '') {
        $message = "Class name cannot be empty.";
        $type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $message = "Class added successfully!";
            $type = "success";
            $name = "";
        } else {
            $message = "Error adding class.";
            $type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter une classe</title>
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

.message.success {
    background: #eafbf0;
    border-color: #15803d;
    color: #0f5c2c;
}

.message.error {
    background: #fdecea;
    border-color: #c0392b;
    color: #c0392b;
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

.top-actions {
    margin-bottom: 20px;
}

</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Ajouter une classe</h1>
        <hr class="title-rule">
        <div class="page-meta">Créer une nouvelle classe pour vos dossiers scolaires.</div>
    </div>
    <div class="card">

        <?php if (!empty($message)) { ?>
            <div class="message <?php echo htmlspecialchars($type); ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="name">Nom de la classe</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Exemple : eng"
                value="<?php echo htmlspecialchars($name); ?>"
                required
                autofocus
            >

            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Retour à la liste</a>
                <button type="submit" name="add" class="btn-primary">
                   Ajouter une classe
                </button>
            </div>

        </form>

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