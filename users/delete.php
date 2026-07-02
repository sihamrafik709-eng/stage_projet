<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

header("Location: list.php");
exit();
?>