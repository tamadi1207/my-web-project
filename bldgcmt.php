<?php
require './db_info.php';
require './cookie.php';
$path= './';

if ($cntid == 1) {
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>団地コメント入力</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>

        /* ==========================================================
   画像プレビュー：未選択時の割れアイコンを隠し、選択時のみ表示
   ========================================================== */

/* 1. 画像が読み込まれていない（srcが空または無効）時は非表示にする */
#imagePreview[src=""], 
#imagePreview:not([src]) {
    display: none !important;
}

/* 2. 画像プレビュー自体の設定：枠を消して左寄せ */
#imagePreview {
    display: block;
    margin: 10px 0 !important;
    padding: 0 !important;
    border: none !important;        /* 枠線を完全に消去 */
    outline: none !important;
    width: 50% !important;          /* 幅を半分に */
    height: auto !important;
    max-width: 200px !important;
    object-fit: contain;
}

/* 3. altテキスト（プレビュー文字）が画像と重ならないよう非表示化 */
#imagePreview {
    font-size: 0 !important;
    color: transparent !important;
}
/* dlタグに対して幅と中央寄せを設定 */
dl.toucmt {
    width: 50% !important;     /* 幅を半分に強制 */
    margin-left: auto !important;
    margin-right: auto !important;
    border-left: none;         /* もし左側に赤い線などがある場合はこれで見栄えを調整 */
    word-break: break-all !important;
}

/* 中の画像も枠からはみ出さないように調整 */
dl.toucmt img.builimg {
    max-width: 100% !important;
    height: auto !important;
}
/* 投稿コンテナ */
.cmt-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* テキストエリア */
.cmt-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    margin-bottom: 15px;
    resize: vertical;
}

/* 画像選択エリア */
.file-upload-wrapper {
    margin-bottom: 20px;
    padding: 15px;
    border: 2px dashed #ddd;
    border-radius: 4px;
    text-align: center;
}

/* 統一ボタン */
.btn-primary {
    display: block;
    width: 100%;
    background: #1976d2;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 4px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-primary:hover { background: #1565c0; }

.btn-delete { background: #e53935; }
.btn-delete:hover { background: #c62828; }

/* プレビュー画像 */
.preview-img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-top: 10px;
}
/* --- 投稿画面共通スタイル --- */

/* コンテナ全体の余白 */
.cmt-container {
    padding: 10px;
    max-width: 800px;
}

/* ヘッダーデザイン（赤色の縦線アクセント） */
.cmt-header h2 {
    background: #ffffff;
    padding: 10px 15px;
    border-left: 8px solid #eb3223; /* スクリーンショットの赤色 */
    font-size: 1.5em;
    color: #000;
    margin: 10px 0 25px 0;
    cursor: default;
    border-bottom: 1px solid #ccc; /* 下線の影 */
    display: flex;
    align-items: center;
}

/* 棟ラベル（buildingcmt用） */
.goutou-label {
    display: inline-block;
    margin-left: 15px;
    font-size: 0.7em;
    color: #666;
    font-weight: normal;
}

/* 項目間の余白 */
.form-group { 
    margin-bottom: 35px; 
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #444;
    text-align: left;
}

/* 画像アップロード：幅50%、高さ60px、左寄せ */
.file-upload-container {
    position: relative;
    width: 50%;
    height: 60px;
    margin: 0;
    border: 2px dashed #bbb;
    border-radius: 8px;
    background-color: #fcfcfc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s;
}

.file-upload-container:hover {
    border-color: #1976d2;
    background-color: #f0f7ff;
}

.file-upload-container input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-text {
    font-size: 14px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* コメントエリア（5行） */
.cmt-textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
    resize: vertical;
}

/* 投稿ボタン：灰色・左寄せ */
.btn-small-gray {
    display: inline-block;
    background: #ececec;
    color: #333;
    border: 1px solid #ccc;
    padding: 10px 30px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    appearance: none;
    transition: background 0.2s;
}

.btn-small-gray:hover {
    background: #e0e0e0;
}

.btn-wrapper {
    text-align: left;
    margin-top: 15px;
}

/* 通信用オーバーレイ */
#fullOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; }
#loader { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; z-index: 9999; font-weight: bold; }

/* --- 投稿画面共通：ここから追加 --- */

/* 見出しの崩れを防止し、赤棒デザインを適用 */
.cmt-header h2 {
    background: #ffffff !important;
    padding: 10px 15px !important;
    border-left: 8px solid #eb3223 !important; /* 左の赤棒 */
    font-size: 1.5em !important;
    color: #000 !important;
    margin: 10px 0 25px 0 !important;
    display: flex !important; /* 縦並びを防ぐ */
    align-items: center !important;
    border-bottom: 1px solid #ccc !important;
    white-space: nowrap !important; /* 改行を禁止 */
    cursor: default;
}

/* コンテナと項目の余白 */
.cmt-container { padding: 10px; max-width: 800px; }
.form-group { margin-bottom: 35px; }
.form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; text-align: left; }

/* 画像アップロードエリア（幅50%・左寄せ） */
.file-upload-container {
    position: relative;
    width: 50%;
    height: 60px;
    border: 2px dashed #bbb;
    border-radius: 8px;
    background-color: #fcfcfc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
}
.file-upload-container input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.upload-text { font-size: 14px; color: #666; display: flex; align-items: center; gap: 5px; }

/* テキストエリア（5行） */
.cmt-textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
    resize: vertical;
}

/* 投稿ボタン（灰色・左寄せ） */
.btn-small-gray {
    display: inline-block;
    background: #ececec;
    color: #333;
    border: 1px solid #ccc;
    padding: 10px 30px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    appearance: none;
}
.btn-wrapper { text-align: left; margin-top: 15px; }

/* ローディング */
#fullOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; }
#loader { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; z-index: 9999; font-weight: bold; }


/* --- 既存のスタイル --- */
dt{ font-style: italic; font-size: 0.95em; background-color: #d8d6d6; }
dl{ padding-bottom: 1%; background-color: #f0f0f0; border-bottom: dotted; border-width: 1px; }
.parts{ background-color: #EEEEEE; margin: 20px 0px; padding: 10px 0px; }
.parts table{ margin: 0 auto; border: 1px solid; table-layout: fixed; }
/* ... (中略: アップロードいただいた既存のbuhin.cssの内容) ... */

/* --- 投稿画面共通スタイル (追加分) --- */

/* コンテナ：パンくずリストの下に配置されるよう調整 */
.cmt-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    clear: both; /* 回り込み解除 */
}

/* ヘッダー：重なりを防ぐためにブロック要素化 */
.cmt-header {
    width: 100%;
    margin-bottom: 25px;
    border-bottom: 1px solid #ccc;
}

.cmt-header h2 {
    background: #fff !important;
    padding: 10px 15px !important;
    border-left: 8px solid #eb3223 !important; /* 赤い棒 */
    font-size: 1.5em !important;
    color: #000 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center;
    cursor: default;
}

.goutou-label {
    display: inline-block;
    margin-left: 15px;
    font-size: 0.7em;
    color: #666;
    font-weight: normal;
}

/* フォーム要素 */
.form-group { margin-bottom: 30px; text-align: left; }
.form-label { display: block; margin-bottom: 8px; font-weight: bold; color: #444; }

/* 画像アップロード：50%幅・左寄せ */
.file-upload-container {
    position: relative;
    width: 50%;
    height: 60px;
    border: 2px dashed #bbb;
    border-radius: 8px;
    background-color: #fcfcfc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.file-upload-container input[type="file"] { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
.upload-text { font-size: 14px; color: #666; display: flex; align-items: center; gap: 5px; }

/* テキストエリア */
.cmt-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
    resize: vertical;
}

/* 投稿ボタン：灰色 */
.btn-small-gray {
    background: #ececec;
    color: #333;
    border: 1px solid #ccc;
    padding: 10px 30px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    appearance: none;
}
.btn-small-gray:hover { background: #e0e0e0; }

/* オーバーレイ */
#fullOverlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; }
#loader { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; z-index: 9999; font-weight: bold; }


@media screen and (max-width: 480px) {
    /* 1. dl.toucmt 自体の幅を100%にして左右いっぱいに広げる */
    dl.toucmt {
        width: 100% !important;   /* 50%から100%へ変更 */
        margin: 10px 0 !important; /* 左右の余白をなくす */
        border-left: none;
        box-sizing: border-box;    /* パディングを含めた幅計算にする */
    }

    /* 2. 中身の dd (コメント内容) も幅を合わせて改行させる */
    dl.toucmt dd {
        width: 100% !important;
        margin: 5px 0 0 0 !important;
        padding: 0 10px !important; /* 左右に最低限の余白を確保 */
        display: block !important;
        white-space: normal !important;
        word-break: break-all !important; /* はみ出し防止の強制改行 */
        box-sizing: border-box;
    }

    /* 3. dt (名前など) も左端に寄せる */
    dl.toucmt dt {
        margin-left: 10px !important;
    }

    /* --- 以下、既存の .parts 設定（変えない部分） --- */
    .parts table {
        font-size: 0.9em;
    }
    /* ...以下省略... */
}

    .parts .other {
        text-align: center; /* あなたの既存設定を維持 */
        margin: 10px 0px;   /* あなたの既存設定を維持 */
        width: 369px;       /* あなたの既存設定を維持 */
    }

    .parts .other th { 
        width: 100px;       /* あなたの既存設定を維持 */
    }

    .parts .other td {
        display: block;
        width: 90%;         /* あなたの既存設定を維持 */
        word-break: break-all !important; /* 改行パラメータのみ追加 */
    }

    /* --- 以下、その他の既存設定をそのまま維持 --- */
    .partsbox label {
        display: block;
    }
    .menu .user {
        clear: both;
    }
    form {
        margin-left: -4px;
    }
    .submit {
        margin: 20px 80px;
        padding: 13px 70px;
    }
    .parts .img {
        display: inline;
    }
    .parts .upimg th {
        width: 356px;
    }
    .parts .img2 {
        margin: 3px;
    }
    .parts .img2 th {
        width: 350px;
    }
    .innerimg {
        margin-left: 20%;
    }
    .img2box {
        text-align: center;
    }
    #upbox {
        margin: 0;
    }
/* --- buildingcmt.php プレビュー画像（JS生成分）の比率修正 --- */
#preview img, 
.preview-img, 
#preview-container img {
    width: auto !important;         /* 幅を固定しない */
    height: auto !important;        /* 高さを固定しない */
    max-width: 200px !important;    /* 最大幅を制限（お好みで調整） */
    max-height: 200px !important;   /* 最大高を制限 */
    object-fit: contain !important; /* 枠の中に比率を維持して収める */
    display: block;
    margin: 10px auto !important;   /* 中央寄せ */
    border-radius: 5px;
    border: 1px solid #ddd;
}

/* もし枠（コンテナ）がある場合の設定 */
#preview {
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

    
        </style>

</head>

<body>
    <div id="fullOverlay"></div>
    <div id="loader"></div>

    <?php require './require/header.php'; 
    $userid = $_COOKIE['ID'];
    $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
    $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
    $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
    $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
    ?>

    <div class="cmt-container">
        <div class="cmt-header">
            <h2><?= $syubetu ?>&nbsp;<?= $name ?></h2>
        </div>

        <?php if(!isset($_POST['comment']) && empty($_FILES['upload']['name'])){ ?>
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
        if(isset($_POST['comment']) || !empty($_FILES['upload']['name'])){
            $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;
            $img = NULL;

            if(!empty($_FILES['upload']['name'])){
                $type = $_FILES['upload']['type'];
                if($type == "image/jpeg" || $type == "image/png"){
                    $ext = ($type == "image/jpeg") ? "jpg" : "png";
                    $date = time();
                    $img = "$code-$date.$ext";
                    $folder = "./img/bldg/$code";
                    if(!is_dir($folder)){ @mkdir($folder, 0777, true); }
                    move_uploaded_file($_FILES["upload"]["tmp_name"], "$folder/$img");
                }
            }

            if(!empty($comment) || !empty($img)){
                $sql = $pdo->prepare("INSERT INTO danchicomment (code,comment,type,name,img,hiduke) VALUES(?,?,?,?,?,now())");
                $sql->execute([$code, $comment, $typeid, $userid, $img]);
                
                echo '<div style="text-align:center; padding:40px; font-weight:bold;">✅ 投稿しました。</div>';
                echo '<script>setTimeout(function(){ location.href="./building.php?code='.$code.'&name='.$name.'&address='.$address.'&syubetu='.$syubetu.'"; }, 1000);</script>';
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
                
                // 強制的に「半分」にするための最優先命令
                preview.style.setProperty('width', '50%', 'important');
                preview.style.height = 'auto';
            }
            reader.readAsDataURL(file);
        }
    });

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