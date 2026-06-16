<?php
include("../config/db.php");

$message = "";
$type = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid student ID.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student not found.");
}

$row = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $class_id   = trim($_POST['class_id']);

    if ($first_name === "" || $last_name === "" || $class_id === "") {

        $message = "Please fill all required fields.";
        $type = "error";

    } else {

        $update = $conn->prepare("
            UPDATE students
            SET first_name=?,
                last_name=?,
                email=?,
                phone=?,
                class_id=?
            WHERE id=?
        ");

        $update->bind_param(
            "ssssii",
            $first_name,
            $last_name,
            $email,
            $phone,
            $class_id,
            $id
        );

        if ($update->execute()) {

            $message = "Student updated successfully!";
            $type = "success";

            $row['first_name'] = $first_name;
            $row['last_name']  = $last_name;
            $row['email']      = $email;
            $row['phone']      = $phone;
            $row['class_id']   = $class_id;

        } else {

            $message = "Error updating student.";
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
<title>Edit Student</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
:root{
    --bg-page:#eef1f6;
    --surface:#ffffff;
    --navy-900:#16213e;
    --navy-700:#2c3e67;
    --gold-500:#b8902a;
    --slate-700:#334155;
    --slate-400:#94a3b8;
    --green-600:#15803d;
    --green-700:#0f5c2c;
    --green-050:#eafbf0;
    --red-600:#c0392b;
    --red-050:#fdecea;
}

body{
    background:var(--bg-page);
}

.container{
    max-width:700px;
    margin:50px auto;
    padding:0 20px;
}

.page-header{
    margin-bottom:25px;
}

.page-eyebrow{
    display:block;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.12em;
    color:var(--gold-500);
}

.title{
    font-size:30px;
    font-weight:800;
    color:var(--navy-900);
    margin:10px 0;
}

.title-rule{
    width:60px;
    height:3px;
    border:none;
    background:var(--gold-500);
}

.page-meta{
    color:var(--slate-400);
}

.card{
    background:var(--surface);
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(22,33,62,.08);
}

.row{
    display:flex;
    gap:15px;
}

.row .field{
    flex:1;
}

.field-label{
    display:block;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:8px;
    color:var(--slate-700);
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #dbe1ea;
    border-radius:8px;
    box-sizing:border-box;
    margin-bottom:18px;
}

input:focus,
select:focus{
    outline:none;
    border-color:var(--navy-700);
}

.message{
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}

.success{
    background:var(--green-050);
    color:var(--green-700);
    border:1px solid var(--green-600);
}

.error{
    background:var(--red-050);
    color:var(--red-600);
    border:1px solid var(--red-600);
}

.form-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.btn-back{
    text-decoration:none;
    color:var(--slate-400);
    font-weight:600;
}

.btn-primary{
    background:var(--green-600);
    color:white;
    border:none;
    padding:12px 24px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.btn-primary:hover{
    background:var(--green-700);
}
</style>

</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">School Records</span>
        <h1 class="title">Edit Student</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Update student information.
        </div>
    </div>

    <div class="card">

        <?php if(!empty($message)) { ?>
            <div class="message <?php echo $type; ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="row">

                <div class="field">
                    <label class="field-label">First Name</label>
                    <input type="text" name="first_name"
                        value="<?php echo htmlspecialchars($row['first_name']); ?>"
                        required>
                </div>

                <div class="field">
                    <label class="field-label">Last Name</label>
                    <input type="text" name="last_name"
                        value="<?php echo htmlspecialchars($row['last_name']); ?>"
                        required>
                </div>

            </div>

            <label class="field-label">Email</label>
            <input type="email" name="email"
                value="<?php echo htmlspecialchars($row['email']); ?>">

            <label class="field-label">Phone</label>
            <input type="text" name="phone"
                value="<?php echo htmlspecialchars($row['phone']); ?>">

            <label class="field-label">Class</label>

            <select name="class_id" required>

                <?php
                $classes = $conn->query("SELECT * FROM classes ORDER BY name");

                while($c = $classes->fetch_assoc()){

                    $selected = ($c['id'] == $row['class_id']) ? "selected" : "";

                    echo "<option value='{$c['id']}' $selected>
                            {$c['name']}
                          </option>";
                }
                ?>

            </select>

            <div class="form-actions">

                <a href="list.php" class="btn-back">
                    ← Back to list
                </a>

                <button type="submit" name="update" class="btn-primary">
                    Update Student
                </button>

            </div>

        </form>

    </div>

</div>

<script>
setTimeout(function(){

    let msg = document.getElementById("msg");

    if(msg){

        msg.style.transition = "opacity .5s ease";
        msg.style.opacity = "0";

        setTimeout(() => {
            msg.remove();
        }, 500);
    }

}, 3000);
</script>

</body>
</html>