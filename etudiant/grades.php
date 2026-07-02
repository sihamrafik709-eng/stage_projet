<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare("
SELECT subject, score
FROM grades
WHERE student_id = ?
ORDER BY subject ASC
");

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mes notes</title>

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
    width: 90%;
    max-width: 900px;
    margin: 40px auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

h1 {
    color: #0f1f3d;
    font-size: 24px;
    position: relative;
    padding-bottom: 8px;
}

h1::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 40px; height: 3px;
    background: #c9a84c;
    border-radius: 2px;
}

.btn {
    text-decoration: none;
    background: #0f1f3d;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    font-size: 14px;
    border: 2px solid transparent;
    transition: .25s;
}

.btn:hover {
    background: #1e3a6e;
    border-color: #c9a84c;
    color: #c9a84c;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(15,31,61,.08);
}

th {
    background: #0f1f3d;
    color: #c9a84c;
    padding: 15px 18px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .08em;
}

td {
    padding: 14px 18px;
    text-align: center;
    border-bottom: 1px solid #eef1f6;
    color: #2d3a55;
    font-size: 15px;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: #fdf6e3;
}

.score {
    font-weight: 800;
    color: #0f1f3d;
    background: #fdf6e3;
    border: 1px solid #e8c96d;
    border-radius: 6px;
    padding: 4px 14px;
    display: inline-block;
    min-width: 52px;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #7a8fb5;
    font-style: italic;
}
</style>

</head>
<body>

<div class="container">

<div class="header">
    <h1>Mes notes</h1>
    <a href="index.php" class="btn">← Retour</a>
</div>

<table>

<tr>
    <th>Matière</th>
    <th>Note</th>
</tr>

<?php if($result->num_rows > 0){ ?>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
    <td><?php echo htmlspecialchars($row['subject']); ?></td>
    <td><span class="score"><?php echo $row['score']; ?></span></td>
</tr>

<?php } ?>

<?php }else{ ?>

<tr>
<td colspan="2" class="empty">
Aucune note disponible
</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>