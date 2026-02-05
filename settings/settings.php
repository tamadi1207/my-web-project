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

    // ▼▼▼ 1. 設定保存処理 (Todoistデフォルトのみ連動) ▼▼▼
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['default_project_id'])) {
        try {
            // db_user_settings テーブルに保存
            $sql = "INSERT INTO db_user_settings (user_name, default_project_id) 
                    VALUES (:uid, :pid) 
                    ON DUPLICATE KEY UPDATE default_project_id = :pid";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':pid', $_POST['default_project_id'], PDO::PARAM_STR);
            $stmt->bindValue(':uid', $id, PDO::PARAM_STR); 
            $stmt->execute();
            
            $message = "<p style='color:green; font-weight:bold; font-size: 17px; margin-bottom:10px;'>✅ 設定が保存されました。</p>";
        } catch (PDOException $e) {
            $message = "<p style='color:red; font-size: 11px; margin-bottom:10px;'>保存失敗</p>";
        }
    }

    // ▼▼▼ 2. Todoistプロジェクト一覧の取得 ▼▼▼
    $projects = [];
    try {
        $stmt = $pdo->prepare("SELECT project_id, project_name FROM todoist_projects_cache WHERE name = :user ORDER BY project_name ASC");
        $stmt->execute([':user' => $id]);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}

    // ▼▼▼ 3. 現在の設定値を取得 ▼▼▼
    $current_default = "";
    try {
        $stmt = $pdo->prepare("SELECT default_project_id FROM db_user_settings WHERE user_name = :uid");
        $stmt->execute([':uid' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) { $current_default = $row['default_project_id']; }
    } catch (PDOException $e) {}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>設定</title>
    <?php require '../require/header.php'; ?>
    <link href="<?php print $path;?>css/cmt_and_settings.css?v=<?php echo date('His'); ?>" rel="stylesheet" media="all">
</head>

<body>
    <div id="contener">
        <div class="settings-container">
            
            <div class="settings-header">
                <h1>設定</h1>
            </div>

            <?= $message ?>

            <form method='POST' action=''>
                
                <div class="settings-group">
                    <label class="settings-label">📅 Todoistデフォルト</label>
                    <p class="settings-desc">起動時に表示するプロジェクトを選択します</p>
                    <select name="default_project_id" class="settings-select-sm">
                        <option value="">-- 未設定 --</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?= htmlspecialchars($p['project_id']) ?>" 
                                <?= ($current_default == $p['project_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['project_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="settings-group stripe">
                    <label class="settings-label">デフォルトナビ</label>
                    <p class="settings-desc">地図起動時のアプリを選択します</p>
                    <select name="default_navi_app" class="settings-select-sm">
                        <option value="google">Google Maps</option>
                        <option value="yahoo">Yahoo!カーナビ</option>
                        <option value="apple">Apple Map</option>
                    </select>
                </div>

                <div class="settings-footer">
                    <input class="btn-small-gray settings-btn" type='submit' value="保存">
                </div>

            </form>
        </div>
    </div>
</body>
</html>
<?php
}
$pdo = NULL;
?>