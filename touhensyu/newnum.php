<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
$builedit= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地情報編集</a></li>",
                 "<li><a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>棟追加</a></li>",
                 "<li><a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'>団地削除</a></li>");
$builedit= array("<li><a href='{$path}danchihensyu/danchihensyu.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地情報編集</span></a>",
                 "<a href='{$path}touhensyu/newnum.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>棟追加</span></a>",
                 "<a href='{$path}danchihensyu/buildelete.php?code={$_GET['code']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}'><span>団地削除</span></a></li>");
                 
// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>

<html>
    <head>
        <title>棟追加</title>

<?php require '../require/header.php';?>

    <div class="block">
        <h1>棟追加</h1>
<?php
$userid = $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : NULL;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : NULL;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;

if(isset($_GET['hensyu']))
{
if(empty($goutou)){
        print '棟Noを入力して下さい。';
        print '<br/><br/>';
        print "<strong>入力ページへ自動的に戻ります...</strong>";?>
<SCRIPT>

function autoLink()
 {
 location.href="newnum.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1500); 
 
 </SCRIPT>
<?php }else{
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

function autoLink()
 {
 location.href="newnum.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 
 </SCRIPT>
<?php }
else{
    if(ctype_digit($goutou)){
$sql = $pdo->prepare("INSERT IGNORE INTO goutou(code,codeno,name,goutou) SELECT $code, concat($code,$goutou), '$name', $goutou FROM goutou WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql = $pdo->prepare("INSERT IGNORE INTO goutou2(code,codeno,name,goutou) SELECT $code, concat($code,$goutou), '$name', $goutou FROM goutou WHERE code='$code'") or die ("失敗");
$sql->execute();}
        else{
$sql = $pdo->prepare("INSERT IGNORE INTO goutou(code,codeno,name,goutouvar) SELECT '$code', concat('$code','$goutou'), '$name', '$goutou' FROM goutou WHERE code='$code'");
$sql->execute();
$sql = $pdo->prepare("INSERT IGNORE INTO goutou2(code,codeno,name,goutouvar) SELECT '$code', concat('$code','$goutou'), '$name', '$goutou' FROM goutou WHERE code='$code'");
$sql->execute();
        }
print "追加しました<br>";
echo "<strong>2秒後にジャンプします。...</strong>";
?>
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="../building.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",2000); 
 // -->
 </SCRIPT>
<?php

}}}  else {

$sql2= $pdo->prepare("SELECT * FROM danchilist WHERE code='$code'") or die ("失敗");
$sql2->execute();

?><div>
    <span class="editcmt">追加する棟Noを入力して下さい。</span>
    <form method="GET" action="newnum.php">        
 <?php
while ($row= $sql2->fetch())
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
        <dt>追加棟No<span class="location">入力箇所</span></dt>
        <dd>
<input type="text" name="goutou" class="hankaku" style="width: 60px;">
        </dd>
    </dl>
        <input type="hidden" name="code" value="<?php print $code; ?>">
        <input type="hidden" name="address" value="<?php print $address; ?>">
        <input type="hidden" name="hensyu">
        <input class="registbtn" type="submit" value="追加">
        </div></div></form>
<?php }}}
$pdo = NULL;
?>
    <div id="footer">
        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
    </div>

<script>
// 全角英数文字を半角に変換する関数
function convertStr(str) {
    return str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    });
}

$(function() {
    // クラス「hankaku」を持つ要素からフォーカスが外れた時に実行
    $('.hankaku').on('blur', function() {
        var str = $(this).val();
        $(this).val(convertStr(str));
    });
});
</script>

<script src="../jquery/Lightbox/js/lightbox.min.js"></script> 
    </body>
    </html>