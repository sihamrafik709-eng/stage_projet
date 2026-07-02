<?php 
session_start();
include("../includes/navbar.php"); ?>
<?php

if(!isset($_SESSION['user'])){
    header("Location: ../auth/login.php");
    exit();
}
include("../config/db.php");
$result = $conn->query("
    SELECT students.*, classes.name AS class_name
    FROM students
    LEFT JOIN classes ON students.class_id = classes.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste des étudiants</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
body{
    background:#eef3f8;
    margin:0;
    font-family:Arial,sans-serif;
}

.container{
    max-width:1200px;
    margin:50px auto;
    padding:0 20px;
    margin-left:330px;
}

.page-header{
    margin-bottom:25px;
}

.page-eyebrow{
    display:block;
    font-size:12px;
    font-weight:700;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:#d4a017;
    margin-bottom:8px;
}

.title{
    font-size:30px;
    font-weight:800;
    color:#16213e;
    margin:0;
}

.title-rule{
    width:60px;
    height:3px;
    background:#d4a017;
    border:none;
    margin:12px 0;
}

.page-meta{
    color:#64748b;
}

.card{
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(30,58,138,.08);
    overflow:hidden;
}

.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#16213e; ;
    color:#ffffff;
}

th{
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #e5e7eb;
}

.actions{
    display:flex;
    gap:8px;
}

.btn-edit{
    background:green;
    color:#ffffff;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-size:14px;
}

.btn-edit:hover{
    background:green;
}

.btn-delete{
    background:#dc2626;
    color:#ffffff;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-size:14px;
}

.btn-delete:hover{
    opacity:.9;
}

.top-actions{
    margin-bottom:20px;
}

.btn-add{
    background:green; 
    color:#ffffff;
    text-decoration:none;
    padding:12px 18px;
    border-radius:8px;
    font-weight:600;
}

.btn-add:hover{
    background:green;
}

.badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.badge.active{
    background: #b4f89f;
    color:green;
}

.badge.inactive{
    background:#fee2e2;
    color:#dc2626;
}
</style>

</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Liste des étudiants</h1>
        <hr class="title-rule">
        <div class="page-meta">
           Gérez tous les dossiers des étudiants.
        </div>
    </div>

    <div class="top-actions">
        <a href="add.php" class="btn-add">+ Ajouter un étudiant</a>
    </div>

    <div class="card">

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Adresse e-mail</th>
                        <th>Téléphone</th>
                        <th>Classe</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($row = $result->fetch_assoc()) { ?>

                    <tr>

                        <td><?= htmlspecialchars($row['id']) ?></td>

                        <td>
                            <?= htmlspecialchars($row['first_name']) ?>
                            <?= htmlspecialchars($row['last_name']) ?>
                        </td>

                        <td><?= htmlspecialchars($row['email']) ?></td>

                        <td><?= htmlspecialchars($row['phone']) ?></td>

                        <td><?= htmlspecialchars($row['class_name']) ?></td>
                        <td><?php if($row['status'] == 'active'){ ?>
                        <span class="badge active">Actif</span><?php } else { ?>
                        <span class="badge inactive">Inactif</span> <?php } ?></td>

                        <td>

                            <div class="actions">

                                <a
                                    href="edit.php?id=<?= $row['id'] ?>"
                                    class="btn-edit">
                                    Modifier
                                </a>

                                <a
                                    href="delete.php?id=<?= $row['id'] ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this student?')">
                                    Supprimer
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>