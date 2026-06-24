<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$result = $conn->query("SELECT * FROM teachers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teachers List</title>

<style>
:root{
    --bg-page:#eef1f6;
    --surface:#ffffff;
    --navy-900:#16213e;
    --navy-700:#2c3e67;
    --gold-500:#b8902a;
    --slate-400:#94a3b8;
    --green-600:#15803d;
    --green-700:#0f5c2c;
    --red-600:#c0392b;
}

*{
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:var(--bg-page);
    margin:0;
}

.container{
    max-width:1200px;
    margin:50px auto;
    padding:0 20px;
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
    color:var(--gold-500);
    margin-bottom:8px;
}

.title{
    font-size:30px;
    font-weight:800;
    color:var(--navy-900);
    margin:0;
}

.title-rule{
    width:60px;
    height:3px;
    background:var(--gold-500);
    border:none;
    margin:12px 0;
}

.page-meta{
    color:var(--slate-400);
}

.top-actions{
    margin-bottom:20px;
}

.btn-add{
    background:var(--green-600);
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:8px;
    font-weight:600;
}

.btn-add:hover{
    background:var(--green-700);
}

.card{
    background:var(--surface);
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
    background:var(--navy-900);
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
    background:var(--green-600);
    color:white;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    font-size:14px;
}

.btn-edit:hover{
    background:var(--green-700);
}

.btn-delete{
    background:var(--red-600);
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
    color:var(--slate-400);
}
</style>

</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">School Records</span>
        <h1 class="title">Teachers List</h1>
        <hr class="title-rule">
        <div class="page-meta">
            Manage all teacher records.
        </div>
    </div>

    <div class="top-actions">
        <a href="add.php" class="btn-add">+ Add Teacher</a>
    </div>

    <div class="card">

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Subject</th>
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
                                        Edit
                                    </a>

                                    <a
                                                 href="delete.php?id=<?= $row['id'] ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Are you sure you want to delete this teacher?')">
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="4" class="empty">
                            No teachers found.
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