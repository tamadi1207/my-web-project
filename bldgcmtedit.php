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
?>
    <!DOCTYPE html>

<html>
    <head>
<link href="css/buhin.css" rel="stylesheet" media="all">
<title>団地コメント編集</title>

<?php require './require/header.php';

$userid= $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
$no = isset($_GET['no']) ? htmlspecialchars($_GET['no']) : null;
?>
                <h1><?php print $syubetu;?>&nbsp;
                <span class="danchiname"><?php print $name;?></span></h1>
<?php
$sql= $pdo->prepare("SELECT * FROM danchicomment WHERE no='$no'") or die ('失敗');
$sql->execute();

while($row= $sql->fetch()){

if(!isset($_GET['comment'])){
?>
        <form method='GET' action='bldgcmtedit.php'>
            <strong>コメント編集</strong>
       <br clear="right">
       <textarea name='comment' rows='15' cols='50' ><?php print $row['comment'];?></textarea>
       </br>
        <input type='hidden' name='code' value='<?php print $code;?>'>
        <input type='hidden' name='no' value='<?php print $no;?>'>
        <input type='hidden' name='name' value='<?php print $name;?>'>
        <input type='hidden' name='address' value='<?php print $address;?>'>
        <input type='hidden' name='syubetu' value='<?php print $syubetu;?>'>
       <input type='submit' class="registbtn" value=コメント登録>
       </form>
<?php
}}
if(isset($_GET['comment'])){
        $comment= $_GET['comment'];
$sql2= $pdo->prepare("UPDATE danchicomment SET comment = '$comment' WHERE no='$no'") or die ("失敗");
$sql2->execute();
?>コメントを編集しました。
<SCRIPT>
function autoLink()
 {
 location.href="./building.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1000); 
</SCRIPT>
<?php }}
$pdo= NULL;?>          
        <div id="footer">
                Copyright &copy;  <?php echo $htcreate;?> All Rights Reserved.
        </div>
</div>
</body>
</html>