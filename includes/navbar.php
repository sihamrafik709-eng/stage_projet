<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="sidebar">
    <h2>🎓 SMS Admin</h2>
 <?php if($_SESSION['role'] == 'admin'){?>

        <a href="../dashboard/index.php">📊 Tableau de bord</a>
        <a href="../students/list.php">🎓 Étudiants</a>
        <a href="../teachers/list.php">📋 Enseignants</a>
        <a href="../classes/list.php">👥 Classes</a>
        <a href="../attendance/list.php">✅ Présences</a>
        <a href="../grades/list.php">📈 Notes</a>
        <a href="../users/list.php">🔐 Users</a>
<?php  } ?>
<?php if($_SESSION['role'] == 'teacher'){?>
        <a href="../dashboard/index.php">📊 Tableau de bord</a>
        <a href="../students/list.php">🎓 Étudiants</a>
        <a href="../attendance/list.php">✅ Présences</a>
        <a href="../grades/list.php">📈 Notes</a>
<?php  } ?>
</div>
<style>
 .sidebar{
        position:fixed;
        left:0;
        top:0;
        width:230px;
        height:100%;
        background:#0f1b3c ;
        padding:24px 16px;
        color:#fff;
        border-right:3px solid #c9a44c;
    }

    .sidebar h2{
    color:#e3c878;
    font-size:20px;
    letter-spacing:0.5px;
    margin:0 0 30px 6px;
    font-weight:700;
}


    .sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    color:#dfe3ee;
    text-decoration:none;
    padding:11px 12px;
    margin-bottom:6px;
    border-radius:8px;
    font-size:14.5px;
    transition:all .2s ease;
}
.sidebar a:hover{
    background:#1f2a4a;
    color:#e3c878;
}

    .sidebar a.active{
    background:#1f2a4a;
    color:#e3c878;
    border-left:3px solid #c9a44c;
    font-weight:600;
}
</style>
