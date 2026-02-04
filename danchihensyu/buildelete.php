<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
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
        <title>団地削除</title>

<?php require '../require/header.php'; ?>
        
<div class="block">
    <h1>団地削除</h1>
<?php

$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
$city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : null;
$jusyo = isset($_GET['jusyo']) ? htmlspecialchars($_GET['jusyo']) : null;
$nendo = isset($_GET['nendo']) ? htmlspecialchars($_GET['nendo']) : null;
$map = isset($_GET['map']) ? htmlspecialchars($_GET['map']) : null;

if(isset($_GET['delete']))
{
    $sql= $pdo->prepare("DELETE FROM danchilist WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM goutou WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM goutou2 WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM danchicomment WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM goutoucomment WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM partshistory WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM maphistory WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM builhistory WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM partsfullhistory WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM partsfullhistory2 WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM partsreset WHERE code='$code'") or die ("失敗");
    $sql->execute();
    $sql= $pdo->prepare("DELETE FROM partsimg WHERE code='$code'") or die ("失敗");
    $sql->execute();
    
    print '削除しました';?>
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="../index.php";
 }
 setTimeout("autoLink()",2000); 
 // -->
 </SCRIPT>
<?php 
}else
{

$sql2= $pdo->prepare("SELECT * FROM danchilist WHERE code='$code'") or die ("失敗");
$sql2->execute();

?>
 <div>
    <form method="GET" action="buildelete.php">
        <span class="editcmt">団地を削除しますか？</span>
<?php
while ($kekka= $sql2->fetch())
{
?>
<dl class="inner">
    <dt>種別</dt>
        <dd>
            <input type="hidden" name="syubetu" value="<?php print $kekka[1];?>"><?php print $kekka[1];?>
        </dd>
</dl>
<dl class="inner">
    <dt>団地名</dt>
        <dd>
            <input type="hidden" name="name" value="<?php print $kekka[2];?>"><?php print $kekka[2];?>
        </dd>
</dl>
<dl class="inner">
    <dt>住所</dt>    
        <dd>
             <input type="hidden" name="city" value="<?php print $kekka[3];?>"><?php print $kekka[3];?>        
             <input type="hidden" name="jusyo" value="<?php print $kekka[4];?>"><?php print $kekka[4];?>
        </dd> 
</dl>
<dl class="inner"> 
    <dt>建築年度</dt>
        <dd>
             <input type="hidden" name="nendo" value="<?php print $kekka[5];?>"><?php print $kekka[5];?>
        </dd>
</dl>
<dl class="inner">
    <dt>地図帳ページ</dt>
        <dd>
             <input type="hidden" name="map" value="<?php print $kekka[6];?>"><?php print $kekka[6];?>
        </dd>     
</dl>
        <input type="hidden" name="code" value="<?php print $kekka[0];?>">
        <input type="hidden" name="address" value="<?php print $address;?>">
        <input type="hidden" name="delete">
        <input class="registbtn" type="submit" value="削除" id="delete">
    </form>
 </div>
</div>
<?php }}}
$pdo = NULL;
?>
    <script>
        $('#delete').click(function(){
            if(!confirm('本当に削除しますか?\n\
(コメント、部品情報すべて削除されます。)')){
                /*キャンセルの時の処理*/
                return false;
            }
        });
    </script>
            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
            </div>
          </div>
</body>
</html>