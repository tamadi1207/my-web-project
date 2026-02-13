<?php
// 余計な空白や改行が出力されないよう、ファイルの先頭でバッファリングを開始
ob_start();

require './db_info.php';
require './cookie.php';

// ログイン状態のチェック
if ($cntid == 1) {         
    $code    = !empty($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
    $name    = !empty($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL; 
    $address = !empty($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;
    $typeid  = !empty($_GET['type']) ? htmlspecialchars($_GET['type']) : 'map';

    if (empty($address)) {
        echo "住所情報が見つかりません。";
        exit;
    }

    // 1. maphistory履歴を保存する
    try {
        $stmt_hist = $pdo->prepare("INSERT INTO maphistory (code, name, type, user, datetime) VALUES (:code, :name, :type, :user, now())");
        $stmt_hist->execute([
            ':code' => $code,
            ':name' => $name,
            ':type' => $typeid,
            ':user' => $id
        ]);
    } catch (PDOException $e) {
        // 保存失敗でナビを止めない
    }

    // 2. データベースからユーザーの設定を読み込む
    $navi_app = "google"; // 未設定時の初期値
    try {
        $stmt_pref = $pdo->prepare("SELECT default_navi_app FROM db_user_settings WHERE user_name = :uid");
        $stmt_pref->execute([':uid' => $id]);
        $row = $stmt_pref->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['default_navi_app'])) {
            $navi_app = $row['default_navi_app'];
        }
    } catch (PDOException $e) {}

    $search_word = "東京都" . $address; 

    $encoded_word = urlencode(mb_convert_encoding($search_word, 'utf-8'));

    switch ($navi_app) {
        case 'yahoo':
            $address = "yjcnav://navi/select?str=" . $encoded_word;
            break;
        case 'apple':
            $address = "http://maps.apple.com/?daddr=" . $encoded_word;
            break;
        case 'google':
        default:
            $address = "http://maps.google.com/maps?q=" . $encoded_word;
            break;
    }
    // 5. ジャンプ実行
    header('Location: ' . $address);
    exit();
}
?>