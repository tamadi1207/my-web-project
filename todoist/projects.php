<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
$category= 'on';

if ($cntid == 1) {
        try {
            $stmt = $pdo->prepare("SELECT project_id, project_name, task_count FROM todoist_projects_cache WHERE name = :user ORDER BY project_name ASC");
            $stmt->execute([':user' => $id]); // cookie.php から取得した $id を使用
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
                $projects = [];
            }


// 1. DBから現在のデフォルトプロジェクト設定を取得
$stmt_set = $pdo->prepare("SELECT default_project_id FROM db_user_settings WHERE user_name = :id LIMIT 1");
$stmt_set->execute([':id' => $id]); // cookie等から取得したユーザーID
$default_pid = $stmt_set->fetchColumn();

// 2. 自動ジャンプの判定
// !empty() を使うことで、値が ""（選択なし）、0、NULL の場合はジャンプしません
if (!empty($default_pid) && !isset($_GET['change_project'])) {
    header("Location: tasks.php?pid=" . urlencode($default_pid));
    exit;
}


$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");


    
?>
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="../css/history.css" rel="stylesheet" media="all">
            <title>todoistプロジェクト一覧</title>
            <style>
                body, html { background-color: #f0f2f5; margin: 0; padding: 0; }
                
                #contener { 
                    max-width: 1000px; 
                    margin: 0 auto; 
                    background: #fff; 
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }

                h1 { padding: 20px; font-size: 20px; margin: 0; border-bottom: 1px solid #eee; }

                /* リスト自体の設定：左側に寄せる */
                .project-list { 
                    list-style: none; 
                    padding: 0; 
                    margin: 20px 0 20px 20px; /* 左側に固定。右側は自由 */
                    width: 600px;             /* 下線の長さ */
                }

                .project-list li { 
                    border-bottom: 1px solid #ddd; 
                    display: flex; 
                    align-items: center;
                }

                /* リンクを横いっぱいに広げ、中身を両端に */
                .project-list li a { 
                    text-decoration: none; 
                    color: #333; 
                    font-size: 18px;
                    font-weight: bold;
                    display: flex;          /* flexbox化 */
                    justify-content: space-between; /* 名前と件数を両端に */
                    align-items: center;
                    width: 100%;            /* 横幅いっぱいをリンクに */
                    padding: 15px 0;       /* 余白をaタグの中に移動 */
                }

                /* ホバー時に少し背景色を変える（リンク範囲がわかりやすいように） */
                .project-list li a:hover {
                    background-color: #f9f9f9;
                }

                .count { 
                    background: #f0f0f0; 
                    padding: 2px 10px; 
                    border-radius: 12px; 
                    font-size: 14px; 
                    color: #666;
                    margin-left: 20px;
                    flex-shrink: 0; /* 数字が潰れないように */
                }

                #footer { 
                    background-color: #fff; 
                    color: #333; 
                    text-align: center; 
                    padding: 20px 0; 
                    font-size: 12px; 
                    border-top: 1px solid #eee;
                    margin-top: auto;
                }

                /* スマホ表示 */
                @media screen and (max-width: 768px) {
                    #contener { width: 100%; }
                    .project-list { 
                        width: 90%; 
                        margin: 20px auto;
                    }
                }
            </style>
        </head>
        <body>
            <?php require '../require/header.php'; ?>

            <div id="contener">
                <h1>プロジェクト一覧</h1>
                
                <ul class="project-list">
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $p): ?>
                        <li>
                            <a href="tasks.php?pid=<?= urlencode($p['project_id']) ?>&pname=<?= urlencode($p['project_name']) ?>&pcount=<?= (int)$p['task_count'] ?>">
                                <span><?= htmlspecialchars($p['project_name']) ?></span>
                                <span class="count"><?= (int)$p['task_count'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li style="padding: 20px; border:none;">プロジェクトが見つかりません。</li>
                <?php endif; ?>
                </ul>

                <div id="footer">
                    Copyright &copy; <?= date('Y') ?> Rights Reserved.
                </div>
            </div>
        </body>
    </html>
<?php 
    $pdo = NULL;
} 
?>