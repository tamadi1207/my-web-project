<?php
require '../../db_info.php';
// cookie.php はリダイレクト（login.phpへ飛ばされるの）を防ぐため読み込まない

// DB接続（PDO）を自前で作成
try {
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($ex->getMessage());
}

$client_id = '981379b022cc41d3b24239a6cd9306da'; //
$client_secret = '5056ae98f262459d8fb3dff9f88ea03a';

$code = $_GET['code'] ?? null;
$username = $_COOKIE['ID'] ?? null; // 現在のクッキーから「tamadi」を取得

// $cntid ではなく $username があるかどうかで判定する
if ($code && $username) {
    // 1. コードをトークンに交換
    $ch = curl_init("https://todoist.com/oauth/access_token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code,
        'redirect_uri' => 'https://fixhome.me/search/todoist/todoist_token/callback.php'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($response['access_token'])) {
        // 2. DBの db_user テーブルに保存
        $sql = "UPDATE db_user SET todoist_token = :token WHERE name = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':token', $response['access_token'], PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR); 
        $stmt->execute();

        echo "連携に成功しました。2秒後に一覧へ戻ります。";
        header("refresh:2;url=../../index.php");
    } else {
        echo "トークンの取得に失敗しました。Todoist側の設定を確認してください。";
    }
} else {
    // デバッグ用表示
    if (!$code) echo "認証コード(code)が届いていません。";
    if (!$username) echo "ログインクッキー(ID)が届いていません。";
}