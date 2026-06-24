<?php
session_start();
include("../config/db.php");

// check login (important)
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// check id
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = (int) $_GET['id'];

// delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

// redirect back
header("Location: list.php");
exit();
?>