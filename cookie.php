<?php
require_once __DIR__ . '/db_info.php';

// ▼▼▼ DB接続 (PDO) ▼▼▼
try {
    $pdo = new PDO("mysql:host=$serv;dbname=$db_name;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // プリペアドステートメントのエミュレーションを無効化（セキュリティ最大化）
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("データベース接続失敗");
}

// ▼▼▼ セッションからIDを取得 (クッキー直接参照の廃止) ▼▼▼
// login.php でセットされたセッション変数を使います
$id = $_SESSION['USERID'] ?? ''; 

// 変数の初期化
$cntid = 0;
$typeid = '';
$htcreate = 'Fixhome';

// DBチェック (セッションIDが有効か確認)
if (!empty($id)) {
    try {
        $stmtid = $pdo->prepare("SELECT name, type FROM db_user WHERE name = :name");
        $stmtid->bindValue(':name', $id, PDO::PARAM_STR);
        $stmtid->execute();
        
        $cntid = $stmtid->rowCount();
        $comtype = $stmtid->fetch(PDO::FETCH_ASSOC);
        
        if ($comtype) {
            $typeid = htmlspecialchars($comtype['type'], ENT_QUOTES, 'UTF-8');
        }
    } catch (PDOException $e) {
        $cntid = 0;
    }
}

// ログインしていない、またはユーザーが存在しない場合
if ($cntid == 0) {
    // セッション内の情報をクリア
    $_SESSION = array();
    
    // 現在のページが login.php 以外ならログイン画面へ飛ばす処理などを入れても良いですが、
    // ここでは「ログインしていない状態」として処理を進めます。
    // 必要に応じて以下のようにリダイレクトを入れてください。
    /*
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header("Location: ../login/login.php");
        exit;
    }
    */
}

// --- 以下、既存のロジック（他のファイルで使う変数設定） ---
$goutb = 'goutou'; 
$pfulltb = '';
$ratiotb = '';
$list_cmt = '';
$list_cmt2 = '';
$building_cmt = '';
$building_cmt2 = '';

if($typeid === 'rh'){
    $goutb = 'goutou';
    $pfulltb = 'partsfullhistory';
    $ratiotb = 'ratio';
} elseif($typeid === 'koumuten'){
    $goutb= 'goutou2';
    $pfulltb= 'partsfullhistory2';
    $ratiotb= 'ratio2';

    $rhcmt = 1;
    if($rhcmt === 1){
        $list_cmt= "danchicomment.type = 'rh' OR";
        $list_cmt2= "goutoucomment.type = 'rh' OR";
        $building_cmt= "OR danchicomment.type= 'rh'";
        $building_cmt2= "OR goutoucomment.type= 'rh'";
    }
}
?>