<?php
require_once '../db_info.php';
require_once '../cookie.php'; 
$path= '../';
$category= 'on';

if ($cntid == 1) {

    // --- 正規化関数群 (PHP版) ---
    function toHalfWidth($str){ return mb_convert_kana($str,'n'); }
    function numToKanji($num){
        $k=['〇','一','二','三','四','五','六','七','八','九'];
        if($num<10) return $k[$num];
        if($num===10) return '十';
        if($num<20) return '十'.$k[$num%10];
        if($num%10===0) return $k[intval($num/10)].'十';
        return $k[intval($num/10)].'十'.$k[$num%10];
    }
    
    function normalizeTextPHP($text){
        $text = trim($text);
        $text = preg_replace('/(交通局|水道局|総務局|教育庁|消防宿舎)/u','',$text);
        $text = trim($text);
        $parts = preg_split('/[\s　]/u', $text);
        $text = $parts[0];

        if (mb_strpos($text, '興野町住宅') !== false) return '興野町';
        if (mb_strpos($text, '春江町住宅') !== false) return '春江町'; 

        if (preg_match('/[0-9０-９\-\/ーの番号棟室]$/u', $text)) {
            if (preg_match('/^(.*[^\x01-\x7E])([0-9０-９\-\/ーの番号棟室]+)$/u', $text, $matches)) {
                $text = $matches[1];
            }
        }

        $text = preg_replace_callback('/第([0-9０-９]+)住宅/u', function($m){
            return '第'.numToKanji((int)toHalfWidth($m[1]));
        }, $text);
        $text = preg_replace('/第([一二三四五六七八九十〇]+)住宅/u', '第$1', $text);
        
        if (preg_match('/(.+?)町住宅/u', $text, $matches)) {
            $text = $matches[1] . '住宅';
        } elseif (mb_strpos($text, '寮') !== false) {
        } else {
            $text = str_replace('住宅', '', $text);
        }
        
        $text = preg_replace_callback('/([0-9]+)丁目/u', function($m){ return numToKanji((int)$m[1]).'丁目'; }, $text);
        return $text;
    }

    // 日付ラベル生成関数
    function todoistDateLabel(string $date): string {
        $today = new DateTime('today');
        $t = new DateTime($date);
        $d = (int)$today->diff($t)->format('%r%a');
        if ($d === 0) return '<span style="color:#4CAF50;">今日</span>';
        if ($d === 1) return '<span style="color:#b1976b;">明日</span>';
        if ($d <= 6 && $d > 0) {
            $w = ['日', '月', '火', '水', '木', '金', '土'];
            return '<span style="color:#9b72e0;">' . $w[(int)$t->format('w')] . '曜日</span>';
        }
        if ($d < 0) return '<span style="color:#E57373;">'.$t->format('Y-m-d').'</span>';
        return $t->format('Y-m-d');
    }

    $pid = $_GET['pid'] ?? '';
    $pname  = $_GET['pname']  ?? '';
    $pcount = $_GET['pcount'] ?? '';

    if (($pname === '' || $pcount === '') && !empty($pid)) {
        try {
            $stmt_p = $pdo->prepare("SELECT project_name, task_count FROM todoist_projects_cache WHERE project_id = :pid LIMIT 1");
            $stmt_p->execute([':pid' => $pid]);
            $project_info = $stmt_p->fetch(PDO::FETCH_ASSOC);
            if ($project_info) {
                $pname  = $project_info['project_name'];
                $pcount = $project_info['task_count'];
            }
        } catch (PDOException $e) {}
    }
    if ($pname === '') $pname = 'プロジェクト';
    if ($pcount === '') $pcount = 0;

    if (!$pid) die('プロジェクトIDが指定されていません');

    $stmt = $pdo->prepare("SELECT * FROM todoist_task_cmt_cache WHERE project_id = :pid AND name = :name ORDER BY child_order ASC");
    $stmt->execute([':pid' => $pid, ':name' => $id]);
    $cached_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $htcreate = date('Y');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/history.css?ea" rel="stylesheet" media="all">
    <title>todoistタスク一覧</title>

    <style type="text/css">
    body, html { margin: 0; padding: 0; width: 100%; background-color: #f0f2f5; }
    #contener { width: 100%; max-width: 1200px; margin: 0 auto; background: #fff; min-height: 100vh; display: flex; flex-direction: column; }
    h1 { padding: 15px; font-size: 20px; border-bottom: 1px solid #ddd; margin: 0; }
    .project-list { padding: 0; margin: 20px 0 20px 20px; list-style: none; width: 600px; }
    .project-list li { display: flex; border-bottom: 1px solid #dcdcdc; box-sizing: border-box; }
    .project-list li:nth-of-type(odd) { background-color: #f2f2f2 !important; }
    .project-list li:nth-of-type(even) { background-color: #ffffff !important; }
    .task-item-link { text-decoration: none; color: #333; display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 15px; box-sizing: border-box; cursor: pointer; }
    .task-item-link:hover { background-color: #e9e9e9; }
    .task-item-link.disabled { cursor: default; background-color: #f9f9f9 !important; opacity: 0.6; }
    .task-item-link.disabled .task-content { color: #999 !important; }
    .task-info { flex: 1; display: flex; flex-direction: column; }
    .task-content { font-size: 18px; font-weight: bold; line-height: 1.4; }
    .task-meta { display: flex; align-items: center; gap: 15px; margin-top: 5px; }
    .meta-date { font-size: 16px; font-weight: bold; }
    .meta-label { color: #6c6d6d; border: 1px solid #6c6d6d; padding: 1px 5px; border-radius: 3px; font-size: 11px; font-weight: normal; }
    .action-buttons { display: flex; gap: 10px; align-items: center; flex-shrink: 0; margin-left: 15px; }
    .count-bubble { background: transparent !important; padding: 4px 5px; font-size: 15px; color: #666; text-decoration: none; font-weight: bold; }
    .map-link { background: #e1efff !important; color: #1976d2 !important; padding: 4px 12px; border-radius: 15px; font-size: 15px; font-weight: bold; text-decoration: none; }
    #footer { background-color: #fff; color: #333; text-align: center; padding: 20px 0; font-size: 12px; border-top: 1px solid #eee; margin-top: auto; }
    @media screen and (max-width: 768px) {
        .project-list { width: 96%; margin: 10px auto; }
        .project-list li { padding: 0 !important; flex-direction: column; align-items: flex-start; }
        .task-item-link { flex-direction: column; align-items: flex-start; padding: 18px 5%; }
        .action-buttons { width: 100%; justify-content: flex-start; margin-left: 0; margin-top: 10px; }
    }
    </style>
</head>
<body>
<?php require '../require/header.php'; ?>

    <div id="contener">
        <h1 style= "margin-top: 75px;">タスク一覧</h1>
        <div style="margin: 10px 0 0 25px; font-size: 18px;"><?= htmlspecialchars($pname) ?> <?= (int)$pcount ?> 件</div>
        
        <ul class="project-list">
        <?php foreach ($cached_tasks as $row):
            $content = $row['task_name'];
            $task_id = $row['task_id'];
            $due_date = $row['due_date'];
            $comment_cnt = $row['comment_count'];
            $labels = json_decode($row['labels'], true) ?: [];
            
            // 1. 団地マスタ(danchilist)検索
            $normalizedName = normalizeTextPHP($content);
            $stmt_d = $pdo->prepare("SELECT code, syubetu, name, CONCAT(city, jusyo) AS address, map FROM danchilist WHERE name = :name LIMIT 1");
            $stmt_d->execute(['name' => $normalizedName]);
            $t_db = $stmt_d->fetch(PDO::FETCH_ASSOC);

            // ▼▼▼ リンク先判定 ▼▼▼
            $jump_url = '';
            $disabled_class = '';
            $is_goutou_match = false; // 初期化
            
            if ($t_db) {
                // 正規表現: 数字 + (ハイフン または "号棟") を探す
                // 例: "1-205", "１−２０５", "1号棟", "１号棟"
                if (preg_match('/([0-9０-９]+)([-−‐ー]|号棟)/u', $content, $matches)) {
                    $num_half = mb_convert_kana($matches[1], 'n'); // 数字を半角に

                    // ★修正: DB検索を廃止し、code + 号棟番号(半角) で codeno を生成
                    $calc_codeno = $t_db['code'] . $num_half; 

                    // 強制的にヒット扱いにする
                    $is_goutou_match = true;
                    
                    $params = [
                        'syubetu'   => $t_db['syubetu'], 
                        'name'      => $t_db['name'],
                        'address'   => $t_db['address'],
                        'code'      => $t_db['code'],
                        'map'       => $t_db['map'],
                        'codeno'    => $calc_codeno,     // 計算した値を入れる
                        'goutou'    => $num_half,        
                        'goutouvar' => '',               
                        'date'      => date('Y-m-d')     
                    ];
                    $jump_url = "{$path}parts.php?" . http_build_query($params);
                }

                // 号棟パターンがない場合は building.php へ
                if (!$is_goutou_match) {
                    $params_b = [
                        'code'    => $t_db['code'],
                        'syubetu' => $t_db['syubetu'],
                        'name'    => $t_db['name'],
                        'address' => $t_db['address']
                    ];
                    $jump_url = "{$path}building.php?" . http_build_query($params_b);
                }

            } else {
                $disabled_class = 'disabled';
                $jump_url = '';
            }
            // ▲▲▲ ここまで ▲▲▲
            ?>
            
            <li>
                <div class="task-item-link <?= $disabled_class ?>"
                     <?php if ($jump_url): ?>onclick="location.href='<?= $jump_url ?>'; return false;"<?php endif; ?>>
                    
                    <div class="task-info">
                        <span class="task-content"><?= htmlspecialchars($content); ?></span>
                        
                        <div class="task-meta" style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                            <?php if (!empty($due_date)): ?>
                                <span class="meta-date" style="white-space: nowrap;"><?= todoistDateLabel($due_date); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($t_db && !empty($t_db['address'])): ?>
                                <span style="font-size: 14px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;">
                                    <?= htmlspecialchars($t_db['address']) ?>
                                </span>
                            <?php endif; ?>

                            <div class="action-buttons" onclick="event.stopPropagation();">
                                <a class="count-bubble" href="comment_detail.php?task_id=<?= urlencode($task_id) ?>&projectName=<?= urlencode($content) ?>">
                                    💬 <?= (int)$comment_cnt ?>
                                </a>
                                <?php if ($t_db && !empty($t_db['address'])): ?>
                                    <a class="map-link" href="../mapjump.php?code=<?= urlencode($t_db['code'] ?? '') ?>&name=<?= urlencode($t_db['name'] ?? '') ?>&address=<?= urlencode($t_db['address']) ?>">
                                        地図
                                    </a>
                                <?php endif; ?>

                                <?php foreach ($labels as $lbl): ?>
                                    <span class="meta-label"><?= htmlspecialchars($lbl); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        <div id="footer">Copyright &copy; <?= $htcreate ?> Rights Reserved.</div>
    </div>
</body>
</html>
<?php $pdo = NULL; } ?>