<?php
require './db_info.php';
require './cookie.php'; // 安全な $id ($cntid) を取得
$path= './';

// ログインチェック
if ($cntid == 1) {
    // セッションから取得した安全なIDを使用
    $userid = $id;

    // GETパラメータを安全に取得
    $code    = htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);
    $syubetu = htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);
    $name    = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);
    $address = htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>団地コメント入力</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* 元のCSSをそのまま維持 */
        #imagePreview[src=""], #imagePreview:not([src]) { display: none !important; }
        #imagePreview { display: block; margin: 10px 0 !important; padding: 0 !important; border: none !important; outline: none !important; width: 50% !important; height: auto !important; max-width: 200px !important; object-fit: contain; font-size: 0 !important; color: transparent !important; }
        dl.toucmt { width: 50% !important; margin-left: auto !important; margin-right: auto !important; border-left: none; word-break: break-all !important; }
        dl.toucmt img.builimg { max-width: 100% !important; height: auto !important; }
        .cmt-container { max-width: 900px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); clear: both; }
        .cmt-header { width: 100%; margin-bottom: 25px; border-bottom: 1px solid #ccc; }
        .cmt-header h2 { background: #fff !important; padding: 10px 15px !important; border-left: 8px solid #eb3223 !important; font-size: 1.5em !important; color: #000 !important; margin: 0 !important; display: flex !important; align-items: center; cursor: default; }
        .goutou-label { display: inline-block; margin-left: 15px; font-size: 0.7em; color: #666; font-weight: normal; }
        .form-group { margin-bottom: 30px; text-align: left; }
        .form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; }
        .file-upload-container { position: relative; width: 50%; height: 60px; border: 2px dashed #bbb; border-radius: 8px; background-color: #fcfcfc; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; }
        .file-upload-container input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .upload-text { font-size: 14px; color: #666; display: flex; align-items: center; gap: 5px; }
        .cmt-textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 16px; resize: vertical; }
        .btn-wrapper { text-align: left; margin-top: 15px; }
        .btn-small-gray { background: #ececec; color: #333; border: 1px solid #ccc; padding: 10px 30px; border-radius: 4px; font-weight: bold; cursor: pointer; appearance: none; }
        .btn-small-gray:hover { background: #e0e0e0; }
        #fullOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; }
        #loader { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; z-index: 9999; font-weight: bold; }
        
        @media screen and (max-width: 480px) {
            dl.toucmt { width: 100% !important; margin: 10px 0 !important; border-left: none; box-sizing: border-box; }
            dl.toucmt dd { width: 100% !important; margin: 5px 0 0 0 !important; padding: 0 10px !important; display: block !important; white-space: normal !important; word-break: break-all !important; box-sizing: border-box; }
            dl.toucmt dt { margin-left: 10px !important; }
        }
    </style>
</head>

<body>
    <div id="fullOverlay"></div>
    <div id="loader"></div>

    <?php require './require/header.php'; ?>

    <div class="cmt-container">
        <div class="cmt-header">
            <h2><?= $syubetu ?>&nbsp;<?= $name ?></h2>
        </div>

        <?php 
        // 投稿完了前、かつ画像アップロードもない場合にフォーム表示
        if(!isset($_POST['comment']) && empty($_FILES['upload']['name'])){ 
        ?>
            <form method='POST' name='form1' enctype="multipart/form-data" id="form_id" action='bldgcmt.php?code=<?= $code ?>&name=<?= $name ?>&address=<?= $address ?>&syubetu=<?= $syubetu ?>'>
                
                <div class="form-group">
                    <label class="form-label">📸 画像</label>
                    <div class="file-upload-container">
                        <div class="upload-text"><span>📁</span> 画像を選択</div>
                        <input type="file" name="upload" id="fileInput" accept="image/*">
                    </div>
                    <div class="preview-container" style="margin-top:10px;">
                        <img id="imagePreview" class="preview-img" src="" alt="プレビュー" style="display:none; border-radius:4px; border:1px solid #ccc;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">💬 コメント</label>
                    <textarea name='comment' class="cmt-textarea" rows="5" placeholder="メモを入力してください..."></textarea>
                </div>

                <div class="btn-wrapper">
                    <input class="btn-small-gray" onclick="check()" type='button' value="投稿">
                </div>
            </form>
        <?php } ?>

        <?php
        // POST処理（コメントまたは画像がある場合）
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = $_POST['comment'] ?? '';
            $img = NULL;

            // 画像処理
            if (!empty($_FILES['upload']['name'])) {
                $file = $_FILES['upload'];
                $type = $file['type'];
                
                if ($type == "image/jpeg" || $type == "image/png") {
                    $ext = ($type == "image/jpeg") ? "jpg" : "png";
                    $date = time();
                    $img = "$code-$date.$ext";
                    $folder = "./img/bldg/$code";
                    
                    if (!is_dir($folder)) {
                        @mkdir($folder, 0777, true);
                    }
                    move_uploaded_file($file["tmp_name"], "$folder/$img");
                }
            }

            if (!empty($comment) || !empty($img)) {
                try {
                    // ▼▼▼ SQLインジェクション対策（プリペアドステートメント） ▼▼▼
                    $sql = $pdo->prepare("INSERT INTO danchicomment (code, comment, type, name, img, hiduke) VALUES (?, ?, ?, ?, ?, now())");
                    $sql->execute([$code, $comment, $typeid, $userid, $img]);
                    
                    echo '<div style="text-align:center; padding:40px; font-weight:bold;">✅ 投稿しました。</div>';
                    echo '<script>setTimeout(function(){ location.href="./building.php?code='.$code.'&name='.$name.'&address='.$address.'&syubetu='.$syubetu.'"; }, 1000);</script>';
                    exit;
                } catch (PDOException $e) {
                    echo "エラーが発生しました。";
                }
            }
        }
        $pdo = NULL;
        ?>
    </div>

    <script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.setProperty('width', '50%', 'important');
                preview.style.height = 'auto';
            }
            reader.readAsDataURL(file);
        }
    });

    // 元のcheck関数を維持しつつ、安全に実装
    var check = async function() {
        var fileInput = document.getElementById('fileInput');
        var commentArea = document.querySelector('textarea[name="comment"]');
        var form = document.getElementById('form_id');

        if (fileInput.value == "" && commentArea.value.trim() == "") {
            alert('内容を入力してください。');
            return false;
        }

        $('#fullOverlay').fadeIn(200);
        $('#loader').show().html("<span>送信中...</span>");

        if (fileInput.files.length > 0) {
            try {
                const file = fileInput.files[0];
                const processedBlob = await resizeWithOrientation(file, 800);
                const formData = new FormData(form);
                formData.set('upload', processedBlob, file.name);

                const response = await fetch(form.action || window.location.href, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const text = await response.text();
                    const match = text.match(/location\.href\s*=\s*['"](.*?)['"]/);
                    if(match) window.location.href = match[1]; else document.body.innerHTML = text;
                } else {
                     // エラー時は通常のsubmitにフォールバック
                     form.submit();
                }
            } catch (e) { form.submit(); }
        } else { form.submit(); }
    };

    function resizeWithOrientation(file, maxWidth) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                if (width > maxWidth) {
                    height = Math.round(height * (maxWidth / width));
                    width = maxWidth;
                }
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                canvas.toBlob((blob) => resolve(blob), 'image/jpeg', 0.85);
            };
            img.src = URL.createObjectURL(file);
        });
    }
    </script>
</body>
</html>
<?php } ?>