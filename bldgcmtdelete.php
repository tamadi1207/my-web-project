<?php
require './db_info.php';
require './cookie.php';
$path= './';

$builedit= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地情報編集</a></li>",
                 "<li><a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>棟追加</a></li>",
                 "<li><a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地削除</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地情報編集</span></a>",
                 "<a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>棟追加</span></a>",
                 "<a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地削除</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
    // GET変数を安全に取得
    $code    = htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);
    $syubetu = htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);
    $name    = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);
    $address = htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);
    
    // 削除対象のNo（POST優先、GETも許可）
    $no = $_POST['no'] ?? $_GET['no'] ?? null;
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="./css/buhin.css" rel="stylesheet" media="all">
        <title>団地コメント削除</title>
        <?php require './require/header.php'; ?>

        <h1><?= $syubetu ?>&nbsp;
            <span class="danchiname"><?= $name ?></span>
        </h1>

        <?php
        // ▼▼▼ 削除実行処理（POST） ▼▼▼
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sakuzyo'])) {
            try {
                // 画像ファイルの削除用情報取得
                $sql3 = $pdo->prepare("SELECT img FROM danchicomment WHERE no = :no");
                $sql3->bindValue(':no', $no, PDO::PARAM_INT);
                $sql3->execute();
                
                while($row2 = $sql3->fetch()){
                    if(!empty($row2['img'])){
                        $deletefile = "./img/bldg/$code/" . $row2['img'];
                        @unlink($deletefile);
                    }
                }

                // レコード削除
                $sql = $pdo->prepare("DELETE FROM danchicomment WHERE no = :no");
                $sql->bindValue(':no', $no, PDO::PARAM_INT);
                $sql->execute();
                ?>
                削除しました。
                <SCRIPT>
                    // ▼▼▼ 修正箇所: 1秒後に元の画面へ戻る処理を追加 ▼▼▼
                    setTimeout(function(){
                        location.href="./building.php?code=<?= $code ?>&name=<?= $name ?>&address=<?= $address ?>&syubetu=<?= $syubetu ?>";
                    }, 1000);
                 </SCRIPT>
            <?php
            } catch (PDOException $e) {
                echo "エラーが発生しました。";
            }
        } else {
            // ▼▼▼ 削除確認画面（表示用） ▼▼▼
            $sql2 = $pdo->prepare("SELECT * FROM danchicomment WHERE no = :no");
            $sql2->bindValue(':no', $no, PDO::PARAM_INT);
            $sql2->execute();

            while ($row = $sql2->fetch()) {
        ?> 
                <strong>削除しますか？</strong>
                <form method="POST" action="bldgcmtdelete.php?code=<?= $code ?>&name=<?= $name ?>&address=<?= $address ?>&syubetu=<?= $syubetu ?>">
                    <input type="hidden" name="code" value="<?= $code ?>">
                    <input type="hidden" name="syubetu" value="<?= $syubetu ?>">
                    <input type="hidden" name="name" value="<?= $name ?>">
                    <input type="hidden" name="address" value="<?= $address ?>">
                    <input type="hidden" name="no" value="<?= $no ?>">
                    
                    <p>
                        <?= nl2br(htmlspecialchars($row['comment'], ENT_QUOTES)) ?>
                        <?php if(!empty($row['img'])){ ?>
                            <img class="builimg" src="./img/bldg/<?= $code ?>/<?= htmlspecialchars($row['img'], ENT_QUOTES) ?>">
                        <?php } ?>
                    </p>
                    
                    <br/>　
                    <input type="hidden" name="sakuzyo" value="1">
                    <input type="submit" class="registbtn" value="削除">
                </form>
        <?php 
            }
        }
        $pdo = NULL;
        ?>
        <div id="footer">
            Copyright &copy;  <?= htmlspecialchars($htcreate ?? '', ENT_QUOTES) ?> All Rights Reserved.
        </div>
    </div>
</body>
</html>
<?php } ?>