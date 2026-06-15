<?php
include("../config/db.php");

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM students WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $class_id = $_POST['class_id'];

    $sql = "UPDATE students
        SET first_name='$first_name',
            last_name='$last_name',
            email='$email',
            phone='$phone',
            class_id='$class_id'
        WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error updating";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container">

    <div class="title">Edit Student</div>

    <form method="POST">

        <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>" required>

        <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>" required>
        <input type="email" name="email" value="<?php echo $row['email']; ?>">

        <input type="text" name="phone" value="<?php echo $row['phone']; ?>">

        <select name="class_id">
            <?php
            $classes = $conn->query("SELECT * FROM classes");
            while($c = $classes->fetch_assoc()){
                $selected = ($c['id'] == $row['class_id']) ? "selected" : "";
                echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
            }
            ?>
        </select>

        <button type="submit" name="update">Update Student</button>

    </form>

</div>

</body>
</html>