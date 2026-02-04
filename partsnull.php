<?php
require './db_info.php';
require './cookie.php';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
$path= './';
$builedit= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟No変更</a></li>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟削除</a>",
                 "<li><a href='{$path}partsnull.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>部品リセット</a></li>");
$builedit2= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟No変更</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟削除</span></a>",
                 "<a href='{$path}partsnull.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>部品リセット</span></a></li>");
// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
<html>
    <head>
<title>部品リセット</title>

<?php require 'require/header.php';
?>
<div class="block">    
    <h1>部品リセット</h1><?php

     $userid = $_COOKIE['ID'];
     $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
     $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
     $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
     $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : null;
     $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : null;
     $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;

if(isset($_GET['reset'])){
     $sql= $pdo->prepare("UPDATE $goutb SET hiduke=NULL, kuresent=NULL, roller=NULL, kuresent2=NULL, roller2=NULL, ami=NULL, swsize=NULL, pushtype=NULL, glasstype=NULL, smallfloor=NULL, swsize2=NULL, pushtype2=NULL, glasstype2=NULL,
 swsize3=NULL, smallfloor2=NULL, toumei=NULL, glass=NULL, beet=NULL, beet2=NULL, scope=NULL, news=NULL, newsother=NULL, cylinder=NULL, cylinder2=NULL, small=NULL, down=NULL, folding=NULL, handle=NULL, handle2=NULL, otherimg=NULL, otherimg2=NULL,
 sash=NULL, sashother=NULL, sash2=NULL, sashother2=NULL, bathroom=NULL, bathkey=NULL, toilet=NULL, toiletcmt=NULL, wood=NULL, woodcmt=NULL, setpost=NULL, setpostother=NULL, angle=NULL, fall=NULL, fallother=NULL, ev=NULL, floor=NULL, frame=NULL, framesize=NULL, dial=NULL,
 dialimg=NULL, otherimg=NULL, otherimg2=NULL, material=NULL, airtight=NULL, airtight2=NULL, stansize=NULL, bathkama=NULL, toiletkama=NULL, user=NULL, datetime=NULL WHERE codeno='$codeno'") or die ("失敗");
     $sql->execute();
     $sql= $pdo->prepare("DELETE FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
     $sql->execute();
//レコード追加
$sql= $pdo->prepare("INSERT INTO partsreset (code,codeno,name,type,user,datetime) VALUES('$code','$codeno','$name','$typeid','$id',now())") or die ("失敗");
$sql->execute();     
     echo '部品情報をリセットしました。';?>
<SCRIPT>
 <!--
function autoLink()
 {
 location.href="building.php?code=<?php print $code;?>&codeno=<?php print $codeno?>&name=<?php print $name;?>&address=<?php print $address;?>&goutou=<?php print $goutou;?>&syubetu=<?php print $syubetu;?>";
 }
 setTimeout("autoLink()",1500);
 // -->
 </SCRIPT>
<?php }else{
     $sql= $pdo->prepare("SELECT * FROM $goutb WHERE codeno='$codeno'") or die ("失敗");
     $sql->execute();
?>
    <div>
        <span class='editcmt'>部品情報をリセットしますか?</span>
        <form method='GET' action='partsnull.php'>
<?php
while($row= $sql->fetch()){
?>
            <dl class='inner'>
                <dt>種別</dt>
                <dd>
                    <?php echo $syubetu;?>
                </dd>
            </dl>
            <dl class='inner'>
                <dt>団地名</dt>
                <dd>
                <?php echo $name;?>
                </dd>
            </dl>
            <dl class='inner'>
                <dt>棟No</dt>
                <dd>
                    <?php if(!empty($goutou)){echo $goutou;}else{echo $goutouvar;}?>号棟
                </dd>
            </dl>
            <dl class='inner'>
                <dt>最終更新日時</dt>
                <dd>
                <?php echo $row['hiduke'];?>
                </dd>
            </dl>
            <dl class='inner'>
                <dt>最終更新ユーザ</dt>
                <dd>
                <?php echo $row['user'];?>
                </dd>
            </dl>
            <input type='hidden' name='code' value='<?php echo $code;?>'>
            <input type='hidden' name='codeno' value='<?php echo $codeno;?>'>
            <input type='hidden' name='syubetu' value='<?php echo $syubetu;?>'>
            <input type='hidden' name='name' value='<?php echo $name;?>'>
            <input type='hidden' name='goutou' value='<?php if(!empty($goutou)){echo $goutou;}else{echo $goutouvar;}?>'>
            <input type='hidden' name='address' value='<?php echo $address;?>'>
            <input type='hidden' name='reset'>
            <input id='reset' class='registbtn' type='submit' value='リセット'>
        </form>    
        </div></div>
<?php }}}
  $pdo= NULL;
?>
    <script>
        $('#reset').click(function(){
            if(!confirm('本当にリセットしますか?\n\
(日付、部品項目、アップロード画像すべて削除されます。)')){
                /*キャンセルの時の処理*/
                return false;
            }
        });
    </script>    
    <div id="footer">
        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
    </div>
<script src="jquery/Lightbox/js/lightbox.min.js"></script>
</body>
</html>     