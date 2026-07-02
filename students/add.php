<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$message = "";
$type = "";

$first_name = "";
$last_name  = "";
$email      = "";
$phone      = "";
$class_id   = "";
$status = "active";

if (isset($_POST['add'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $class_id   = trim($_POST['class_id']);
    $status  = $_POST['status'] ?? 'active';

    if ($first_name === "" || $last_name === "" || $class_id === "") {

        $message = "Please fill all required fields.";
        $type = "error";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO students
            (first_name, last_name, email, phone, class_id, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssis",
            $first_name,
            $last_name,
            $email,
            $phone,
            $class_id,
            $status
        );

        if ($stmt->execute()) {

            $message = "Student added successfully!";
            $type = "success";

            $first_name = "";
            $last_name = "";
            $email = "";
            $phone = "";
            $class_id = "";
            $status = "";

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
<title>Ajouter un étudiant</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
body{
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
    border:1px solid transparent;
}

.success{
    background:#eafbf0;
    color:#0f5c2c;
    border-color:#15803d;
}

.error{
    background:#fdecea;
    color:#c0392b;
    border-color:#c0392b;
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
    color:#334155;
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
    border-color:#2c3e67;
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
    color:#2c3e67;
}

.btn-primary{
    background:#15803d;
    color:#fff;
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
        <h1 class="title">Ajouter un étudiant</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Créer un nouveau dossier étudiant
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
                    <label class="field-label">Prénom</label>
                    <input
                        type="text"
                        name="first_name"
                        value="<?php echo htmlspecialchars($first_name); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label class="field-label">Nom</label>
                    <input
                        type="text"
                        name="last_name"
                        value="<?php echo htmlspecialchars($last_name); ?>"
                        required
                    >
                </div>

            </div>

            <label class="field-label">Adresse e-mail</label>
            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($email); ?>"
            >

            <label class="field-label">Téléphone</label>
            <input
                type="text"
                name="phone"
                value="<?php echo htmlspecialchars($phone); ?>"
            >

            <label class="field-label">Classe</label>
            <select name="class_id" required>

                <option value="">Sélectionnez une classe</option>

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
            <label class="field-label">Statut</label>

           <select name="status">
        <option value="active" <?php echo $status == "active" ? "selected" : ""; ?>>
        Actif
        </option>

        <option value="inactive" <?php echo $status == "inactive" ? "selected" : ""; ?>>
        Inactif
        </option>
            </select>

            <div class="form-actions">
                <a href="list.php" class="btn-back">
                    ← Retour à la liste
                </a>

                <button type="submit" name="add" class="btn-primary">
                    Ajouter un étudiant
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