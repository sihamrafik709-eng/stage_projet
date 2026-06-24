<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die("Class not found.");
}

$error = '';

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);

    if ($name === '') {
        $error = "Class name cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error updating class.";
        }
    }
}

$displayName = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']))
    ? $_POST['name']
    : $row['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Class</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    :root {
        --bg-page: #eef1f6;
        --surface: #ffffff;
        --navy-900: #16213e;
        --navy-700: #2c3e67;
        --gold-500: #b8902a;
        --slate-700: #334155;
        --slate-400: #94a3b8;
        --green-600: #15803d;
        --green-700: #0f5c2c;
        --red-600: #c0392b;
    }

    body {
        background: var(--bg-page);
    }

    .container {
        max-width: 480px;
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
        color: var(--gold-500);
        margin-bottom: 8px;
    }

    .title {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.01em;
        color: var(--navy-900);
        margin: 0 0 10px;
    }

    .title-rule {
        width: 56px;
        height: 3px;
        background: var(--gold-500);
        border: none;
        margin: 0 0 12px;
    }

    .page-meta {
        font-size: 14px;
        color: var(--slate-400);
    }

    .id-tag {
        display: inline-block;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 12px;
        font-weight: 700;
        color: var(--navy-900);
        background: #fbf3df;
        border: 1px solid var(--gold-500);
        padding: 2px 8px;
        border-radius: 6px;
    }

    .card {
        background: var(--surface);
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
        padding: 28px;
    }

    .alert {
        background: #fdecea;
        border: 1px solid var(--red-600);
        color: var(--red-600);
        font-size: 14px;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 18px;
    }

    .field-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--slate-700);
        margin-bottom: 8px;
    }

    input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 14px;
        font-size: 15px;
        color: var(--navy-900);
        border: 1.5px solid #dde2ec;
        border-radius: 8px;
        margin-bottom: 22px;
        transition: border-color 0.15s ease;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: var(--navy-700);
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .btn-primary {
        background: var(--green-600);
        color: #ffffff;
        border: none;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-primary:hover {
        background: var(--green-700);
    }

    .btn-back {
        font-size: 14px;
        font-weight: 600;
        color: var(--slate-400);
        text-decoration: none;
    }

    .btn-back:hover {
        color: var(--navy-700);
    }
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">School Records</span>
        <h1 class="title">Edit Class</h1>
        <hr class="title-rule">
        <div class="page-meta">Editing class <span class="id-tag">#<?php echo $id; ?></span></div>
    </div>

    <div class="card">

        <?php if ($error !== '') { ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="name">Class Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?php echo htmlspecialchars($displayName); ?>"
                required
                autofocus
            >

            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Back to list</a>
                <button type="submit" name="update" class="btn-primary">
                    Update Class
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>