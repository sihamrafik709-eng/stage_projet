<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!in_array($_SESSION['role'], ['admin', 'teacher'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM grades WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    header("Location: list.php");
    exit();
}

$sql = "SELECT grades.*, students.first_name, students.last_name
        FROM grades
        JOIN students ON grades.student_id = students.id
        ORDER BY grades.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste des notes — SMS Admin</title>
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
    font-size:14px;
    color:#94a3b8;
}

.top-bar{
    display:flex;
    justify-content:flex-end;
    margin-bottom:20px;
}

.add-btn{
    background:#15803d;
    color:white;
    text-decoration:none;
    padding:11px 20px;
    border-radius:8px;
    font-weight:600;
}

.add-btn:hover{
    background:#0f5c2c;
}

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:320px;
    padding:11px 14px;
    border:1px solid #dde2ec;
    border-radius:8px;
}

.panel{
    background:white;
    border-radius:12px;
    box-shadow:0 6px 24px rgba(22,33,62,.08);
    overflow:hidden;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#16213e;
    color:white;
    padding:14px;
    text-align:left;
}

td{
    padding:14px;
    border-bottom:1px solid #eee;
}

.btn-edit{
    background:#15803d;
    color:white;
    padding:7px 12px;
    border-radius:6px;
    text-decoration:none;
}

.btn-delete{
    background:#c0392b;
    color:white;
    padding:7px 12px;
    border-radius:6px;
    border:none;
    cursor:pointer;
}

.actions{
    display:flex;
    gap:8px;
}

.badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

.badge.good{
    background:#eafbf0;
    color:#15803d;
}

.badge.average{
    background:#fff4db;
    color:#b8902a;
}

.badge.low{
    background:#fdecea;
    color:#c0392b;
}
</style>

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container">

    <div class="page-header">
    <span class="page-eyebrow">Gestion des notes</span>
    <h1 class="title">Liste des notes</h1>
    <hr class="title-rule">
    <div class="page-meta">
        Gérer et suivre les notes des étudiants
    </div>
</div>

<div class="top-bar">
    <a href="add.php" class="add-btn">+ Ajouter une note</a>
</div>

    <div class="search-box">
        <input type="text" id="search" placeholder="Rechercher un étudiant ou une matière...">
    </div>

    <div class="panel">
        <table id="table">
            <thead>
            <tr>
                <th>Étudiant</th>
                <th>Matière</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr class="empty-row">
                    <td colspan="4">Aucune note n'a encore été enregistrée.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()):
                    $score = (float) $row['score'];
                    if ($score >= 14) {
                        $badgeClass = 'good';
                    } elseif ($score >= 10) {
                        $badgeClass = 'average';
                    } else {
                        $badgeClass = 'low';
                    }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo htmlspecialchars($row['score']); ?>/20
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a class="btn-action btn-edit" href="edit.php?id=<?php echo (int)$row['id']; ?>"> Modifier</a>
                            <button type="button" class="btn-action btn-delete"
                                onclick="confirmDelete(<?php echo (int)$row['id']; ?>)">Supprimer </button>
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
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#table tbody tr");

    rows.forEach((row) => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});

function confirmDelete(id){
    if (confirm("Voulez-vous vraiment supprimer cette note ?")) {
        window.location.href = "list.php?delete=" + id;
    }
}
</script>

</body>
</html>