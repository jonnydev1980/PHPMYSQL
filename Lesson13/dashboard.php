<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        table, td, th {
            border: 1px solid black;
            border-collapse: collapse;
        }

        td, th {
            padding: 10px 20px;
        }
    </style>
</head>

<body>

<?php
include_once('config.php');

$sql = "SELECT * FROM users";
$getUsers = $conn->prepare($sql);
$getUsers->execute();
$users = $getUsers->fetchAll();
?>

<br><br>

<table>
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
    </thead>

    <tbody>
        <?php
        foreach($users as $user){
        ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<a href="add.php">Add user</a>

</body>
</html>
