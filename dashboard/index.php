<?php

session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}
$studentsCount = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$teachersCount = $conn->query("SELECT COUNT(*) AS total FROM teachers")->fetch_assoc()['total'];
$classesCount  = $conn->query("SELECT COUNT(*) AS total FROM classes")->fetch_assoc()['total'];


$presenceRow = $conn->query("
    SELECT 
        ROUND(SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) / COUNT(*) * 100, 0) AS avg_presence
    FROM attendance
")->fetch_assoc();
$avgPresence = $presenceRow['avg_presence'] ?? 0;

$weekDays = ['Mon' => 'Lun', 'Tue' => 'Mar', 'Wed' => 'Mer', 'Thu' => 'Jeu', 'Fri' => 'Ven'];
$weeklyData = [];
foreach ($weekDays as $en => $fr) {
    $stmt = $conn->prepare("
        SELECT ROUND(SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) / COUNT(*) * 100, 0) AS rate
        FROM attendance
        WHERE DAYNAME(date) = ?
    ");
    $stmt->bind_param("s", $en);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $weeklyData[$fr] = $res['rate'] ?? 0;
}

$subjectAverages = [];
$res = $conn->query("SELECT subject, ROUND(AVG(score),1) AS avg_score FROM grades GROUP BY subject");
while ($row = $res->fetch_assoc()) {
    $subjectAverages[$row['subject']] = $row['avg_score'];
}

$recentStudents = $conn->query("SELECT * FROM students ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de bord — SMS Admin</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
    :root{
        --navy:#0f1b3c;
        --navy-light:#16213e;
        --navy-lighter:#1f2a4a;
        --gold:#c9a44c;
        --gold-light:#e3c878;
        --forest:#2e7d4f;
        --danger:#c0392b;
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

    .sidebar{
        position:fixed;
        left:0;
        top:0;
        width:230px;
        height:100%;
        background:var(--navy);
        padding:24px 16px;
        color:#fff;
        border-right:3px solid var(--gold);
    }

    .sidebar h2{
        color:var(--gold-light);
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
        background:var(--navy-lighter);
        color:var(--gold-light);
    }

    .sidebar a.active{
        background:var(--navy-lighter);
        color:var(--gold-light);
        border-left:3px solid var(--gold);
        font-weight:600;
    }

    .main{
        margin-left:230px;
        padding:28px 32px;
    }

    .header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:24px;
    }

    .header h1{
        font-size:24px;
        margin:0;
        color:var(--navy);
    }

    .user-box{
        display:flex;
        align-items:center;
        gap:14px;
        font-size:14px;
        color:var(--text-muted);
    }

    .logout{
        background:var(--danger);
        color:#fff;
        padding:8px 14px;
        border-radius:6px;
        text-decoration:none;
        font-size:13px;
        font-weight:600;
    }

    .avatar{
        width:34px;
        height:34px;
        border-radius:50%;
        background:var(--navy-light);
        color:var(--gold-light);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
    }

    .cards{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:18px;
        margin-bottom:24px;
    }

    .card{
        background:#fff;
        padding:20px;
        border-radius:12px;
        border:1px solid var(--card-border);
        box-shadow:0 2px 6px rgba(15,27,60,0.05);
        display:flex;
        align-items:center;
        gap:16px;
    }

    .card-icon{
        width:48px;
        height:48px;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
        flex-shrink:0;
    }

    .icon-students{background:#e8f0fe; color:#1a56db;}
    .icon-teachers{background:#e6f4ea; color:var(--forest);}
    .icon-classes{background:#f1edfb; color:#6d4fc7;}
    .icon-presence{background:#fdf1e7; color:#c9821f;}

    .card-label{
        font-size:13px;
        color:var(--text-muted);
        margin-bottom:4px;
        font-weight:600;
    }

    .card-value{
        font-size:26px;
        font-weight:800;
        color:var(--navy);
    }

    .charts{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
        margin-bottom:24px;
    }

    .panel{
        background:#fff;
        border-radius:12px;
        border:1px solid var(--card-border);
        box-shadow:0 2px 6px rgba(15,27,60,0.05);
        padding:20px;
    }

    .panel h3{
        margin:0 0 16px 0;
        font-size:16px;
        color:var(--navy);
        display:flex;
        align-items:center;
        gap:8px;
    }

    .panel canvas{
        max-height:280px;
    }

    .table-panel{
        background:#fff;
        border-radius:12px;
        border:1px solid var(--card-border);
        box-shadow:0 2px 6px rgba(15,27,60,0.05);
        padding:20px;
    }

    .table-panel h3{
        margin:0 0 16px 0;
        color:var(--navy);
        font-size:16px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        text-align:left;
        font-size:12.5px;
        text-transform:uppercase;
        letter-spacing:0.4px;
        color:var(--text-muted);
        border-bottom:2px solid var(--card-border);
        padding:10px 8px;
    }

    td{
        padding:12px 8px;
        border-bottom:1px solid #f0f0f0;
        font-size:14px;
    }

    tr:hover td{
        background:#faf9f5;
    }

    .badge{
        display:inline-block;
        padding:3px 10px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
        background:#e6f4ea;
        color:var(--forest);
    }

    @media (max-width:1100px){
        .cards{grid-template-columns:repeat(2,1fr);}
        .charts{grid-template-columns:1fr;}
    }
</style>
</head>
<body>


<div class="sidebar">
    <h2>🎓 SMS Admin</h2>
    <a href="index.php" class="active">📊 Tableau de bord</a>
    <a href="../students/list.php">🎓 Étudiants</a>
    <a href="../teachers/list.php">📋 Enseignants</a>
    <a href="../classes/list.php">👥 Classes</a>
    <a href="../attendance/list.php">✅ Présences</a>
    <a href="../grades/list.php">📈 Notes</a>
     <a href="../users/list.php">🔐Users</a>
</div>

<div class="main">

    <div class="header">
        <h1>Tableau de bord</h1>
        <div class="user-box">
            <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['user'],0,1)); ?></div>
            <a href="../auth/logout.php" class="logout">Déconnexion</a>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <div class="card-icon icon-students">🎓</div>
            <div>
                <div class="card-label">Total Étudiants</div>
                <div class="card-value"><?php echo $studentsCount; ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-icon icon-teachers">📋</div>
            <div>
                <div class="card-label">Total Enseignants</div>
                <div class="card-value"><?php echo $teachersCount; ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-icon icon-classes">👥</div>
            <div>
                <div class="card-label">Total Classes</div>
                <div class="card-value"><?php echo $classesCount; ?></div>
            </div>
        </div>

        <div class="card">
            <div class="card-icon icon-presence">✅</div>
            <div>
                <div class="card-label">Présence Moyenne</div>
                <div class="card-value"><?php echo $avgPresence; ?>%</div>
            </div>
        </div>
    </div>

    <div class="charts">
        <div class="panel">
            <h3>📈 Taux de présence hebdomadaire</h3>
            <canvas id="weeklyChart"></canvas>
        </div>

        <div class="panel">
            <h3>📊 Moyenne par matière</h3>
            <canvas id="subjectChart"></canvas>
        </div>
    </div>

    <div class="table-panel">
        <h3>Étudiants récents</h3>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = $recentStudents->fetch_assoc()): ?>
                <tr>
    <td>
        <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($s['email'] ?? '-'); ?>
    </td>

    <td><span class="badge">Actif</span></td>
</tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
new Chart(weeklyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($weeklyData)); ?>,
        datasets: [{
            label: 'Présence (%)',
            data: <?php echo json_encode(array_values($weeklyData)); ?>,
            borderColor: '#c9a44c',
            backgroundColor: 'rgba(201,164,76,0.15)',
            tension: 0.35,
            fill: true,
            pointBackgroundColor: '#16213e',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});

const subjectCtx = document.getElementById('subjectChart').getContext('2d');
new Chart(subjectCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_keys($subjectAverages)); ?>,
        datasets: [{
            label: 'Moyenne',
            data: <?php echo json_encode(array_values($subjectAverages)); ?>,
            backgroundColor: '#16213e',
            borderRadius: 6,
            barThickness: 36
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 20 } }
    }
});
</script>

</body>
</html>