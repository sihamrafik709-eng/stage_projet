<?php
include("../includes/navbar.php");
include("../config/db.php");

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: list.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: list.php");
    exit();
}

$message = "";
$type = "";

if (isset($_POST['update'])) {
    $username = trim($_POST['username'] ?? '');
    $role     = $_POST['role'] ?? 'teacher';
    $password = $_POST['password'] ?? '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : NULL;

    if ($username === '') {
        $message = "Username cannot be empty.";
        $type = "error";
    } else {
        if (!empty($password)) {

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        UPDATE users
        SET username = ?, password = ?, role = ?, student_id = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssii",
        $username,
        $hashed,
        $role,
        $student_id,
        $id
    );

} else {

    $stmt = $conn->prepare("
        UPDATE users
        SET username = ?, role = ?, student_id = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssii",
        $username,
        $role,
        $student_id,
        $id
    );
}
        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $message = "Error updating user.";
            $type = "error";
        }
        $stmt->close();
    }
    $user['username'] = $username;
    $user['role']     = $role;
    $user['student_id']     = $student_id;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier un utilisateur — SMS Admin</title>
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

.field-hint {
    display: block;
    font-size: 12px;
    color: #94a3b8;
    margin-top: -16px;
    margin-bottom: 18px;
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
        <h1 class="title">Modifier un utilisateur </h1>
        <hr class="title-rule">
        <div class="page-meta">Mettre à jour les informations du compte de cet utilisateur.</div>
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
                value="<?php echo htmlspecialchars($user['username']); ?>"
                required
                autofocus
            >

            <label class="field-label" for="password">Nouveau mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Leave blank to keep current password"
            >
            <span class="field-hint">Laissez ce champ vide pour conserver le mot de passe actuel.</span>

            <label class="field-label" for="role">Role</label>
            <select id="role" name="role">
                <option value="admin" <?php echo $user['role']=='admin'?'selected':''; ?>>Administrateur</option>
                <option value="teacher" <?php echo $user['role']=='teacher'?'selected':''; ?>>Enseignant</option>
                <option value="student" <?php echo $user['role']=='student'?'selected':''; ?>>Étudiant</option>
            </select>
            <div id="studentBox">

            <label class="field-label">Étudiant</label>

             <select name="student_id" id="student_id">
              <option value="">Aucun</option>

           <?php
             $students = $conn->query("SELECT id, first_name, last_name FROM students ORDER BY first_name");

               while($student = $students->fetch_assoc()){
             $selected = ($student['id'] == $user['student_id']) ? "selected" : "";

             echo "<option value='{$student['id']}' $selected>
                {$student['first_name']} {$student['last_name']}
              </option>";
    }
    ?>
</select>

</div>
            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Retour à la liste</a>
                <button type="submit" name="update" class="btn-primary">Mettre à jour l'utilisateur</button>
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
</html>