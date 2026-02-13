<?php
require './db_info.php';
require './cookie.php';
$path= './';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar'], ENT_QUOTES) : null;

$builedit= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟No変更</a></li>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟削除</a>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>部品リセット</a></li>");
$builedit2= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟No変更</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟削除</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>部品リセット</span></a></li>");


// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>
<html>
<head>
    <link href="css/buhin.css" rel="stylesheet" media="all">
    <title>棟コメント編集</title>
    <?php require './require/header.php';

    $userid= $id; // 安全なID
    $code = isset($_GET['code']) ? htmlspecialchars($_GET['code'], ENT_QUOTES) : null;
    $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno'], ENT_QUOTES) : null;
    $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou'], ENT_QUOTES) : null;
    $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu'], ENT_QUOTES) : null;
    $name = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES) : null;
    $address = isset($_GET['address']) ? htmlspecialchars($_GET['address'], ENT_QUOTES) : null;
    
    // POST優先、なければGET
    $no = $_POST['no'] ?? $_GET['no'] ?? null;
    ?>
    <h2><?php print $syubetu;?>&nbsp;
    <span class="danchiname"><?php print $name;?>&nbsp;<span class="strong"><?php print $goutou;?>号棟</span></span></h2>

    <?php
    // ▼▼▼ 更新処理（POST） ▼▼▼
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        try {
            $sql2 = $pdo->prepare("UPDATE goutoucomment SET comment = :comment WHERE no = :no");
            $sql2->bindValue(':comment', $comment, PDO::PARAM_STR);
            $sql2->bindValue(':no', $no, PDO::PARAM_INT);
            $sql2->execute();
            ?>
            コメントを編集しました。
            <SCRIPT>
             setTimeout(function(){
                location.href="./parts.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&syubetu=<?php print $syubetu;?>";
             }, 1000); 
            </SCRIPT>
            <?php
        } catch (PDOException $e) {
            echo "エラーが発生しました。";
        }

    } else {
        // ▼▼▼ 編集画面表示 ▼▼▼
        $sql = $pdo->prepare("SELECT * FROM goutoucomment WHERE no = :no");
        $sql->bindValue(':no', $no, PDO::PARAM_INT);
        $sql->execute();

        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
    ?>
        <form method='POST' action='buildingcmtedit.php?code=<?= $code ?>&codeno=<?= $codeno ?>&name=<?= $name ?>&address=<?= $address ?>&goutou=<?= $goutou ?>&goutouvar=<?= $goutouvar ?>&syubetu=<?= $syubetu ?>'>
            <strong>コメント編集</strong>
            <br clear="right">
            <textarea name='comment' rows='15' cols='50'><?php print htmlspecialchars($row['comment'], ENT_QUOTES); ?></textarea>
            <br>
            <input type='hidden' name='no' value='<?php print $no;?>'>
            <input type='submit' class="registbtn" value="コメント登録">
        </form>
    <?php
        }
    }
    $pdo= NULL;
    ?>          
    <div id="footer">
        Copyright &copy; <?php echo $htcreate ?? '';?> All Rights Reserved.
    </div>
</div>
</body>
</html>
<?php } ?>