<?php
require './db_info.php';
require './cookie.php';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
$path= './';

if ($cntid == 1) {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>棟コメント入力</title>
    <link href="css/cmt.css?eo" rel="stylesheet" media="all">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="fullOverlay"></div>
    <div id="loader"></div>

    <?php require './require/header.php'; 
    $userid = $_COOKIE['ID'];
    $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
    $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : null;
    $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : null;
    $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
    $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
    $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
    ?>

    <div class="cmt-container">
        <div class="cmt-header">
            <h2>
                <?= $syubetu ?> <?= $name ?>
                <span class="goutou-label">
                    <?php if(empty($goutou)){print $goutouvar;}else{print $goutou;}?>号棟
                </span>
            </h2>
        </div>

        <?php if(!isset($_POST['comment']) && empty($_FILES['upload']['name'])){ ?>
            <form method='POST' name='form1' enctype="multipart/form-data" id="form_id" action='buildingcmt.php?code=<?= $code ?>&codeno=<?= $codeno ?>&name=<?= $name ?>&address=<?= $address ?>&goutou=<?= $goutou ?>&goutouvar=<?= $goutouvar ?>&syubetu=<?= $syubetu ?>'>
                
                <div class="form-group">
                    <label class="form-label">📸 画像</label>
                    <div class="file-upload-container">
                        <div class="upload-text"><span>📁</span> 画像を選択</div>
                        <input type="file" name="upload" id="fileInput" accept="image/*">
                    </div>
                    <div class="preview-container" style="margin-top:8px;">
                        <img id="imagePreview" class="preview-img" src="" alt="プレビュー" style="display:none; border-radius:4px; border:1px solid #ccc;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">💬 コメント</label>
                    <textarea name='comment' class="cmt-textarea" rows="5" placeholder="メモを入力してください..."></textarea>
                </div>

                <div class="btn-wrapper" style="text-align:left;">
                    <input class="btn-small-gray" onclick="check()" type='button' value="投稿する">
                </div>
            </form>
        <?php } ?>

        <?php
        if(isset($_POST['comment']) || !empty($_FILES['upload']['name'])){
            $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;
            $img = NULL;
            if(!empty($_FILES['upload']['name'])){
                $type = $_FILES['upload']['type'];
                if($type == "image/jpeg" || $type == "image/png"){
                    $ext = ($type == "image/jpeg") ? "jpg" : "png";
                    $img = "$code-".time().".$ext";
                    $folder = "./img/building/$code";
                    if(!is_dir($folder)){ @mkdir($folder, 0777, true); }
                    move_uploaded_file($_FILES["upload"]["tmp_name"], "$folder/$img");
                }
            }
            if(!empty($comment) || !empty($img)){
                $target_goutou = !empty($goutouvar) ? $goutouvar : $goutou;
                $sql3 = $pdo->prepare("INSERT INTO goutoucomment (code,codeno,goutou,comment,type,name,img,hiduke) VALUES(?,?,?,?,?,?,?,now())");
                $sql3->execute([$code, $codeno, $target_goutou, $comment, $typeid, $userid, $img]);
                echo '<div style="text-align:center; padding:40px; font-weight:bold;">✅ 投稿しました。</div>';
                echo '<script>setTimeout(function(){ location.href="./parts.php?code='.$code.'&codeno='.$codeno.'&name='.$name.'&address='.$address.'&goutou='.$goutou.'&goutouvar='.$goutouvar.'&syubetu='.$syubetu.'"; }, 1000);</script>';
            }
        }
        $pdo = NULL;
        ?>
    </div>

<script>
// 1. 画像を選択した瞬間のプレビュー表示
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // 画像を表示
            
            // --- ここから追加：表示サイズを半分にする設定 ---
            preview.style.width = "50%";     // 横幅を親要素の半分に
            preview.style.height = "auto";    // アスペクト比を維持
            // --------------------------------------------
        }
        reader.readAsDataURL(file);
    }
});

// 2. 投稿ボタン（check）が押された時の処理
var check = async function() {
    var fileInput = document.getElementById('fileInput');
    var commentArea = document.querySelector('textarea[name="comment"]');
    var form = document.getElementById('form_id');

    if (fileInput.value == "" && commentArea.value.trim() == "") {
        alert('内容を入力してください。');
        return false;
    }

    // --- ここで背景を暗くし、文字を出す ---
    // jQueryを使っている場合
    $('#fullOverlay').css('display', 'block'); // fadeInより確実に即座に表示
    $('#loader').css('display', 'block').html("<span>送信中...</span>");

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
                // 完了後は自動的に画面が変わるので消す必要はありません
                const match = text.match(/location\.href\s*=\s*['"](.*?)['"]/);
                if(match) window.location.href = match[1]; else document.body.innerHTML = text;
            }
        } catch (e) { form.submit(); }
    } else {
        form.submit();
    }
};

// 3. 画像リサイズ & 向き補正関数
function resizeWithOrientation(file, maxWidth) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;

            // 比率を維持してリサイズ
            if (width > maxWidth) {
                height = Math.round(height * (maxWidth / width));
                width = maxWidth;
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Blobに変換して返す
            canvas.toBlob((blob) => {
                resolve(blob);
            }, 'image/jpeg', 0.85); // 85%の画質で圧縮
        };
        img.src = URL.createObjectURL(file);
    });
}
</script>
</body>
</html>
<?php } ?>