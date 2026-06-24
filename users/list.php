<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Users List</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:900px;
    margin:60px auto;
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#0f1b3c;
}

/* ADD BUTTON */
.top{
    text-align:right;
    margin-bottom:15px;
}

.btn-add{
    background:#0f1b3c;
    color:white;
    padding:10px 15px;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

.btn-add:hover{
    background:#162a5a;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
    background:#0f1b3c;
    color:white;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f1f5ff;
}

/* ACTIONS */
a{
    text-decoration:none;
    padding:6px 10px;
    border-radius:6px;
    font-size:13px;
}

.edit{
    background:#15803d;
    color:white;
}

.delete{
    background:#c0392b;
    color:white;
}

.edit:hover{ background:#0f5c2c; }
.delete:hover{ background:#a5281f; }

.role{
    padding:4px 8px;
    border-radius:20px;
    background:#e6f4ea;
    color:#15803d;
    font-size:12px;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">

    <h2>👤 Users List</h2>

    <div class="top">
        <a href="add.php" class="btn-add">+ Add User</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td>
                <span class="role"><?php echo $row['role']; ?></span>
            </td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete"
                   onclick="return confirm('Delete this user?');">
                   Delete
                </a>
            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>