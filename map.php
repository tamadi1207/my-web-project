<?php
require './db_info.php';
require './cookie.php';

// ログイン状態のチェック
if ($cntid == 1) {                
    $code = !empty($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
    $name = !empty($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
    $address = !empty($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;

$address= "http://maps.google.com/maps?q=".urlencode(mb_convert_encoding($address, 'utf-8'));                
 header('Location: '.$address);
 exit();
}?>
