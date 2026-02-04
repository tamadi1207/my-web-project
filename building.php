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
        <link href="css/goutou.css?q" rel="stylesheet" media="all">
        <link rel="stylesheet" href="jquery/Lightbox/css/lightbox.css">
        <title>団地内検索</title>

<?php require './require/header.php';

$building_cmt = $building_cmt ?? ''; 
$building_cmt2 = $building_cmt2 ?? '';

$userid= $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : NULL;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;
$map = isset($_GET['map']) ? htmlspecialchars($_GET['map']) : NULL;


//レコードがなければINSERT,あればUPDATEをする
////gptが書いたコード(プリペアドステートメント + バインド)セキュリティ上安全
$sql = $pdo->prepare(
    "INSERT INTO builhistory (code, name, type, user, datetime)
     VALUES (:code, :name, :type, :user, NOW())"
);
$sql->execute([
    ':code' => ($code === '' ? null : (int)$code),
    ':name' => $name,
    ':type' => $typeid,
    ':user' => $userid
]);


?>
    <div class="builbox">
        <h2><?php print $syubetu;?>&nbsp;
         <label><?php print $name; ?></label>&nbsp;&nbsp;&nbsp;
         <span><?php print $address; ?>&nbsp;&nbsp;<a href='./mapjump.php?code=<?php print htmlspecialchars($code);?>&name=<?php print htmlspecialchars($name);?>&address=<?php print $address;?>'>地図</a></span>
        </h2>
            <a class="commentbtn builcommentbtn" href="bldgcmt.php?code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&syubetu=<?php print $syubetu;?>"><i class="fa fa-camera"></i><i class="fa fa-comment"></i>団地COMMENT</a>

<?php 
//$sql2 = $pdo->prepare("SELECT * FROM goutou WHERE code='$code' ORDER BY `goutouvar`,`goutou` ASC") or die ("失敗");
//$sql2->execute();

//アップロードした部品画像もcnt_upimgでカウントしているが使用していない
$sql2 = $pdo->prepare("SELECT $goutb.code, $goutb.codeno,  $goutb.goutou, $goutb.goutouvar, $goutb.hiduke, 
count(distinct(goutoucomment.`comment`)) as `cntcmt`, 
count(distinct(goutoucomment.img)) as cntimg, 
count(distinct(partsimg.img)) as cnt_upimg 
FROM $goutb LEFT JOIN goutoucomment ON $goutb.codeno = goutoucomment.codeno 
LEFT JOIN partsimg ON $goutb.codeno = partsimg.codeno 
where $goutb.code= '$code' GROUP BY goutouvar, goutou") or die ("失敗");
$sql2->execute();

?>

<div class="boxchenge">   
    <div class="toubox">
        <h3><span>棟一覧</span></h3>
       
<?php
while ($row2= $sql2->fetch())
{
      //棟一覧?>
      <ul id="toulist">
      <li style="list-style:none;">
  <?php if(empty($row2['hiduke'])){
          
      if($row2['cntcmt'] || $row2['cntimg'] > 0){?><i class="fa fa-commenting-o icon"></i><?php }?><a class="button" href='parts.php?syubetu=<?php print $syubetu;?>&name=<?php print $name;?>&address=<?php print $address;?>&code=<?php print $row2['code'];?>&map=<?php print $map;?>
      &codeno=<?php print $row2['codeno'];?>&goutou=<?php print $row2['goutou'];?>&goutouvar=<?php print $row2['goutouvar'];?>&date=<?php echo $row2['hiduke'];?>'><?php if(empty($row2['goutou'])){print htmlspecialchars($row2['goutouvar']);}else{print htmlspecialchars($row2['goutou']);}?>号棟
      <span class="touhiduke"><?php print $row2['hiduke'];?></span></a>
                       <?php }
        else {
      if($row2['cntcmt'] || $row2['cntimg'] > 0){?><i class="fa fa-commenting-o icon"></i><?php }?><a class="button" href='parts.php?syubetu=<?php print $syubetu;?>&name=<?php print $name;?>&address=<?php print $address;?>&code=<?php print $row2['code'];?>&map=<?php print $map;?>
      &codeno=<?php print $row2['codeno'];?>&goutou=<?php print $row2['goutou'];?>&goutouvar=<?php print $row2['goutouvar'];?>&date=<?php echo $row2['hiduke'];?>'><?php if(empty($row2['goutou'])){print htmlspecialchars($row2['goutouvar']);}else{print htmlspecialchars($row2['goutou']);}?>号棟
      <span class="touhiduke"><?php print $row2['hiduke'];?></span></a>
      <?php }?>
      </li></ul>
<?php }?>
    </div>
     <div class="info">
<div class="builinfo">
        <h4>団地コメント</h4>
<?php

//団地コメント表示
$sql= ("SELECT * FROM danchicomment WHERE code='$code' AND type='$typeid' $building_cmt ORDER BY hiduke desc") or die ("失敗");
$stmt= $pdo->query($sql);
$stmt->execute();
$count=$stmt->rowcount();

if($count == 0){
    print 'コメントはありません。';
}  else {
while ($row= $stmt->fetch())
{
 ?>
    <dl>
        <dt><span class="hiduke"><?php print htmlspecialchars($row['hiduke']);?></span>&nbsp;<?php print htmlspecialchars($row['name']);?></dt>
        <dd><div class="builcmt"><?php print nl2br(htmlspecialchars($row['comment']));?></div><?php
             if(isset($row['img'])){?>
                 <a href="./img/bldg/<?php print $row['code'];?>/<?php print $row['img'];?>" data-lightbox="bldg" data-title="<?php print $row['comment'];?>">
                 <img class="builimg" onerror="this.style.display='none'" src="./img/bldg/<?php print $row['code'];?>/<?php print $row['img'];?>"></a><?php
             }

              ?><div class="buildel"><?php
             if(htmlspecialchars($row['name'] == 'hasumi') && ($_COOKIE['ID'] == 'hasumi'))
                 {
             if(!empty($row['comment'])){ ?>
                 <a href='bldgcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row['no'];?>'>編集</a>
             <?php }?>
                 <a href='bldgcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row['no'];?>&comment2=<?php print $row['comment'];?>'>削除</a><?php 
               }
               if(htmlspecialchars($row['name'] !== 'hasumi') && ($_COOKIE['ID'] !== 'hasumi')){
             if(!empty($row['comment'])){ ?>
                 <a href='bldgcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row['no'];?>'>編集</a>
             <?php }?>
                 <a href='bldgcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row['no'];?>&comment2=<?php print $row['comment'];?>'>削除</a><?php 
               }?>
              </div>


           </dd>
    </dl>
<?php
}}?>
</div>
  <div class="bldginfo">
      <h4>棟コメント</h4>
<?php
//号棟コメント表示
$sql4= ("SELECT * FROM goutoucomment WHERE code='$code' AND type='$typeid' $building_cmt2 ORDER BY hiduke desc") or die ("失敗");
$stmt2= $pdo->query($sql4);
$stmt2->execute();
$count2=$stmt2->rowcount();

if($count2 == 0){
    print 'コメントはありません。';
}  else {
while ($row3= $stmt2->fetch())
{
  ?>
    <dl class='toucmt'>
        <dt><span class="hiduke"><?php print htmlspecialchars($row3['hiduke']);?></span>&nbsp;<?php print htmlspecialchars($row3['name']);?>&nbsp;(<?php print htmlspecialchars($row3['goutou']);?>号棟)</dt>
             <dd><div class="builcmt"><?php print nl2br(htmlspecialchars($row3['comment']));?></div><?php
             if(isset($row3['img'])){ ?>
                 <a href="./img/building/<?php print $row3['code'];?>/<?php print $row3['img'];?>" data-lightbox="building" data-title="<?php print $row3['comment'];?>">
                 <img class="builimg" onerror="this.style.display='none'" src="./img/building/<?php print $row3['code'];?>/<?php print $row3['img'];?>"></a><?php
             }

              ?><div class="buildel"><?php
             if(htmlspecialchars($row3['name'] == 'hasumi') && ($_COOKIE['ID'] == 'hasumi'))
                 {
             if(!empty($row3['comment'])){ ?>

                 <a href='buildingcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $row3['codeno'];?>&goutou=<?php print $row3['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row3['no'];?>'>編集</a>
                 <?php }?>
                 <a href='buildingcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $row3['codeno'];?>&goutou=<?php print $row3['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row3['no'];?>&comment2=<?php print $row3['comment'];?>'>削除</a><?php }
             if(htmlspecialchars($row3['name'] !== 'hasumi') && ($_COOKIE['ID'] !== 'hasumi'))
                 {
             if(!empty($row3['comment'])){ ?>
                 <a href='buildingcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $row3['codeno'];?>&goutou=<?php print $row3['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row3['no'];?>'>編集</a>
                 <?php }?>
                 <a href='buildingcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $row3['codeno'];?>&goutou=<?php print $row3['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $row3['no'];?>&comment2=<?php print $row3['comment'];?>'>削除</a><?php }?>
               </div>


             </dd>
    </dl>
<?php }}}?>
  </div>
</div>
</div>
</div>

<?php
if($count > 0){?>
<style>
@media screen and (max-width: 414px) {
.boxchenge{
    display:flex;
    flex-flow: row wrap;
}
.toubox{
    order: 2;
}
.info{
    order: 1;
}
.bldginfo{
  display: none;
}}
</style>
<?php }

if($count2 > 0){?>
<style>
@media screen and (max-width: 414px) {
.boxchenge{
    display:flex;
    flex-flow: row wrap;
}
.toubox{
    order: 2;
}
.info{
    order: 1;
}
.builinfo{
  display: none;
}}
</style>
<?php }

if($count > 0 && $count2 > 0){?>
<style>
@media screen and (max-width: 414px) {
.boxchenge{
    display:flex;
    flex-flow: row wrap;
}
.toubox{
    order: 2;
}
.info{
    order: 1;
}
.builinfo{
  display: block;
}
.bldginfo{
  display: block;
}}
</style>
<?php }
$pdo = NULL;
?>

            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> Rights Reserved.
            </div>
    <script src="jquery/Lightbox/js/lightbox.min.js"></script>
</body>
</html>
