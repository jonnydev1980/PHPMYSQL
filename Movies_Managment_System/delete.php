<?php
require_once __DIR__ . '/includes/bootstrap.php';require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if($id){$s=$conn->prepare('DELETE FROM movies WHERE id=:id');$s->execute(['id'=>$id]);flash('success','Filmi u fshi.');}header('Location: list_movies.php');exit;
