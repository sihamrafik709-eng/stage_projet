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
<title>Ajouter un étudiant</title>

<style>
*{
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    margin:0;
    background:#eef1f6;
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
    color:#b8902a;
    margin-bottom:8px;
}

.title{
    font-size:30px;
    font-weight:800;
    color:#16213e;
    margin:0;
}

.title-rule{
    width:60px;
    height:3px;
    background:#b8902a;
    border:none;
    margin:12px 0;
}

.page-meta{
    color:#94a3b8;
}

.card{
    background:#ffffff;
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
    background:#eafbf0;
    color:#0f5c2c;
    border:1px solid #15803d;
}

.error{
    background:#fdecea;
    color:#c0392b;
    border:1px solid #c0392b;
}

.field-label{
    display:block;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    color:#334155;
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
    border-color:#16213e;
}

.form-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.btn-back{
    text-decoration:none;
    color:#94a3b8;
    font-weight:600;
}

.btn-back:hover{
    color:#16213e;
}

.btn-primary{
    background:#15803d;
    color:white;
    border:none;
    padding:12px 24px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.btn-primary:hover{
    background:#0f5c2c;
}
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title"> Ajouter un enseignant</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Créer un nouveau dossier enseignant
        </div>
    </div>

    <div class="card">

        <?php if(!empty($message)) { ?>
            <div class="message <?php echo $type; ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label">Nom de l'enseignant</label>
            <input
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                required
            >

            <label class="field-label">Matière</label>
            <input
                type="text"
                name="subject"
                value="<?php echo htmlspecialchars($subject); ?>"
                required
            >

            <div class="form-actions">

                <a href="list.php" class="btn-back">
                    ← Retour à la liste
                </a>

                <button type="submit" name="add" class="btn-primary">
                    Ajouter un enseignant
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