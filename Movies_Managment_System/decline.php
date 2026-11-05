<?php
require_once __DIR__ . '/includes/bootstrap.php';require_admin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if($id){$s=$conn->prepare("UPDATE bookings SET is_approved='false' WHERE id=:id");$s->execute(['id'=>$id]);flash('success','Rezervimi u refuzua.');}header('Location: bookings.php');exit;
