<?php
session_start();
include("../config/db.php");

$message = "";
$type = "";
$username_val = "";

if (isset($_POST['add'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'teacher';

    if ($username === '' || $password === '') {
        $message = "Please fill in all fields.";
        $type = "error";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : NULL;
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, student_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $hashed, $role, $student_id);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $message = "Error adding user.";
            $type = "error";
        }
        $stmt->close();
    }
    $username_val = htmlspecialchars($username);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter un utilisateur — SMS Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    body {
    background: #eef1f6;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
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
    color:#16213e; 
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

input[type="text"],
input[type="password"],
select {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 14px;
    font-size: 15px;
    color: #16213e;
    border: 1.5px solid #dde2ec;
    border-radius: 8px;
    margin-bottom: 22px;
    background: #ffffff;
    transition: border-color 0.15s ease;
    appearance: auto;
}

input[type="text"]:focus,
input[type="password"]:focus,
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
        <span class="page-eyebrow">Administration</span>
        <h1 class="title">Ajouter un utilisateur</h1>
        <hr class="title-rule">
        <div class="page-meta">Créer un nouveau compte administrateur ou enseignant.</div>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $type; ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label class="field-label" for="username">Nom d'utilisateur</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="e.g. john_doe"
                value="<?php echo $username_val; ?>"
                required
                autofocus
            >

            <label class="field-label" for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                required
            >

            <label class="field-label" for="role">Rôle</label>
            <select id="role" name="role">
                <option value="admin">Administrateur</option>
                <option value="teacher">Enseignant</option>
                <option value="student">Étudiant</option>
            </select>
            <div id="studentBox">

<label class="field-label" for="student_id">Étudiant</label>

<select id="student_id" name="student_id">
    <option value="">Aucun</option>

    <?php
    $students = $conn->query("SELECT id, first_name, last_name FROM students ORDER BY first_name");

    while($student = $students->fetch_assoc()){
        echo "<option value='{$student['id']}'>
                {$student['first_name']} {$student['last_name']}
              </option>";
    }
    ?>
</select>

</div>
            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr;Retour à la liste</a>
                <button type="submit" name="add" class="btn-primary">Enregistrer l'utilisateur</button>
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
<script>
const role = document.getElementById("role");
const studentBox = document.getElementById("studentBox");

function toggleStudent(){
    if(role.value === "student"){
        studentBox.style.display = "block";
    }else{
        studentBox.style.display = "none";
        document.getElementById("student_id").value = "";
    }
}

toggleStudent();
role.addEventListener("change", toggleStudent);
</script>
</body>
<?php include("../includes/navbar.php"); ?>
</html>