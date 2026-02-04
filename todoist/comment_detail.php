<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
$category= 'on'; 

// ログイン状態のチェック
if ($cntid == 1) {
    $task_id = $_GET['task_id'] ?? null;
    $projectName = $_GET['projectName'] ?? null;

    if (!$task_id) die('task_id が指定されていません');

    // ------------------------
    // 1. トークン取得 (iPhone対策)
    // ------------------------
    $stmt_token = $pdo->prepare("SELECT todoist_token FROM db_user WHERE name = :name LIMIT 1");
    $stmt_token->execute([':name' => $id]); // cookie.phpの$idを使用
    $user_data = $stmt_token->fetch(PDO::FETCH_ASSOC);
    $api_token = $user_data['todoist_token'] ?? '';

    // ------------------------
    // 2. コメント取得
    // ------------------------
    $stmt = $pdo->prepare("SELECT comments FROM todoist_task_cmt_cache WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $task_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $comments_text = $row['comments'] ?? '';
    $comments_arr = json_decode($comments_text, true) ?? [];

    $htcreate = date('Y');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/history.css" rel="stylesheet" media="all">
    <title>todoistコメント - <?= htmlspecialchars($projectName) ?></title>
    <style type="text/css">
        body, html { margin: 0; padding: 0; width: 100%; background-color: #f0f2f5; }
        #contener { width: 100%; max-width: 1200px; margin: 0 auto; background: #fff; min-height: 100vh; display: flex; flex-direction: column; }
        .comment-title { border-left: 6px solid #3B5998; width: 90%; margin-left: 15px; padding: 5px 10px; font-size: 22px; color: #333; background: #fff; margin-bottom: 5px; font-weight: bold; }
        .comment-container { padding: 10px 20px; flex: 1; }
        .comment-item { padding: 20px 0; border-bottom: 1px solid #eee; display: flex; flex-direction: column; }
        .comment-number { font-weight: bold; color: #3B5998; font-size: 14px; margin-bottom: 10px; }
        .comment-content { font-size: 16px; line-height: 1.6; white-space: pre-wrap; word-break: break-all; color: #222; }
        .attachment-area { margin: 15px 0; text-align: left; }
        .comment-thumb { max-width: 350px; width: auto; height: auto; border-radius: 6px; border: 1px solid #ddd; display: inline-block; }
        .pdf-link { display: inline-block; padding: 10px 15px; background: #fff5f5; border: 1px solid #ebccd1; color: #a94442; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .comment-date { font-size: 12px; color: #999; margin-top: 10px; }
        #footer { background-color: #fff; color: #333; text-align: center; padding: 20px 0; font-size: 12px; border-top: 1px solid #eee; }
        @media screen and (max-width: 768px) {
            .comment-thumb { max-width: 100%; }
            .attachment-area { text-align: center; }
        }
    </style>
</head>
<body>
<?php require '../require/header.php'; ?>
<div id="contener">
    <h2 class="comment-title"><?= htmlspecialchars($projectName) ?></h2>
    <div class="comment-container">
        <span>コメント</span>
        <?php if (empty($comments_arr)): ?>
            <p style="padding: 40px; text-align: center; color: #999;">コメントはありません</p>
        <?php else: ?>
            <?php $i = 1; foreach ($comments_arr as $c): ?>
                <div class="comment-item">
                    <div class="comment-number">No. <?= $i ?></div>
                    <div class="comment-content"><?= htmlspecialchars($c['content'] ?? '') ?></div>
                    
                    <?php if (isset($c['attachment']['file_url'])): ?>
                        <div class="attachment-area">
                            <?php 
                                $f_type = $c['attachment']['file_type'] ?? '';
                                $f_url  = $c['attachment']['file_url'];
                                $f_name = $c['attachment']['file_name'] ?? 'ファイル';

                                // トークン付加処理
                                if (!empty($api_token) && strpos($f_url, 'token=') === false) {
                                    $sep = (strpos($f_url, '?') === false) ? '?' : '&';
                                    $f_url .= $sep . "token=" . $api_token;
                                }
                                $f_url = str_replace('http://', 'https://', $f_url);
                            ?>
<?php if (strpos($f_type, 'image') === 0): ?>
    <?php 
        // サーバー側で画像をバイナリとして取得
        $base64_img = get_image_data($f_url, $api_token);
    ?>
    <?php if ($base64_img): ?>
        <a href="<?= htmlspecialchars($f_url) ?>" target="_blank">
            <img src="<?= $base64_img ?>" class="comment-thumb">
        </a>
    <?php else: ?>
        <a href="<?= htmlspecialchars($f_url) ?>" target="_blank" class="pdf-link">
            ⚠️ 画像を読み込めませんでした（クリックして確認）
        </a>
    <?php endif; ?>
                            <?php elseif (strpos($f_type, 'pdf') !== false): ?>
                                <a href="<?= htmlspecialchars($f_url) ?>" target="_blank" class="pdf-link">
                                    PDF：<?= htmlspecialchars($f_name) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="comment-date">
                        <?php 
                        if (isset($c['posted_at'])) {
                            $date = new DateTime($c['posted_at']);
                            $date->setTimezone(new DateTimeZone('Asia/Tokyo'));
                            echo $date->format('Y/m/d H:i');
                        }
                        ?>
                    </div>
                </div>
            <?php $i++; endforeach; ?>
        <?php endif; ?>
    </div>
    <div id="footer">Copyright &copy; <?= $htcreate ?> Rights Reserved.</div>
</div>
</body>
</html>
<?php 
    $pdo = NULL;
}
/* --- ファイルの末尾に追加 --- */
function get_image_data($url, $token) {
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "Authorization: Bearer " . $token . "\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $data = @file_get_contents($url, false, $context);
    if ($data === false) return null;
    return 'data:image/jpeg;base64,' . base64_encode($data);
}

?>