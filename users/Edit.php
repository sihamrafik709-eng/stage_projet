<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

$id = $_GET['id'];


$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

  
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $conn->query("UPDATE users SET 
            username='$username',
            password='$password',
            role='$role'
            WHERE id=$id
        ");
    } else {
        $conn->query("UPDATE users SET 
            username='$username',
            role='$role'
            WHERE id=$id
        ");
    }

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:420px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    color:#0f1b3c;
    margin-bottom:20px;
}

input, select{
    width:100%;
    padding:10px;
    margin-bottom:12px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
}

input:focus, select:focus{
    border-color:#0f1b3c;
}

button{
    width:100%;
    padding:10px;
    background:#0f1b3c;
    color:white;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#162a5a;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#666;
}

.back:hover{
    color:#0f1b3c;
}
</style>
</head>

<body>

<div class="container">

    <h2>✏️ Edit User</h2>

    <form method="POST">

        <input type="text" name="username"
               value="<?php echo htmlspecialchars($user['username']); ?>"
               required>

        <input type="password" name="password"
               placeholder="New password (optional)">

        <select name="role">
            <option value="admin" <?php if($user['role']=="admin") echo "selected"; ?>>Admin</option>
            <option value="teacher" <?php if($user['role']=="teacher") echo "selected"; ?>>Teacher</option>
        </select>

        <button type="submit" name="update">Update User</button>

    </form>

    <a href="list.php" class="back">← Back to list</a>

</div>

</body>
</html>