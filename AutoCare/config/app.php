<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/database.php';
function e($v){return htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');}
function redirect($url){header('Location: '.$url);exit;}
function require_role($role){if(empty($_SESSION['user_id']) || ($_SESSION['role']??'')!==$role) redirect('../login.php');}
function flash($message,$type='success'){$_SESSION['flash']=['message'=>$message,'type'=>$type];}
function show_flash(){if(!empty($_SESSION['flash'])){$f=$_SESSION['flash'];echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>';unset($_SESSION['flash']);}}
function stmt($conn,$sql,$types='',$params=[]){$s=$conn->prepare($sql);if($types!=='')$s->bind_param($types,...$params);$s->execute();return $s;}
