<?php
include("../config/db.php");

$message = "";
$type = "";
$name = "";

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if ($name === '') {
        $message = "Class name cannot be empty.";
        $type = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $message = "Class added successfully!";
            $type = "success";
            $name = "";
        } else {
            $message = "Error adding class.";
            $type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Class</title>
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
        --green-050: #eafbf0;
        --red-600: #c0392b;
        --red-050: #fdecea;
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

    .card {
        background: var(--surface);
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(22, 33, 62, 0.08);
        padding: 28px;
    }

    .message {
        font-size: 14px;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 18px;
        border: 1px solid transparent;
    }

    .message.success {
        background: var(--green-050);
        border-color: var(--green-600);
        color: var(--green-700);
    }

    .message.error {
        background: var(--red-050);
        border-color: var(--red-600);
        color: var(--red-600);
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
    .top-actions{
    margin-bottom:20px;
}

</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <span class="page-eyebrow">School Records</span>
        <h1 class="title">Add Class</h1>
        <hr class="title-rule">
        <div class="page-meta">Create a new class for your school records.</div>
    </div>
    <div class="card">

        <?php if (!empty($message)) { ?>
            <div class="message <?php echo htmlspecialchars($type); ?>" id="msg">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label class="field-label" for="name">Class Name</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="e.g. Grade 10 - Section A"
                value="<?php echo htmlspecialchars($name); ?>"
                required
                autofocus
            >

            <div class="form-actions">
                <a href="list.php" class="btn-back">&larr; Back to list</a>
                <button type="submit" name="add" class="btn-primary">
                    Add Class
                </button>
            </div>

        </form>

    </div>

</div>

<script src="../assets/js/script.js"></script>

<script>
setTimeout(function(){
    let msg = document.getElementById("msg");

    if(msg){
        msg.style.transition = "opacity 0.5s ease";
        msg.style.opacity = "0";

        setTimeout(() => {
            msg.remove();
        }, 500);
    }
}, 3000);
</script>

</body>
</html>