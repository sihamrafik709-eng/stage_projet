<?php
session_start();
include("../includes/navbar.php");
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit();
}
include("../config/db.php");

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste des utilisateurs — SMS Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
   body {
    background: #eef1f6;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
}

.container {
    max-width: 860px;
    margin: 56px auto;
    padding: 0 20px;
}

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-header-left {}

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
    font-size: 27px;
    font-weight: 650;
    letter-spacing: -0.01em;
    color: #16213e;
    margin: 0 0 10px;
}

.title-rule {
    width: 56px;
    height: 3px;
    background: #b8902a;
    border: none;
    margin: 0;
}

.btn-add {
    display: inline-block;
    background:green;
    color: #fff;
    padding: 11px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.15s ease;
    white-space: nowrap;
}

.btn-add:hover {
    background: green;
}

.card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    background: #16213e;
    color: #fff;
    padding: 13px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    text-align: left;
}

tbody td {
    padding: 13px 16px;
    font-size: 14px;
    color: #334155;
    border-bottom: 1px solid #edf0f7;
}

tbody tr:last-child td {
    border-bottom: none;
}

tbody tr:hover td {
    background: #f1f5f9;
}

.role-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #e6f4ea;
    color: #158023;
}

.role-badge.admin {
    background: #e8eaf6;
    color: #2c3e67;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-edit,
.btn-delete {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s ease;
}

.btn-edit {
    background: #15803d;
    color: #fff;
}

.btn-edit:hover {
    background: #0f5c2c;
}

.btn-delete {
    background: #c0392b;
    color: #fff;
}

.btn-delete:hover {
    background: #a5281f;
}

.empty {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
    font-size: 14px;
}
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <div class="page-header-left">
            <span class="page-eyebrow">Administration</span>
            <h1 class="title">Liste des utilisateurs</h1>
            <hr class="title-rule">
        </div>
        <a href="add.php" class="btn-add">+ Ajouter un utilisateur</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th> ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="4" class="empty">Aucun utilisateur trouvé</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <span class="role-badge <?php echo $row['role'] === 'admin' ? 'admin' : ''; ?>">
                                <?php echo htmlspecialchars($row['role']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="edit.php?id=<?php echo (int)$row['id']; ?>" class="btn-edit">Modifier</a>
                                <a href="delete.php?id=<?php echo (int)$row['id']; ?>" class="btn-delete"
                                   onclick="return confirm('Delete this user?');">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>