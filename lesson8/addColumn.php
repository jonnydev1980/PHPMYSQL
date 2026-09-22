<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=db4","root","");

    $sql = "ALTER TABLE products ADD email VARCHAR(255)";

    $pdo->exec($sql);

    echo "Column created successfully!";
}catch(PDOException $e){
    echo "Error creating column: " . $e->getMessage();
}
?>
