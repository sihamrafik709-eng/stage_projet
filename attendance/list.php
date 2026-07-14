<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Contrôle d'accès par rôle
if (!in_array($_SESSION['role'], ['admin', 'teacher'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    header("Location: list.php");
    exit();
}

$sql = "SELECT attendance.*, students.first_name, students.last_name
        FROM attendance
        JOIN students ON attendance.student_id = students.id
        ORDER BY attendance.date DESC";

$result = $conn->query($sql);
$count = $result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Présences — SMS Admin</title>
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

.page-meta {
    font-size: 14px;
    color: #94a3b8;
}

.top-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-add {
    display: inline-block;
    background: #15803d;
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: background .2s;
}

.btn-add:hover {
    background: #0f5c2c;
}

.search-box input {
    padding: 10px 14px 10px 38px;
    border: 1px solid #dde3ef;
    border-radius: 8px;
    font-size: 14px;
    background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2394a3b8" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>') no-repeat 12px center;
    background-size: 14px;
    min-width: 240px;
    outline: none;
    transition: border-color .2s;
}

.search-box input:focus {
    border-color: #b8902a;
}

.table-wrapper {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 24px rgba(22,33,62,0.08);
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

.student-name {
    font-weight: 600;
    color: #303d61;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
}

.badge.present {
    background: #e6f4ea;
    color: #15803d;
}

.badge.absent {
    background: #fdecea;
    color: #c0392b;
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
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: opacity .15s;
}

.btn:hover {
    opacity: .85;
}

.btn svg {
    width: 13px;
    height: 13px;
}

.btn-edit {
    background: #188003;
    color: #fff;
}

.btn-delete {
    background: #ad0303;
    color: #fff;
}

.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #94a3b8;
    font-size: 15px;
}

@media (max-width: 600px) {
    .table-wrapper {
        overflow-x: auto;
    }

    table {
        min-width: 560px;
    }
}
</style>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">Suivi scolaire</span>
        <h1 class="title">Présences</h1>
        <hr class="title-rule">
        <p class="page-meta">
            <?php echo $count; ?> présence<?php echo $count !== 1 ? 's' : ''; ?> enregistrée<?php echo $count !== 1 ? 's' : ''; ?>
        </p>
    </div>

    <div class="top-actions">
        <a href="add.php" class="btn-add">+ Ajouter une présence</a>
        <div class="search-box">
            <input type="text" id="search" placeholder="Rechercher un étudiant...">
        </div>
    </div>

    <div class="table-wrapper">
        <table id="table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($count === 0): ?>
                <tr>
                    <td colspan="4" class="empty-state">Aucune présence enregistrée pour le moment.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()):
                    $status      = $row['status'];
                    $statusLabel = $status === 'present' ? 'Présent' : 'Absent';
                ?>
                <tr>
                    <td><span class="student-name"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td>
                        <span class="badge <?php echo htmlspecialchars($status); ?>">
                            <?php echo htmlspecialchars($statusLabel); ?>
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="Edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Modifier
                            </a>
                            <button type="button" class="btn btn-delete"
                                onclick="confirmDelete(<?php echo (int)$row['id']; ?>)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    const value = this.value.toLowerCase();
    document.querySelectorAll("#table tbody tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? "" : "none";
    });
});

function confirmDelete(id){
    if (confirm("Voulez-vous vraiment supprimer cette présence ?")) {
        window.location.href = "list.php?delete=" + id;
    }
}
</script>

</body>
</html>