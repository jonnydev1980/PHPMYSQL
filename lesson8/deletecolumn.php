<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db4", "root", "");

    $sql = "ALTER TABLE products DROP COLUMN name";

    $pdo->exec($sql);

    echo "Column dropped successfully!";
} catch (PDOException $e) {
    echo "Error deleting column: " . $e->getMessage();
}
?>
