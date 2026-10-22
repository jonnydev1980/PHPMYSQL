
<?php

    include_once('config.php');

    if(isset($_POST['submit']))
    {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];

        $sql = "INSERT INTO users(name,surname,email) VALUES (:name, :surname, :email)";

        $insertSql = $conn->prepare($sql);

        $insertSql->bindParam(':name', $name);
        $insertSql->bindParam(':surname', $surname);
        $insertSql->bindParam(':email', $email);

        $insertSql->execute();

        echo "The user has been added successfully";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="index.php"></a>

    <form action="add.php">
        <input type="text" name="name" placeholder="name"><br>
        <input type="text" name="surname" placeholder="surname"><br>
        <input type="email" name="email" placeholder="email"><br>
        <button type="submit" name="submit">Add</button>
    </form>
</body>
</html>