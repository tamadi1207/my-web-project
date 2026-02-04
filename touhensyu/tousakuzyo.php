<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
$builedit= array("<li><a href='{$path}touhensyu/touhensyu.php?code=" . ($_GET['code'] ?? '') . "&codeno=" . ($_GET['codeno'] ?? '') . "&syubetu=" . ($_GET['syubetu'] ?? '') . "&name=" . ($_GET['name'] ?? '') . "&address=" . ($_GET['address'] ?? '') . "&goutou=" . ($_GET['goutou'] ?? '') . "&goutouvar=" . ($_GET['goutouvar'] ?? '') . "'>棟No変更</a></li>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code=" . ($_GET['code'] ?? '') . "&codeno=" . ($_GET['codeno'] ?? '') . "&syubetu=" . ($_GET['syubetu'] ?? '') . "&name=" . ($_GET['name'] ?? '') . "&address=" . ($_GET['address'] ?? '') . "&goutou=" . ($_GET['goutou'] ?? '') . "&goutouvar=" . ($_GET['goutouvar'] ?? '') . "'>棟削除</a></li>");

$builedit2= array("<li><a href='{$path}touhensyu/touhensyu.php?code=" . ($_GET['code'] ?? '') . "&codeno=" . ($_GET['codeno'] ?? '') . "&syubetu=" . ($_GET['syubetu'] ?? '') . "&name=" . ($_GET['name'] ?? '') . "&address=" . ($_GET['address'] ?? '') . "&goutou=" . ($_GET['goutou'] ?? '') . "&goutouvar=" . ($_GET['goutouvar'] ?? '') . "'><span>棟No変更</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code=" . ($_GET['code'] ?? '') . "&codeno=" . ($_GET['codeno'] ?? '') . "&syubetu=" . ($_GET['syubetu'] ?? '') . "&name=" . ($_GET['name'] ?? '') . "&address=" . ($_GET['address'] ?? '') . "&goutou=" . ($_GET['goutou'] ?? '') . "&goutouvar=" . ($_GET['goutouvar'] ?? '') . "'><span>棟削除</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>棟削除</title>

<?php require '../require/header.php';?>

    <div class="block">
        <h1>棟削除</h1>
<?php
$userid  = $_COOKIE['ID'] ?? null; 
$code    = $_GET['code'] ?? null;
$codeno  = $_GET['codeno'] ?? null;
$goutou  = $_GET['goutou'] ?? null;
$syubetu = $_GET['syubetu'] ?? null;
$name    = $_GET['name'] ?? null;
$address = $_GET['address'] ?? null;

        if (isset($_GET['sakuzyo'])) {
                    $sql = $pdo->prepare("DELETE goutou, goutoucomment FROM goutou LEFT JOIN goutoucomment ON goutou.codeno = goutoucomment.codeno WHERE goutou.codeno='$codeno'") or die("失敗");
                    $sql->execute();
                    $sql = $pdo->prepare("DELETE goutou2, goutoucomment FROM goutou2 LEFT JOIN goutoucomment ON goutou2.codeno = goutoucomment.codeno WHERE goutou2.codeno='$codeno'") or die("失敗");
                    $sql->execute();

                    $sql= $pdo->prepare("DELETE FROM partshistory WHERE codeno='$codeno'") or die ("失敗");
                    $sql->execute();
                    $sql= $pdo->prepare("DELETE FROM partsfullhistory WHERE codeno='$codeno'") or die ("失敗");
                    $sql->execute();
                    $sql= $pdo->prepare("DELETE FROM partsfullhistory2 WHERE codeno='$codeno'") or die ("失敗");
                    $sql->execute();
                    $sql= $pdo->prepare("DELETE FROM partsreset WHERE codeno='$codeno'") or die ("失敗");
                    $sql->execute();
                    $sql= $pdo->prepare("DELETE FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
                    $sql->execute();

                    print "削除しました。<br>";
                    echo "<strong>2秒後にジャンプします。...</strong>";
                    ?>
<SCRIPT>

function autoLink()
 {
 location.href="../building.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 
 </SCRIPT>
                    <?php
                } else {



                    $sql2 = $pdo->prepare("SELECT * FROM goutou WHERE code='$code'") or die("失敗");
                    $sql2->execute();
                    $count= $sql2->rowCount();
                    
                    if($count == 1){
                        print "残り1棟の為、削除できません。";
                        print '<br/><br/>';
                        print "<strong>棟一覧へ自動的に戻ります...</strong>";?>
<SCRIPT>

function autoLink()
 {
 location.href="../building.php?code=<?php print $code;?>&name=<?php print $name;?>&goutou=<?php print $goutou;?>&address=<?php print $address;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 
 </SCRIPT>                        
                   <?php }else{
                    ?>
                <div>　
                    <span class="editcmt">棟を削除しますか？</span>
                    <form method="GET" action="tousakuzyo.php">
                        <dl class="inner">
                            <dt>種別</dt>
                                <dd>
                                <input type="hidden" name="syubetu" value="<?php print $syubetu; ?>"><?php print $syubetu; ?>
                                </dd>
                        </dl>        
                        <dl class="inner">
                            <dt>団地名</dt>
                            <dd>
                            <input type="hidden" name="name" value="<?php print $name; ?>"><?php print $name; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>棟No</dt>
                            <dd>
                            <input type="hidden" name="goutou" value="<?php if(!empty($goutouvar)){print $goutouvar;}else{print $goutou;}?>"><?php if(!empty($goutouvar)){print $goutouvar;}else{print $goutou;}?>
                            </dd>
                        </dl>    
                            <input type="hidden" name="code" value="<?php print $code; ?>">
                            <input type="hidden" name="codeno" value="<?php print $codeno; ?>">
                            <input type="hidden" name="address" value="<?php print $address; ?>">
                            <input type="hidden" name="sakuzyo">
                            <input id="del" class="registbtn" type="submit" value="削除">
                </div></form>
                    <?php }}
            }
            $pdo = NULL;
            ?>
<script>
        $('#del').click(function(){
            if(!confirm('本当に削除しますか?')){
                /*キャンセルの時の処理*/
                return false;
            }
        });
</script> 
            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
            </div>
        </div>
        <script src="../jquery/Lightbox/js/lightbox.min.js"></script>

    </body>
</html>