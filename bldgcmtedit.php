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
    $userid = $id; // cookie.php由来の安全なID

    $code    = htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);
    $syubetu = htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);
    $name    = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);
    $address = htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);
    $no      = $_POST['no'] ?? $_GET['no'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <link href="css/buhin.css" rel="stylesheet" media="all">
    <title>団地コメント編集</title>
    <?php require './require/header.php'; ?>

    <h1><?= $syubetu ?>&nbsp;
        <span class="danchiname"><?= $name ?></span>
    </h1>

    <?php
    // ▼▼▼ 編集画面表示ループ ▼▼▼
    $sql = $pdo->prepare("SELECT * FROM danchicomment WHERE no = :no");
    $sql->bindValue(':no', $no, PDO::PARAM_INT);
    $sql->execute();

    while($row = $sql->fetch()){
        // まだ更新ボタンが押されていない場合
        if(!isset($_POST['comment'])){
    ?>
        <form method='POST' action='bldgcmtedit.php?code=<?= $code ?>&name=<?= $name ?>&address=<?= $address ?>&syubetu=<?= $syubetu ?>'>
            <strong>コメント編集</strong>
            <br clear="right">
            <textarea name='comment' rows='15' cols='50'><?= htmlspecialchars($row['comment'], ENT_QUOTES) ?></textarea>
            </br>
            <input type='hidden' name='code' value='<?= $code ?>'>
            <input type='hidden' name='no' value='<?= $no ?>'>
            <input type='hidden' name='name' value='<?= $name ?>'>
            <input type='hidden' name='address' value='<?= $address ?>'>
            <input type='hidden' name='syubetu' value='<?= $syubetu ?>'>
            <input type='submit' class="registbtn" value=コメント登録>
        </form>
    <?php
        }
    }
    
    // ▼▼▼ 更新実行処理 ▼▼▼
    if(isset($_POST['comment'])){
        $comment = $_POST['comment'];
        try {
            // プリペアドステートメントで安全に更新
            $sql2 = $pdo->prepare("UPDATE danchicomment SET comment = :comment WHERE no = :no");
            $sql2->bindValue(':comment', $comment, PDO::PARAM_STR);
            $sql2->bindValue(':no', $no, PDO::PARAM_INT);
            $sql2->execute();
            ?>
            コメントを編集しました。
            <SCRIPT>
            function autoLink() {
             location.href="./building.php?code=<?= $code ?>&name=<?= $name ?>&address=<?= $address ?>&syubetu=<?= $syubetu ?>";
            }
            setTimeout("autoLink()",1000); 
            </SCRIPT>
            <?php 
        } catch (PDOException $e) {
            echo "エラーが発生しました。";
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