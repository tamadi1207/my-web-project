<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';

// カテゴリーメニューを非表示
$category= 'on';
$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
    <html>
<head>
    <title>設定</title>
    <?php require '../require/header.php'; ?>
    <link href="<?php print $path;?>css/settings.css?v=<?php echo date('His'); ?>" rel="stylesheet" media="all">
</head>

<body>
    <div id="setting">
        <div class="setting-card">
            
            <h3 class="setting-title">
                <span>設定</span><i class="fa fa-cog"></i>
            </h3>
<form action="../todoist/save_settings.php" method="POST">
    <div class="setting-item">
        <label>Todoistプロジェクト名 (デフォルト)</label>
        <select name="todoist_project_id" class="setting-selectbox">
            <option value="">プロジェクトを選択してください</option>
            <?php
            $sample_projects = [['id' => '1', 'name' => '団地メンテナンス'], ['id' => '2', 'name' => '備品発注']];
            foreach ($sample_projects as $p) {
                echo "<option value='{$p['id']}'>{$p['name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="setting-item logout-row">
        <label>アカウント</label>
        <a href="../login/logout.php" class="logout-link">
            ログアウト <i class="fa fa-sign-out"></i>
        </a>
    </div>

    <div class="setting-actions">
        <button type="submit" class="setting-btn-save">保存する</button>
    </div>
</form>

        </div>
    </div>
</body>

<?php
} // login check end
?>
    </body>
</html>