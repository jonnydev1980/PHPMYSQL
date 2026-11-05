<?php
require_once __DIR__ . '/includes/bootstrap.php';require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if($id && $id!==(int)$_SESSION['id']){$s=$conn->prepare('DELETE FROM users WHERE id=:id');$s->execute(['id'=>$id]);flash('success','Përdoruesi u fshi.');}else{flash('warning','Nuk mund ta fshish llogarinë tënde nga kjo faqe.');}header('Location: dashboard.php');exit;
