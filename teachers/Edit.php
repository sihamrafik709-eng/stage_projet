<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$id = $_GET['id'] ?? null;

if(!$id){
    header("Location: list.php");
    exit;
}

$message = "";
$type = "";
$stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if(!$teacher){
    header("Location: list.php");
    exit;
}

$name = $teacher['name'];
$subject = $teacher['subject'];

if(isset($_POST['update'])){

    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');

    if($name === "" || $subject === ""){

        $message = "Please fill all fields.";
        $type = "error";

    } else {

        $stmt = $conn->prepare("
            UPDATE teachers
            SET name = ?, subject = ?
            WHERE id = ?
        ");

        $stmt->bind_param("ssi", $name, $subject, $id);

        if($stmt->execute()){
            $message = "Teacher updated successfully!";
            $type = "success";
        } else {
            $message = "Error updating teacher.";
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
<title>Modifier un enseignant</title>

<style>
*{
    box-sizing:border-box;
    font-family:Arial;
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

.card{
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(0,0,0,.08);
}

.title{
    font-size:28px;
    font-weight:800;
    color:#16213e;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:8px;
}

.btn{
    background:#15803d;
    color:#fff;
    padding:12px 24px;
    margin-left:300px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.btn:hover{
    background:#16213e;
}

.message{
    padding:10px;
    margin-bottom:15px;
    border-radius:8px;
}

.success{
    background:#eafbf0;
    color:#15803d;
}

.error{
    background:#fdecea;
    color:#c0392b;
}
</style>

</head>
<body>

<div class="container">

    <div class="card">

        <h1 class="title">Modifier un enseignant</h1>

        <?php if($message){ ?>
            <div class="message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
            <a href="list.php" style="margin-left:10px;text-decoration:none;color:#4b5563;font-weight:600;"> ← Retour à la liste </a>
            <button type="submit" name="update" class="btn">Mettre à jour l'enseignant</button>

        </form>

    </div>

</div>

</body>
</html>