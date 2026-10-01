<?php
include_once('config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "insert into users (name,username,email) values (:name,:username,:email)";
    $sqlQuery = $conn->prepare($sql);

    $sqlQuery->bindParam(':name',$name);
    $sqlQuery->bindParam(':username',$username);
    $sqlQuery->bindParam(':email',$email);

    $sqlQuery->execute();

    echo "User added successfully ...<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a user</title>
</head>
<body>

    <a href="index.php">Dashboard</a>

    <form action="add.php" method="POST">
        <input type="text" name="name" placeholder="name"><br>
        <input type="text" name="username" placeholder="username"><br>
        <input type="email" name="email" placeholder="email"><br>
        <button type="submit" name="submit">Add</button>
    </form>

</body>
</html>
