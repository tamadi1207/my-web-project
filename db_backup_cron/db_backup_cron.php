
<?php

//cron実行専用：DBバックアップ用


// ===================== 設定 =====================
$db   = 'ss222251_danchisearch';
$user = 'ss222251_ht';
$pass = 'tamadi1192';
$host = 'mysql2.star.ne.jp';

// バックアップ元（searchフォルダの絶対パスを確認してください）
$sourceDir = '/home/ss222251/fixhome.me/public_html/search';
// バックアップ保存先
$backupDir = '/home/ss222251/fixhome.me/public_html/search/db_backup_cron/backup';
if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

// ===================== 日付チェック =====================
if (date('j') % 2 === 0) exit; // 偶数日はスキップ

$dateStr = date('Ymd_His');

// ===================== 1. DBバックアップ =====================
$sqlFile = $backupDir . "/db_{$dateStr}.sql";
$zipFile = $backupDir . "/full_backup_{$dateStr}.zip"; // ファイルと共通のZIPにする

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (Exception $e) {
    exit("DB接続失敗\n");
}

$fp = fopen($sqlFile, 'w');
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $row = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    fwrite($fp, "DROP TABLE IF EXISTS `$table`;\n" . $row['Create Table'] . ";\n\n");
    $stmt = $pdo->query("SELECT * FROM `$table` ");
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cols = array_map(function($c){ return "`$c`"; }, array_keys($data));
        $vals = array_map(function($v) use ($pdo){ return is_null($v) ? 'NULL' : $pdo->quote($v); }, array_values($data));
        fwrite($fp, "INSERT INTO `$table` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n");
    }
}
fclose($fp);

// ===================== 2. ファイルバックアップ & ZIP圧縮 =====================
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
    // DBのSQLファイルを追加
    $zip->addFile($sqlFile, basename($sqlFile));

    // searchフォルダ内のファイルをスキャンして追加
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir) + 1);

            // ★ pdf フォルダを除外
            if (strpos($relativePath, 'pdf/') === 0 || strpos($relativePath, 'pdf\\') === 0) {
                continue; 
            }
            // ★ バックアップ保存先自体も除外（無限ループ防止）
            if (strpos($filePath, $backupDir) === 0) {
                continue;
            }

            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    unlink($sqlFile); // 元のSQL削除
}

// ===================== 3. 古いバックアップ削除（60日保持） =====================
$keepDays = 150;
$now = time();
foreach (glob($backupDir . '/full_backup_*.zip') as $file) {
    if (is_file($file) && ($now - filemtime($file)) > ($keepDays * 86400)) {
        unlink($file);
    }
}