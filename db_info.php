<?php
//2030年頃までの長期間ログイン設定
// 60秒 * 60分 * 24時間 * 365日 * 4年 = 約1億2600万秒
$session_lifetime = 60 * 60 * 24 * 365 * 4; 

// 1. サーバー側のセッション保存期間を延ばす
ini_set('session.gc_maxlifetime', $session_lifetime);

// 2. ブラウザ側のクッキー（鍵）の有効期限を延ばす
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false, // localhostでも動くように false (本来httpsなら true 推奨)
    'httponly' => true,  // JavaScriptから盗まれないようにする
    'samesite' => 'Lax'
]);

// セッション開始（二重開始エラーを防止）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ブラウザキャッシュの無効化（セキュリティ強化）
header('Expires: Tue, 01 Jan 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// 1はweb / 0はlocalhost
$web = 0;

switch ($web) {
    case 1: // 本番環境
        $db_name = 'ss222251_danchisearch'; 
        $serv = 'mysql2.star.ne.jp'; 
        $user = 'ss222251_ht'; 
        $pass = 'tamadi1192'; 
        break;
    case 0: // ローカル環境
        $db_name = 'ss222251_danchisearch'; 
        $serv = '127.0.0.1'; 
        $user = 'root'; 
        $pass = '';
        break;
}

switch($web){
    case 1:
        $URL = 'https://www.fixhome.me/search/';
        break;
    case 0:
        $URL = 'http://localhost/search';
        break;
}
?>