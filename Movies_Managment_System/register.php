<?php
require_once __DIR__ . '/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: index.php');exit;}
verify_csrf();
$name=trim($_POST['emri']??'');$username=trim($_POST['username']??'');$email=trim($_POST['email']??'');$password=$_POST['password']??'';$confirm=$_POST['confirm_password']??'';
if($name===''||$username===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<8||$password!==$confirm){flash('danger','Plotëso të gjitha fushat, përdor email të vlefshëm dhe fjalëkalim me së paku 8 karaktere. Konfirmimi duhet të përputhet.');header('Location: index.php');exit;}
$check=$conn->prepare('SELECT id FROM users WHERE username=:u OR email=:e');$check->execute(['u'=>$username,'e'=>$email]);
if($check->fetch()){flash('danger','Ky username ose email është duke u përdorur.');header('Location: index.php');exit;}
$hash=password_hash($password,PASSWORD_DEFAULT);
$s=$conn->prepare("INSERT INTO users(emri,username,email,password,confirm_password,is_admin) VALUES(:n,:u,:e,:p,:c,'')");
$s->execute(['n'=>$name,'u'=>$username,'e'=>$email,'p'=>$hash,'c'=>$hash]);
flash('success','Llogaria u krijua me sukses. Tani mund të kyçesh.');header('Location: login.php');exit;
