<?php include("../includes/navbar.php"); ?>
<?php
include("../config/db.php");

if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $conn->query("INSERT INTO users (username, password, role)
    VALUES ('$username', '$password', '$role')");

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add User</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:400px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#0f1b3c;
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
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#162a5a;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#666;
    text-decoration:none;
}

.back:hover{
    color:#0f1b3c;
}
</style>
</head>

<body>

<div class="container">

    <h2>➕ Add User</h2>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <select name="role">
            <option value="admin">Admin</option>
            <option value="teacher">Teacher</option>
        </select>

        <button type="submit" name="add">Save User</button>

    </form>

    <a href="list.php" class="back">← Back to list</a>

</div>

</body>
</html>