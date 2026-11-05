<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: home.php');exit;}
verify_csrf();
$movieId=filter_input(INPUT_POST,'movie_id',FILTER_VALIDATE_INT);
$tickets=filter_input(INPUT_POST,'nr_tickets',FILTER_VALIDATE_INT);
$date=$_POST['date']??''; $time=$_POST['time']??'';
$validTimes=['12:00','15:00','17:00','19:00'];
$today=date('Y-m-d');
if(!$movieId || !$tickets || $tickets<1 || $tickets>10 || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) || $date<$today || !in_array($time,$validTimes,true)){
 flash('danger','Kontrollo numrin e biletave, datën dhe orarin. Mund të rezervosh nga 1 deri në 10 bileta.');header('Location: details.php?id='.(int)$movieId);exit;
}
$check=$conn->prepare('SELECT id FROM movies WHERE id=:id');$check->execute(['id'=>$movieId]);
if(!$check->fetch()){flash('danger','Filmi nuk u gjet.');header('Location: home.php');exit;}
$s=$conn->prepare("INSERT INTO bookings(user_id,movie_id,nr_tickets,date,is_approved,time) VALUES(:uid,:mid,:tickets,:date,'pending',:time)");
$s->execute(['uid'=>$_SESSION['id'],'mid'=>$movieId,'tickets'=>$tickets,'date'=>$date,'time'=>$time]);
flash('success','Rezervimi u regjistrua dhe është në pritje të konfirmimit.');header('Location: bookings.php');exit;
