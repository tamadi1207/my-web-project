<?php
require '../db_info.php';
require '../cookie.php';

// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>

<html>
    <head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="css/style.css" rel="stylesheet" media="all">
<link rel="icon" type="image/vnd.microsoft.icon" href="img/html/icon.jpg">
<script src="../jquery/jquerybody/jquery-2.2.0.js"></script>
<title>団地削除</title></head>
        
<body>      

<?php

$syubetu= $_GET['syubetu'];
$code= $_GET['code'];
$name= $_GET['name'];
$city= $_GET['city'];
$jusyo= $_GET['jusyo'];
$nendo= $_GET['nendo'];
$map= $_GET['map'];

if(isset($_GET['sakuzyo']))
{
    print $code;
    $sql= $pdo->prepare("DELETE FROM danchilist WHERE code='$code'") or die ("失敗");
    $sql->execute();
    print '削除しました<br>';
    echo "<strong>2秒後にジャンプします。...</strong>";
    
}else
{

$sql2= $pdo->prepare("SELECT * FROM danchilist WHERE code='$code'") or die ("失敗");
$sql2->execute();

?>
    <form method="GET" action="danchisakuzyo.php">
<table>
    <tr><th>コード</th>
        <th>種別</th> 
        <th>団地名</th>
        <th>区</th>
        <th>住所</th>
        <th>建築年度</th>
        <th>地図帳</th></tr>

 <?php
while ($kekka= $sql2->fetch())
{
    ?>

    　　<tr><td><input type="hidden" name="code" value="<?php print $kekka[0];?>"><?php print $kekka[0];?></td>
          <td><input type="hidden" name="syubetu" value="<?php print $kekka[1];?>"><?php print $kekka[1];?></td>
          <td><input type="hidden" name="name" value="<?php print $kekka[2];?>"><?php print $kekka[2];?></td>
          <td><input type="hidden" name="city" value="<?php print $kekka[3];?>"><?php print $kekka[3];?></td>
          <td><input type="hidden" name="jusyo" value="<?php print $kekka[4];?>"><?php print $kekka[4];?></td>
          <td><input type="hidden" name="nendo" value="<?php print $kekka[5];?>"><?php print $kekka[5];?></td>
          <td><input type="hidden" name="map" value="<?php print $kekka[6];?>"><?php print $kekka[6];?></td></tr>
        <input type="hidden" name="sakuzyo">
        <input type="submit" value="削除" id="delete">
    </form>

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
</body>
</html>