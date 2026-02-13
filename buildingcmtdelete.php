<?php
require './db_info.php';
require './cookie.php';
$path= './';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar'], ENT_QUOTES) : null;
$builedit= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地情報編集</a></li>",
                 "<li><a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>棟追加</a></li>",
                 "<li><a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地削除</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地情報編集</span></a>",
                 "<a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>棟追加</span></a>",
                 "<a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地削除</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="./css/buhin.css" rel="stylesheet" media="all">
        <title>棟コメント削除</title>
        <?php require './require/header.php';            
        
        $userid = $id; // 安全なID
        $code = isset($_GET['code']) ? htmlspecialchars($_GET['code'], ENT_QUOTES) : null;
        $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno'], ENT_QUOTES) : null;
        $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu'], ENT_QUOTES) : null;
        $name = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES) : null;
        $address = isset($_GET['address']) ? htmlspecialchars($_GET['address'], ENT_QUOTES) : null;
        $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou'], ENT_QUOTES) : null;
        $goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar'], ENT_QUOTES) : null;
        $comment2 = isset($_GET['comment2']) ? htmlspecialchars($_GET['comment2'], ENT_QUOTES) : null;
        
        // POSTを優先、なければGETから取得
        $no = $_POST['no'] ?? $_GET['no'] ?? null;
        ?>

        <h2><?php print $syubetu;?>&nbsp;
        <span class="danchiname"><?php print $name;?>&nbsp;<span class="strong"><?php if(!empty($goutouvar)){print $goutouvar;}else{print $goutou;}?>号棟</span></span></h2>
        
        <?php
        // ▼▼▼ 削除実行処理（POST） ▼▼▼
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sakuzyo'])) {
            try {
                // 画像削除処理
                $sql3 = $pdo->prepare("SELECT img FROM goutoucomment WHERE no = :no");
                $sql3->bindValue(':no', $no, PDO::PARAM_INT);
                $sql3->execute();
                
                while($row2 = $sql3->fetch(PDO::FETCH_ASSOC)){
                    if(!empty($row2['img'])){
                        $deletefile = "./img/building/$code/" . $row2['img'];
                        @unlink($deletefile);
                    }
                }

                // レコード削除処理
                $sql = $pdo->prepare("DELETE FROM goutoucomment WHERE no = :no");
                $sql->bindValue(':no', $no, PDO::PARAM_INT);
                $sql->execute();
                ?>
                削除しました。
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
            // ▼▼▼ 削除確認画面（表示） ▼▼▼
            $sql2 = $pdo->prepare("SELECT * FROM goutoucomment WHERE no = :no");
            $sql2->bindValue(':no', $no, PDO::PARAM_INT);
            $sql2->execute();

            while ($row = $sql2->fetch(PDO::FETCH_ASSOC)) {
            ?> 
                <strong>削除しますか？</strong>
                <form method="POST" action="buildingcmtdelete.php?code=<?= $code ?>&codeno=<?= $codeno ?>&name=<?= $name ?>&address=<?= $address ?>&goutou=<?= $goutou ?>&goutouvar=<?= $goutouvar ?>&syubetu=<?= $syubetu ?>">
                    <input type="hidden" name="no" value="<?php print $no; ?>">
                    <p>
                        <?php print nl2br(htmlspecialchars($row['comment'], ENT_QUOTES)); ?>
                        <?php if(!empty($row['img'])){ ?>
                            <img class="builimg" src="./img/building/<?php print $code;?>/<?php print $row['img'];?>">
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
            Copyright &copy; <?php echo $htcreate ?? '';?> All Rights Reserved.
        </div>
    </div>
    </body>
</html>
<?php } ?>