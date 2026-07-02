<?php include("../includes/navbar.php"); ?>
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
    $status  = $_POST['status'];

    if ($first_name === "" || $last_name === "" || $class_id === "") {

        $message = "Please fill all required fields.";
        $type = "error";

    } else {

        $sql = "UPDATE students
        SET first_name=?,
            last_name=?,
            email=?,
            phone=?,
            class_id=?,
            `status`=?
        WHERE id=?";

$update = $conn->prepare($sql);

if (!$update) {
    die($conn->error);
}
        $update->bind_param(
    "ssssisi",
    $first_name,
    $last_name,
    $email,
    $phone,
    $class_id,
    $status,
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
<title>Modifier un étudiant</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
body{
    background:#eef3f8;
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
    color:#d4a017;
}

.title{
    font-size:30px;
    font-weight:800;
    color:#16213e;;
    margin:10px 0;
}

.title-rule{
    width:60px;
    height:3px;
    border:none;
    background:#d4a017;
    margin-left: 0px;
}

.page-meta{
    color:#64748b;
}

.card{
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(30,58,138,.08);
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
    color:#1e293b;
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #dbe4f0;
    border-radius:8px;
    box-sizing:border-box;
    margin-bottom:18px;
}

input:focus,
select:focus{
    outline:none;
    border-color:#1e3a8a;
}

.message{
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}

.success{
    background:#dbeafe;
    color:#2563eb;
    border:1px solid #2563eb;
}

.error{
    background:#fee2e2;
    color:#dc2626;
    border:1px solid #dc2626;
}

.form-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.btn-back{
    text-decoration:none;
    color:#64748b;
    font-weight:600;
}

.btn-back:hover{
    color:#1e3a8a;
}

.btn-primary{
    background:green;
    color:white;
    border:none;
    padding:12px 24px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.btn-primary:hover{
    background:green;
}
</style>

</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Modifier un étudiant</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Mettre à jour les informations de l'étudiant
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
                    <label class="field-label">Prenom</label>
                    <input type="text" name="first_name"
                        value="<?php echo htmlspecialchars($row['first_name']); ?>"
                        required>
                </div>

                <div class="field">
                    <label class="field-label">Nom</label>
                    <input type="text" name="last_name"
                        value="<?php echo htmlspecialchars($row['last_name']); ?>"
                        required>
                </div>

            </div>

            <label class="field-label">Adresse e-mail</label>
            <input type="email" name="email"
                value="<?php echo htmlspecialchars($row['email']); ?>">

            <label class="field-label">Téléphone</label>
            <input type="text" name="phone"
                value="<?php echo htmlspecialchars($row['phone']); ?>">

            <label class="field-label">Classe</label>

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
            <label class="field-label">Statut</label>

<select name="status" required>
    <option value="active" <?php echo ($row['status'] == 'active') ? 'selected' : ''; ?>>
        Actif
    </option>

    <option value="inactive" <?php echo ($row['status'] == 'inactive') ? 'selected' : ''; ?>>
        Inactif
    </option>
</select>

            <div class="form-actions">

                <a href="list.php" class="btn-back">
                    ← Retour à la liste
                </a>

                <button type="submit" name="update" class="btn-primary">
                   Mettre à jour l'étudiant
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