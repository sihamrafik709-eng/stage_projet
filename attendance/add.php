<?php
session_start();
include("../includes/navbar.php");
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

$students = $conn->query("SELECT * FROM students ORDER BY first_name");

$message = "";
$type = "";

if (isset($_POST['save'])) {

    $student_id = $_POST['student_id'] ?? '';
    $date       = $_POST['date'] ?? '';
    $status     = $_POST['status'] ?? '';

    if ($student_id === '' || $date === '' || $status === '') {

        $message = "Please fill in all fields.";
        $type = "error";

    } else {

        $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $student_id, $date, $status);

        if ($stmt->execute()) {

            $message = "Attendance added successfully!";
            $type = "success";

        } else {

            $message = "Error while saving attendance.";
            $type = "error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter une présence</title>

<style>

:body{
    background:#eef1f6;
    font-family:'Segoe UI',sans-serif;
    margin:0;
}

.container{
    max-width:520px;
    margin:56px auto;
    padding:0 20px;
}

.page-header{
    margin-bottom:28px;
}

.page-eyebrow{
    display:block;
    font-size:12px;
    font-weight:700;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:#b8902a;
    margin-bottom:8px;
}

.title{
    font-size:28px;
    font-weight:800;
    color:#16213e;
    margin:0 0 10px;
}

.title-rule{
    width:56px;
    height:3px;
    background:#b8902a;
    border:none;
    margin:0 0 12px;
}

.page-meta{
    font-size:14px;
    color:#94a3b8;
}

.card{
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(22,33,62,.08);
    padding:28px;
}

.message{
    font-size:14px;
    padding:10px 14px;
    border-radius:8px;
    margin-bottom:18px;
    border:1px solid transparent;
}

.message.success{
    background:#eafbf0;
    border-color:#15803d;
    color:#0f5c2c;
}

.message.error{
    background:#fdecea;
    border-color:#c0392b;
    color:#c0392b;
}

.field-label{
    display:block;
    font-size:12px;
    font-weight:700;
    letter-spacing:.06em;
    text-transform:uppercase;
    color:#334155;
    margin-bottom:8px;
}

input,
select{
    width:100%;
    box-sizing:border-box;
    padding:11px 14px;
    font-size:15px;
    border:1.5px solid #dde2ec;
    border-radius:8px;
    margin-bottom:22px;
    background:#fff;
}

input:focus,
select:focus{
    outline:none;
    border-color:#2c3e67;
}

.form-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
}

.btn-primary{
    background:#15803d;
    color:#fff;
    border:none;
    padding:11px 22px;
    font-size:14px;
    font-weight:600;
    border-radius:8px;
    cursor:pointer;
}

.btn-primary:hover{
    background:#0f5c2c;
}

.btn-back{
    color:#94a3b8;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
}

.btn-back:hover{
    color:#2c3e67;
}
</style>
</head>

<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Gestion des présences</span>
        <h1 class="title">Ajouter une présence</h1>
        <hr class="title-rule">
        <div class="page-meta">
           Enregistrer la présence des étudiants rapidement et en toute sécurité.
        </div>
    </div>

    <div class="card">

        <?php if (!empty($message)) { ?>
            <div class="message <?php echo htmlspecialchars($type); ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="student_id">
                Étudiant
            </label>

            <select id="student_id" name="student_id" required>
                <option value="">Select Student</option>

                <?php while($row = $students->fetch_assoc()) { ?>

                    <option value="<?php echo (int)$row['id']; ?>">
                        <?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?>
                    </option>

                <?php } ?>
            </select>

            <label class="field-label" for="date">
                Date
            </label>

            <input
                type="date"
                id="date"
                name="date"
                required
            >

            <label class="field-label" for="status">
                Statut
            </label>

            <select id="status" name="status" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
            </select>

            <div class="form-actions">

                <a href="list.php" class="btn-back">
                    &larr; Retour à la liste
                </a>

                <button type="submit" name="save" class="btn-primary">
                    Enregistrer la présence
                </button>

            </div>

        </form>

    </div>

</div>

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