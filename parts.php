<?php
require './db_info.php';
require './cookie.php';
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : null;
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : null;
$path= './';

//エラー消すための空文字
$building_cmt2 = '';

//日付パラメータの有無判定。ヘッダーメニュー部品リセット項目の表示、非表示
if(!empty($date)){
$reset= "<li><a href='{$path}partsnull.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>部品情報リセット</a></li>";
$reset2= "<a href='{$path}partsnull.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>部品情報リセット</span></a></li>";
}else{
     $reset= NULL;
     $reset2= NULL;
     }
//END     

$builedit= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟No変更</a></li>",
                 "<li><a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'>棟削除</a>",
                 "$reset");
$builedit2= array("<li><a href='{$path}touhensyu/touhensyu.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟No変更</span></a>",
                 "<a href='{$path}touhensyu/tousakuzyo.php?code={$_GET['code']}&codeno={$_GET['codeno']}&syubetu={$_GET['syubetu']}&name={$_GET['name']}&address={$_GET['address']}&goutou={$_GET['goutou']}&goutouvar={$goutouvar}'><span>棟削除</span></a>",
                 "$reset2");
// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
<html>
    <head>
<link href="css/buhin.css?yi" rel="stylesheet" media="all">
<link rel="stylesheet" href="jquery/Lightbox/css/lightbox.css">
<title>部品一覧</title>

<?php require 'require/header.php';

     $userid = $_COOKIE['ID'];
     $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
     $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
     $code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : null;
     $codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : null;
     $goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : null;
     $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
     
?>
    <div class="partsbox">
            <h2><?php print $syubetu;?>&nbsp;
                <span class="danchiname"><a href='building.php?syubetu=<?php print $syubetu;?>&name=<?php print $name;?>&address=<?php print $address;?>&code=<?php print $code;
                ?>'><?php print $name;?></a>&nbsp;</span><span class="strong"><?php if(!empty($goutouvar)){print $goutouvar;}else{print $goutou;}?>号棟</span>&nbsp;&nbsp;&nbsp;
                <label><?php print $address; ?>&nbsp;&nbsp;<a href='./mapjump.php?code=<?php print htmlspecialchars($code);?>&name=<?php print htmlspecialchars($name);?>&address=<?php print $address;?>'>地図</a></label>
            </h2>
                <a class="commentbtn toucommentbtn" href='buildingcmt.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $codeno;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&name=<?php print $name;?>&address=<?php print $address;?>'><i class="fa fa-camera"></i><i class="fa fa-comment"></i>棟COMMENT</a>
      
<?php
$sql4= $pdo->prepare("SELECT * FROM goutoucomment WHERE codeno='$codeno' AND type='$typeid' $building_cmt2 ORDER BY hiduke desc") or die ('失敗4');
$sql4->execute();
  if(empty($kekka['comment'])){ ?>

    <h4>棟コメント</h4>
<?php
while($kekka2= $sql4->fetch())
{
?>    <dl class="toucmt">
        <dt><span class="hiduke"><?php print htmlspecialchars($kekka2['hiduke']);?></span>&nbsp;<?php print htmlspecialchars($kekka2['name']);?></dt>
             <dd><div class="builcmt"><?php print nl2br(htmlspecialchars($kekka2['comment']));?></div><?php
             if(isset($kekka2['img'])){?>
                 <a href="./img/building/<?php print $kekka2['code'];?>/<?php print $kekka2['img'];?>" data-lightbox="building" data-title="<?php print $kekka2['comment'];?>">
                 <img class="builimg" onerror="this.style.display='none'" src="./img/building/<?php print $kekka2['code'];?>/<?php print $kekka2['img'];?>"></a><?php
             }

              ?><div class="buildel"><?php             
             if(htmlspecialchars($kekka2['name'] == 'hasumi') && ($_COOKIE['ID'] == 'hasumi'))
                 {
             if(!empty($kekka2['comment'])){ ?>
                 <a href='buildingcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $kekka2['codeno'];?>&goutou=<?php print $kekka2['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $kekka2['no'];?>'>編集　</a>
             <?php }?>
                 <a href='buildingcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $codeno;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $kekka2['no'];?>&comment2=<?php print $kekka2['comment'];?>'>削除</a><?php }
             if(htmlspecialchars($kekka2['name'] !== 'hasumi') && ($_COOKIE['ID'] !== 'hasumi'))
                 {
             if(!empty($kekka2['comment'])){ ?>
                 <a href='buildingcmtedit.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $kekka2['codeno'];?>&goutou=<?php print $kekka2['goutou'];?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $kekka2['no'];?>'>編集　</a>
             <?php }?>
                 <a href='buildingcmtdelete.php?syubetu=<?php print $syubetu;?>&code=<?php print $code;?>&codeno=<?php print $codeno;?>&goutou=<?php print $goutou;?>&goutouvar=<?php print $goutouvar;?>&name=<?php print $name;?>&address=<?php print $address;?>&no=<?php print $kekka2['no'];?>&comment2=<?php print $kekka2['comment'];?>'>削除</a><?php }?>
             </div>
             </dd>

    </dl>
<?php }}?>

        <div class="parts">
           <form method='GET' action='partsregist.php'>
                <input class="submit" type='submit' value='部品編集'>
<?php
$kuresent = isset($_POST['kuresent']) ? htmlspecialchars($_POST['kuresent']) : null;

    if(isset($_POST['toroku'])){
  
    //MYSQL kuresent1カラム
    $kuresent = isset($_POST['kuresent']) ? htmlspecialchars($_POST['kuresent']) : null;
    $kuresent2 = isset($_POST['kuresent2']) ? htmlspecialchars($_POST['kuresent2']) : null;
    //END

    //MYSQL kuresent2カラム
    $kuresent3 = isset($_POST['kuresent3']) ? htmlspecialchars($_POST['kuresent3']) : null;
    $kuresent4 = isset($_POST['kuresent4']) ? htmlspecialchars($_POST['kuresent4']) : null;
    //END
    
    //MYSQL rollerカラム
    $roller = isset($_POST['roller']) ? htmlspecialchars($_POST['roller']) : null;
    $roller2 = isset($_POST['roller2']) ? htmlspecialchars($_POST['roller2']) : null;
    //END
    
    //MYSQL roller2カラム
    $roller3 = isset($_POST['roller3']) ? htmlspecialchars($_POST['roller3']) : null;
    $roller4 = isset($_POST['roller4']) ? htmlspecialchars($_POST['roller4']) : null;
    //END
    
    //MYSQL 1カラム
    $ami = isset($_POST['ami']) ? htmlspecialchars($_POST['ami']) : null;
    $ami2 = isset($_POST['ami2']) ? htmlspecialchars($_POST['ami2']) : null;
    $ami3 = isset($_POST['ami3']) ? htmlspecialchars($_POST['ami3']) : null;
    $ami4 = isset($_POST['ami4']) ? htmlspecialchars($_POST['ami4']) : null;
    //END

    //MYSQL 1カラム
    $width = isset($_POST['width']) ? htmlspecialchars($_POST['width']) : null;
    $height = isset($_POST['height']) ? htmlspecialchars($_POST['height']) : null;
    //END
    $pushtype = isset($_POST['pushtype']) ? htmlspecialchars($_POST['pushtype']) : null;
    $glasstype = isset($_POST['glasstype']) ? htmlspecialchars($_POST['glasstype']) : null;
    //MYSQL 1カラム
    $width2 = isset($_POST['width2']) ? htmlspecialchars($_POST['width2']) : null;
    $height2 = isset($_POST['height2']) ? htmlspecialchars($_POST['height2']) : null;
    //END
    $pushtype2 = isset($_POST['pushtype2']) ? htmlspecialchars($_POST['pushtype2']) : null;
    $glasstype2 = isset($_POST['glasstype2']) ? htmlspecialchars($_POST['glasstype2']) : null;
    //MYSQL 1カラム
    $width3 = isset($_POST['width3']) ? htmlspecialchars($_POST['width3']) : null;
    $height3 = isset($_POST['height3']) ? htmlspecialchars($_POST['height3']) : null;
    //END
    //MYSQL 1カラム    
    $stansizeA = isset($_POST['stansizeA']) ? htmlspecialchars($_POST['stansizeA']) : null;
    $stansizeB = isset($_POST['stansizeB']) ? htmlspecialchars($_POST['stansizeB']) : null;
    $stansizeC = isset($_POST['stansizeC']) ? htmlspecialchars($_POST['stansizeC']) : null;
    $stansizeD = isset($_POST['stansizeD']) ? htmlspecialchars($_POST['stansizeD']) : null;
    //END
    //MYSQL 1カラム    
    $framesizeA = isset($_POST['framesizeA']) ? htmlspecialchars($_POST['framesizeA']) : null;
    $framesizeB = isset($_POST['framesizeB']) ? htmlspecialchars($_POST['framesizeB']) : null;
    $framesizeC = isset($_POST['framesizeC']) ? htmlspecialchars($_POST['framesizeC']) : null;
    //END    
    $pushtype3 = isset($_POST['pushtype3']) ? htmlspecialchars($_POST['pushtype3']) : null;
    $glasstype3 = isset($_POST['glasstype3']) ? htmlspecialchars($_POST['glasstype3']) : null;

    $toumei = isset($_POST['toumei']) ? htmlspecialchars($_POST['toumei']) : null;
    $glass = isset($_POST['glass']) ? htmlspecialchars($_POST['glass']) : null;
    $beet = isset($_POST['beet']) ? htmlspecialchars($_POST['beet']) : null;
    $beet2 = isset($_POST['beet2']) ? htmlspecialchars($_POST['beet2']) : null;
    $scope = isset($_POST['scope']) ? htmlspecialchars($_POST['scope']) : null;
    //MYSQL 1カラム
    $news = isset($_POST['news']) ? htmlspecialchars($_POST['news']) : null;
    $kakou = isset($_POST['kakou']) ? htmlspecialchars($_POST['kakou']) : null;
    $vertical = isset($_POST['vertical']) ? htmlspecialchars($_POST['vertical']) : null;
    //END
    $cylinder = isset($_POST['cylinder']) ? htmlspecialchars($_POST['cylinder']) : null;
    $cylinder2 = isset($_POST['cylinder2']) ? htmlspecialchars($_POST['cylinder2']) : null;
    $small = isset($_POST['small']) ? htmlspecialchars($_POST['small']) : null;
    $down = isset($_POST['down']) ? htmlspecialchars($_POST['down']) : null;
    $folding = isset($_POST['folding']) ? htmlspecialchars($_POST['folding']) : null;
    $handle = isset($_POST['handle']) ? htmlspecialchars($_POST['handle']) : null;
    $handle2 = isset($_POST['handle2']) ? htmlspecialchars($_POST['handle2']) : null;
    $setpost = isset($_POST['setpost']) ? htmlspecialchars($_POST['setpost']) : null;
    $setpostother = isset($_POST['setpostother']) ? htmlspecialchars($_POST['setpostother']) : null;
    $sash = isset($_POST['sash']) ? htmlspecialchars($_POST['sash']) : null;
    $sashother = isset($_POST['sashother']) ? htmlspecialchars($_POST['sashother']) : null;
    $sash2 = isset($_POST['sash2']) ? htmlspecialchars($_POST['sash2']) : null;
    $sashother2 = isset($_POST['sashother2']) ? htmlspecialchars($_POST['sashother2']) : null;
    $bathroom = isset($_POST['bathroom']) ? htmlspecialchars($_POST['bathroom']) : null;
    $bathkey = isset($_POST['bathkey']) ? htmlspecialchars($_POST['bathkey']) : null;
    $toilet = isset($_POST['toilet']) ? htmlspecialchars($_POST['toilet']) : null;
    $toiletcmt = isset($_POST['toiletcmt']) ? htmlspecialchars($_POST['toiletcmt']) : null;
    $toiletcmt2 = isset($_POST['toiletcmt2']) ? htmlspecialchars($_POST['toiletcmt2']) : null;
    $toiletcmt3 = isset($_POST['toiletcmt3']) ? htmlspecialchars($_POST['toiletcmt3']) : null;
    $wood = isset($_POST['wood']) ? htmlspecialchars($_POST['wood']) : null;
    $woodcmt = isset($_POST['woodcmt']) ? htmlspecialchars($_POST['woodcmt']) : null;
    $woodcmt2 = isset($_POST['woodcmt2']) ? htmlspecialchars($_POST['woodcmt2']) : null;
    $woodcmt3 = isset($_POST['woodcmt3']) ? htmlspecialchars($_POST['woodcmt3']) : null;
    $newsother = isset($_POST['newsother']) ? htmlspecialchars($_POST['newsother']) : null;
    $angle = isset($_POST['angle']) ? htmlspecialchars($_POST['angle']) : null;
    $fall = isset($_POST['fall']) ? htmlspecialchars($_POST['fall']) : null;
    $fallother = isset($_POST['fallother']) ? htmlspecialchars($_POST['fallother']) : null;
    $ev = isset($_POST['ev']) ? htmlspecialchars($_POST['ev']) : null;
    $floor = isset($_POST['floor']) ? htmlspecialchars($_POST['floor']) : null;
    $frame = isset($_POST['frame']) ? htmlspecialchars($_POST['frame']) : null;
    $smallfloor = isset($_POST['smallfloor']) ? htmlspecialchars($_POST['smallfloor']) : null;
    $smallfloor2 = isset($_POST['smallfloor2']) ? htmlspecialchars($_POST['smallfloor2']) : null;
    $dial = isset($_POST['dial']) ? htmlspecialchars($_POST['dial']) : null;
    $dialimg = isset($_POST['dialimg']) ? htmlspecialchars($_POST['dialimg']) : null;
    $material = isset($_POST['material']) ? htmlspecialchars($_POST['material']) : null;
    $airtight = isset($_POST['airtight']) ? htmlspecialchars($_POST['airtight']) : null;
    $airtight2 = isset($_POST['airtight2']) ? htmlspecialchars($_POST['airtight2']) : null;
    $otherimg = isset($_POST['otherimg']) ? htmlspecialchars($_POST['otherimg']) : null;
    $otherimg2 = isset($_POST['otherimg2']) ? htmlspecialchars($_POST['otherimg2']) : null;
    $bathkama = isset($_POST['bathkama']) ? htmlspecialchars($_POST['bathkama']) : null;
    $toiletkama = isset($_POST['toiletkama']) ? htmlspecialchars($_POST['toiletkama']) : null;
    if(empty($dial)){$dialimg= NULL;}

if($sash == 'その他'){
}  else {
$sashother= '';
}
if($sash2 == 'その他'){
}  else {
$sashother2= '';
}
if($bathroom == '片開ドア'){
}  else {
$bathkey= '';
}
if($setpost == 'その他'){
}  else {
$setpostother= '';
}

if($material == ''){
    $airtight2= NULL;
    //impload関数で(,)を配列の間に保存する為、NULL使用できず
    $stansizeA= '';
    $stansizeB= '';
    $stansizeC= '';
    $stansizeD= '';
    $framesizeA= '';
    $framesizeB= '';
    $framesizeC= '';
    $framesizeD= '';
        $frame= NULL;
    $airtight= NULL;
}
//下枠寸法(例:$materialがスチールの場合はステンレスの値をNULLにする)
if(empty($material)){
    $material= NULL;
}elseif($material == 'スチール'){
    $airtight2= NULL;
    //impload関数で(,)を配列の間に保存する為、NULL使用できず
    $stansizeA= '';
    $stansizeB= '';
    $stansizeC= '';
    $stansizeD= '';
    //
}elseif($material == 'ステンレス'){
    $framesizeA= '';
    $framesizeB= '';
    $framesizeC= '';
    $framesizeD= '';
    $frame= NULL;
    $airtight= NULL;
}
///////////
if($toilet !== 'その他'){
    $toiletcmt= '';
    $toiletcmt2= '';
    $toiletcmt3= '';
}
if($wood !== 'その他'){
    $woodcmt= '';
    $woodcmt2= '';
    $woodcmt3= '';
}

//結合(implode)
$swsize= array($width,$height);
$multisize= implode(",", $swsize);

$swsize2= array($width2,$height2);
$multisize2= implode(",", $swsize2);

$swsize3= array($width3,$height3);
$multisize3= implode(",", $swsize3);

$newskakou= array($news,$kakou,$vertical);
$multinews= implode(",", $newskakou);

$toiletarray2= array($toiletcmt,$toiletcmt2,$toiletcmt3);
$toiletarray3= implode(",", $toiletarray2);

$woodarray2= array($woodcmt,$woodcmt2,$woodcmt3);
$woodarray3= implode(",", $woodarray2);

$checkami= array($ami,$ami2,$ami3,$ami4);
$multiami= implode("　", $checkami);

$stan= array($stansizeA,$stansizeB,$stansizeC,$stansizeD);
$stansize= implode(",", $stan);

$frameset= array($framesizeA,$framesizeB,$framesizeC);
$framesize= implode(",", $frameset);

///////////部品カラム追加した時はpartsnull.phpのupdateクエリもカラム追加する！！！////////////
$sql2= $pdo->prepare("UPDATE $goutb SET kuresent='$kuresent,$kuresent2' , roller='$roller,$roller2' , kuresent2='$kuresent3,$kuresent4' , roller2='$roller3,$roller4' , ami='$multiami' , swsize='$multisize'
 , pushtype='$pushtype' , glasstype='$glasstype' , smallfloor='$smallfloor' , swsize2='$multisize2' , pushtype2='$pushtype2' , glasstype2='$glasstype2' , swsize3='$multisize3' , smallfloor2='$smallfloor2' , toumei='$toumei' , glass='$glass' , beet='$beet' , beet2='$beet2' , scope='$scope' , news='$multinews' , newsother='$newsother' , cylinder='$cylinder' , cylinder2='$cylinder2'
 , small='$small' , down='$down' , folding='$folding' , handle='$handle' , handle2='$handle2' , setpost='$setpost' , setpostother='$setpostother' , sash='$sash' , sashother='$sashother' , sash2='$sash2' , sashother2='$sashother2' , bathroom='$bathroom' , bathkey='$bathkey' , toilet='$toilet' , toiletcmt='$toiletarray3' , wood='$wood' , woodcmt='$woodarray3' , angle='$angle' , fall='$fall' , fallother='$fallother'
 , ev='$ev' , floor='$floor' , frame='$frame' , framesize='$framesize' , material='$material' , airtight='$airtight' , airtight2='$airtight2' , stansize='$stansize' , dial='$dial' , dialimg='$dialimg' , otherimg='$otherimg' , otherimg2='$otherimg2' , bathkama='$bathkama' , toiletkama='$toiletkama' , hiduke = cast( now() as date ) , user = '$userid' , datetime= now() WHERE codeno='$codeno'") or die ("失敗");
$sql2->execute();

///////////下記のsqlもカラム追加！！！(mysqlのpartsfullhistoryテーブルにも追加！！)////////////////
$sql= $pdo->prepare("INSERT INTO $pfulltb SET code='$code' , codeno='$codeno' , name='$name' , goutou='$goutou' , goutouvar='$goutouvar' , hiduke=cast(now() as date) , user='$userid' , kuresent='$kuresent,$kuresent2' , roller='$roller,$roller2' , kuresent2='$kuresent3,$kuresent4' , roller2='$roller3,$roller4' , ami='$multiami' , swsize='$multisize'
 , pushtype='$pushtype' , glasstype='$glasstype' , smallfloor='$smallfloor' , swsize2='$multisize2' , pushtype2='$pushtype2' , glasstype2='$glasstype2' , swsize3='$multisize3' , smallfloor2='$smallfloor2' , toumei='$toumei' , glass='$glass' , beet='$beet' , beet2='$beet2' , scope='$scope' , news='$multinews' , newsother='$newsother' , cylinder='$cylinder' , cylinder2='$cylinder2'
 , small='$small' , down='$down' , folding='$folding' , handle='$handle' , handle2='$handle2' , setpost='$setpost' , setpostother='$setpostother' , sash='$sash' , sashother='$sashother' , sash2='$sash2' , sashother2='$sashother2' , bathroom='$bathroom' , bathkey='$bathkey' , toilet='$toilet' , toiletcmt='$toiletarray3' , wood='$wood' , woodcmt='$woodarray3' , angle='$angle' , fall='$fall' , fallother='$fallother'
 , ev='$ev' , floor='$floor' , frame='$frame' , framesize='$framesize' , material='$material' , airtight='$airtight' , airtight2='$airtight2' , stansize='$stansize' , dial='$dial' , dialimg='$dialimg' , otherimg='$otherimg' , otherimg2='$otherimg2' , bathkama='$bathkama' , toiletkama='$toiletkama' , datetime= now()") or die ("失敗");
$sql->execute();

//レコードがなければINSERT,あればUPDATEをする
$sql3= $pdo->prepare("INSERT INTO partshistory (code,codeno,name,type,user,datetime) VALUES($code,'$codeno','$name','$typeid','$userid',now())") or die ("失敗");
$sql3->execute();
}

$sql= $pdo->prepare("SELECT * FROM $goutb WHERE codeno='$codeno'") or die ("失敗");
$sql->execute();

while($row= $sql->fetch())
{
    //分割(explode)
$kuresent= explode(",", $row['kuresent'] ?? '');
$roller= explode(",", $row['roller'] ?? '');
$kuresent2= explode(",", $row['kuresent2'] ?? '');
$roller2= explode(",", $row['roller2'] ?? '');
$swsize= explode(",", $row['swsize'] ?? '');
$swsize2= explode(",", $row['swsize2'] ?? '');
$swsize3= explode(",", $row['swsize3'] ?? '');
$splitnews= explode(",", $row['news'] ?? '');
$toiletarray= explode(",", $row['toiletcmt'] ?? '');
$woodarray= explode(",", $row['woodcmt'] ?? '');

//explodeで分割した($stan)配列がすべて空の場合(array_filter関数)で配列を削除する
$stan= explode(",", $row['stansize'] ?? '');
$arraydel= array_filter($stan);

$framesize= explode(",", $row['framesize'] ?? '');
$arraydel2= array_filter($framesize);
//

// partslist.phpのid値へ飛ぶための配列
$i = 0;
$i2 = 0;
$sasharray = array('YKK','不二サッシ','トステム','日軽サッシ','三井軽金属','キンキ','三協アルミ','三協立山アルミ','立山アルミ','日鐵サッシ','LIXCIL','新日軽');
//
?>

            <table class="other">
                <tr><th>サッシメーカー</th><td <?php if(empty($row['sashother'])){print "colspan='2'";}?>>
                        <?php if(empty($row['sash'])){
                            print '&nbsp;';
                        }else {
                                foreach($sasharray as $value){
                                    $i++;
                                    if($row['sash'] == $value){
                                        echo "<a href='./partslist.php#$i'>". $row['sash'] ."</a>";
                                    }
                                }
                                if($row['sash'] == 'その他'){
                                            echo $row['sash'];
                                        }
                             if(!empty($row['sashother'])){?></td><td><?php
                        print $row['sashother'];}}?>
                </td></tr>

                        <?php if(!empty($row['sash2'] || $row['sashother2'])){?>
                        <tr><th>サッシメーカー2</th><td>
                        <?php if(empty($row['sash2'])){
                            print '&nbsp;';
                        }else {
                                foreach($sasharray as $value){
                                    $i2++;
                                    if($row['sash2'] == $value){
                                        echo "<a href='./partslist.php#$i2'>". $row['sash2'] ."</a>";
                                    }
                                }
                                if($row['sash2'] == 'その他'){
                                            echo $row['sash2'];
                                        }

                            if(!empty($row['sashother2'])){?></td><td><?php
                     print $row['sashother2'];?>
                        </td></tr><?php }}}?>
                
                <tr><th>加工ガラス</th><td colspan="2">
    <?php if(empty($row['glass'])){
        print '&nbsp;';
    }else {print $row['glass'];}?>
                    </td></tr>                        
                        
                <tr><th>ポケット</th><td colspan="2">
    <?php if(empty($row['beet'])){
    print '&nbsp;';
    }else {print $row['beet'];}
    ?><br><?php
    if(empty($row['beet2'])){
        print '&nbsp;';
        }else {print $row['beet2']; print '&nbsp;&nbsp;追加サッシ';}
        ?>
                    </td></tr>                
                
                <tr><th>網ガラス</th>
                <td colspan="2">
<?php if(empty($row['ami'])){
    print '&nbsp;';
}else {print $row['ami'];}?>
                </td></tr>                
                
                <tr><th>小窓ガラス(ベランダ側)</th><td colspan="2">
    <?php if(empty($swsize[0] ?? '') || ($swsize[1] ?? '') || $row['pushtype'] ?? '' || $row['glasstype']){
       print '&nbsp;'; 
       }if(!empty($row['smallfloor'])){?><strong><?php print $row['smallfloor']?></strong>&nbsp;&nbsp;<?php ;}
       if(!empty($swsize[0] ?? '') || ($swsize[1] ?? '')){print 'W'?>&nbsp;<?php ;print $swsize[0];?>&nbsp;x&nbsp;<?php print 'H'?>&nbsp;<?php ;print $swsize[1];}if(!empty($row['glasstype'])){?>
       &nbsp;<strong>ガラス</strong>&nbsp;=&nbsp;<?php print $row['glasstype'];}?>
              &nbsp;<?php if(!empty($row['pushtype'])){?><strong>押方法</strong>&nbsp;=&nbsp;<?php print $row['pushtype'];}?>
                    </td></tr>

    <?php if(!empty($swsize3[0] ?? '') || ($swsize3[1] ?? '')){?>
                <tr><th>小窓ガラス2(ベランダ側)</th><td colspan="2">
    <?php print '&nbsp;';
     if(!empty($row['smallfloor2'])){?><strong><?php print $row['smallfloor2']?></strong>&nbsp;&nbsp;<?php ;}
     if(!empty($swsize3[0] || $swsize3[1])){print 'W'?>&nbsp;<?php ;print $swsize3[0];?>&nbsp;x&nbsp;<?php print 'H'?>&nbsp;<?php ;print $swsize3[1];}?>
                </td></tr><?php }?>
                
                <tr><th>小窓ガラス(廊下側)</th><td colspan="2">
    <?php if(empty($swsize2[0] ?? '') || ($swsize2[1] ?? '') || $row['pushtype2'] || $row['glasstype2']){
       print '&nbsp;'; 
       }if(!empty($swsize2[0] ?? '') || ($swsize2[1] ?? '')){print 'W'?>&nbsp;<?php ;print $swsize2[0];?>&nbsp;x&nbsp;<?php print 'H'?>&nbsp;<?php ;print $swsize2[1];
       }if(!empty($row['glasstype2'])){?>
       &nbsp;&nbsp;<strong>ガラス</strong>&nbsp;=&nbsp;<?php print $row['glasstype2'];}?>
              &nbsp;&nbsp;<?php if(!empty($row['pushtype2'])){?><strong>押方法</strong>&nbsp;=&nbsp;<?php print $row['pushtype2'];}?>
                    </td></tr>

                <tr><th>透明ガラス</th><td colspan="2">
    <?php if(empty($row['toumei'])){
        print '&nbsp;';
    }else {print $row['toumei'];}?>
                    </td></tr>
          
                <tr><th>スコープ</th><td colspan="2">
    <?php if(empty($row['scope'])){
    print '&nbsp;';
    }else {print $row['scope'];}?>
                    </td></tr>

                <tr><th>新聞受け</th><td>
    <?php if(empty($row['news'])){
    echo '&nbsp;<td>';
    }else {print $splitnews[0];?>
                    
                    &nbsp;&nbsp;&nbsp;&nbsp;<?php print $row['newsother'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $splitnews[1];
                }
                    ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php print $splitnews[2] ?? ''; ?></tr>

                <tr><th>玄関(錠前)</th><td colspan="2">
    <?php if(empty($row['cylinder'])){
    print '&nbsp;';
    }else {print $row['cylinder'];}?>   
                    </td></tr>
                
                <tr><th>シリンダー</th><td colspan="2">
    <?php if(empty($row['cylinder2'])){
    print '&nbsp;';
    }else {print $row['cylinder2'];}?>        
                    </td></tr>                
                
                <tr><th>浴室ドア</th>
                    <td>
                        <?php if(empty($row['bathroom'])){
                            print '&nbsp;';
                            }else {print $row['bathroom'];}?></td>
                    <td style="border-left: none;">
                        <?php if($row['bathroom'] == '片開ドア'){
                            print $row['bathkey'];}?>
                    </td></tr>

                <tr><th>トイレ(錠前)</th><td colspan="2">
                            <?php if(empty($row['toilet'])){
                            print '&nbsp;';
                            }else {print $row['toilet'];}?>
                    </td>
                    <?php if(!empty($toiletarray[0]) || !empty($toiletarray[1]) || !empty($toiletarray[2])){
                    ?><td><?php if(!empty($toiletarray[0])){ echo "<strong>型名= </strong> ".$toiletarray[0];}
                                if(!empty($toiletarray[1])){ echo "&nbsp;&nbsp;&nbsp;&nbsp;<strong>BS= </strong> ".$toiletarray[1];}
                                if(!empty($toiletarray[2])){ echo "&nbsp;&nbsp;&nbsp;&nbsp;<strong>D= </strong> ".$toiletarray[2];}?>
                    </td><?php }?>
                </tr>

                <tr><th>木製ドア</th><td colspan="2">
                    <?php if(empty($row['wood'])){
                        print '&nbsp';
                    }else {print $row['wood'];}?></td>
                    <?php if(!empty($woodarray[0]) || !empty($woodarray[1]) || !empty($woodarray[2])){
                    ?><td><?php if(!empty($woodarray[0])){ echo "<strong>型名= </strong> ".$woodarray[0];}
                                if(!empty($woodarray[1])){ echo "&nbsp;&nbsp;&nbsp;&nbsp;<strong>BS= </strong> ".$woodarray[1];}
                                if(!empty($woodarray[2])){ echo "&nbsp;&nbsp;&nbsp;&nbsp;<strong>D= </strong> ".$woodarray[2];}?>
                    </td><?php }?>
                </tr>

                <tr><th>集合ポスト</th><td>
    <?php if(empty($row['setpost'])){
        print '&nbsp;';
    }else {print $row['setpost'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
           print $row['setpostother'];?>
         </td>
              <td><?php if($row['dial'] == '有'){print "ダイヤル錠:　${row['dial']}";}?>
         </td></tr>
 
                <tr><th>落下防止網</th><td>
    <?php if(empty($row['fall'])){
        print '&nbsp;';
    }else {print $row['fall'];}?>
    </td><td style="border-left: none;"><?php print $row['fallother'];?></td></tr>
                
                <tr><th>EV</th><td colspan="2">
    <?php if(empty($row['ev'])){
        print '&nbsp;';
    }else {print $row['ev'];}?>
                    </td></tr>
                
                <tr><th>階層</th><td colspan="2">
    <?php if(empty($row['floor'])){
        print '&nbsp;';
    }else {print $row['floor'];}?>
                    </td></tr>
                
                <tr><th>クレセント(Lアングル)</th><td colspan="2">
    <?php if(empty($row['angle'])){
        print '&nbsp;';
    }else {print $row['angle'];}?></td></tr>

    <?php if(!empty($row['material'])){?>
                <tr><th>下枠材質</th><td colspan="2">
    <?php print $row['material'];}
        if(!empty($row['airtight'])){?>&nbsp;&nbsp;&nbsp;&nbsp;<strong>エアータイト</strong>&nbsp;=&nbsp;<?php print $row['airtight'];}
        if(!empty($row['airtight2'])){?>&nbsp;&nbsp;&nbsp;&nbsp;<strong>エアータイト</strong>&nbsp;=&nbsp;<?php print $row['airtight2'];}?>
                    </td></tr>
            </table>

<?php 
       if(!empty($kuresent[0] ?? '' || $kuresent[1] ?? '')){?>
            <table class="img">
                <tr><th>クレセント
                    <?php if(!empty($row['angle'])){echo "<span>Lアングル必要</span>";}?>
                </th></tr>
                <tr><td>   
             <?php  if('CRE.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/CRE.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/CRE.jpg" width="256"></a><?php };
                    if('FUJI.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI.jpg" width="256"></a><?php };?>
             <?php if('FUJI2.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI2.jpg" width="256"></a><?php };?>
             <?php if('FUJI3.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI3.jpg" width="256"></a><?php };?>
             <?php if('FUJI4.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI4.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI4.jpg" width="256"></a><?php };?>
             <?php if('FUJI5.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI5.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI5.jpg" width="256"></a><?php };?>
             <?php if('FUJI6.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI6.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI6.jpg" width="256"></a><?php };?>
             <?php if('FUJI7.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI7.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI7.jpg" width="256"></a><?php };?>
             <?php if('FUJI8.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI8.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI8.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI_R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R-KEY.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI_R-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI_S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S-KEY.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI_S-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg" width="256"></a><?php };?>
             <?php if('KINKI_K-GOOD.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/KINKI_K-GOOD.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg" width="256"></a><?php };?>
             <?php if('MITUIKEIKINZOKU.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MITUIKEIKINZOKU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB-1.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MIWA_PB-1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-H.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MIWA_PB2-H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-S.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MIWA_PB2-S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM2.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/NAKANISI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI.jpg" width="256"></a><?php };?>
             <?php if('SANKYO.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SANKYO.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO.jpg" width="256"></a><?php };?>
             <?php if('SANKYO2.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SANKYO2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO2.jpg" width="256"></a><?php };?>
             <?php if('SANKYO3.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SANKYO3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO3.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SIBUTANI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI_KEY.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SIBUTANI_KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI3.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/SIBUTANI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI3.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM2.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('YKK1.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/YKK1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK1.jpg" width="256"></a><?php };?>
             <?php if('YKK2.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/YKK2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK2.jpg" width="256"></a><?php };?>
             <?php if('YKK3.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/YKK3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK3.jpg" width="256"></a><?php };
                    if('FUJI_haiban.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/FUJI_haiban.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_haiban.jpg" width="256"></a><?php };
                    if('KIYOMATU_SD-EK-2003H-20B.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" width="256"></a><?php };
                    if('MATUEI_AS-155H.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MATUEI_AS-155H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg" width="256"></a><?php };
                    if('MATUEI_LS-11.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/MATUEI_LS-11.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10757.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/YKK_HH-K-10757.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10759-132.jpg' == $kuresent[0]){?> <a href="img/buhin/kuresent/YKK_HH-K-10759-132.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg" width="256"></a><?php };

                    if('CRE.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/CRE.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/CRE.jpg" width="256"></a><?php };
                    if('FUJI.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI.jpg" width="256"></a><?php };?>
             <?php if('FUJI2.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI2.jpg" width="256"></a><?php };?>
             <?php if('FUJI3.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI3.jpg" width="256"></a><?php };?>
             <?php if('FUJI4.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI4.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI4.jpg" width="256"></a><?php };?>
             <?php if('FUJI5.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI5.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI5.jpg" width="256"></a><?php };?>
             <?php if('FUJI6.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI6.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI6.jpg" width="256"></a><?php };?>
             <?php if('FUJI7.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI7.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI7.jpg" width="256"></a><?php };?>
             <?php if('FUJI8.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI8.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI8.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI_R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R-KEY.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI_R-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI_S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S-KEY.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI_S-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg" width="256"></a><?php };?>
             <?php if('KINKI_K-GOOD.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/KINKI_K-GOOD.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg" width="256"></a><?php };?>
             <?php if('MITUIKEIKINZOKU.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MITUIKEIKINZOKU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB-1.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MIWA_PB-1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-H.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MIWA_PB2-H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-S.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MIWA_PB2-S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM2.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/NAKANISI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI.jpg" width="256"></a><?php };?>
             <?php if('SANKYO.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SANKYO.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO.jpg" width="256"></a><?php };?>
             <?php if('SANKYO2.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SANKYO2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO2.jpg" width="256"></a><?php };?>
             <?php if('SANKYO3.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SANKYO3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO3.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SIBUTANI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI_KEY.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SIBUTANI_KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI3.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/SIBUTANI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI3.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM2.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('YKK1.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/YKK1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK1.jpg" width="256"></a><?php };?>
             <?php if('YKK2.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/YKK2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK2.jpg" width="256"></a><?php };?>
             <?php if('YKK3.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/YKK3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK3.jpg" width="256"></a><?php };
                    if('FUJI_haiban.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/FUJI_haiban.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_haiban.jpg" width="256"></a><?php };
                    if('KIYOMATU_SD-EK-2003H-20B.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" width="256"></a><?php };
                    if('MATUEI_AS-155H.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MATUEI_AS-155H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg" width="256"></a><?php };
                    if('MATUEI_LS-11.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/MATUEI_LS-11.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10757.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/YKK_HH-K-10757.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10759-132.jpg' == $kuresent[1]){?> <a href="img/buhin/kuresent/YKK_HH-K-10759-132.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg" width="256"></a><?php };
                    }?> 
                    </td></tr></table>
<?php if(!empty($roller[0] ?? '') || ($roller[1] ?? '')){?>
                <table class="img"><tr><th>戸車</th></tr>
                    <tr><td><?php
              if('8type.jpg' == $roller[0]){?> <a href="img/buhin/roller/8type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/8type.jpg" width="256"></a><?php };
              if('9type.jpg' == $roller[0]){?> <a href="img/buhin/roller/9type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/9type.jpg" width="256"></a><?php };
              if('12type.jpg' == $roller[0]){?> <a href="img/buhin/roller/12type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/12type.jpg" width="256"></a><?php };
              if('14type.jpg' == $roller[0]){?> <a href="img/buhin/roller/14type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/14type.jpg" width="256"></a><?php };
              if('FUJI_TOPACE_FR3011-L.jpg' == $roller[0]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg" width="256"></a><?php };
              if('FUJI_TOPACE_FR3011-R.jpg' == $roller[0]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg" width="256"></a><?php };
              if('FUJI_FR-70series-R00920NN.jpg' == $roller[0]){?> <a href="img/buhin/roller/FUJI_FR-70series-R00920NN.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-FR0033.jpg' == $roller[0]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-FR0033.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-R00320.jpg' == $roller[0]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-R00320.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-kosimado.jpg' == $roller[0]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-hakidasi.jpg' == $roller[0]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('KOMSTU.jpg' == $roller[0]){?> <a href="img/buhin/roller/KOMSTU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KOMSTU.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-hakidasi.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-kosimado.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_B30266A-LorR.jpg' == $roller[0]){?> <a href="img/buhin/roller/SANKYO_B30266A-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BF4520B-LorR.jpg' == $roller[0]){?> <a href="img/buhin/roller/SANKYO_BF4520B-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL001K-outside.jpg' == $roller[0]){?> <a href="img/buhin/roller/SANKYO_BL001K-outside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL002K-inside.jpg' == $roller[0]){?> <a href="img/buhin/roller/SANKYO_BL002K-inside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM_BHP-59.jpg' == $roller[0]){?> <a href="img/buhin/roller/TOSTEM_BHP-59.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-2K-7515A.jpg' == $roller[0]){?> <a href="img/buhin/roller/YKK_AP_HH-2K-7515A.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-12431_32.jpg' == $roller[0]){?> <a href="img/buhin/roller/YKK_AP_HH-K-12431_32.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-15157.jpg' == $roller[0]){?> <a href="img/buhin/roller/YKK_AP_HH-K-15157.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-T-0029.jpg' == $roller[0]){?> <a href="img/buhin/roller/YKK_AP_HH-T-0029.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg" width="256"></a><?php };
              if('KINKI_old.jpg' == $roller[0]){?> <a href="img/buhin/roller/KINKI_old.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_old.jpg" width="256"></a><?php };
              if('NIKKEI_LC-86.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI_LC-86.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-L.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI_LC-156-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-R.jpg' == $roller[0]){?> <a href="img/buhin/roller/NIKKEI_LC-156-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg" width="256"></a><?php };
              if('SANKYO_BL002K.jpg' == $roller[0]){?> <a href="img/buhin/roller/SANKYO_BL002K.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K.jpg" width="256"></a><?php };
              if('TOSTEM.jpg' == $roller[0]){?> <a href="img/buhin/roller/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM.jpg" width="256"></a><?php };
              if('YKK_2K-17412.jpg' == $roller[0]){?> <a href="img/buhin/roller/YKK_2K-17412.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_2K-17412.jpg" width="256"></a><?php };
              if('YOKOZUNA_EKW-0002.jpg' == $roller[0]){?> <a href="img/buhin/roller/YOKOZUNA_EKW-0002.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg" width="256"></a><?php };
              if('YOKOZUNA_TBM-0281.jpg' == $roller[0]){?> <a href="img/buhin/roller/YOKOZUNA_TBM-0281.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg" width="256"></a><?php };
              
              
             if('8type.jpg' == $roller[1]){?> <a href="img/buhin/roller/8type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/8type.jpg" width="256"></a><?php };
             if('9type.jpg' == $roller[1]){?> <a href="img/buhin/roller/9type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/9type.jpg" width="256"></a><?php };
             if('12type.jpg' == $roller[1]){?> <a href="img/buhin/roller/12type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/12type.jpg" width="256"></a><?php };
             if('14type.jpg' == $roller[1]){?> <a href="img/buhin/roller/14type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/14type.jpg" width="256"></a><?php };             
             if('FUJI_TOPACE_FR3011-L.jpg' == $roller[1]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg" width="256"></a><?php };?>
             <?php if('FUJI_TOPACE_FR3011-R.jpg' == $roller[1]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_FR-70series-R00920NN.jpg' == $roller[1]){?> <a href="img/buhin/roller/FUJI_FR-70series-R00920NN.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-FR0033.jpg' == $roller[1]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-FR0033.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-R00320.jpg' == $roller[1]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-R00320.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-kosimado.jpg' == $roller[1]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-hakidasi.jpg' == $roller[1]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('KOMSTU.jpg' == $roller[1]){?> <a href="img/buhin/roller/KOMSTU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KOMSTU.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-hakidasi.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-kosimado.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_B30266A-LorR.jpg' == $roller[1]){?> <a href="img/buhin/roller/SANKYO_B30266A-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BF4520B-LorR.jpg' == $roller[1]){?> <a href="img/buhin/roller/SANKYO_BF4520B-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL001K-outside.jpg' == $roller[1]){?> <a href="img/buhin/roller/SANKYO_BL001K-outside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL002K-inside.jpg' == $roller[1]){?> <a href="img/buhin/roller/SANKYO_BL002K-inside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM_BHP-59.jpg' == $roller[1]){?> <a href="img/buhin/roller/TOSTEM_BHP-59.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-2K-7515A.jpg' == $roller[1]){?> <a href="img/buhin/roller/YKK_AP_HH-2K-7515A.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-12431_32.jpg' == $roller[1]){?> <a href="img/buhin/roller/YKK_AP_HH-K-12431_32.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-15157.jpg' == $roller[1]){?> <a href="img/buhin/roller/YKK_AP_HH-K-15157.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-T-0029.jpg' == $roller[1]){?> <a href="img/buhin/roller/YKK_AP_HH-T-0029.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg" width="256"></a><?php };
              if('KINKI_old.jpg' == $roller[1]){?> <a href="img/buhin/roller/KINKI_old.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_old.jpg" width="256"></a><?php };
              if('NIKKEI_LC-86.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI_LC-86.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-L.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI_LC-156-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-R.jpg' == $roller[1]){?> <a href="img/buhin/roller/NIKKEI_LC-156-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg" width="256"></a><?php };
              if('SANKYO_BL002K.jpg' == $roller[1]){?> <a href="img/buhin/roller/SANKYO_BL002K.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K.jpg" width="256"></a><?php };
              if('TOSTEM.jpg' == $roller[1]){?> <a href="img/buhin/roller/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM.jpg" width="256"></a><?php };
              if('YKK_2K-17412.jpg' == $roller[1]){?> <a href="img/buhin/roller/YKK_2K-17412.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_2K-17412.jpg" width="256"></a><?php };
              if('YOKOZUNA_EKW-0002.jpg' == $roller[1]){?> <a href="img/buhin/roller/YOKOZUNA_EKW-0002.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg" width="256"></a><?php };
              if('YOKOZUNA_TBM-0281.jpg' == $roller[1]){?> <a href="img/buhin/roller/YOKOZUNA_TBM-0281.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg" width="256"></a><?php };}?>
             
                        </td></tr></table>
                
             <?php if(!empty($kuresent2[0] ?? '') || ($roller2[1] ?? '')){?>
<?php if(!empty($kuresent2[0] ?? '') || ($kuresent2[1] ?? '')){?>                        
                    <table class="img"><tr><th>追加サッシ クレセント</th></tr>
                <tr><td><?php
                    if('CRE.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/CRE.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/CRE.jpg" width="256"></a><?php };
                    if('FUJI.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI.jpg" width="256"></a><?php };?>
             <?php if('FUJI2.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI2.jpg" width="256"></a><?php };?>
             <?php if('FUJI3.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI3.jpg" width="256"></a><?php };?>
             <?php if('FUJI4.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI4.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI4.jpg" width="256"></a><?php };?>
             <?php if('FUJI5.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI5.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI5.jpg" width="256"></a><?php };?>
             <?php if('FUJI6.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI6.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI6.jpg" width="256"></a><?php };?>
             <?php if('FUJI7.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI7.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI7.jpg" width="256"></a><?php };?>
             <?php if('FUJI8.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI8.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI8.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI_R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R-KEY.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI_R-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI_S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S-KEY.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI_S-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg" width="256"></a><?php };?>
             <?php if('KINKI_K-GOOD.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/KINKI_K-GOOD.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg" width="256"></a><?php };?>
             <?php if('MITUIKEIKINZOKU.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MITUIKEIKINZOKU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB-1.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MIWA_PB-1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-H.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MIWA_PB2-H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-S.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MIWA_PB2-S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM2.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/NAKANISI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI.jpg" width="256"></a><?php };?>
             <?php if('SANKYO.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SANKYO.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO.jpg" width="256"></a><?php };?>
             <?php if('SANKYO2.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SANKYO2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO2.jpg" width="256"></a><?php };?>
             <?php if('SANKYO3.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SANKYO3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO3.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SIBUTANI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI_KEY.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SIBUTANI_KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI3.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/SIBUTANI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI3.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM2.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('YKK1.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/YKK1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK1.jpg" width="256"></a><?php };?>
             <?php if('YKK2.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/YKK2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK2.jpg" width="256"></a><?php };?>
             <?php if('YKK3.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/YKK3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK3.jpg" width="256"></a><?php };
                    if('FUJI_haiban.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/FUJI_haiban.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_haiban.jpg" width="256"></a><?php };
                    if('KIYOMATU_SD-EK-2003H-20B.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" width="256"></a><?php };
                    if('MATUEI_AS-155H.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MATUEI_AS-155H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg" width="256"></a><?php };
                    if('MATUEI_LS-11.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/MATUEI_LS-11.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10757.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/YKK_HH-K-10757.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10759-132.jpg' == $kuresent2[0]){?> <a href="img/buhin/kuresent/YKK_HH-K-10759-132.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg" width="256"></a><?php };

                    if('CRE.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/CRE.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/CRE.jpg" width="256"></a><?php };
                    if('FUJI.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI.jpg" width="256"></a><?php };?>
             <?php if('FUJI2.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI2.jpg" width="256"></a><?php };?>
             <?php if('FUJI3.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI3.jpg" width="256"></a><?php };?>
             <?php if('FUJI4.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI4.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI4.jpg" width="256"></a><?php };?>
             <?php if('FUJI5.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI5.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI5.jpg" width="256"></a><?php };?>
             <?php if('FUJI6.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI6.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI6.jpg" width="256"></a><?php };?>
             <?php if('FUJI7.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI7.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI7.jpg" width="256"></a><?php };?>
             <?php if('FUJI8.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI8.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI8.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI_R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_R-KEY.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI_R-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI_S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S.jpg" width="256"></a><?php };?>
             <?php if('FUJI_S-KEY.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI_S-KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg" width="256"></a><?php };?>
             <?php if('KINKI_K-GOOD.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/KINKI_K-GOOD.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg" width="256"></a><?php };?>
             <?php if('MITUIKEIKINZOKU.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MITUIKEIKINZOKU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB-1.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MIWA_PB-1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-H.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MIWA_PB2-H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg" width="256"></a><?php };?>
             <?php if('MIWA_PB2-S.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MIWA_PB2-S.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI TOSTEM2.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/NAKANISI TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('NAKANISI.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/NAKANISI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/NAKANISI.jpg" width="256"></a><?php };?>
             <?php if('SANKYO.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SANKYO.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO.jpg" width="256"></a><?php };?>
             <?php if('SANKYO2.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SANKYO2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO2.jpg" width="256"></a><?php };?>
             <?php if('SANKYO3.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SANKYO3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SANKYO3.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SIBUTANI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI_KEY.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SIBUTANI_KEY.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg" width="256"></a><?php };?>
             <?php if('SIBUTANI3.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/SIBUTANI3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/SIBUTANI3.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM2.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/TOSTEM2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/TOSTEM2.jpg" width="256"></a><?php };?>
             <?php if('YKK1.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/YKK1.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK1.jpg" width="256"></a><?php };?>
             <?php if('YKK2.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/YKK2.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK2.jpg" width="256"></a><?php };?>
             <?php if('YKK3.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/YKK3.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK3.jpg" width="256"></a><?php };
                    if('FUJI_haiban.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/FUJI_haiban.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/FUJI_haiban.jpg" width="256"></a><?php };
                    if('KIYOMATU_SD-EK-2003H-20B.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg" width="256"></a><?php };
                    if('MATUEI_AS-155H.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MATUEI_AS-155H.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg" width="256"></a><?php };
                    if('MATUEI_LS-11.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/MATUEI_LS-11.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10757.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/YKK_HH-K-10757.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg" width="256"></a><?php };
                    if('YKK_HH-K-10759-132.jpg' == $kuresent2[1]){?> <a href="img/buhin/kuresent/YKK_HH-K-10759-132.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg" width="256"></a><?php };
                    }?> 
                    </td></tr></table>
                        
<?php if(!empty($roller2[0] || $roller2[1])){?>                
                <table class="img"><tr><th>追加サッシ 戸車</th></tr>
                    <tr><td><?php
                
              if('8type.jpg' == $roller2[0]){?> <a href="img/buhin/roller/8type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/8type.jpg" width="256"></a><?php };
              if('9type.jpg' == $roller2[0]){?> <a href="img/buhin/roller/9type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/9type.jpg" width="256"></a><?php };
              if('12type.jpg' == $roller2[0]){?> <a href="img/buhin/roller/12type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/12type.jpg" width="256"></a><?php };
              if('14type.jpg' == $roller2[0]){?> <a href="img/buhin/roller/14type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/14type.jpg" width="256"></a><?php };
              if('FUJI_TOPACE_FR3011-L.jpg' == $roller2[0]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg" width="256"></a><?php };
              if('FUJI_TOPACE_FR3011-R.jpg' == $roller2[0]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg" width="256"></a><?php };
              if('FUJI_FR-70series-R00920NN.jpg' == $roller2[0]){?> <a href="img/buhin/roller/FUJI_FR-70series-R00920NN.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-FR0033.jpg' == $roller2[0]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-FR0033.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-R00320.jpg' == $roller2[0]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-R00320.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-kosimado.jpg' == $roller2[0]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-hakidasi.jpg' == $roller2[0]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('KOMSTU.jpg' == $roller2[0]){?> <a href="img/buhin/roller/KOMSTU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KOMSTU.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-hakidasi.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-kosimado.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_B30266A-LorR.jpg' == $roller2[0]){?> <a href="img/buhin/roller/SANKYO_B30266A-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BF4520B-LorR.jpg' == $roller2[0]){?> <a href="img/buhin/roller/SANKYO_BF4520B-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL001K-outside.jpg' == $roller2[0]){?> <a href="img/buhin/roller/SANKYO_BL001K-outside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL002K-inside.jpg' == $roller2[0]){?> <a href="img/buhin/roller/SANKYO_BL002K-inside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM_BHP-59.jpg' == $roller2[0]){?> <a href="img/buhin/roller/TOSTEM_BHP-59.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-2K-7515A.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YKK_AP_HH-2K-7515A.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-12431_32.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YKK_AP_HH-K-12431_32.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-15157.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YKK_AP_HH-K-15157.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-T-0029.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YKK_AP_HH-T-0029.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg" width="256"></a><?php };
              if('KINKI_old.jpg' == $roller2[0]){?> <a href="img/buhin/roller/KINKI_old.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_old.jpg" width="256"></a><?php };
              if('NIKKEI_LC-86.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI_LC-86.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-L.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI_LC-156-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-R.jpg' == $roller2[0]){?> <a href="img/buhin/roller/NIKKEI_LC-156-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg" width="256"></a><?php };
              if('SANKYO_BL002K.jpg' == $roller2[0]){?> <a href="img/buhin/roller/SANKYO_BL002K.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K.jpg" width="256"></a><?php };
              if('TOSTEM.jpg' == $roller2[0]){?> <a href="img/buhin/roller/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM.jpg" width="256"></a><?php };
              if('YKK_2K-17412.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YKK_2K-17412.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_2K-17412.jpg" width="256"></a><?php };
              if('YOKOZUNA_EKW-0002.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YOKOZUNA_EKW-0002.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg" width="256"></a><?php };
              if('YOKOZUNA_TBM-0281.jpg' == $roller2[0]){?> <a href="img/buhin/roller/YOKOZUNA_TBM-0281.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg" width="256"></a><?php };
              
              
             if('8type.jpg' == $roller2[1]){?> <a href="img/buhin/roller/8type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/8type.jpg" width="256"></a><?php };
             if('9type.jpg' == $roller2[1]){?> <a href="img/buhin/roller/9type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/9type.jpg" width="256"></a><?php };
             if('12type.jpg' == $roller2[1]){?> <a href="img/buhin/roller/12type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/12type.jpg" width="256"></a><?php };
             if('14type.jpg' == $roller2[1]){?> <a href="img/buhin/roller/14type.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/14type.jpg" width="256"></a><?php };             
             if('FUJI_TOPACE_FR3011-L.jpg' == $roller2[1]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg" width="256"></a><?php };?>
             <?php if('FUJI_TOPACE_FR3011-R.jpg' == $roller2[1]){?> <a href="img/buhin/roller/FUJI_TOPACE_FR3011-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg" width="256"></a><?php };?>
             <?php if('FUJI_FR-70series-R00920NN.jpg' == $roller2[1]){?> <a href="img/buhin/roller/FUJI_FR-70series-R00920NN.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-FR0033.jpg' == $roller2[1]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-FR0033.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg" width="256"></a><?php };?>
             <?php if('FUJI_KJ-Btype-R00320.jpg' == $roller2[1]){?> <a href="img/buhin/roller/FUJI_KJ-Btype-R00320.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-kosimado.jpg' == $roller2[1]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('KINKI_KJ-Btype-hakidasi.jpg' == $roller2[1]){?> <a href="img/buhin/roller/KINKI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('KOMSTU.jpg' == $roller2[1]){?> <a href="img/buhin/roller/KOMSTU.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KOMSTU.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-hakidasi.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-hakidasi.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg" width="256"></a><?php };?>
             <?php if('NIKKEI_KJ-Btype-kosimado.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI_KJ-Btype-kosimado.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_B30266A-LorR.jpg' == $roller2[1]){?> <a href="img/buhin/roller/SANKYO_B30266A-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BF4520B-LorR.jpg' == $roller2[1]){?> <a href="img/buhin/roller/SANKYO_BF4520B-LorR.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL001K-outside.jpg' == $roller2[1]){?> <a href="img/buhin/roller/SANKYO_BL001K-outside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg" width="256"></a><?php };?>
             <?php if('SANKYO_BL002K-inside.jpg' == $roller2[1]){?> <a href="img/buhin/roller/SANKYO_BL002K-inside.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg" width="256"></a><?php };?>
             <?php if('TOSTEM_BHP-59.jpg' == $roller2[1]){?> <a href="img/buhin/roller/TOSTEM_BHP-59.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-2K-7515A.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YKK_AP_HH-2K-7515A.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-12431_32.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YKK_AP_HH-K-12431_32.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-K-15157.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YKK_AP_HH-K-15157.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg" width="256"></a><?php };?>
             <?php if('YKK_AP_HH-T-0029.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YKK_AP_HH-T-0029.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg" width="256"></a><?php };
              if('KINKI_old.jpg' == $roller2[1]){?> <a href="img/buhin/roller/KINKI_old.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/KINKI_old.jpg" width="256"></a><?php };
              if('NIKKEI_LC-86.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI_LC-86.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-L.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI_LC-156-L.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg" width="256"></a><?php };
              if('NIKKEI_LC-156-R.jpg' == $roller2[1]){?> <a href="img/buhin/roller/NIKKEI_LC-156-R.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg" width="256"></a><?php };
              if('SANKYO_BL002K.jpg' == $roller2[1]){?> <a href="img/buhin/roller/SANKYO_BL002K.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/SANKYO_BL002K.jpg" width="256"></a><?php };
              if('TOSTEM.jpg' == $roller2[1]){?> <a href="img/buhin/roller/TOSTEM.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/TOSTEM.jpg" width="256"></a><?php };
              if('YKK_2K-17412.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YKK_2K-17412.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YKK_2K-17412.jpg" width="256"></a><?php };
              if('YOKOZUNA_EKW-0002.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YOKOZUNA_EKW-0002.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg" width="256"></a><?php };
              if('YOKOZUNA_TBM-0281.jpg' == $roller2[1]){?> <a href="img/buhin/roller/YOKOZUNA_TBM-0281.jpg" data-lightbox="buhin"><img src="img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg" width="256"></a><?php };}?>

                        </td></tr>
                    </table>
             <?php }?>

                <div class="img2box">
<?php if(!empty($row['small'])){?>
                <table class="img2"><tr><th>小窓金具</th></tr>
                <tr><td><?php

    if('FUJI_prastic.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('K-GOOD_steel.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('SANKYO1.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('SANKYO2.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('FUJI_sw.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };    
    if('YKK_HH-K-7307A.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('YKK_K-35167.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('LIXILpro_sw.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };
    if('newYKK_sw.jpg' == $row['small']){?> <a href="img/buhin/small/<?php print $row['small'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/small/<?php print $row['small'];?>" width="256"></a><?php };}?>

                    </td></tr></table>

<?php if(!empty($row['down'])){?>
                <table class="img2"><tr><th>内倒し金具</th></tr>
                <tr><td><?php
    if('K-GOOD_outside.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('YKK_AP.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('YKK_outside.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI_outsid.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('FUJI_KJ-B.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('K-GOOD.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI2.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI3.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI4.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };
    if('NAKANISHI5.jpg' == $row['down']){?> <a href="img/buhin/down/<?php print $row['down'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/down/<?php print $row['down'];?>" width="256"></a><?php };}?>

                    </td></tr></table>

    <?php if(!empty($row['folding'])){?>
                <table class="img2"><tr><th>中折れ金具</th></tr>
                    <tr><td><?php
    if('newYKK.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('newYKK_gray.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('newYKK_ivory.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('YKK3_gray.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('YKK3_ivory.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };    
    if('TOSTEM.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('HITACHI.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('oldYKK.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('topTOSTEM.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('YKK3.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('YKK4.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('SANKYO.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };
    if('SANKYO2.jpg' == $row['folding']){?> <a href="img/buhin/folding/<?php print $row['folding'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/folding/<?php print $row['folding'];?>" width="256"></a><?php };}?>
    
                    </td></tr></table>

<?php if(!empty($row['handle'])){?>                    
                <table class="img2"><tr><th>平面ハンドル</th></tr>
                    <tr><td><?php
    if('ACE.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('PRINCE_S.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('PRINCE_M.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('PRINCE_L.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('SUEHIRO.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('SUEHIRO2.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('SUEHIRO3.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('TAKIGEN.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };}

    //久しぶり
    if('T_type.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('GOAL.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };
    if('TAKIGEN_stan.jpg' == $row['handle']){?> <a href="img/buhin/handle/<?php print $row['handle'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle'];?>" width="256"></a><?php };?>
                    </td></tr></table>

<?php if(!empty($row['handle2'])){?>
                <table class="img2"><tr><th>平面ハンドル2</th></tr>
                    <tr><td>
<?php
    if('ACE.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('PRINCE_S.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('PRINCE_M.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('PRINCE_L.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('SUEHIRO.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('SUEHIRO2.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('SUEHIRO3.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('TAKIGEN.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };

    //久しぶり
    if('T-type.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('GOAL.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    if('TAKIGEN_stan.jpg' == $row['handle2']){?> <a href="img/buhin/handle/<?php print $row['handle2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/handle/<?php print $row['handle2'];?>" width="256"></a><?php };
    }?>
                    </td></tr></table>
                    
<?php if(!empty($row['dialimg'])){?>
                    <table class="img2"><tr><th>ダイヤル錠</th></tr>
                    <tr><td><?php
    if('KOWASONIA.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('KYOWANASTA1.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('KYOWANASTA2.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('MIWA_ODS1.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('MIWA_ODS2.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('NAKAKOGYO.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('NASTA_SPK8.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('TAZIMA.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('POSTE.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };
    if('TAJIMA_side.jpg' == $row['dialimg']){?> <a href="img/buhin/dial/<?php print $row['dialimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/dial/<?php print $row['dialimg'];?>" width="256"></a><?php };?>
                    </td></tr></table><?php }


if(!empty($row['bathkama'])){?>
                <table class="img2"><tr><th>浴室鎌錠</th></tr>
                    <tr><td><?php
    if('bath_kama.jpg' == $row['bathkama']){?> <a href="img/buhin/bathkama/<?php print $row['bathkama'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/bathkama/<?php print $row['bathkama'];?>" width="256"></a><?php };}?>
                    </td></tr></table>

<?php

if(!empty($row['toiletkama'])){?>
                <table class="img2"><tr><th>トイレ鎌錠</th></tr>
                    <tr><td><?php
    if('atom_kama.jpg' == $row['toiletkama']){?> <a href="img/buhin/toiletkama/<?php print $row['toiletkama'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/toiletkama/<?php print $row['toiletkama'];?>" width="256"></a><?php };
    if('best_kama.jpg' == $row['toiletkama']){?> <a href="img/buhin/toiletkama/<?php print $row['toiletkama'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/toiletkama/<?php print $row['toiletkama'];?>" width="256"></a><?php };
    if('MIWA_kama.jpg' == $row['toiletkama']){?> <a href="img/buhin/toiletkama/<?php print $row['toiletkama'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/toiletkama/<?php print $row['toiletkama'];?>" width="256"></a><?php };}?>
                    </td></tr></table>

<?php

if(!empty($row['otherimg'])){?>
                <table class="img2"><tr><th>その他部品</th></tr>
                    <tr><td><?php
    if('TAKIGEN_key.jpg' == $row['otherimg']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg'];?>" width="256"></a><?php };
    if('OLD_YKK.jpg' == $row['otherimg']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg'];?>" width="256"></a><?php };
    if('new_YKK.jpg' == $row['otherimg']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg'];?>" width="256"></a><?php };
    if('sand.jpg' == $row['otherimg']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg'];?>" width="256"></a><?php };}?>
                    </td></tr></table>

<?php if(!empty($row['otherimg2'])){?>
                <table class="img2"><tr><th>その他部品2</th></tr>
                    <tr><td>
<?php
    if('TAKIGEN_key.jpg' == $row['otherimg2']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg2'];?>" width="256"></a><?php };
    if('OLD_YKK.jpg' == $row['otherimg2']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg2'];?>" width="256"></a><?php };
    if('new_YKK.jpg' == $row['otherimg2']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg2'];?>" width="256"></a><?php };}
    if('sand.jpg' == $row['otherimg2']){?> <a href="img/buhin/otherimg/<?php print $row['otherimg2'];?>" data-lightbox="buhin"><img src="img/buhin/thumbnail/otherimg/<?php print $row['otherimg2'];?>" width="256"></a><?php };?>
                    </td></tr></table>
<?php

// if(!empty($row['material'] || $arraydel)){
if(!empty($row['frame'] || $arraydel)){
    ?>
                <table class="img2"><tr><th>下枠寸法</th></tr>
                    <tr><td>
<?php if(!empty($row['frame'])){
    if('1.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('2.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('3.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('4.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('5.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('6.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('7.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('8.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('9.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('10.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('11.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('12.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('13.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('14.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('15.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    if('framecs.jpg' == $row['frame']){?> <a href="img/buhin/frame/<?php print $row['frame'];?>" data-lightbox="buhin"><img src="img/buhin/frame/<?php print $row['frame'];?>" width="256"></a><?php };
    }
    //explode分割した配列に値が定義されている場合に表示
    if(!empty($arraydel2)){
        ?>
    <br>&nbsp;&nbsp;&nbsp;<strong>A</strong>&nbsp;=<?php print $framesize[0];?>&nbsp;&nbsp;&nbsp;<strong>B</strong>&nbsp;=<?php print $framesize[1];?>
    &nbsp;&nbsp;&nbsp;<strong>C</strong>&nbsp;=<?php print $framesize[2];
    }
    //explode分割した配列に値が定義されている場合に表示
    if(!empty($arraydel)){
        ?><a href="img/buhin/frame/stan.jpg" data-lightbox="buhin"><img src="img/buhin/frame/stan.jpg" width="256"></a><br>
    &nbsp;&nbsp;&nbsp;<strong>A</strong>&nbsp;=<?php print $stan[0];?>&nbsp;&nbsp;&nbsp;<strong>B</strong>&nbsp;=<?php print $stan[1];?>
    &nbsp;&nbsp;&nbsp;<strong>C</strong>&nbsp;=<?php print $stan[2];?>&nbsp;&nbsp;&nbsp;<strong>D</strong>&nbsp;=<?php print $stan[3];
    }?>
    </td></tr></table>
                </div>
<?php }


//アップロード画像部品毎にカウントする。$cntに配列でカウントを代入している(partsregist.phpでも同コメント)
//第1引数で合計をカウントしている
$sql6= $pdo->prepare("SELECT partsimg.codeno, COUNT(*) AS total, 
 SUM(CASE WHEN partsimg.nametype='other' THEN 1 ELSE 0 END) AS cnt_other,
 SUM(CASE WHEN partsimg.nametype='kuresent' THEN 1 ELSE 0 END) AS cnt_kuresent,
 SUM(CASE WHEN partsimg.nametype='roller' THEN 1 ELSE 0 END) AS cnt_roller,
 SUM(CASE WHEN partsimg.nametype='down' THEN 1 ELSE 0 END) AS cnt_down,
 SUM(CASE WHEN partsimg.nametype='folding' THEN 1 ELSE 0 END) AS cnt_folding,
 SUM(CASE WHEN partsimg.nametype='handle' THEN 1 ELSE 0 END) AS cnt_handle,
 SUM(CASE WHEN partsimg.nametype='small' THEN 1 ELSE 0 END) AS cnt_small,
 SUM(CASE WHEN partsimg.nametype='keylock' THEN 1 ELSE 0 END) AS cnt_keylock
FROM partsimg where partsimg.codeno = '$codeno'
GROUP BY partsimg.codeno");
$sql6->execute();
$cnt= $sql6->fetch(PDO::FETCH_ASSOC);

$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt2= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt3= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt4= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt5= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt6= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt7= $pdo->query($sql5);
$sql5= "SELECT * FROM partsimg WHERE codeno='$codeno'";
$stmt8= $pdo->query($sql5);
//アップロード画像を出力する。
      if(!empty($cnt['cnt_other'])){?>
<div id="upbox">
            <table class="upimg">
                <tr><th>その他(投稿画像)</th></tr>
                <tr><td>
<?php
foreach($stmt as $row){
if($row['nametype'] == 'other'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/other/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/other/<?php echo $row['img'];?>"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?>
                    </td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_kuresent'])){?>
            <table class="upimg">
                <tr><th>クレセント(投稿画像)</th></tr>
                <tr><td><?php
foreach($stmt2 as $row){
if($row['nametype'] == 'kuresent'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/kuresent/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/kuresent/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_roller'])){?>
            <table class="upimg">
                <tr><th>戸車(投稿画像)</th></tr>
                <tr><td>
<?php
foreach($stmt3 as $row){
if($row['nametype'] == 'roller'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/roller/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/roller/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_small'])){?>
            <table class="upimg">
                <tr><th>小窓金具(投稿画像)</th></tr>
                <tr><td><?php
foreach($stmt4 as $row){
if($row['nametype'] == 'small'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/small/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/small/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_down'])){?>
            <table class="upimg">
                <tr><th>内倒し金具(投稿画像)</th></tr>
                <tr><td><?php
foreach($stmt5 as $row){
if($row['nametype'] == 'down'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/down/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/down/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_folding'])){?>
            <table class="upimg">
                <tr><th>中折れ金具(投稿画像)</th></tr>
                <tr><td>
<?php
foreach($stmt6 as $row){
if($row['nametype'] == 'folding'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/folding/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/folding/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>
<?php }

      if(!empty($cnt['cnt_handle'])){?>
            <table class="upimg">
                <tr><th>平面ハンドル(投稿画像)</th></tr>
                <tr><td>
<?php
foreach($stmt7 as $row){
if($row['nametype'] == 'handle'){?>
                        <div class="innerimg">
                            <a href="img/partsregist/handle/<?php echo $row['img'];?>" data-lightbox="buhin" data-title="<?php echo $row['comment'];?>"><img src="img/partsregist/handle/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php echo nl2br(htmlspecialchars($row['comment']));?></span>
                        </div>
<?php }}?></td></tr>
            </table>

<?php }

      if(!empty($cnt['cnt_keylock'])){?>
            <table class="upimg">
                <tr><th>錠前(投稿画像)</th></tr>
                <tr><td>
<?php
foreach($stmt8 as $row){
if($row['nametype'] == 'keylock'){
    $keyarray= explode(',',$row['comment'] ?? '');?>
                        <div class="innerimg">
                            <a href="img/partsregist/keylock/<?php echo $row['img'];?>" data-lightbox="buhin"><img src="img/partsregist/keylock/<?php echo $row['img'];?>" width="256"></a>
                            <span><?php if(!empty($keyarray[0])){echo '型名：'.htmlspecialchars($keyarray[0]);}if(!empty($keyarray[1])){echo '<br>BS：'.htmlspecialchars($keyarray[1]).'mm';}if(!empty($keyarray[2])){echo ' 扉厚：'.htmlspecialchars($keyarray[2]).'mm';}?></span>
                        </div>
<?php }}?></td></tr>
            </table>

<?php }?>

           <input type='hidden' name='code' value='<?php print $code;?>'>
           <input type='hidden' name='address' value='<?php print $address;?>'>
           <input type='hidden' name='codeno' value='<?php print $codeno;?>'>
           <input type='hidden' name='goutou' value='<?php print $goutou;?>'>
　　　　　　<input type='hidden' name='syubetu' value='<?php print $syubetu;?>'>
           <input type='hidden' name='name' value='<?php print $name;?>'>
           <input type='hidden' name='kuresent' value='<?php print $kuresent?>'>
           <input type='hidden' name='goutouvar' value='<?php print $goutouvar;?>'>
           <input type='hidden' name='date' value='<?php echo $date;?>'>
           <input class="submit" type='submit' value='部品編集'>
           </form>

        </div>
    </div>
<?php }
  $pdo= NULL;
}?>
    <div id="footer">
        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
    </div>
<script src="jquery/Lightbox/js/lightbox.min.js"></script>
</body>
</html>