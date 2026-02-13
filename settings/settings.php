<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';

// カテゴリーメニュー設定
$category= 'on';
$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {

    // ▼▼▼ 1. 設定保存処理 ▼▼▼
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pid = isset($_POST['default_project_id']) ? $_POST['default_project_id'] : '';
        $navi = isset($_POST['default_navi_app']) ? $_POST['default_navi_app'] : 'google';

        try {
            $sql = "INSERT INTO db_user_settings (user_name, default_project_id, default_navi_app) 
                    VALUES (:uid, :pid, :navi) 
                    ON DUPLICATE KEY UPDATE default_project_id = :pid, default_navi_app = :navi";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':pid', $pid, PDO::PARAM_STR);
            $stmt->bindValue(':navi', $navi, PDO::PARAM_STR);
            $stmt->bindValue(':uid', $id, PDO::PARAM_STR); 
            $stmt->execute();
            
            sleep(2);
            header('Location: ../index.php');
            exit();

        } catch (PDOException $e) {
            $message = "<p style='color:red; font-size: 17px; margin-bottom:10px;'>保存失敗</p>";
        }
    }

    // プロジェクト一覧 & 現在の設定値取得 (既存維持)
    $projects = [];
    try {
        $stmt = $pdo->prepare("SELECT project_id, project_name FROM todoist_projects_cache WHERE name = :user ORDER BY project_name ASC");
        $stmt->execute([':user' => $id]);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}

    $current_default_pid = "";
    $current_navi = "google"; 
    try {
        $stmt = $pdo->prepare("SELECT default_project_id, default_navi_app FROM db_user_settings WHERE user_name = :uid");
        $stmt->execute([':uid' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) { 
            $current_default_pid = $row['default_project_id']; 
            $current_navi = $row['default_navi_app'];
        }
    } catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>設定</title>
    <?php require '../require/header.php'; ?>
    <link href="<?php print $path;?>css/cmt_and_settings.css?v=<?php echo date('His'); ?>" rel="stylesheet" media="all">
    <style>
        .btn-disabled { opacity: 0.6 !important; cursor: wait !important; }
        .success-msg { color: green; font-weight: bold; font-size: 17px; margin-bottom: 10px; display: none; }
        
        /* ログアウトセクションのデザイン */
        .logout-section { margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; }
        .logout-id-label { font-size: 13px; color: #888; margin-bottom: 10px; }
        .btn-logout { background: #e74c3c !important; color: white !important; padding: 5px 20px; text-decoration: none; display: inline-block; border-radius: 3px; font-weight: bold; border: none; cursor: pointer; }
        .btn-logout:hover { background: #c0392b !important; }
    </style>
</head>

<style>
/* スッと上から表示するように動かす */
@keyframes nurutto-down {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.settings-container {
    animation: nurutto-down 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
</style>

<body>
    <div id="contener">
        <div class="settings-container">
            <div class="settings-header"><h1>設定</h1></div>

            <div id="js-message" class="success-msg">✅ 設定を保存しました。<span style="font-size: 0.8em;">自動で移動します。</span></div>
            <?= $message ?>

            <form method='POST' action='' id="settings-form" onsubmit="return startSaving();">
                <div class="settings-group">
                    <label class="settings-label">Todoistプロジェクト一覧のスキップ設定</label>
                    <p class="settings-desc">プロジェクトを選択すると、プロジェクト一覧をスキップして直接タスクを表示します。「選択しない」の場合は一覧を表示します。</p>
                    <select name="default_project_id" class="settings-select-sm">
                        <option value="">選択しない</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?= htmlspecialchars($p['project_id']) ?>" 
                                <?= ($current_default_pid == $p['project_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['project_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="settings-group stripe">
                    <label class="settings-label">地図の起動アプリ</label>
                    <p class="settings-desc">地図を開く時に、起動するアプリを選択します</p>
                    <select name="default_navi_app" class="settings-select-sm">
                        <option value="google" <?= ($current_navi == 'google') ? 'selected' : '' ?>>Google Maps</option>
                        <option value="yahoo" <?= ($current_navi == 'yahoo') ? 'selected' : '' ?>>Yahoo!カーナビ</option>
                        <option value="apple" <?= ($current_navi == 'apple') ? 'selected' : '' ?>>Apple Map</option>
                    </select>
                </div>

                <div class="settings-footer">
                    <input id="save-btn" class="btn-small-gray settings-btn" type='submit' value="保存">
                </div>
            </form>

            <div class="logout-section">
                <p class="logout-id-label">ログイン中のID: <?= htmlspecialchars($id, ENT_QUOTES); ?></p>
                <button type="button" class="btn-logout" onclick="confirmLogout()">ログアウト</button>
            </div>
        </div>
    </div>

    <script>
    // ブラウザの「戻る」対策
    window.addEventListener('pageshow', (event) => {
        const btn = document.getElementById('save-btn');
        const msg = document.getElementById('js-message');
        btn.disabled = false;
        btn.value = '保存';
        btn.classList.remove('btn-disabled');
        msg.style.display = 'none';
    });

    function startSaving() {
        const btn = document.getElementById('save-btn');
        const msg = document.getElementById('js-message');
        btn.disabled = true;
        btn.value = '保存中...';
        btn.classList.add('btn-disabled');
        msg.style.display = 'block';
        return true;
    }

    // ✅ ログアウトの念押しポップアップ
    function confirmLogout() {
        if (confirm("本当にログアウトしますか？")) {
            window.location.href = "<?php print $path;?>login/logout.php";
        }
    }
    </script>
</body>
</html>
<?php
}
$pdo = NULL;
?>