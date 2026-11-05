<?php
// Database connection settings for the local XAMPP installation.
$server = 'localhost';
$dbname = 'mms';
$user = 'root';
$pass = '';

try {
    $conn = new PDO(
        "mysql:host={$server};dbname={$dbname};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    http_response_code(500);
    exit('Nuk mund te lidhemi me databazen. Kontrollo konfigurimin e MySQL dhe databazen mms.');
}
