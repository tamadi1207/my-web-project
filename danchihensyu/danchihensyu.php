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
<title>団地情報編集</title>
<style>
/* 緑の「入力箇所」ラベルが途中で改行されるのを防ぐ */
.location {
    display: inline-block;
    white-space: nowrap; /* これで「入力箇所」が1行に固定されます */
    background: #5cb85c;
    color: white;
    padding: 2px 5px;
    border-radius: 4px;
    font-size: 10px;
    margin-left: 5px;
    vertical-align: middle;
}

/* 親要素(dt)が狭くなりすぎないようにする（スマホ対策） */
.inner dt {
    display: flex;
    align-items: center;
    flex-shrink: 0; /* 画面が狭くてもラベルを押し潰さない */
}
</style>
<?php require '../require/header.php';?>

        <div class="block">
            <h1>団地情報編集</h1>
<?php

$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : NULL;
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
$city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : NULL;
$jusyo = isset($_GET['jusyo']) ? htmlspecialchars($_GET['jusyo']) : NULL;
$nendo = isset($_GET['nendo']) ? htmlspecialchars($_GET['nendo']) : NULL;
$map = isset($_GET['map']) ? htmlspecialchars($_GET['map']) : NULL;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;


if(isset($_GET['hensyu']))
{
$sql= $pdo->prepare("UPDATE danchilist SET name='$name' , city='$city' , jusyo='$jusyo' , nendo='$nendo' , map='$map' WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql= $pdo->prepare("UPDATE goutou SET name='$name' WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql= $pdo->prepare("UPDATE goutou2 SET name='$name' WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql= $pdo->prepare("UPDATE partshistory SET name='$name' WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql= $pdo->prepare("UPDATE maphistory SET name='$name' WHERE code='$code'") or die ("失敗");
$sql->execute();
$sql= $pdo->prepare("UPDATE builhistory SET name='$name' WHERE code='$code'") or die ("失敗");
$sql->execute();
?>
<?php
print "登録しました<br>";
echo "<strong>2秒後にジャンプします。...</strong>";?>
<SCRIPT>

function autoLink()
 {
 location.href="../building.php?code=<?php print $code;?>&name=<?php print $name;?>&syubetu=<?php print $syubetu;?>&address=<?php print $jusyo;?>";
 }
 setTimeout("autoLink()",1000); 
 
 </SCRIPT>

<?php
}  else {
    
$sql2= $pdo->prepare("SELECT * FROM danchilist WHERE code='$code'") or die ("失敗");
$sql2->execute();

?>
<div>
    <form method="GET" action="danchihensyu.php">
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
            <dt>団地名<span class="location">入力箇所</span></dt>
            <dd>
            <input type="text" name="name" value="<?php print $kekka[2];?>" style="width:237px">
            </dd>
        </dl>
        <dl class="inner">
                <dt>住所<span class="location">入力箇所</span></dt>
                <dd class="item">
                <input type="hidden" name="city" value="<?php print $kekka[3];?>"><?php print $kekka[3];?>
                <input type="text" name="jusyo" value="<?php print $kekka[4];?>">
                </dd>
        </dl>
        <dl class="inner">
            <dt>建築年度<span class="location">入力箇所</span></dt>
                <dd>
                <input type="text" name="nendo" value="<?php print $kekka[5];?>" style="width:60px">
                </dd>
        </dl>
        <dl class="inner">
                <dt>地図帳</dt>
                <dd>
                <input type="hidden" name="map" value="<?php print $kekka[6];?>"><?php print $kekka[6];?>
                </dd>
        </dl>
      <input type="hidden" name="code" value="<?php print $kekka[0];?>">
      <input type="hidden" name="address" value="<?php print $address;?>">
      <input type="hidden" name="hensyu">
      <input class="registbtn" type="submit" value="登録">
    </form>
</div>
    </div>
<?php }}}
$pdo = NULL;
?>
            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
            </div>
          </div>

    </body>
    </html>