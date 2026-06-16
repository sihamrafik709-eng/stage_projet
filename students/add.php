<?php
include("../config/db.php");

$message = "";
$type = "";

$first_name = "";
$last_name  = "";
$email      = "";
$phone      = "";
$class_id   = "";

if (isset($_POST['add'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $class_id   = trim($_POST['class_id']);

    if ($first_name === "" || $last_name === "" || $class_id === "") {

        $message = "Please fill all required fields.";
        $type = "error";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO students
            (first_name, last_name, email, phone, class_id)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssi",
            $first_name,
            $last_name,
            $email,
            $phone,
            $class_id
        );

        if ($stmt->execute()) {

            $message = "Student added successfully!";
            $type = "success";

            $first_name = "";
            $last_name = "";
            $email = "";
            $phone = "";
            $class_id = "";

        } else {

            $message = "Error adding student.";
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
<title>Add Student</title>

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
    letter-spacing:.12em;
    text-transform:uppercase;
    color:var(--gold-500);
    margin-bottom:8px;
}

.title{
    font-size:30px;
    font-weight:800;
    color:var(--navy-900);
    margin:0;
}

.title-rule{
    width:60px;
    height:3px;
    background:var(--gold-500);
    border:none;
    margin:12px 0;
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

.message{
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    border:1px solid transparent;
}

.success{
    background:var(--green-050);
    color:var(--green-700);
    border-color:var(--green-600);
}

.error{
    background:var(--red-050);
    color:var(--red-600);
    border-color:var(--red-600);
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
    letter-spacing:.05em;
    color:var(--slate-700);
    margin-bottom:8px;
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

.btn-back:hover{
    color:var(--navy-700);
}

.btn-primary{
    background:var(--green-600);
    color:#fff;
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
        <h1 class="title">Add Student</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Create a new student record.
        </div>
    </div>

    <div class="card">

        <?php if(!empty($message)) { ?>
            <div class="message <?php echo htmlspecialchars($type); ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="row">

                <div class="field">
                    <label class="field-label">First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        value="<?php echo htmlspecialchars($first_name); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label class="field-label">Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        value="<?php echo htmlspecialchars($last_name); ?>"
                        required
                    >
                </div>

            </div>

            <label class="field-label">Email</label>
            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email); ?>"
            >

            <label class="field-label">Phone</label>
            <input
                type="text"
                name="phone"
                value="<?php echo htmlspecialchars($phone); ?>"
            >

            <label class="field-label">Class</label>
            <select name="class_id" required>

                <option value="">Select Class</option>

                <?php
                $result = $conn->query("SELECT * FROM classes ORDER BY name");

                while($row = $result->fetch_assoc()){

                    $selected = ($class_id == $row['id']) ? "selected" : "";

                    echo "
                    <option value='{$row['id']}' $selected>
                        {$row['name']}
                    </option>";
                }
                ?>

            </select>

            <div class="form-actions">
                <a href="list.php" class="btn-back">
                    ← Back to list
                </a>

                <button type="submit" name="add" class="btn-primary">
                    Add Student
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