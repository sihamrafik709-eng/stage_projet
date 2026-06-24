<?php include("../includes/navbar.php"); ?>
<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
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
    :root{
        --navy:#0f1b3c;
        --navy-light:#16213e;
        --gold:#c9a44c;
        --gold-light:#e3c878;
        --forest:#2e7d4f;
        --danger:#c0392b;
        --warning:#c9821f;
        --bg:#f4f5f7;
        --card-border:#e6e2d6;
        --text-dark:#1c1f2a;
        --text-muted:#6b7280;
    }

    *{box-sizing:border-box;}

    body{
        margin:0;
        font-family:'Segoe UI', Arial, sans-serif;
        background:var(--bg);
        color:var(--text-dark);
    }

    .container{
        width:90%;
        max-width:1100px;
        margin:36px auto;
    }

    .top-bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:20px;
    }

    h2{
        color:var(--navy);
        margin:0;
        font-size:22px;
    }

    .add-btn{
        background:var(--navy-light);
        color:#fff;
        text-decoration:none;
        padding:10px 18px;
        border-radius:8px;
        font-size:13.5px;
        font-weight:700;
        transition:background .2s ease;
    }

    .add-btn:hover{
        background:var(--navy);
    }

    .search-box{
        position:relative;
        margin-bottom:16px;
    }

    .search-box input{
        width:100%;
        max-width:320px;
        padding:11px 14px 11px 38px;
        border:1px solid var(--card-border);
        border-radius:8px;
        font-size:14px;
        background:#fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%236b7280" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>') no-repeat 12px center;
        background-size:14px;
    }

    .search-box input:focus{
        outline:none;
        border-color:var(--gold);
    }

    .panel{
        background:#fff;
        border-radius:12px;
        border:1px solid var(--card-border);
        box-shadow:0 2px 6px rgba(15,27,60,0.05);
        overflow:hidden;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        background:var(--navy-light);
        color:#fff;
        padding:13px 14px;
        text-align:left;
        font-size:13px;
        text-transform:uppercase;
        letter-spacing:0.4px;
    }

    td{
        padding:13px 14px;
        border-bottom:1px solid #f0f0f0;
        font-size:14.5px;
    }

    tbody tr:hover td{
        background:#faf9f5;
    }

    .badge{
        display:inline-block;
        padding:4px 12px;
        border-radius:20px;
        font-size:12.5px;
        font-weight:700;
    }

    .badge.good{
        background:#e6f4ea;
        color:var(--forest);
    }

    .badge.average{
        background:#fdf1e7;
        color:var(--warning);
    }

    .badge.low{
        background:#fdecea;
        color:var(--danger);
    }

    .empty-row td{
        text-align:center;
        color:var(--text-muted);
        padding:24px;
    }

    .actions{
        display:flex;
        gap:8px;
    }

    .btn-action{
        display:inline-flex;
        align-items:center;
        gap:5px;
        padding:7px 12px;
        border-radius:7px;
        font-size:12.5px;
        font-weight:700;
        text-decoration:none;
        border:none;
        cursor:pointer;
        font-family:inherit;
    }

    .btn-edit{
        background:green;
        color:white;
    }

    .btn-delete{
        background:red;
        color:white;
    }

    
</style>

</head>

<body>

<div class="container">

    <div class="top-bar">
        <h2>Liste des notes</h2>
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
                    <td colspan="4">Aucune note enregistrée pour le moment.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()):
                    $score = (float) $row['score'];
                    if ($score >= 14) {
                        $badgeClass = 'Bon';
                    } elseif ($score >= 10) {
                        $badgeClass = 'Moyen';
                    } else {
                        $badgeClass = 'Faible';
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
                                onclick="confirmDelete(<?php echo (int)$row['id']; ?>)"> Supprimer</button>
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