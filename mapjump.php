<?php
require './db_info.php';
require './cookie.php';

// ログイン状態のチェック
if ($cntid == 1) {         
    $code = !empty($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
    $name = !empty($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL; 
    $address = !empty($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;
     

//$sql= $pdo->prepare("INSERT INTO danchicomment (code,comment,name,img,hiduke) VALUES('$code','$comment','$userid','$img',now())") or die ("失敗");
//$sql->execute();

     
//レコードがなければINSERT,あればUPDATEをする
$sql= $pdo->prepare("INSERT INTO maphistory (code,name,type,user,datetime) VALUES($code,'$name','$typeid','$id',now())") or die ("失敗");
$sql->execute();

$address= "http://maps.google.com/maps?q=".urlencode(mb_convert_encoding($address, 'utf-8'));
 header('Location: '.$address);
 exit();
}?>
