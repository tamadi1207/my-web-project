<?php
//////データの自動バックアップ（同期）cron実行用

// 1. db_info.php の読み込み (絶対パス)
$db_file = __DIR__ . '/../db_info.php';
if (!file_exists($db_file)) {
    die("エラー: db_info.php が見つかりません。");
}
require_once $db_file;

// 2. $pdo (データベース接続) の確立
if (!isset($pdo) || $pdo === null) {
    try {
        $pdo = new PDO("mysql:dbname=$db_name;host=$serv", "$user", "$pass", [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (Exception $ex) {
        die("DB接続失敗: " . $ex->getMessage());
    }
}

// --- API呼び出し関数 ---
if (!function_exists('callTodoist')) {
    function callTodoist($url, $token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}

// 3. db_userテーブルからトークンを持つユーザーだけを抽出
$stmt_u = $pdo->query("SELECT name, todoist_token FROM db_user WHERE todoist_token IS NOT NULL AND todoist_token != ''");
$users = $stmt_u->fetchAll(PDO::FETCH_ASSOC);

if (!$users) {
    die("同期対象のユーザー（トークン保持者）がDBに見つかりませんでした。");
}

$sync_start_time = date('Y-m-d H:i:s');
echo "同期開始: " . $sync_start_time . "\n";

foreach ($users as $user_row) {
    $current_user = $user_row['name'];
    $todoist_token = $user_row['todoist_token'];
    echo ">>> ユーザー [ " . htmlspecialchars($current_user) . " ] の同期を開始\n";

    // 4. プロジェクト一覧を取得
    $proj_res = callTodoist("https://api.todoist.com/rest/v2/projects", $todoist_token);
    $projects = json_decode($proj_res, true);

    if (!is_array($projects)) {
        echo "   - プロジェクト取得失敗 (トークン無効の可能性)\n";
        continue;
    }

    foreach ($projects as $proj) {
        $project_id = $proj['id'];
        $project_name = $proj['name'];

        // 5. タスク一覧を取得して件数を数える
        $task_res = callTodoist("https://api.todoist.com/rest/v2/tasks?project_id=" . $project_id, $todoist_token);
        $tasks = json_decode($task_res, true);
        $current_task_count = is_array($tasks) ? count($tasks) : 0;

        // --- プロジェクト情報の保存 (todoist_projects_cache) ---
        // task_count を保存対象に追加
        $sql_p = "INSERT INTO todoist_projects_cache (project_id, name, project_name, task_count, updated_at) 
                  VALUES (:pid, :uname, :pname, :tcount, NOW()) 
                  ON DUPLICATE KEY UPDATE project_name = VALUES(project_name), task_count = VALUES(task_count), updated_at = NOW()";
        
        $stmt_p = $pdo->prepare($sql_p);
        $stmt_p->execute([
            ':pid'    => $project_id,
            ':uname'  => $current_user,
            ':pname'  => $project_name,
            ':tcount' => $current_task_count
        ]);

        if (!is_array($tasks)) continue;

        foreach ($tasks as $t) {
            // 6. コメント取得
            $comments_text = "";
            if ($t['comment_count'] > 0) {
                $cmt_res = callTodoist("https://api.todoist.com/rest/v2/comments?task_id=" . $t['id'], $todoist_token);
                $comments_text = $cmt_res;
            }

            // 7. タスク・コメントの保存 (todoist_task_cmt_cache)
            $sql_t = "INSERT INTO todoist_task_cmt_cache (
                task_id, name, project_id, task_name, due_date, labels, child_order, comments, comment_count, updated_at
            ) VALUES (
                :task_id, :name, :project_id, :task_name, :due_date, :labels, :child_order, :comments, :count, NOW()
            ) ON DUPLICATE KEY UPDATE 
                task_name = VALUES(task_name), 
                due_date = VALUES(due_date), 
                labels = VALUES(labels), 
                child_order = VALUES(child_order),
                comments = VALUES(comments), 
                comment_count = VALUES(comment_count), 
                updated_at = NOW()";

            $stmt_t = $pdo->prepare($sql_t);
            $stmt_t->execute([
                ':task_id'    => $t['id'],
                ':name'       => $current_user,
                ':project_id' => $project_id,
                ':task_name'  => $t['content'],
                ':due_date'   => isset($t['due']['date']) ? $t['due']['date'] : null,
                ':labels'     => json_encode($t['labels']),
                ':child_order'=> $t['order'],
                ':comments'   => $comments_text,
                ':count'      => $t['comment_count']
            ]);
        }
    }

    // 8. 削除済みデータのクリーニング
    $stmt_del_p = $pdo->prepare("DELETE FROM todoist_projects_cache WHERE name = :name AND updated_at < :sync");
    $stmt_del_p->execute([':name' => $current_user, ':sync' => $sync_start_time]);

    $stmt_del_t = $pdo->prepare("DELETE FROM todoist_task_cmt_cache WHERE name = :name AND updated_at < :sync");
    $stmt_del_t->execute([':name' => $current_user, ':sync' => $sync_start_time]);

    echo "   - 同期完了\n";
}
echo "\nすべての処理が終了しました。\n";