<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
verify_csrf();
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$stmt = $conn->prepare('SELECT id, emri, username, email, password, is_admin FROM users WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();
$passwordValid = false;
if ($user) {
    $stored = (string)$user['password'];
    $passwordValid = password_verify($password, $stored);
    // Compatibility for older local records that still contain a plain-text password.
    if (!$passwordValid && hash_equals($stored, $password)) {
        $passwordValid = true;
        $upgrade = $conn->prepare('UPDATE users SET password = :password WHERE id = :id');
        $upgrade->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $user['id']]);
    }
}
if (!$passwordValid) {
    $_SESSION['login_error'] = 'Username ose fjalëkalimi nuk është i saktë.';
    header('Location: login.php'); exit;
}
session_regenerate_id(true);
$_SESSION['id'] = (int)$user['id'];
$_SESSION['emri'] = $user['emri'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];
$_SESSION['is_admin'] = $user['is_admin'];
header('Location: dashboard.php'); exit;
