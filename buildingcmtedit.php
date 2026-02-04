<?php
require './db_info.php';
require './cookie.php';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
$path= './';
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

$userid= $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
$codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : null;
$goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : null;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
$no = isset($_GET['no']) ? htmlspecialchars($_GET['no']) : null;
?>
                <h2><?php print $syubetu;?>&nbsp;
                <span class="danchiname"><?php print $name;?>&nbsp;<span class="strong"><?php print $goutou;?>号棟</span></span></h2>
<?php
$sql= $pdo->prepare("SELECT * FROM goutoucomment WHERE no='$no'") or die ('失敗');
$sql->execute();

while($row= $sql->fetch()){

if(!isset($_GET['comment'])){
?>
        <form method='GET' action='buildingcmtedit.php'>
            <strong>コメント編集</strong>
       <br clear="right">
       <textarea name='comment' rows='15' cols='50' ><?php print $row['comment'];?></textarea>
       </br>
        <input type='hidden' name='code' value='<?php print $code;?>'>
        <input type='hidden' name='codeno' value='<?php print $codeno;?>'>
        <input type='hidden' name='no' value='<?php print $no;?>'>
        <input type='hidden' name='name' value='<?php print $name;?>'>
        <input type='hidden' name='goutou' value='<?php print $goutou;?>'>
        <input type='hidden' name='address' value='<?php print $address;?>'>
        <input type='hidden' name='syubetu' value='<?php print $syubetu;?>'>
       <input type='submit' class="registbtn" value=コメント登録>
       </form>
<?php
}}
if(isset($_GET['comment'])){
        $comment= $_GET['comment'];
$sql2= $pdo->prepare("UPDATE goutoucomment SET comment = '$comment' WHERE no='$no'") or die ("失敗");
$sql2->execute();
?>コメントを編集しました。
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="./parts.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1000); 
 // -->
</SCRIPT>
<?php }}
$pdo= NULL;?>          
        <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
        </div>
</div>
</body>
</html>