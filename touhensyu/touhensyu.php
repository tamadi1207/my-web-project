<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';

$g_code    = $_GET['code'] ?? '';
$g_codeno  = $_GET['codeno'] ?? '';
$g_syubetu = $_GET['syubetu'] ?? '';
$g_name    = $_GET['name'] ?? '';
$g_address = $_GET['address'] ?? '';
$g_goutou  = $_GET['goutou'] ?? '';

$builedit= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$g_code}&codeno={$g_codeno}&syubetu={$g_syubetu}&name={$g_name}&address={$g_address}&goutou={$g_goutou}'>棟No変更</a></li>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code={$g_code}&codeno={$g_codeno}&syubetu={$g_syubetu}&name={$g_name}&address={$g_address}&goutou={$g_goutou}'>棟削除</a></li>");

$builedit2= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$g_code}&codeno={$g_codeno}&syubetu={$g_syubetu}&name={$g_name}&address={$g_address}&goutou={$g_goutou}'><span>棟No変更</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code={$g_code}&codeno={$g_codeno}&syubetu={$g_syubetu}&name={$g_name}&address={$g_address}&goutou={$g_goutou}'><span>棟削除</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>

<html>
    <head>
        <title>棟No変更</title>

<?php require '../require/header.php';?>
        
<div class="block">
        <h1>棟No変更</h1>
<?php
$userid = $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : NULL;
$goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : NULL;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : NULL;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;

if(isset($_GET['hensyu']))
{
    if (empty($goutou)){
        print '棟Noを入力して下さい。';
        print '<br/><br/>';
        print "<strong>入力ページへ自動的に戻ります...</strong>";?>
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="touhensyu.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1500); 
 // -->
 </SCRIPT>
<?php }  else {
    $sql3= "SELECT goutou, goutouvar FROM goutou WHERE code='$code'";
    $result= $pdo->query($sql3);
    $goutouarray= array();
    while ($row3= $result->fetch(PDO::FETCH_ASSOC))
{
       $goutouarray[]= $row3;
}
     $goutouarray2= new RecursiveIteratorIterator(
             new RecursiveArrayIterator($goutouarray),
             RecursiveIteratorIterator::LEAVES_ONLY);
     $goutouarray3= iterator_to_array($goutouarray2, false);
     
    if(in_array($goutou, $goutouarray3)){
        print '棟Noが重複しています。';
        print '<br/><br/>';
        print "<strong>入力ページへ自動的に戻ります...</strong>";?>
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="touhensyu.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 // -->
 </SCRIPT>
<?php }

else{
    if(ctype_digit($goutou)){
    $sql= $pdo->prepare("UPDATE goutou SET goutou.codeno=$code$goutou , goutou.goutou='$goutou' , goutou.goutouvar=NULL WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("UPDATE goutou2 SET goutou2.codeno=$code$goutou , goutou2.goutou='$goutou' , goutou2.goutouvar=NULL WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE goutoucomment SET goutoucomment.codeno=$code$goutou , goutoucomment.goutou='$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partshistory SET partshistory.codeno=$code$goutou WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsfullhistory SET partshistory.codeno=$code$goutou WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsfullhistory2 SET partshistory2.codeno=$code$goutou WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();

    $sql= $pdo->prepare("UPDATE partsreset SET partshistory.codeno=$code$goutou WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsimg SET partsimg.codeno=$code$goutou , partsimg.goutou=$goutou WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    }else{
    $sql= $pdo->prepare("UPDATE goutou SET goutou.codeno='$code$goutou' , goutou.goutou=NULL , goutou.goutouvar='$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();

    $sql= $pdo->prepare("UPDATE goutou2 SET goutou2.codeno='$code$goutou' , goutou2.goutou=NULL , goutou2.goutouvar='$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE goutoucomment SET goutoucomment.codeno='$code$goutou' , goutoucomment.goutou='$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partshistory SET partshistory.codeno='$code$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsfullhistory SET partsfullhistory.codeno='$code$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();

    $sql= $pdo->prepare("UPDATE partsfullhistory2 SET partsfullhistory2.codeno='$code$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsreset SET partsreset.codeno='$code$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
    
    $sql= $pdo->prepare("UPDATE partsimg SET partsimg.codeno='$code$goutou' , partsimg.goutou='$goutou' WHERE codeno='$codeno'") or die ("失敗");
    $sql->execute();
}

print "変更しました。<br>";
echo "<strong>2秒後にジャンプします。...</strong>";?>

 <SCRIPT>
 <!--
function autoLink()
 {
 location.href="../parts.php?code=<?php print $code;?>&codeno=<?php print $code;print $goutou;?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 // -->
 </SCRIPT>
                
<?php        
}}}  else {
    
$sql2= $pdo->prepare("SELECT * FROM goutou WHERE codeno='$codeno'") or die ("失敗");
$sql2->execute();

?>　<div>
    <span class="editcmt">棟Noを入力して下さい。</span>
    <form method="GET" action="touhensyu.php">
<?php
while ($kekka= $sql2->fetch())
{
?>
    <dl class="inner">
        <dt>種別</dt>
        <dd>
        <input type="hidden" name="syubetu" value="<?php print $syubetu;?>"><?php print $syubetu;?>
        </dd>
    </dl>
    <dl class="inner">
        <dt>団地名</dt>
        <dd>
        <input type="hidden" name="name" value="<?php print $name;?>"><?php print $name;?>
        </dd>
    </dl>
    <dl class="inner">
        <dt>棟No</dt>
        <dd>
        <input class="number" type="text" name="goutou" class="hankaku" value="<?php if(!empty($kekka['goutouvar'])){print $kekka['goutouvar'];}else{print $kekka['goutou'];}?>">
        </dd>
    </dl>    
      <input type="hidden" name="code" value="<?php print $code;?>">
      <input type="hidden" name="codeno" value="<?php print $codeno;?>">
      <input type="hidden" name="address" value="<?php print $address;?>">
      <input type="hidden" name="hensyu">
      <input class="registbtn" type="submit" value="登録">
    </form>
</div></div>
<?php }}}
$pdo = NULL;
?>
    <div id="footer">
        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
    </div>
<!--全角英数を半角英数に変換する-->
<script>
  function convertStr(str) {
    return str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
    });
}
$(function() {
    $('.hankaku').on('blur', function() {
        var str = $(this).val();
        $(this).val(convertStr(str));
    });
});
</script>    
<script src="../jquery/Lightbox/js/lightbox.min.js"></script>
    </body>
    </html>