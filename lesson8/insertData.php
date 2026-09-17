<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=db4","root","");

    $username = "Jack";
    $pass = "test";

    $sql = "INSERT INTO users (username,pass) VALUES ('$username','$pass')";

    $pdo -> exec($sql);

    echo "New record created successfully";

}catch(Excpetion $e){
    echo $e -> getMessage();
}

?>