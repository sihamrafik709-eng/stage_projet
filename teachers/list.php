<?php 
session_start();
include("../includes/navbar.php"); ?>
<?php

if($_SESSION['role'] != 'admin'){
    header("Location: ../dashboard/index.php");
    exit();
}
include("../config/db.php");
$result = $conn->query("SELECT * FROM teachers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste des enseignants</title>

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

.title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.01em;
    color: #16213e;
    margin: 0 0 10px;
}
.title-rule{
    width:60px;
    height:3px;
    background:#d4a017;
    border:none;
    margin:12px 0;
}
.page-meta{
    color:#94a3b8;
}

.top-actions{
    margin-bottom:20px;
}

.btn-add{
    background:#15803d;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:8px;
    font-weight:600;
}

.btn-add:hover{
    background:#0f5c2c;
}

.card{
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(22,33,62,.08);
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
    background:#16213e;
    color:white;
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
    background:#15803d;
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-size:14px;
}

.btn-edit:hover{
    background:#0f5c2c;
}

.btn-delete{
    background:#c0392b;
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-size:14px;
}

.btn-delete:hover{
    opacity:.9;
}

.empty{
    text-align:center;
    padding:20px;
    color:#94a3b8;
}
</style>

</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Liste des enseignants</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Gérer tous les dossiers des enseignants
        </div>
    </div>

    <div class="top-actions">
        <a href="add.php" class="btn-add">+ Ajouter un enseignant</a>
    </div>

    <div class="card">

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Matière</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if($result && $result->num_rows > 0){ ?>

                    <?php while($row = $result->fetch_assoc()){ ?>

                        <tr>

                            <td><?= htmlspecialchars($row['id']) ?></td>

                            <td><?= htmlspecialchars($row['name']) ?></td>

                            <td><?= htmlspecialchars($row['subject']) ?></td>

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
                                        onclick="return confirm('Are you sure you want to delete this teacher?')">
                                        Supprimer
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="4" class="empty">
                           Aucun enseignant trouvé
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