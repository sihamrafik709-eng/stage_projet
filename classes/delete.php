<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$id = $_GET['id'];

$sql = "DELETE FROM classes WHERE id=$id";

if($conn->query($sql)){
    header("Location: list.php");
    exit();
}else{
    echo "Error deleting class";
}
?>