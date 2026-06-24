<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$message = "";
$type = "";

$name = "";
$subject = "";

if (isset($_POST['add'])) {

    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');

    if ($name === "" || $subject === "") {

        $message = "Please fill all required fields.";
        $type = "error";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO teachers (name, subject)
            VALUES (?, ?)
        ");

        $stmt->bind_param("ss", $name, $subject);

        if ($stmt->execute()) {

            $message = "Teacher added successfully!";
            $type = "success";

            $name = "";
            $subject = "";

        } else {

            $message = "Error adding teacher.";
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
<title>Add Teacher</title>

<style>
:root{
    --bg-page:#eef1f6;
    --surface:#ffffff;
    --navy-900:#16213e;
    --gold-500:#b8902a;
    --slate-700:#334155;
    --slate-400:#94a3b8;
    --green-600:#15803d;
    --green-700:#0f5c2c;
    --green-050:#eafbf0;
    --red-600:#c0392b;
    --red-050:#fdecea;
}

*{
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    margin:0;
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

.field-label{
    display:block;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    color:var(--slate-700);
    margin-bottom:8px;
}

input{
    width:100%;
    padding:12px;
    border:1px solid #dbe1ea;
    border-radius:8px;
    margin-bottom:18px;
}

input:focus{
    outline:none;
    border-color:var(--navy-900);
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
    color:var(--navy-900);
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
        <h1 class="title">Add Teacher</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Create a new teacher record.
        </div>
    </div>

    <div class="card">

        <?php if(!empty($message)) { ?>
            <div class="message <?php echo $type; ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label">Teacher Name</label>
            <input
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                required
            >

            <label class="field-label">Subject</label>
            <input
                type="text"
                name="subject"
                value="<?php echo htmlspecialchars($subject); ?>"
                required
            >

            <div class="form-actions">

                <a href="list.php" class="btn-back">
                    ← Back to list
                </a>

                <button type="submit" name="add" class="btn-primary">
                    Add Teacher
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