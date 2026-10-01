<?php
session_start();

$user = "root";
$pass = "";
$server = "localhost";
$dbname = "db5";

try {
    $conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    echo "error : " . $e->getMessage();
}
?>
