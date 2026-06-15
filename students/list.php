<?php
include("../config/db.php");

$result = $conn->query("
    SELECT students.*, classes.name AS class_name
    FROM students
    LEFT JOIN classes ON students.class_id = classes.id
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students List</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container">

    <div class="title">Students List</div>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>   
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Class</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>

                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['class_name']; ?></td>

                   
                    <td>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure?')"
                           style="color:red; padding:6px 10px; border-radius:6px; text-decoration:none;">
                           Delete
                        </a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" 
                           style="color:green;padding:6px 10px;border-radius:6px;text-decoration:none;margin-right:5px;">
                          Edit
</a> 
                    </td>

                </tr>
                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>