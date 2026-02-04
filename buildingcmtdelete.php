<?php
require './db_info.php';
require './cookie.php';
$path= './';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
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
            
                $userid = $_COOKIE['ID'];
                $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
                $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : null;
                $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
                $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
                $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
                $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : null;
                $no = isset($_GET['no']) ? htmlspecialchars($_GET['no']) : null;
                $comment2 = isset($_GET['comment2']) ? htmlspecialchars($_GET['comment2']) : null;
                $goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
?>

                <h2><?php print $syubetu;?>&nbsp;
                <span class="danchiname"><?php print $name;?>&nbsp;<span class="strong"><?php if(!empty($goutouvar)){print $goutouvar;}else{print $goutou;}?>号棟</span></span></h2>
                <?php
                if (isset($_GET['sakuzyo'])) {
                    $sql3 = $pdo->prepare("SELECT * FROM goutoucomment WHERE no='$no'") or die("失敗");
                    $sql3->execute();
                    while($row2= $sql3->fetch())
                  {
                   if(isset($row2[7])){
                        $deletefile= "./img/building/$code/$row2[7]";

                //htmlディレクトリ画像削除
                  @unlink($deletefile);
                  }}
                    $sql = $pdo->prepare("DELETE FROM goutoucomment WHERE no='$no'") or die("失敗");
                    $sql->execute();?>
                削除しました。
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="./parts.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1000); 
 // -->
 </SCRIPT>
                    <?php
                  } else {

                    $sql2 = $pdo->prepare("SELECT * FROM goutoucomment WHERE no='$no'") or die("失敗");
                    $sql2->execute();

               while ($row = $sql2->fetch()) {
                   ?> <strong>削除しますか？</strong>
                    <form method="GET" action="buildingcmtdelete.php">
                        　  <input type="hidden" name="code" value="<?php print $code; ?>">
                            <input type="hidden" name="codeno" value="<?php print $codeno; ?>">
                            <input type="hidden" name="syubetu" value="<?php print $syubetu; ?>">
                            <input type="hidden" name="name" value="<?php print $name; ?>">
                            <input type="hidden" name="address" value="<?php print $address;?>">
                            <input type="hidden" name="goutou" value="<?php print $goutou;?>">
                            <input type="hidden" name="goutouvar" value="<?php print $goutouvar;?>">
                            <input type="hidden" name="comment2" value="<?php print $comment2; ?>">
                            <input type="hidden" name="no" value="<?php print $no; ?>">
                            <p><?php print $row['comment'];
                            if(!empty($row['img'])){?><img class="builimg" src="./img/building/<?php print $code;?>/<?php print $row['img'];?>"><?php }else{}
                            ?></p>
                        <?php
                    }?>
         　　　　　　　　<br/>
                            <input type="hidden" name="sakuzyo">
                            <input type="submit" class="registbtn" value="削除">
                    </form>
                <?php }
            }
            $pdo = NULL;
            ?>
            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
            </div>
        </div>
        <script src="../jquery/Lightbox/js/lightbox.min.js"></script>

    </body>
</html>