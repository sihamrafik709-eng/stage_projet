<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
  $first_name = $_POST['first_name'] ?? '';
  $last_name  = $_POST['last_name'] ?? '';
  $email      = $_POST['email'] ?? '';
  $phone      = $_POST['phone'] ?? '';
  $class_id   = $_POST['class_id'] ?? '';

    $sql = "INSERT INTO students (first_name, last_name, email, phone, class_id)
    VALUES ('$first_name', '$last_name', '$email', '$phone', '$class_id')";

    if ($conn->query($sql)) {
        $message = "Student added successfully!";
        $type = "success";
    } else {
        $message = "Error: " . $conn->error;
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <div class="title">Add Student</div>

    <?php if(!empty($message)) { ?>
        <div class="message <?php echo $type; ?>" id="msg">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="row">
    <input type="text" name="first_name" placeholder="First Name" required><br><br>
    <input type="text" name="last_name" placeholder="Last Name" required><br>
    </div><br>
        <input type="email" name="email" placeholder="Email">
        <br>
        <input type="text" name="phone" placeholder="Phone">

        <br>
        <select name="class_id" required>
            <option value="">Select Class </option>

            <?php
            $result = $conn->query("SELECT * FROM classes");
            while($row = $result->fetch_assoc()){
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <button type="submit" name="add">Add Student</button>

    </form>

</div>

<script>
setTimeout(function(){
    let msg = document.getElementById("msg");
    if(msg){
        msg.style.opacity = "0";
        msg.style.transition = "0.5s";
        setTimeout(() => msg.remove(), 500);
    }
}, 3000);
</script>

</body>
</html>