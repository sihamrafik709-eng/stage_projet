<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare("
    SELECT students.*, classes.name AS class_name
    FROM students
    LEFT JOIN classes ON students.class_id = classes.id
    WHERE students.id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_grades,
           AVG(score) AS average_score
    FROM grades
    WHERE student_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$grades = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_present
    FROM attendance
    WHERE student_id = ?
    AND status = 'present'
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$present = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_absent
    FROM attendance
    WHERE student_id = ?
    AND status = 'absent'
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$absent = $stmt->get_result()->fetch_assoc();

$avg = $grades['average_score'] ? round($grades['average_score'], 2) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de bord de l'étudiant</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #e8edf5;
}

.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

.header {
    background: linear-gradient(130deg, #0f1f3d 60%, #1e3a6e);
    color: #fff;
    padding: 30px 32px;
    border-radius: 14px;
    margin-bottom: 25px;
    border-left: 5px solid #c9a84c;
    position: relative;
    overflow: hidden;
}

.header::after {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(201,168,76,.08);
    pointer-events: none;
}

.header h1 {
    margin-bottom: 10px;
    font-size: 22px;
    color: #ffffff;
}

.header h1 span {
    color: #c9a84c;
}

.header p {
    color: #b0c4e8;
    font-size: 14px;
    margin-top: 4px;
}

.header p strong {
    color: #e8c96d;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
}

.card {
    background: #ffffff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(15,31,61,.08);
    border-top: 3px solid #c9a84c;
    transition: transform .2s, box-shadow .2s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(15,31,61,.14);
}

.card h2 {
    font-size: 36px;
    color: #0f1f3d;
    margin-bottom: 8px;
    font-weight: 800;
}

.card p {
    color: #7a8fb5;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.actions {
    margin-top: 30px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.btn {
    text-decoration: none;
    background: #0f1f3d;
    color: #ffffff;
    padding: 12px 24px;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    border: 2px solid transparent;
    transition: .25s;
}

.btn:hover {
    background: #1e3a6e;
    border-color: #c9a84c;
    color: #c9a84c;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Bienvenue   <span><?php echo htmlspecialchars($student['first_name'].' '.$student['last_name']); ?></span></h1>
        <p>Classe : <strong><?php echo htmlspecialchars($student['class_name']); ?></strong></p>
        <p>Statut : <strong><?php echo htmlspecialchars($student['status']); ?></strong></p>
    </div>

    <div class="cards">

        <div class="card">
            <h2><?php echo $grades['total_grades']; ?></h2>
            <p>Nombre total de notes</p>
        </div>

        <div class="card">
            <h2><?php echo $present['total_present'] + $absent['total_absent']; ?></h2>
            <p>Nombre total de présences</p>
        </div>

        <div class="card">
            <h2><?php echo $present['total_present']; ?></h2>
            <p>Présent</p>
        </div>

        <div class="card">
            <h2><?php echo $absent['total_absent']; ?></h2>
            <p>Absent</p>
        </div>

        <div class="card">
            <h2><?php echo $avg; ?></h2>
            <p>Moyenne des notes</p>
        </div>

    </div>

    <div class="actions">
        <a href="../etudiant/grades.php" class="btn">Voir les notes</a>
        <a href="../etudiant/attendances.php" class="btn">Voir les présences</a>
        <a href="../auth/logout.php" class="btn">Déconnexion</a>
    </div>

</div>

</body>
</html>