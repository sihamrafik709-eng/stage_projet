<?php 
session_start();
include("../includes/navbar.php"); ?>
<?php
if($_SESSION['role'] != 'admin'){
    header("Location: ../dashboard/index.php");
    exit();
}
include("../config/db.php");
$result = $conn->query("SELECT * FROM classes ORDER BY id ASC");
if ($result === false) {
    die("Database error: " . htmlspecialchars($conn->error));
}
$count = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste des classes</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
   body {
    background: #eef1f6;
}

.container {
    max-width: 880px;
    margin: 56px auto;
    padding: 0 20px;
}

.page-header {
    margin-bottom: 28px;
}

.page-eyebrow {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #b8902a;
    margin-bottom: 8px;
}

.title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -0.01em;
    color: #16213e;
    margin: 0 0 10px;
}

.title-rule {
    width: 56px;
    height: 3px;
    background: #b8902a;
    border: none;
    margin: 0 0 12px;
}

.page-meta {
    font-size: 14px;
    color: #94a3b8;
}

.table-wrapper {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f1b3c;
}

thead th {
    color: #ffffff;
    font-weight: 600;
    text-align: left;
    padding: 14px 22px;
    font-size: 12px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

tbody tr {
    border-bottom: 1px solid #eef0f4;
    transition: background 0.15s ease;
}

tbody tr:last-child {
    border-bottom: none;
}

tbody tr:hover {
    background: #eef1fa;
}

td {
    padding: 14px 22px;
    color: #334155;
    font-size: 15px;
    vertical-align: middle;
}

.id-tag {
    display: inline-block;
    font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
    font-size: 12px;
    font-weight: 700;
    color: #303d61;
    padding: 3px 9px;
    border-radius: 6px;
}

.class-name {
    font-weight: 600;
    color: #303d61;
}

.actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 13px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: background 0.15s ease, border-color 0.15s ease;
}

.btn svg {
    width: 13px;
    height: 13px;
}

.btn-edit {
    color: #ffffff;
    background-color: #188003;
}

.btn-delete {
    color: #ffffff;
    background-color: #ad0303;
}

.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #94a3b8;
    font-size: 15px;
}

@media (max-width: 560px) {
    .table-wrapper {
        overflow-x: auto;
    }

    table {
        min-width: 480px;
    }
}

.top-actions {
    margin-bottom: 20px;
}

.btn-add {
    display: inline-block;
    background: #15803d;
    color: #fff;
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 8px;
    font-weight: 600;
    transition: .2s;
}

.btn-add:hover {
    background: #0f5c2c;
}
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Dossiers scolaires</span>
        <h1 class="title">Classes</h1>
        <hr class="title-rule">
        <div class="page-meta">
            <?php echo $count; ?> class<?php echo $count === 1 ? '' : 'es'; ?> on record
        </div>
    </div>
<div class="top-actions">
    <a href="add.php" class="btn-add">
        + Ajouter une classe
    </a>
</div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la classe</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($count > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><span class="id-tag"><?php echo (int) $row['id']; ?></span></td>
                            <td><span class="class-name"><?php echo htmlspecialchars($row['name']); ?></span></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=<?php echo (int) $row['id']; ?>" class="btn btn-edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Modifier
                                    </a>
                                    <a href="delete.php?id=<?php echo (int) $row['id']; ?>"
                                       class="btn btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this class?');">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="3" class="empty-state">No classes recorded yet — add one to get started.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>