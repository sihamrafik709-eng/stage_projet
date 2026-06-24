<?php
include("../config/db.php");

$id = $_GET['id'] ?? null;

if($id){

    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: list.php");
exit;