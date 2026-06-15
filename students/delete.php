<?php
include("../config/db.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM students WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error deleting record";
    }
}
?>