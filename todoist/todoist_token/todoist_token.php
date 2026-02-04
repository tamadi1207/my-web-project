<?php

// 二つ上の階層にある cookie.php を読み込む
require_once __DIR__ . '/../../cookie.php';

// cookie.php で定義された $id と $pdo を使用
if (!empty($id) && isset($pdo)) {
    
    $sql = "SELECT todoist_token FROM db_user WHERE name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $id, PDO::PARAM_STR);
    $stmt->execute();
    $todoist_token = $stmt->fetchColumn();

    if (!empty($todoist_token)) {
        // トークンあり
        header("Location: /search/todoist/projects.php");
        exit;
    } else {
        // トークンなし
        header("Location: ./todoist_auth.php");
        exit;
    }
} else {
    die("ログイン情報が正しく読み込めませんでした。クッキーを確認してください。");
}