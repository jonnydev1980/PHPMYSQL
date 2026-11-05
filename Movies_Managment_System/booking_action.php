<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: bookings.php');exit;}
verify_csrf();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
$status=$_POST['status']??'';
if(!$id || !in_array($status,['true','false'],true)){flash('danger','Të dhënat e veprimit nuk janë të vlefshme.');header('Location: bookings.php');exit;}
$s=$conn->prepare('UPDATE bookings SET is_approved=:status WHERE id=:id');
$s->execute(['status'=>$status,'id'=>$id]);
flash('success',$status==='true'?'Rezervimi u aprovua.':'Rezervimi u refuzua.');
header('Location: bookings.php');exit;
