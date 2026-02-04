<?php
require_once __DIR__ . '/db_info.php';

try {
    // 接続（db_info.php の変数がここで使われる）
    $pdo = new PDO("mysql:host=$serv;dbname=$db_name;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("接続失敗: " . $e->getMessage());
}

// クッキー取得
$id = $_COOKIE['ID'] ?? '';
// ... 以下、既存のSQL処理


//クッキーが0か1で条件分岐
$sqlid= ("SELECT db_user.name, db_user.type FROM db_user WHERE name = '$id'") or die ("失敗");
$stmtid= $pdo->query($sqlid);
$stmtid->execute();
$cntid=$stmtid->rowcount();
// 上のsqlから会社属性(typeカラム)を選択
$comtype= $stmtid->fetch(PDO::FETCH_ASSOC);                                                                 
$typeid= htmlspecialchars($comtype['type']);

//フッターの会社名変数
$htcreate = 'Fixhome';

///////////danchihensyuフォルダ、touhensyuフォルダ、touhensyuフォルダのクエリは手動で追加する！！！////////////
if($typeid === 'rh'){
$goutb = 'goutou';
$pfulltb= 'partsfullhistory';
$ratiotb= 'ratio';
}
elseif($typeid === 'koumuten'){
$goutb= 'goutou2';
$pfulltb= 'partsfullhistory2';
$ratiotb= 'ratio2';

//1でrhのコメントon(1以外でoff)
$rhcmt = 1;

if($rhcmt === 1){
$list_cmt= "danchicomment.type = 'rh' OR"; //list.php_88
$list_cmt2= "goutoucomment.type = 'rh' OR"; //list.php_124
$building_cmt= "OR danchicomment.type= 'rh'"; //building.php_88
$building_cmt2= "OR goutoucomment.type= 'rh'"; //building.php_121	parts.php_54
}
}

if($cntid == 0){
  // クッキー破棄(現在時刻からマイナスの時間で指定する(例:60秒))
setcookie('ID', $_COOKIE['ID'], time() - 60, '/');

    switch($web){
        case 1;
            header("Location: https://www.fixhome.me/search/login/login.php");
            break;
        case 0;
        header("Location: http://localhost/search/login/login.php");
            break;
        }
    exit;
}
?>