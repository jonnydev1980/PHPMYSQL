<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: list_movies.php');exit;}
verify_csrf();
$name=trim($_POST['movie_name']??'');$desc=trim($_POST['movie_desc']??'');$quality=trim($_POST['movie_quality']??'');$rating=filter_input(INPUT_POST,'movie_rating',FILTER_VALIDATE_INT);
if($name===''||$desc===''||$quality===''||$rating===false||$rating===null||$rating<0||$rating>10){flash('danger','Plotëso të gjitha fushat. Vlerësimi duhet të jetë nga 0 deri në 10.');header('Location: list_movies.php');exit;}
$image='';
if(!empty($_FILES['movie_image']['name'])){
 $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];$mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['movie_image']['tmp_name']);
 if(!isset($allowed[$mime])||$_FILES['movie_image']['size']>5*1024*1024){flash('danger','Fotografia duhet të jetë JPG, PNG ose WEBP dhe maksimumi 5 MB.');header('Location: list_movies.php');exit;}
 $image=bin2hex(random_bytes(12)).'.'.$allowed[$mime];move_uploaded_file($_FILES['movie_image']['tmp_name'],__DIR__.'/movie_images/'.$image);
}
$s=$conn->prepare('INSERT INTO movies(movie_name,movie_desc,movie_quality,movie_rating,movie_image) VALUES(:n,:d,:q,:r,:i)');$s->execute(['n'=>$name,'d'=>$desc,'q'=>$quality,'r'=>$rating,'i'=>$image]);
flash('success','Filmi u shtua me sukses.');header('Location: list_movies.php');exit;
