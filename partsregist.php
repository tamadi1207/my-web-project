<?php
require './db_info.php';
require './cookie.php';
$path= './';

$userid = $_COOKIE['ID'];
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : NULL;
$codeno = isset($_GET['codeno']) ? htmlspecialchars($_GET['codeno']) : NULL;
$goutou = isset($_GET['goutou']) ? htmlspecialchars($_GET['goutou']) : NULL;
$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : NULL;
$syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : NULL;
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : NULL;
$goutouvar = isset($_GET['goutouvar']) ? htmlspecialchars($_GET['goutouvar']) : NULL;
$comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : NULL;
$comment2 = isset($_POST['comment2']) ? htmlspecialchars($_POST['comment2']) : NULL;
$comment3 = isset($_POST['comment3']) ? htmlspecialchars($_POST['comment3']) : NULL;
$del = isset($_POST['del']) ? htmlspecialchars($_POST['del']) : NULL;
$nametype = isset($_POST['nametype']) ? htmlspecialchars($_POST['nametype']) : NULL;
$imgname = isset($_POST['imgname']) ? htmlspecialchars($_POST['imgname']) : NULL;
$result = isset($_GET['result']) ? htmlspecialchars($_GET['result']) : NULL;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //画像を削除
    if(isset($del)){
        $result=  '画像を削除しました。';
        $sql5= $pdo->prepare("DELETE FROM partsimg WHERE img = '$imgname'") or die ("失敗");
        $sql5->execute();
        //(./img/partsregist/hoge.jpg)等ディレクトリを指定してファイルを削除する
        unlink("./img/partsregist/$nametype/$imgname");
    }else
    //画像にコメント
      if(isset($comment)){
        //錠前画像投稿の(型名、BS、D)コメントを取得して結合する
        if(isset($comment2) || isset($comment3)){
            $keycmt= array($comment,$comment2,$comment3);
            $comment= implode(',',$keycmt);
        }
        $result=  'コメント登録しました。';
        $sql4= $pdo->prepare("UPDATE partsimg SET comment = '$comment' WHERE img = '$imgname'") or die ("失敗");
        $sql4->execute();

    }else
    //画像をアップロード
      if(isset($_FILES['upload'])){
        $result=  '  投稿完了しました。';
          require './require/uploadimg.php';
        $sql3= $pdo->prepare("INSERT INTO partsimg (code,codeno,goutou,nametype,type,user,img,comment,datetime) VALUES('$code','$codeno','$goutou','$nametype','$type','$userid','$img','$comment',now())") or die ("失敗");
        $sql3->execute();
        //building.phpの日付を更新するだけのsql
        $sql3= $pdo->prepare("UPDATE $goutb SET hiduke = cast( now() as date ) , user = '$userid' , datetime= now() WHERE codeno='$codeno'") or die ("失敗");
        $sql3->execute();
        }
        header("Location: ./partsregist.php?code=$code&codeno=$codeno&goutou=$goutou"
                . "&name=$name&address=$address&syubetu=$syubetu&goutouvar=$goutouvar&result=$result#result");
}
// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="./css/style.css?a" rel="stylesheet" media="all">
<link href="./css/buhintoroku.css?am" rel="stylesheet" media="all">
<link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/vnd.microsoft.icon" href="./img/html/builicon2.ico">
<link rel="stylesheet" href="./jquery/imageselect/imageselect.css" type="text/css">
<link rel="stylesheet" href="jquery/Lightbox/css/lightbox.css">
<script src="./jquery/jquerybody/jquery-2.2.0.js"></script>
<script type="text/javascript" src="./jquery/imageselect/imageselect.js"></script> 
<script type="text/javascript" src="./jquery/footerFixed/footerFixed.js"></script>
<!-- ハンバーガー機能 -->
<script src="./jquery/hammenu/jquery.min.js"></script>
<script src="./jquery/hammenu/iscroll.js"></script>
<link rel="stylesheet" href="./jquery/hammenu/drawer.min.css">
<script src="./jquery/hammenu/drawer.min.js"></script>
<!--END-->




<!-- 部品を画像表示させて選択する関数(ImageSelect) -->
<!-- geminiが短いコードにした -->
<script>
$(function() {
$('select[name=kuresent], select[name=kuresent2], select[name=roller], select[name=roller2], select[name=kuresent3], select[name=kuresent4], select[name=roller3], select[name=roller4], select[name=small], select[name=down], select[name=folding], select[name=handle], select[name=handle2], select[name=dialimg], select[name=otherimg], select[name=otherimg2], select[name=bathkama], select[name=toiletkama]').ImageSelect({
    width: 250,
    height: 150,
    dropdownWidth: 680,
    dropdownHeight: 630
});
});
</script>




<!-- ラジオボタンを、チェックボックスのように『選択解除可能』にするための関数 -->
<!-- geminiが短いコードにした -->
<script>
$(function(){
  const names = [
    'glasstype','pushtype','glasstype2','pushtype2','glasstype3','pushtype3',
    'toumei','glass','scope','news','kakou',
    'cylinder','cylinder2','small','setpost','bathroom'
  ];

  names.forEach(function(name){
    let nowchecked = $(`input[name=${name}]:checked`).val();
    $(`input[name=${name}]`).on('click', function(){
      if ($(this).val() === nowchecked) {
        $(this).prop('checked', false);
        nowchecked = false;
      } else {
        nowchecked = $(this).val();
      }
    });
  });
});
</script>




<title>部品登録</title>
        </head>
        <body class="drawer drawer--left">

        <div id="contener">
                <div id="header">
                    <a href="./index.php"><img src="./img/html/logo.png" alt="rogo" id="rogoimg"></a>
                    <div id="search">
                        <form name="searchform2" id="searchform2" method="POST" action="list.php">  
                            <input name="name" id="keywords2" type="text" placeholder="団地を検索">
                            <input type="hidden" name="page" value="0">
                            <input type="image" src="./img/html/btn.gif" alt="検索" id="searchBtn2" />
                        </form>
                    </div>
                </div>

<!-- ハンバーガーボタンを非表示にしてdisplay: inline;でメニューを横並び表示 -->
    <ul id="menu">
        <li><a href="./index.php">TOP</a></li>
                <li>
                    <a href="./history.php">閲覧履歴</a>
                </li>
                <li>
                    <a class="disable">棟編集<i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="child">
                        <li><a href='./touhensyu/touhensyu.php?code=<?php print $_GET['code'];?>&codeno=<?php print $_GET['codeno'];?>&syubetu=<?php print $_GET['syubetu'];?>&name=<?php print $_GET['name'];?>&goutou=<?php print $_GET['goutou'];?>&goutouvar=<?php print $_GET['goutouvar'];?>'>棟No変更</a></li>
                        <li><a href='./touhensyu/tousakuzyo.php?code=<?php print $_GET['code'];?>&codeno=<?php print $_GET['codeno'];?>&syubetu=<?php print $_GET['syubetu'];?>&name=<?php print $_GET['name'];?>&goutou=<?php print $_GET['goutou'];?>&goutouvar=<?php print $_GET['goutouvar'];?>'>棟削除</a></li>
                    </ul>
                </li>
        <li><a class="disable"><?= htmlspecialchars($_COOKIE["ID"], ENT_QUOTES); ?><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
            <ul class="child">
                <li><a href="./login/logout.php">ログアウト</a></li>
            </ul>
        </li>
    </ul>
<!-- END -->

<script src="./jquery/dropdownmenu/dropdown.js"></script>

<!-- ハンバーガーボタンを表示(レスポンシブ用) -->
<button type="button" class="drawer-toggle drawer-hamburger">
  <span class="sr-only">toggle navigation</span>
  <span class="drawer-hamburger-icon"></span>
</button>
            <nav class="drawer-nav">
                <ul class="drawer-menu">
                    <li><i class="fa fa-user-o" aria-hidden="true"></i><span><?= htmlspecialchars($_COOKIE["ID"], ENT_QUOTES); ?></span></li>
                    <li><a href="./index.php"><span>TOP</span></a></li>
                    <li><a href="/history.php#clickhistory"><i class="fa fa-file-text-o" aria-hidden="true"></i><span>部品登録履歴</span></a>
                        <a href="./history.php#partshistory"><i class="fa fa-mouse-pointer" aria-hidden="true"></i><span>団地Click履歴</span></a>
                    </li>
                    <li><a href='./touhensyu/touhensyu.php?code=<?php print $_GET['code'];?>&codeno=<?php print $_GET['codeno'];?>&syubetu=<?php print $_GET['syubetu'];?>&name=<?php print $_GET['name'];?>&goutou=<?php print $_GET['goutou'];?>&goutouvar=<?php print $_GET['goutouvar'];?>'><span>棟No変更</span></a>
                        <a href='./touhensyu/tousakuzyo.php?code=<?php print $_GET['code'];?>&codeno=<?php print $_GET['codeno'];?>&syubetu=<?php print $_GET['syubetu'];?>&name=<?php print $_GET['name'];?>&goutou=<?php print $_GET['goutou'];?>&goutouvar=<?php print $_GET['goutouvar'];?>'><span>棟削除</span></a></li>
                    <li><a href="./login/logout.php"><span>ログアウト</span></a></li>
                </ul>
            </nav>
<!-- END -->

<?php 
//カテゴリーメニューを非表示(index.phpとlist.phpのみ)
if(!isset($category)){ 
//
?>
    <div class="category">
        <span><a href="<?php print $path;?>index.php">TOP</a> > </span>

        <form name='Form' method="POST" action="<?php print $path;?>list.php">
            <input type="hidden" name="toei" value="<?php print $_SESSION['toei'];?>">
            <input type="hidden" name="kosya" value="<?php print $_SESSION['kosya'];?>">
            <input type="hidden" name="tomin" value="<?php print $_SESSION['tomin'];?>">
            <input type="hidden" name="kuei" value="<?php print $_SESSION['kuei'];?>">
            <input type="hidden" name="other" value="<?php print $_SESSION['other'];?>">
            <input type="hidden" name="name" value="<?php print $_SESSION['name'];?>">
            <input type="hidden" name="jusyo" value="<?php print $_SESSION['jusyo'];?>">
            <input type="hidden" name="page" value="0">
            <span><a href="javascript:Form.submit()">検索結果</a> > </span>
        </form>

        <span><a href='<?php print $path;?>building.php?syubetu=<?php print $_GET['syubetu'];?>&name=<?php print $_GET['name'];?>&address=<?php print $_GET['address'];?>&code=<?php print $_GET['code'];?>'>
            <?php print $_GET['name'];?></a></span>
        <?php if(isset($_GET['goutou'])){
                   print "<span> >&nbsp&nbsp&nbsp{$_GET['goutou']}{$_GET['goutouvar']}号棟</span>";
}
    ?>
    </div>
<?php }?>

<div class="registbox">
            <h2><?php print $syubetu;?>&nbsp;
                <span class="danchiname"><a href='./building.php?syubetu=<?php print $syubetu;?>&name=<?php print $name;?>&address=<?php print $address;?>&code=<?php print $code;
                ?>'><?php print $name;?></a>&nbsp;<span class="strong"><?php if(!empty($goutou)){print $goutou;}else{print $goutouvar;}?>号棟</span></span>&nbsp;&nbsp;&nbsp;
                <label><?php print $address; ?>&nbsp;&nbsp;<a href='./mapjump.php?code=<?php print htmlspecialchars($code);?>&name=<?php print htmlspecialchars($name);?>&address=<?php print $address;?>'>地図</a></label>
            </h2>

            <div class="entry">
                <form method="POST" action="./parts.php?code=<?php echo "$code";?>&codeno=<?php echo "$codeno";?>&goutou=<?php echo "$goutou";?>&goutouvar=<?php echo "$goutouvar";?>&address=<?php echo "$address";?>&syubetu=<?php echo "$syubetu";?>&name=<?php echo "$name";?>">
                    <input class="submit" type="submit" value="部品登録">
<?php

$sql = $pdo->prepare("SELECT * FROM $goutb WHERE codeno='$codeno'") or die ("失敗");
$sql->execute();

while ($kekka= $sql->fetch())
{
$kuresent= explode(",", $kekka['kuresent'] ?? '');
$roller= explode(",", $kekka['roller'] ?? '');
$kuresent2= explode(",", $kekka['kuresent2'] ?? '');
$roller2= explode(",", $kekka['roller2'] ?? '');
    //分割(explode)
$swsize= explode(",", $kekka['swsize'] ?? '');
$swsize2= explode(",", $kekka['swsize2'] ?? '');
$swsize3= explode(",", $kekka['swsize3'] ?? '');
$splitnews= explode(",", $kekka['news'] ?? '');
$toiletcmt= explode(",", $kekka['toiletcmt'] ?? '');
$woodcmt= explode(",", $kekka['woodcmt'] ?? '');
$framesize= explode(",", $kekka['framesize'] ?? '');
$stansize= explode(",", $kekka['stansize'] ?? '');

$ami= array_pad(explode("　", $kekka['ami'] ?? ''), 4, null);
?>
<table class="t-line">

<script>
function entryChange2(){
if(document.getElementById('changeSelect')){
id = document.getElementById('changeSelect').value;
 
if(id == 'その他'){
//フォーム
document.getElementById('firstBox').style.display = "";
}else{
document.getElementById('firstBox').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange2;
</script>

    <tr><th>サッシメーカー</th>
        <td colspan="2">
            <select class="select" name="sash" id="changeSelect" onchange="entryChange2();">
                <option value="">---◇ サッシメーカー ◇---</option>
                <option value="YKK" <?php if($kekka['sash'] == 'YKK'){print 'selected';};?>>YKK</option>
                <option value="不二サッシ" <?php if($kekka['sash'] == '不二サッシ'){print 'selected';};?>>不二サッシ</option>
                <option value="トステム" <?php if($kekka['sash'] == 'トステム'){print 'selected';};?>>トステム</option>
                <option value="日軽サッシ" <?php if($kekka['sash'] == '日軽サッシ'){print 'selected';};?>>日軽サッシ</option>
                <option value="三井軽金属" <?php if($kekka['sash'] == '三井軽金属'){print 'selected';};?>>三井軽金属</option>
                <option value="キンキ" <?php if($kekka['sash'] == 'キンキ'){print 'selected';};?>>キンキ</option>
                <option value="三協アルミ" <?php if($kekka['sash'] == '三協アルミ'){print 'selected';};?>>三協アルミ</option>
                <option value="三協立山アルミ" <?php if($kekka['sash'] == '三協立山アルミ'){print 'selected';};?>>三協立山アルミ</option>
                <option value="立山アルミ" <?php if($kekka['sash'] == '立山アルミ'){print 'selected';};?>>立山アルミ</option>
                <option value="日鐵サッシ" <?php if($kekka['sash'] == '日鐵サッシ'){print 'selected';};?>>日鐵サッシ</option>                
                <option value="LIXCIL" <?php if($kekka['sash'] == 'LIXCIL'){print 'selected';};?>>LIXCIL</option>
                <option value="新日軽" <?php if($kekka['sash'] == '新日軽'){print 'selected';};?>>新日軽</option>
                <option value="その他" <?php if($kekka['sash'] == 'その他'){print 'selected';};?>>その他メーカー</option>
            </select>
               <input class="number" id="firstBox" type="text" name="sashother" placeholder="メーカー名入力" value="<?php if($kekka['sash'] == 'その他'){print $kekka['sashother'];}?>">
        
<script>
function entryChange6(){
if(document.getElementById('changeSelect6')){
id = document.getElementById('changeSelect6').value;
 
if(id == 'その他'){
//フォーム
document.getElementById('firstBox6').style.display = "";
}else{
document.getElementById('firstBox6').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange6;
</script>
               
<input type="button" value="サッシ追加" id="check">
        <div class="box">
            <select class="select" name="sash2" id="changeSelect6" onchange="entryChange6();">
                <option value="">---◇ サッシメーカー2 ◇---</option>
                <option value="YKK" <?php if($kekka['sash2'] == 'YKK'){print 'selected';};?>>YKK</option>
                <option value="不二サッシ" <?php if($kekka['sash2'] == '不二サッシ'){print 'selected';};?>>不二サッシ</option>
                <option value="トステム" <?php if($kekka['sash2'] == 'トステム'){print 'selected';};?>>トステム</option>
                <option value="日軽サッシ" <?php if($kekka['sash2'] == '日軽サッシ'){print 'selected';};?>>日軽サッシ</option>
                <option value="三井軽金属" <?php if($kekka['sash2'] == '三井軽金属'){print 'selected';};?>>三井軽金属</option>
                <option value="キンキ" <?php if($kekka['sash2'] == 'キンキ'){print 'selected';};?>>キンキ</option>
                <option value="三協アルミ" <?php if($kekka['sash2'] == '三協アルミ'){print 'selected';};?>>三協アルミ</option>
                <option value="三協立山アルミ" <?php if($kekka['sash2'] == '三協立山アルミ'){print 'selected';};?>>三協立山アルミ</option>
                <option value="立山アルミ" <?php if($kekka['sash2'] == '立山アルミ'){print 'selected';};?>>立山アルミ</option>
                <option value="日鐵サッシ" <?php if($kekka['sash2'] == '日鐵サッシ'){print 'selected';};?>>日鐵サッシ</option>                
                <option value="LIXCIL" <?php if($kekka['sash2'] == 'LIXCIL'){print 'selected';};?>>LIXCIL</option>
                <option value="新日軽" <?php if($kekka['sash2'] == '新日軽'){print 'selected';};?>>新日軽</option>
                <option value="その他" <?php if($kekka['sash2'] == 'その他'){print 'selected';};?>>その他メーカー</option>
            </select>
               <input class="number" id="firstBox6" type="text" name="sashother2" placeholder="メーカー名入力" value="<?php if($kekka['sash2'] == 'その他'){print $kekka['sashother2'];}?>">
        </div>


<script>
$('#check').click(function() { 
//クリックイベントで要素をトグルさせる 
$("[class=box]").slideToggle(this.checked);
});
</script>
    </tr>

        <tr><th>(スリ.カタ)ガラス</th>
            <td colspan="2">
                <label><input type="radio" name="glass" value="スリ" <?php if($kekka['glass'] == 'スリ'){print 'checked';};?>>スリ</label>
                <label><input type="radio" name="glass" value="カタ" <?php if($kekka['glass'] == 'カタ'){print 'checked';};?>>カタ</label>
                <label><input type="radio" name="glass" value="スリカタなし" <?php if($kekka['glass'] == 'スリカタなし'){print 'checked';};?>>スリカタなし</label>
            </td></tr>
        
        <tr><th>ポケット</th>
            <td colspan="2"><select class="select" name="beet">
                    <option value="">---◇ Pサイズ ◇---</option>
                    <option value="9mm" <?php if($kekka['beet'] == '9mm'){print 'selected';};?>>9mm</option>
                    <option value="10mm" <?php if($kekka['beet'] == '10mm'){print 'selected';};?>>10mm</option>
                    <option value="11mm" <?php if($kekka['beet'] == '11mm'){print 'selected';};?>>11mm</option>
                    <option value="12mm" <?php if($kekka['beet'] == '12mm'){print 'selected';};?>>12mm</option>
                    <option value="13mm" <?php if($kekka['beet'] == '13mm'){print 'selected';};?>>13mm</option>
                    <option value="14mm" <?php if($kekka['beet'] == '14mm'){print 'selected';};?>>14mm</option>
                    <option value="15mm" <?php if($kekka['beet'] == '15mm'){print 'selected';};?>>15mm</option>
                    <option value="16mm" <?php if($kekka['beet'] == '16mm'){print 'selected';};?>>16mm</option>
                    <option value="17mm" <?php if($kekka['beet'] == '17mm'){print 'selected';};?>>17mm</option>
                    <option value="18mm" <?php if($kekka['beet'] == '18mm'){print 'selected';};?>>18mm</option>
                    <option value="19mm" <?php if($kekka['beet'] == '19mm'){print 'selected';};?>>19mm</option>
                    <option value="20mm" <?php if($kekka['beet'] == '20mm'){print 'selected';};?>>20mm</option>
                </select>
            <br>
            <div class="box">
<select class="select" name="beet2">
                    <option value="">---◇ Pサイズ ◇---</option>
                    <option value="9mm" <?php if($kekka['beet2'] == '9mm'){print 'selected';};?>>9mm</option>
                    <option value="10mm" <?php if($kekka['beet2'] == '10mm'){print 'selected';};?>>10mm</option>
                    <option value="11mm" <?php if($kekka['beet2'] == '11mm'){print 'selected';};?>>11mm</option>
                    <option value="12mm" <?php if($kekka['beet2'] == '12mm'){print 'selected';};?>>12mm</option>
                    <option value="13mm" <?php if($kekka['beet2'] == '13mm'){print 'selected';};?>>13mm</option>
                    <option value="14mm" <?php if($kekka['beet2'] == '14mm'){print 'selected';};?>>14mm</option>
                    <option value="15mm" <?php if($kekka['beet2'] == '15mm'){print 'selected';};?>>15mm</option>
                    <option value="16mm" <?php if($kekka['beet2'] == '16mm'){print 'selected';};?>>16mm</option>
                    <option value="17mm" <?php if($kekka['beet2'] == '17mm'){print 'selected';};?>>17mm</option>
                    <option value="18mm" <?php if($kekka['beet2'] == '18mm'){print 'selected';};?>>18mm</option>
                    <option value="19mm" <?php if($kekka['beet2'] == '19mm'){print 'selected';};?>>19mm</option>
                    <option value="20mm" <?php if($kekka['beet2'] == '20mm'){print 'selected';};?>>20mm</option>
                </select> 追加サッシ</div>
            </td></tr>
    
        <tr><th>網ガラス</th>
        <td colspan="2">
                <label><input type="checkbox" name="ami" value="菱形" <?php if(($ami[0] == '菱形') or ($ami[1] == '菱形') or ($ami[2] == '菱形') or ($ami[3] == '菱形')){print 'checked';};?>>菱形</label>
                <label><input type="checkbox" name="ami2" value="クロス" <?php if(($ami[0] == 'クロス') or ($ami[1] == 'クロス') or ($ami[2] == 'クロス') or ($ami[3] == 'クロス')){print 'checked';};?>>クロス</label>
                <label><input type="checkbox" name="ami3" value="縦" <?php if(($ami[0] == '縦') or ($ami[1] == '縦') or ($ami[2] == '縦') or ($ami[3] =='縦')){print 'checked';};?>>縦</label>
                <label><input type="checkbox" name="ami4" value="網なし" <?php if(($ami[0] == '網なし') or ($ami[1] == '網なし') or ($ami[2] == '網なし') or ($ami[3] == '網なし')){print 'checked';};?>>網なし</label>
        </td></tr>
        
        <tr><th>小窓ガラス(ベランダ側)<br><input type="button" value="追加" id="check3"></th>
            <td><label><input type="radio" name="glasstype" value="なし" <?php if($kekka['glasstype'] == 'なし'){print 'checked';};?>>なし</label>
                <label><input type="radio" name="glasstype" value="透明" <?php if($kekka['glasstype'] == '透明'){print 'checked';};?>>透明</label>
                <label><input type="radio" name="glasstype" value="網" <?php if($kekka['glasstype'] == '網'){print 'checked';};?>>網</label>
                <span class="res2">W&nbsp;<input class="number" type="number" size="2" name="width" placeholder="mm" value="<?php print $swsize[0];?>">
                                   H&nbsp;<input class="number" type="number" size="2" name="height" placeholder="mm" value="<?php print $swsize[1];?>"></span>
                </td>
            <td>
                <label><input type="radio" name="pushtype" value="パテ" <?php if($kekka['pushtype'] == 'パテ'){print 'checked';};?>>パテ</label> 
                <label><input type="radio" name="pushtype" value="ビート" <?php if($kekka['pushtype'] == 'ビート'){print 'checked';};?>>ビート</label>
                <span class="res2"><input type="text" name="smallfloor" placeholder="ルームタイプ" value="<?php if(!empty($kekka['smallfloor'])){print $kekka['smallfloor'];}?>"></span>
            </td>
        </tr>
        
            <tr class="box4"><th>小窓ガラス2(ベランダ側)</th>
            <td>
                <span class="res2">W&nbsp;<input class="number" type="number" size="2" name="width3" placeholder="mm" value="<?php print $swsize3[0];?>">
                                   H&nbsp;<input class="number" type="number" size="2" name="height3" placeholder="mm" value="<?php print $swsize3[1];?>"></span>
            </td>
            <td>
                <input type="text" name="smallfloor2" placeholder="ルームタイプ" value="<?php if(!empty($kekka['smallfloor2'])){print $kekka['smallfloor2'];}?>">
            </td></tr>
<script>
$('#check3').click(function() { 
//クリックイベントで要素をトグルさせる 
$("[class=box4]").slideToggle(this.checked);
});
</script>

<?php if(empty($swsize3[0]) && empty($swsize3[1]) && empty($kekka['smallfloor2'])){ ?>
<script>
//'小窓ベランダ側追加'のチェックボックスを自動実行
    $('#check3').trigger('click');
</script>
<?php }?>

        <tr><th>小窓ガラス(廊下側)</th>
            <td><label><input type="radio" name="glasstype2" value="なし" <?php if($kekka['glasstype2'] == 'なし'){print 'checked';};?>>なし</label>
                <label><input type="radio" name="glasstype2" value="透明" <?php if($kekka['glasstype2'] == '透明'){print 'checked';};?>>透明</label>
                <label><input type="radio" name="glasstype2" value="網" <?php if($kekka['glasstype2'] == '網'){print 'checked';};?>>網</label>
                <label><input type="radio" name="glasstype2" value="スリ" <?php if($kekka['glasstype2'] == 'スリ'){print 'checked';};?>>スリ</label>
                <span class="res2">W&nbsp;<input class="number" type="number" size="2" name="width2" placeholder="mm" value="<?php print $swsize2[0];?>">
                                   H&nbsp;<input class="number" type="number" size="2" name="height2" placeholder="mm" value="<?php print $swsize2[1];?>"></span>
                </td>
            <td>
                <label><input type="radio" name="pushtype2" value="パテ" <?php if($kekka['pushtype2'] == 'パテ'){print 'checked';};?>>パテ</label> 
                <label><input type="radio" name="pushtype2" value="ビート" <?php if($kekka['pushtype2'] == 'ビート'){print 'checked';};?>>ビート</label>
        </td></tr>

        <tr><th>透明ガラス</th>
            <td colspan="2">
                <label><input type="radio" name="toumei" value="5mmのみ" <?php if($kekka['toumei'] == '5mmのみ'){print 'checked';};?>>5mmのみ</label>
            </td></tr>

        <tr><th>スコープ</th>
            <td colspan="2">
                <label><input type="radio" name="scope" value="片面" <?php if($kekka['scope'] == '片面'){print 'checked';};?>>片面</label>
            　　<label><input type="radio" name="scope" value="両面" <?php if($kekka['scope'] == '両面'){print 'checked';};?>>両面</label>
            </td></tr>
<script>
function entryChange4(){
if(document.getElementById('changeSelect3')){
id = document.getElementById('changeSelect3').value;
 
if(id == 'その他'){
//フォーム
document.getElementById('firstBox4').style.display = "";
}else{
document.getElementById('firstBox4').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange4;
</script>
        <tr><th>新聞受け</th>
            <td>
            <select style="width: 180px;" class="select" name="news" id="changeSelect3" onchange="entryChange4();">
                <option value="">---◇ 新聞受け ◇---</option>
                <option value="大" onclick="entryChange4();" <?php if($splitnews[0] == '大'){print 'selected';};?>>大</option>
                <option value="小" onclick="entryChange4();" <?php if($splitnews[0] == '小'){print 'selected';};?>>小</option>
                <option value="その他" onclick="entryChange4();" <?php if($splitnews[0] == 'その他'){print'selected';};?>>その他</option></select>
                &nbsp;&nbsp;<input class="number" type="text" name="newsother" id="firstBox4" placeholder="コメント" value="<?php if(($splitnews[0] == '大') or ($splitnews[0] == '小')){}  else {print $kekka['newsother'];};?>">
            </td>
            <td>
                <label><input type="checkbox" name="kakou" value="切加工要" <?php if(empty($splitnews[1])){}else{if($splitnews[1] == '切加工要'){print 'checked';}};?>>切加工要</label>
                <label><input type="checkbox" name="vertical" value="縦ベロ" <?php if(empty($splitnews[2])){}else{if($splitnews[2] == '縦ベロ'){print 'checked';}};?>>縦ベロ</label>
            </td></tr>
               
        <tr><th>玄関(錠前)</th>
            <td colspan="2">
                <label><input type="radio" name="cylinder" value="PMK" <?php if($kekka['cylinder'] == 'PMK'){print 'checked';};?>>PMK</label>
                <label><input type="radio" name="cylinder" value="RA" <?php if($kekka['cylinder'] == 'RA'){print 'checked';};?>>RA</label>
                <label><input type="radio" name="cylinder" value="HPL" <?php if($kekka['cylinder'] == 'HPL'){print 'checked';};?>>HPL</label>
                <label><input type="radio" name="cylinder" value="GOAL" <?php if($kekka['cylinder'] == 'GOAL'){print 'checked';};?>>GOAL</label>
                <label><input type="radio" name="cylinder" value="LA" <?php if($kekka['cylinder'] == 'LA'){print 'checked';};?>>LA</label>
            </td></tr>
        
        <tr><th>シリンダー</th>
            <td colspan="2">
                <label><input type="radio" name="cylinder2" value="U9" <?php if($kekka['cylinder2'] == 'U9'){print 'checked';};?>>U9</label>
                <label><input type="radio" name="cylinder2" value="UR" <?php if($kekka['cylinder2'] == 'UR'){print 'checked';};?>>UR</label>
                <label><input type="radio" name="cylinder2" value="PR" <?php if($kekka['cylinder2'] == 'PR'){print 'checked';};?>>PR</label>
            </td></tr>        
        
<script>
function entryChange1(){
if(document.getElementById('changeSelect2')){
id = document.getElementById('changeSelect2').value;
 
if(id == '片開ドア'){
//フォーム
document.getElementById('firstBox2').style.display = "";
}else{
document.getElementById('firstBox2').style.display = "none";
}
}
if(id == '引き戸'){
//フォーム
document.getElementById('firstBox10').style.display = "";
}else{
document.getElementById('firstBox10').style.display = "none";
}
}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange1;
</script> 

    <tr><th>浴室ドア</th>
        <td colspan="2">
            <select class="select" name="bathroom" id="changeSelect2" onchange="entryChange1();">
                <option value="">---◇ 浴室ドア ◇---</option>
                <option onclick="entryChange1();" value="中折れ戸"<?php if($kekka['bathroom'] == '中折れ戸'){print 'selected';};?>>中折れ戸</option>
                <option onclick="entryChange1();" value="片開ドア" <?php if($kekka['bathroom'] == '片開ドア'){print 'selected';};?>>片開ドア</option>
                <option onclick="entryChange1();" value="引き戸" <?php if($kekka['bathroom'] == '引き戸'){print 'selected';};?>>引き戸</option>
            </select>
              <select class="select" name="bathkey" id="firstBox2">
                <option value="">浴室(錠前)</option>
                <option value="GOAL (ULW-4E)BS=89" <?php if($kekka['bathkey'] == 'GOAL (ULW-4E)BS=89'){print 'selected';};?>>GOAL (ULW-4E)BS=89</option>
                <option value="GOAL BS=60" <?php if($kekka['bathkey'] == 'GOAL BS=60'){print 'selected';};?>>GOAL BS=60</option>
                <option value="GOAL BS=100" <?php if($kekka['bathkey'] == 'GOAL BS=100'){print 'selected';};?>>GOAL BS=100</option>
                <option value="MIWA (FTA)フロント大 BS=100" <?php if($kekka['bathkey'] == 'MIWA (FTA)フロント大 BS=100'){print 'selected';};?>>MIWA (FTA)フロント大 BS=100</option>
                <option value="GOAL (MS)フロント小 BS=100" <?php if($kekka['bathkey'] == 'GOAL (MS)フロント小 BS=100'){print 'selected';};?>>GOAL (MS)フロント小 BS=100</option>
                <option value="COW BS=90" <?php if($kekka['bathkey'] == 'COW BS=90'){print 'selected';};?>>COW BS=90</option>
                <option value="MIWA (BM-Y)BS=64 D=33mm" <?php if($kekka['bathkey'] == 'MIWA (BM-Y)BS=64 D=33mm'){print 'selected';};?>>MIWA (BM-Y)BS=64 D=33mm</option>
                <option value="MIWA (BM-Y)BS=64 D=40mm[フレキ]" <?php if($kekka['bathkey'] == 'MIWA (BM-Y)BS=64 D=40mm[フレキ]'){print 'selected';};?>>MIWA (BM-Y)BS=64 D=40mm[フレキ]</option>
                <option value="MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ" <?php if($kekka['bathkey'] == 'MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ'){print 'selected';};?>>MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ</option>
                <option value="MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]" <?php if($kekka['bathkey'] == 'MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]'){print 'selected';};?>>MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]</option>
                <option value="トイレ共同" <?php if($kekka['bathkey'] == 'トイレ共同'){print 'selected';};?>>トイレ共同</option>
                <option valeu="引き戸" <?php if($kekka['bathkey'] == '引き戸'){print 'selected';};?>>引き戸</option>                
              </select>

      </td>
    </tr>
              <tr id="firstBox10"><th>浴室鎌錠</th>
            <td><select name="bathkama">
        <option value="">img/buhin/bathkama/bathkamatop.png</option>
        <option value="bath_kama.jpg"<?php if($kekka['bathkama'] == 'bath_kama.jpg'){print'selected';};?>>img/buhin/thumbnail/bathkama/bath_kama.jpg</option>
        </select></td></tr>

 
    <tr><th>トイレ(錠前)</th>
        <td colspan="2">
            <select class="select" name="toilet" id="changeSelect9" onchange="entryChange9();">
                <option value="">---◇ トイレ(錠前) ◇---</option>
                <option value="MIWA (BM-LS)BS=57" <?php if($kekka['toilet'] == 'MIWA (BM-LS)BS=57'){print 'selected';};?>>MIWA (BM-LS)BS=57</option>
                <option value="MIWA (BM-Y)BS=64 D=33mm" <?php if($kekka['toilet'] == 'MIWA (BM-Y)BS=64 D=33mm'){print 'selected';};?>>MIWA (BM-Y)BS=64 D=33mm</option>
                <option value="MIWA (BM-Y)BS=64 D=40mm[フレキ]" <?php if($kekka['toilet'] == 'MIWA (BM-Y)BS=64 D=40mm[フレキ]'){print 'selected';};?>>MIWA (BM-Y)BS=64 D=40mm[フレキ]</option>
                <option value="MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ" <?php if($kekka['toilet'] == 'MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ'){print 'selected';};?>>MIWA (BM-LS + BM-Y)BS=57 D=33mm コンビ</option>
                <option value="MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]" <?php if($kekka['toilet'] == 'MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]'){print 'selected';};?>>MIWA (BM-LS + BM-Y)BS=57 D=40mm コンビ[フレキ]</option>
                <option value="MIWA LL-6KJ(丸形)" <?php if($kekka['toilet'] == 'MIWA LL-6KJ(丸形)'){print 'selected';};?>>MIWA LL-6KJ(丸形)</option>
                <option value="MIWA WLP錠" <?php if($kekka['toilet'] == 'MIWA WLP錠'){print 'selected';};?>>MIWA WLP錠</option>
                <option value="MIWA (HMW-8)BS=64 D=33～42mm" <?php if($kekka['toilet'] == 'MIWA (HMW-8)BS=64 D=33～42mm'){print 'selected';};?>>MIWA (HMW-8)BS=64 D=33～42mm</option>
                <option value="GOAL (ULW-4E)BS=89" <?php if($kekka['toilet'] == 'GOAL (ULW-4E)BS=89'){print 'selected';};?>>GOAL (ULW-4E)BS=89</option>
                <option value="GOAL BS=60" <?php if($kekka['toilet'] == 'GOAL BS=60'){print 'selected';};?>>GOAL BS=60</option>
                <option value="その他" onclick="entryChange9();" <?php if($kekka['toilet'] == 'その他'){print 'selected';};?>>その他(入力項有)</option>
                <option value="浴室共同" <?php if($kekka['toilet'] == '浴室共同'){print 'selected';};?>>浴室共同</option>
                <option valeu="引き戸" <?php if($kekka['toilet'] == '引き戸'){print 'selected';};?>>引き戸</option>
            </select>
            <div id="firstBox9">
            <section><span>型名</span>
                <input class="cmt" type="text" name="toiletcmt" placeholder="またはメモ" value="<?php if(($kekka['toilet'] == 'その他')){echo $toiletcmt[0];}?>"></section>
            <section><span>&nbsp;&nbsp;BS</span>
                <input class="number" type="text" name="toiletcmt2" placeholder="バックセット" value="<?php if(($kekka['toilet'] == 'その他')){echo $toiletcmt[1];}?>"></section>
            <section><span>&nbsp;&nbsp;扉厚</span>
                <input class="number" type="text" name="toiletcmt3" placeholder="mm" value="<?php if(($kekka['toilet'] == 'その他')){echo $toiletcmt[2];}?>"></section>
            </div>
        </td></tr>

                <tr id="firstBox11"><th>トイレ鎌錠</th>
            <td><select name="toiletkama">
        <option value="">img/buhin/toiletkama/toiletkamatop.png</option>
        <option value="MIWA_kama.jpg"<?php if($kekka['toiletkama'] == 'MIWA_kama.jpg'){print'selected';};?>>img/buhin/thumbnail/toiletkama/MIWA_kama.jpg</option>
        <option value="atom_kama.jpg"<?php if($kekka['toiletkama'] == 'atom_kama.jpg'){print'selected';};?>>img/buhin/thumbnail/toiletkama/atom_kama.jpg</option>
        <option value="best_kama.jpg"<?php if($kekka['toiletkama'] == 'best_kama.jpg'){print'selected';};?>>img/buhin/thumbnail/toiletkama/best_kama.jpg</option>
        </select></td></tr>


    <tr><th>木製ドア(錠前)</th>
        <td colspan="2">
            <select class="select" name="wood"  id="changeSelect8" onchange="entryChange8();">
                <option value="">---◇ 木製ドア(錠前) ◇---</option>
                <option value="GOAL (ULW-4E)BS=60" <?php if($kekka['wood'] == 'GOAL (ULW-4E)BS=60'){print 'selected';};?>>GOAL (ULW-4E)BS=60</option>
                <option value="GOAL (ULW-4E)BS=89" <?php if($kekka['wood'] == 'GOAL (ULW-4E)BS=89'){print 'selected';};?>>GOAL (ULW-4E)BS=89</option>
                <option value="MIWA (WLO)BS=51mm[レバーハンドル]" <?php if($kekka['wood'] == 'MIWA (WLO)BS=51mm[レバーハンドル]'){print 'selected';};?>>MIWA (WLO)BS=51mm</option>
                <option value="MIWA (OM)BS=64mm" <?php if($kekka['wood'] == 'MIWA (OM)BS=64mm'){print 'selected';};?>>MIWA (OM)BS=64mm</option>
                <option value="MIWA BS=57mm" <?php if($kekka['wood'] == 'MIWA BS=57mm'){print 'selected';};?>>MIWA BS=57mm</option>
                <option value="その他" onclick="entryChange8();" <?php if($kekka['wood'] == 'その他'){print'selected';};?>>その他(入力項有)</option>
                <option value="木製ドアなし" <?php if($kekka['wood'] == '木製ドアなし'){print 'selected';};?>>木製ドアなし</option>
            </select>
            <div id="firstBox8">
            <section><span>型名</span>
                <input class="cmt" type="text" name="woodcmt" placeholder="またはメモ" value="<?php if(($kekka['wood'] == 'その他')){echo $woodcmt[0];}?>"></section>
            <section><span>&nbsp;&nbsp;BS</span>
                <input class="number" type="text" name="woodcmt2" placeholder="バックセット" value="<?php if(($kekka['wood'] == 'その他')){echo $woodcmt[1];}?>"></section>
            <section><span>&nbsp;&nbsp;扉厚</span>
                <input class="number" type="text" name="woodcmt3" placeholder="mm" value="<?php if(($kekka['wood'] == 'その他')){echo $woodcmt[2];}?>"></section>
            </div>
        </td>
<script>
function entryChange8(){
if(document.getElementById('changeSelect8')){
id = document.getElementById('changeSelect8').value;
if(id == 'その他'){
//フォーム
document.getElementById('firstBox8').style.display = "";
}else{
document.getElementById('firstBox8').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange8;
</script>

<script>
function entryChange9(){
if(document.getElementById('changeSelect9')){
id = document.getElementById('changeSelect9').value;
 
if(id == 'その他'){
//フォーム
document.getElementById('firstBox9').style.display = "";
}else{
document.getElementById('firstBox9').style.display = "none";
}
}
if(id == '引き戸'){
//フォーム
document.getElementById('firstBox11').style.display = "";
}else{
document.getElementById('firstBox11').style.display = "none";
}
}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange9;
</script> 

<script>
function entryChange3(){
if(document.getElementById('changeSelect4')){
id = document.getElementById('changeSelect4').value;
 
if(id == 'その他'){
//フォーム
document.getElementById('firstBox3').style.display = "";
}else{
document.getElementById('firstBox3').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange3;
</script>
<script>
function entryChange7(){
if(document.getElementById('changeSelect7')){
id = document.getElementById('changeSelect7').value;
 
if(id == '有'){
//フォーム
document.getElementById('firstBox7').style.display = "";
}else{
document.getElementById('firstBox7').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange7;
</script>
        <tr><th>集合ポスト</th>
            <td colspan="2">
                <select class="select" name="setpost" id="changeSelect4" onchange="entryChange3();">
                    <option value="">---◇ 集合ポスト ◇---</option>
                    <option onclick="entryChange4();" value="小"<?php if($kekka['setpost'] == '小'){print'selected';};?>>小</option>
                    <option onclick="entryChange4();" onclick="entryChange3();" value="大(横)"<?php if($kekka['setpost'] == '大(横)'){print'selected';};?>>大(横)</option>
                    <option onclick="entryChange4();" onclick="entryChange3();" value="大(縦)"<?php if($kekka['setpost'] == '大(縦)'){print'selected';};?>>大(縦)</option>
                    <option onclick="entryChange4();" onclick="entryChange3();"  value="その他"<?php if($kekka['setpost'] == 'その他'){print'selected';};?>>その他(入力項有)</option></select>
                <input class="number" type="text" name="setpostother" id="firstBox3" placeholder="コメント" value="<?php if(($kekka['setpost'] == '小') or ($kekka['setpost'] == '大(横)') or ($kekka['setpost'] == '大(縦)')){}  else {print $kekka['setpostother'];};?>">
                
                <select class="select" name="dial" id="changeSelect7" onchange="entryChange7();">
                    <option onclick="entryChange7()" value="">ダイヤル錠: 無(または未選択)</option>
                    <option onclick="entryChange7()" value="有"<?php if($kekka['dial'] == '有'){print'selected';};?>>ダイヤル錠: 有(下部で写真選択可能)</option>
                </select>
            </td></tr>

            
        <tr id="firstBox7"><th>ダイヤル錠</th>
            <td><select name="dialimg">
        <option value="">img/buhin/dial/dialtop.png</option>
        <option value="KOWASONIA.jpg"<?php if($kekka['dialimg'] == 'KOWASONIA.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/KOWASONIA.jpg</option>
        <option value="KYOWANASTA1.jpg"<?php if($kekka['dialimg'] == 'KYOWANASTA1.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/KYOWANASTA1.jpg</option>
        <option value="KYOWANASTA2.jpg"<?php if($kekka['dialimg'] == 'KYOWANASTA2.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/KYOWANASTA2.jpg</option>
        <option value="MIWA_ODS1.jpg"<?php if($kekka['dialimg'] == 'MIWA_ODS1.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/MIWA_ODS1.jpg</option>
        <option value="MIWA_ODS2.jpg"<?php if($kekka['dialimg'] == 'MIWA_ODS2.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/MIWA_ODS2.jpg</option>
        <option value="NAKAKOGYO.jpg"<?php if($kekka['dialimg'] == 'NAKAKOGYO.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/NAKAKOGYO.jpg</option>
        <option value="NASTA_SPK8.jpg"<?php if($kekka['dialimg'] == 'NASTA_SPK8.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/NASTA_SPK8.jpg</option>
        <option value="TAZIMA.jpg"<?php if($kekka['dialimg'] == 'TAZIMA.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/TAZIMA.jpg</option>
        <option value="POSTE.jpg"<?php if($kekka['dialimg'] == 'POSTE.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/POSTE.jpg</option>
        <option value="TAJIMA_side.jpg"<?php if($kekka['dialimg'] == 'TAJIMA_side.jpg'){print'selected';};?>>img/buhin/thumbnail/dial/TAJIMA_side.jpg</option>
        </select></td></tr>

            
<script>
function entryChange5(){
if(document.getElementById('changeSelect5')){
id = document.getElementById('changeSelect5').value;
 
if(id == '必要'){
//フォーム
document.getElementById('firstBox5').style.display = "";
}else{
document.getElementById('firstBox5').style.display = "none";
}}}
//オンロードさせ、リロード時に選択を保持
window.onload = entryChange5;
</script>
<script>
function init(){
       entryChange1();
       entryChange3();
       entryChange4();
       entryChange5();
       entryChange6();
       entryChange7();
       entryChange8();
       entryChange9();
}
 window.onload = init;     
</script>
        
        <tr><th>落下防止網</th>
            <td colspan="2">
                <select class="select" name="fall" id="changeSelect5" onchange="entryChange5();">
                    <option value="">---◇ 落下防止網 ◇---</option>
                    <option onclick="entryChange5();" value="必要"<?php if($kekka['fall'] == '必要'){print'selected';};?>>必要</option>
                    <option onclick="entryChange5();" onclick="entryChange5();" value="不要"<?php if($kekka['fall'] == '不要'){print'selected';};?>>不要</option>
                </select>
                    &nbsp;&nbsp;<input class="number" type="text" name="fallother" id="firstBox5" placeholder="場所" value="<?php if($kekka['fall'] == '不要'){}  else {print $kekka['fallother'];};?>">
                </td></tr>

        <tr><th>EV</th>
            <td colspan="2">
                <select class="select" name="ev" >
                    <option value="">---◇ EV ◇---</option>
                    <option value="有" <?php if($kekka['ev'] == '有'){print 'selected';}?>>有</option>
                    <option value="無" <?php if($kekka['ev'] == '無'){print 'selected';}?>>無</option>
                </select>
            </td></tr>
        
        <tr><th>階層</th>
            <td colspan="2">
                <select class="select" name="floor" >
                    <option value="">---◇ 階層 ◇---</option>
                    <option value="2F" <?php if($kekka['floor'] == '2F'){print 'selected';}?>>2F</option>
                    <option value="3F" <?php if($kekka['floor'] == '3F'){print 'selected';}?>>3F</option>
                    <option value="4F" <?php if($kekka['floor'] == '4F'){print 'selected';}?>>4F</option>
                    <option value="5F" <?php if($kekka['floor'] == '5F'){print 'selected';}?>>5F</option>
                    <option value="6F" <?php if($kekka['floor'] == '6F'){print 'selected';}?>>6F</option>
                    <option value="7F" <?php if($kekka['floor'] == '7F'){print 'selected';}?>>7F</option>
                    <option value="8F" <?php if($kekka['floor'] == '8F'){print 'selected';}?>>8F</option>
                    <option value="9F" <?php if($kekka['floor'] == '9F'){print 'selected';}?>>9F</option>
                    <option value="10F" <?php if($kekka['floor'] == '10F'){print 'selected';}?>>10F</option>
                    <option value="11F" <?php if($kekka['floor'] == '11F'){print 'selected';}?>>11F</option>
                    <option value="12F" <?php if($kekka['floor'] == '12F'){print 'selected';}?>>12F</option>
                    <option value="13F" <?php if($kekka['floor'] == '13F'){print 'selected';}?>>13F</option>
                    <option value="14F" <?php if($kekka['floor'] == '14F'){print 'selected';}?>>14F</option>
                    <option value="15F～" <?php if($kekka['floor'] == '15F～'){print 'selected';}?>>15F～</option>
                </select>
            </td></tr>
        
        <tr><th>クレセント(Lアングル)</th>
            <td><label><input type="checkbox" name="angle" value="Lアングル要" <?php if($kekka['angle'] == 'Lアングル要'){print 'checked';};?>>Lアングル要</label></td></tr>
</table>

<div id='scroll'>
        <table class="photo">
            <tr><th id="Langle">クレセント<?php if(!empty($kekka['angle'])){?><br><span>Lアングル必要</span><?php }?></th>
            <td><select name="kuresent" id="changeSelect" onchange="entryChange2();">
        <option value="">img/buhin/kuresent/kuresenttop.png</option>
        <option value="FUJI.jpg"<?php if($kuresent[0] == 'FUJI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI.jpg</option>
        <option value="FUJI2.jpg"<?php if($kuresent[0] == 'FUJI2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI2.jpg</option>
        <option value="YKK1.jpg"<?php if($kuresent[0] == 'YKK1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK1.jpg</option>
        <option value="YKK2.jpg"<?php if($kuresent[0] == 'YKK2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK2.jpg</option>
        <option value="YKK3.jpg"<?php if($kuresent[0] == 'YKK3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK3.jpg</option>
        <option value="MIWA_PB2-H.jpg"<?php if($kuresent[0] == 'MIWA_PB2-H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg</option>
        <option value="MIWA_PB2-S.jpg"<?php if($kuresent[0] == 'MIWA_PB2-S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg</option>
        <option value="SIBUTANI.jpg"<?php if($kuresent[0] == 'SIBUTANI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI.jpg</option>
        <option value="SIBUTANI_KEY.jpg"<?php if($kuresent[0] == 'SIBUTANI_KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg</option>
        <option value="CRE.jpg"<?php if($kuresent[0] == 'CRE.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/CRE.jpg</option>
        <option value="KINKI_K-GOOD.jpg"<?php if($kuresent[0] == 'KINKI_K-GOOD.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg</option>
        <option value="KIYOMATU_SD-EK-2003H-20B.jpg"<?php if($kuresent[0] == 'KIYOMATU_SD-EK-2003H-20B.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg</option>
        <option value="MITUIKEIKINZOKU.jpg"<?php if($kuresent[0] == 'MITUIKEIKINZOKU.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg</option>
        <option value="NAKANISI TOSTEM.jpg"<?php if($kuresent[0] == 'NAKANISI TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg</option>    
        <option value="NAKANISI TOSTEM2.jpg"<?php if($kuresent[0] == 'NAKANISI TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg</option> 
        <option value="NAKANISI.jpg"<?php if($kuresent[0] == 'NAKANISI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI.jpg</option> 
        <option value="SANKYO.jpg"<?php if($kuresent[0] == 'SANKYO.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO.jpg</option>
        <option value="SANKYO2.jpg"<?php if($kuresent[0] == 'SANKYO2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO2.jpg</option>
        <option value="SANKYO3.jpg"<?php if($kuresent[0] == 'SANKYO3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO3.jpg</option>
        <option value="TOSTEM.jpg"<?php if($kuresent[0] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM.jpg</option>
        <option value="TOSTEM2.jpg"<?php if($kuresent[0] == 'TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM2.jpg</option>
        <option value="FUJI3.jpg"<?php if($kuresent[0] == 'FUJI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI3.jpg</option>
        <option value="FUJI4.jpg"<?php if($kuresent[0] == 'FUJI4.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI4.jpg</option>
        <option value="FUJI5.jpg"<?php if($kuresent[0] == 'FUJI5.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI5.jpg</option>
        <option value="FUJI6.jpg"<?php if($kuresent[0] == 'FUJI6.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI6.jpg</option>
        <option value="FUJI7.jpg"<?php if($kuresent[0] == 'FUJI7.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI7.jpg</option>
        <option value="FUJI8.jpg"<?php if($kuresent[0] == 'FUJI8.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI8.jpg</option>
        <option value="FUJI_R.jpg"<?php if($kuresent[0] == 'FUJI_R.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R.jpg</option>
        <option value="FUJI_R-KEY.jpg"<?php if($kuresent[0] == 'FUJI_R-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg</option>
        <option value="FUJI_S.jpg"<?php if($kuresent[0] == 'FUJI_S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S.jpg</option>
        <option value="FUJI_S-KEY.jpg"<?php if($kuresent[0] == 'FUJI_S-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg</option>
        <option value="FUJI_haiban.jpg"<?php if($kuresent[0] == 'FUJI_haiban.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_haiban.jpg</option>
        <option value="MATUEI_AS-155H.jpg"<?php if($kuresent[0] == 'MATUEI_AS-155H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg</option>
        <option value="MATUEI_LS-11.jpg"<?php if($kuresent[0] == 'MATUEI_LS-11.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg</option>
        <option value="YKK_HH-K-10757.jpg"<?php if($kuresent[0] == 'YKK_HH-K-10757.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg</option>
        <option value="YKK_HH-K-10759-132.jpg"<?php if($kuresent[0] == 'YKK_HH-K-10759-132.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg</option>
        <option value="MIWA_PB-1.jpg"<?php if($kuresent[0] == 'MIWA_PB-1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg</option>
        <option value="SIBUTANI3.jpg"<?php if($kuresent[0] == 'SIBUTANI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI3.jpg</option>
        </select></td>
      
        <td><select name="kuresent2">
        <option value="">img/buhin/kuresent/kuresent2top.png</option>
        <option value="FUJI.jpg"<?php if($kuresent[1] == 'FUJI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI.jpg</option>
        <option value="FUJI2.jpg"<?php if($kuresent[1] == 'FUJI2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI2.jpg</option>
        <option value="YKK1.jpg"<?php if($kuresent[1] == 'YKK1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK1.jpg</option>
        <option value="YKK2.jpg"<?php if($kuresent[1] == 'YKK2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK2.jpg</option>
        <option value="YKK3.jpg"<?php if($kuresent[1] == 'YKK3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK3.jpg</option>
        <option value="MIWA_PB2-H.jpg"<?php if($kuresent[1] == 'MIWA_PB2-H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg</option>
        <option value="MIWA_PB2-S.jpg"<?php if($kuresent[1] == 'MIWA_PB2-S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg</option>
        <option value="SIBUTANI.jpg"<?php if($kuresent[1] == 'SIBUTANI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI.jpg</option>
        <option value="SIBUTANI_KEY.jpg"<?php if($kuresent[1] == 'SIBUTANI_KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg</option>
        <option value="CRE.jpg"<?php if($kuresent[1] == 'CRE.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/CRE.jpg</option>
        <option value="NAKANISI TOSTEM.jpg"<?php if($kuresent[1] == 'NAKANISI TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg</option>    
        <option value="NAKANISI TOSTEM2.jpg"<?php if($kuresent[1] == 'NAKANISI TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg</option> 
        <option value="NAKANISI.jpg"<?php if($kuresent[1] == 'NAKANISI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI.jpg</option> 
        <option value="KINKI_K-GOOD.jpg"<?php if($kuresent[1] == 'KINKI_K-GOOD.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg</option>
        <option value="KIYOMATU_SD-EK-2003H-20B.jpg"<?php if($kuresent[1] == 'KIYOMATU_SD-EK-2003H-20B.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg</option>
        <option value="MITUIKEIKINZOKU.jpg"<?php if($kuresent[1] == 'MITUIKEIKINZOKU.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg</option>
        <option value="SANKYO.jpg"<?php if($kuresent[1] == 'SANKYO.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO.jpg</option>
        <option value="SANKYO2.jpg"<?php if($kuresent[1] == 'SANKYO2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO2.jpg</option>
        <option value="SANKYO3.jpg"<?php if($kuresent[1] == 'SANKYO3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO3.jpg</option>

        <option value="TOSTEM.jpg"<?php if($kuresent[1] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM.jpg</option>
        <option value="TOSTEM2.jpg"<?php if($kuresent[1] == 'TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM2.jpg</option>
        <option value="FUJI3.jpg"<?php if($kuresent[1] == 'FUJI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI3.jpg</option>
        <option value="FUJI4.jpg"<?php if($kuresent[1] == 'FUJI4.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI4.jpg</option>
        <option value="FUJI5.jpg"<?php if($kuresent[1] == 'FUJI5.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI5.jpg</option>
        <option value="FUJI6.jpg"<?php if($kuresent[1] == 'FUJI6.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI6.jpg</option>
        <option value="FUJI7.jpg"<?php if($kuresent[1] == 'FUJI7.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI7.jpg</option>
        <option value="FUJI8.jpg"<?php if($kuresent[1] == 'FUJI8.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI8.jpg</option>
        <option value="FUJI_R.jpg"<?php if($kuresent[1] == 'FUJI_R.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R.jpg</option>
        <option value="FUJI_R-KEY.jpg"<?php if($kuresent[1] == 'FUJI_R-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg</option>
        <option value="FUJI_S.jpg"<?php if($kuresent[1] == 'FUJI_S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S.jpg</option>
        <option value="FUJI_S-KEY.jpg"<?php if($kuresent[1] == 'FUJI_S-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg</option>
        <option value="FUJI_haiban.jpg"<?php if($kuresent[1] == 'FUJI_haiban.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_haiban.jpg</option>
        <option value="MATUEI_AS-155H.jpg"<?php if($kuresent[1] == 'MATUEI_AS-155H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg</option>
        <option value="MATUEI_LS-11.jpg"<?php if($kuresent[1] == 'MATUEI_LS-11.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg</option>
        <option value="YKK_HH-K-10757.jpg"<?php if($kuresent[1] == 'YKK_HH-K-10757.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg</option>
        <option value="YKK_HH-K-10759-132.jpg"<?php if($kuresent[1] == 'YKK_HH-K-10759-132.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg</option>
        <option value="MIWA_PB-1.jpg"<?php if($kuresent[1] == 'MIWA_PB-1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg</option>
        <option value="SIBUTANI3.jpg"<?php if($kuresent[1] == 'SIBUTANI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI3.jpg</option>
        </select></td></tr>

     
        <tr><th>戸車</th>

        <td><select name="roller">
        <option value="">img/buhin/roller/rollertop.png</option>
        <option value="8type.jpg"<?php if($roller[0] == '8type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/8type.jpg</option>
        <option value="9type.jpg"<?php if($roller[0] == '9type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/9type.jpg</option>
        <option value="12type.jpg"<?php if($roller[0] == '12type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/12type.jpg</option>
        <option value="14type.jpg"<?php if($roller[0] == '14type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/14type.jpg</option>
        <option value="FUJI_TOPACE_FR3011-L.jpg"<?php if($roller[0] == 'FUJI_TOPACE_FR3011-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg</option>
        <option value="FUJI_TOPACE_FR3011-R.jpg"<?php if($roller[0] == 'FUJI_TOPACE_FR3011-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg</option>
        <option value="FUJI_FR-70series-R00920NN.jpg"<?php if($roller[0] == 'FUJI_FR-70series-R00920NN.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg</option>
        <option value="FUJI_KJ-Btype-FR0033.jpg"<?php if($roller[0] == 'FUJI_KJ-Btype-FR0033.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg</option>
        <option value="FUJI_KJ-Btype-R00320.jpg"<?php if($roller[0] == 'FUJI_KJ-Btype-R00320.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg</option>
        <option value="KINKI_KJ-Btype-kosimado.jpg"<?php if($roller[0] == 'KINKI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg</option>
        <option value="KINKI_KJ-Btype-hakidasi.jpg"<?php if($roller[0] == 'KINKI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg</option>
        <option value="KOMSTU.jpg"<?php if($roller[0] == 'KOMSTU.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KOMSTU.jpg</option>
        <option value="NIKKEI.jpg"<?php if($roller[0] == 'NIKKEI.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI.jpg</option>
        <option value="NIKKEI_KJ-Btype-hakidasi.jpg"<?php if($roller[0] == 'NIKKEI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg</option>
        <option value="NIKKEI_KJ-Btype-kosimado.jpg"<?php if($roller[0] == 'NIKKEI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg</option>
        <option value="SANKYO_B30266A-LorR.jpg"<?php if($roller[0] == 'SANKYO_B30266A-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg</option>
        <option value="SANKYO_BF4520B-LorR.jpg"<?php if($roller[0] == 'SANKYO_BF4520B-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg</option>
        <option value="SANKYO_BL001K-outside.jpg"<?php if($roller[0] == 'SANKYO_BL001K-outside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg</option>
        <option value="SANKYO_BL002K-inside.jpg"<?php if($roller[0] == 'SANKYO_BL002K-inside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg</option>
        <option value="TOSTEM_BHP-59.jpg"<?php if($roller[0] == 'TOSTEM_BHP-59.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg</option>
        <option value="TOSTEM.jpg"<?php if($roller[0] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM.jpg</option>
        <option value="YKK_AP_HH-2K-7515A.jpg"<?php if($roller[0] == 'YKK_AP_HH-2K-7515A.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg</option>
        <option value="YKK_AP_HH-K-12431_32.jpg"<?php if($roller[0] == 'YKK_AP_HH-K-12431_32.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg</option>
        <option value="YKK_AP_HH-K-15157.jpg"<?php if($roller[0] == 'YKK_AP_HH-K-15157.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg</option>
        <option value="YKK_AP_HH-T-0029.jpg"<?php if($roller[0] == 'YKK_AP_HH-T-0029.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg</option>
        <option value="KINKI_old.jpg"<?php if($roller[0] == 'KINKI_old.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_old.jpg</option>
        <option value="NIKKEI_LC-86.jpg"<?php if($roller[0] == 'NIKKEI_LC-86.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg</option>
        <option value="NIKKEI_LC-156-L.jpg"<?php if($roller[0] == 'NIKKEI_LC-156-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg</option>
        <option value="NIKKEI_LC-156-R.jpg"<?php if($roller[0] == 'NIKKEI_LC-156-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg</option>
        <option value="SANKYO_BL002K.jpg"<?php if($roller[0] == 'SANKYO_BL002K.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K.jpg</option>        
        <option value="YKK_2K-17412.jpg"<?php if($roller[0] == 'YKK_2K-17412.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_2K-17412.jpg</option>
        <option value="YOKOZUNA_EKW-0002.jpg"<?php if($roller[0] == 'YOKOZUNA_EKW-0002.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg</option>        
        <option value="YOKOZUNA_TBM-0281.jpg"<?php if($roller[0] == 'YOKOZUNA_TBM-0281.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg</option>
        </select></td>

        
        <td><select name="roller2">
        <option value="">img/buhin/roller/roller2top.png</option>
        <option value="8type.jpg"<?php if($roller[1] == '8type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/8type.jpg</option>
        <option value="9type.jpg"<?php if($roller[1] == '9type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/9type.jpg</option>
        <option value="12type.jpg"<?php if($roller[1] == '12type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/12type.jpg</option>
        <option value="14type.jpg"<?php if($roller[1] == '14type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/14type.jpg</option>
        <option value="FUJI_TOPACE_FR3011-L.jpg"<?php if($roller[1] == 'FUJI_TOPACE_FR3011-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg</option>
        <option value="FUJI_TOPACE_FR3011-R.jpg"<?php if($roller[1] == 'FUJI_TOPACE_FR3011-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg</option>
        <option value="FUJI_FR-70series-R00920NN.jpg"<?php if($roller[1] == 'FUJI_FR-70series-R00920NN.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg</option>
        <option value="FUJI_KJ-Btype-FR0033.jpg"<?php if($roller[1] == 'FUJI_KJ-Btype-FR0033.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg</option>
        <option value="FUJI_KJ-Btype-R00320.jpg"<?php if($roller[1] == 'FUJI_KJ-Btype-R00320.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg</option>
        <option value="KINKI_KJ-Btype-kosimado.jpg"<?php if($roller[1] == 'KINKI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg</option>
        <option value="KINKI_KJ-Btype-hakidasi.jpg"<?php if($roller[1] == 'KINKI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg</option>
        <option value="KOMSTU.jpg"<?php if($roller[1] == 'KOMSTU.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KOMSTU.jpg</option>
        <option value="NIKKEI.jpg"<?php if($roller[1] == 'NIKKEI.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI.jpg</option>
        <option value="NIKKEI_KJ-Btype-hakidasi.jpg"<?php if($roller[1] == 'NIKKEI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg</option>
        <option value="NIKKEI_KJ-Btype-kosimado.jpg"<?php if($roller[1] == 'NIKKEI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg</option>
        <option value="SANKYO_B30266A-LorR.jpg"<?php if($roller[1] == 'SANKYO_B30266A-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg</option>
        <option value="SANKYO_BF4520B-LorR.jpg"<?php if($roller[1] == 'SANKYO_BF4520B-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg</option>
        <option value="SANKYO_BL001K-outside.jpg"<?php if($roller[1] == 'SANKYO_BL001K-outside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg</option>
        <option value="SANKYO_BL002K-inside.jpg"<?php if($roller[1] == 'SANKYO_BL002K-inside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg</option>
        <option value="TOSTEM_BHP-59.jpg"<?php if($roller[1] == 'TOSTEM_BHP-59.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg</option>
        <option value="TOSTEM.jpg"<?php if($roller[1] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM.jpg</option>
        <option value="YKK_AP_HH-2K-7515A.jpg"<?php if($roller[1] == 'YKK_AP_HH-2K-7515A.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg</option>
        <option value="YKK_AP_HH-K-12431_32.jpg"<?php if($roller[1] == 'YKK_AP_HH-K-12431_32.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg</option>
        <option value="YKK_AP_HH-K-15157.jpg"<?php if($roller[1] == 'YKK_AP_HH-K-15157.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg</option>
        <option value="YKK_AP_HH-T-0029.jpg"<?php if($roller[1] == 'YKK_AP_HH-T-0029.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg</option>
        <option value="KINKI_old.jpg"<?php if($roller[1] == 'KINKI_old.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_old.jpg</option>
        <option value="NIKKEI_LC-86.jpg"<?php if($roller[1] == 'NIKKEI_LC-86.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg</option>
        <option value="NIKKEI_LC-156-L.jpg"<?php if($roller[1] == 'NIKKEI_LC-156-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg</option>
        <option value="NIKKEI_LC-156-R.jpg"<?php if($roller[1] == 'NIKKEI_LC-156-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg</option>
        <option value="SANKYO_BL002K.jpg"<?php if($roller[1] == 'SANKYO_BL002K.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K.jpg</option>
        <option value="YKK_2K-17412.jpg"<?php if($roller[1] == 'YKK_2K-17412.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_2K-17412.jpg</option>
        <option value="YOKOZUNA_EKW-0002.jpg"<?php if($roller[1] == 'YOKOZUNA_EKW-0002.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg</option>        
        <option value="YOKOZUNA_TBM-0281.jpg"<?php if($roller[1] == 'YOKOZUNA_TBM-0281.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg</option>
            </select></td></tr>

        <tr class="box"><th>追加サッシ クレセント</th>
            <td><select name="kuresent3" id="changeSelect" onchange="entryChange2();">
        <option value="">img/buhin/kuresent/kuresenttop.png</option>
        <option value="FUJI.jpg"<?php if($kuresent2[0] == 'FUJI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI.jpg</option>
        <option value="FUJI2.jpg"<?php if($kuresent2[0] == 'FUJI2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI2.jpg</option>
        <option value="YKK1.jpg"<?php if($kuresent2[0] == 'YKK1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK1.jpg</option>
        <option value="YKK2.jpg"<?php if($kuresent2[0] == 'YKK2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK2.jpg</option>
        <option value="YKK3.jpg"<?php if($kuresent2[0] == 'YKK3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK3.jpg</option>
        <option value="MIWA_PB2-H.jpg"<?php if($kuresent2[0] == 'MIWA_PB2-H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg</option>
        <option value="MIWA_PB2-S.jpg"<?php if($kuresent2[0] == 'MIWA_PB2-S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg</option>
        <option value="SIBUTANI.jpg"<?php if($kuresent2[0] == 'SIBUTANI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI.jpg</option>
        <option value="SIBUTANI_KEY.jpg"<?php if($kuresent2[0] == 'SIBUTANI_KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg</option>
        <option value="CRE.jpg"<?php if($kuresent2[0] == 'CRE.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/CRE.jpg</option>
        <option value="KINKI_K-GOOD.jpg"<?php if($kuresent2[0] == 'KINKI_K-GOOD.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg</option>
        <option value="KIYOMATU_SD-EK-2003H-20B.jpg"<?php if($kuresent2[0] == 'KIYOMATU_SD-EK-2003H-20B.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg</option>
        <option value="MITUIKEIKINZOKU.jpg"<?php if($kuresent2[0] == 'MITUIKEIKINZOKU.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg</option>
        <option value="NAKANISI TOSTEM.jpg"<?php if($kuresent2[0] == 'NAKANISI TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg</option>
        <option value="NAKANISI TOSTEM2.jpg"<?php if($kuresent2[0] == 'NAKANISI TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg</option>
        <option value="NAKANISI.jpg"<?php if($kuresent2[0] == 'NAKANISI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI.jpg</option>
        <option value="SANKYO.jpg"<?php if($kuresent2[0] == 'SANKYO.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO.jpg</option>
        <option value="SANKYO2.jpg"<?php if($kuresent2[0] == 'SANKYO2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO2.jpg</option>
        <option value="SANKYO3.jpg"<?php if($kuresent2[0] == 'SANKYO3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SANKYO3.jpg</option>
        <option value="TOSTEM.jpg"<?php if($kuresent2[0] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM.jpg</option>
        <option value="TOSTEM2.jpg"<?php if($kuresent2[0] == 'TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM2.jpg</option>
        <option value="FUJI3.jpg"<?php if($kuresent2[0] == 'FUJI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI3.jpg</option>
        <option value="FUJI4.jpg"<?php if($kuresent2[0] == 'FUJI4.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI4.jpg</option>
        <option value="FUJI5.jpg"<?php if($kuresent2[0] == 'FUJI5.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI5.jpg</option>
        <option value="FUJI6.jpg"<?php if($kuresent2[0] == 'FUJI6.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI6.jpg</option>
        <option value="FUJI7.jpg"<?php if($kuresent2[0] == 'FUJI7.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI7.jpg</option>
        <option value="FUJI8.jpg"<?php if($kuresent2[0] == 'FUJI8.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI8.jpg</option>
        <option value="FUJI_R.jpg"<?php if($kuresent2[0] == 'FUJI_R.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R.jpg</option>
        <option value="FUJI_R-KEY.jpg"<?php if($kuresent2[0] == 'FUJI_R-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg</option>
        <option value="FUJI_S.jpg"<?php if($kuresent2[0] == 'FUJI_S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S.jpg</option>
        <option value="FUJI_S-KEY.jpg"<?php if($kuresent2[0] == 'FUJI_S-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg</option>
        <option value="FUJI_haiban.jpg"<?php if($kuresent2[0] == 'FUJI_haiban.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_haiban.jpg</option>
        <option value="MATUEI_AS-155H.jpg"<?php if($kuresent2[0] == 'MATUEI_AS-155H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg</option>
        <option value="MATUEI_LS-11.jpg"<?php if($kuresent2[0] == 'MATUEI_LS-11.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg</option>
        <option value="YKK_HH-K-10757.jpg"<?php if($kuresent2[0] == 'YKK_HH-K-10757.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg</option>
        <option value="YKK_HH-K-10759-132.jpg"<?php if($kuresent2[0] == 'YKK_HH-K-10759-132.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg</option>
        <option value="MIWA_PB-1.jpg"<?php if($kuresent2[0] == 'MIWA_PB-1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg</option>
        <option value="SIBUTANI3.jpg"<?php if($kuresent2[0] == 'SIBUTANI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI3.jpg</option>
        </select></td>
        
        <td><select name="kuresent4">
        <option value="">img/buhin/kuresent/kuresent2top.png</option>
        <option value="FUJI.jpg"<?php if($kuresent2[1] == 'FUJI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI.jpg</option>
        <option value="FUJI2.jpg"<?php if($kuresent2[1] == 'FUJI2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI2.jpg</option>
        <option value="YKK1.jpg"<?php if($kuresent2[1] == 'YKK1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK1.jpg</option>
        <option value="YKK2.jpg"<?php if($kuresent2[1] == 'YKK2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK2.jpg</option>
        <option value="YKK3.jpg"<?php if($kuresent2[1] == 'YKK3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK3.jpg</option>
        <option value="MIWA_PB2-H.jpg"<?php if($kuresent2[1] == 'MIWA_PB2-H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-H.jpg</option>
        <option value="MIWA_PB2-S.jpg"<?php if($kuresent2[1] == 'MIWA_PB2-S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB2-S.jpg</option>
        <option value="SIBUTANI.jpg"<?php if($kuresent2[1] == 'SIBUTANI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI.jpg</option>
        <option value="SIBUTANI_KEY.jpg"<?php if($kuresent2[1] == 'SIBUTANI_KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI_KEY.jpg</option>
        <option value="CRE.jpg"<?php if($kuresent2[1] == 'CRE.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/CRE.jpg</option>
        <option value="KINKI_K-GOOD.jpg"<?php if($kuresent2[1] == 'KINKI_K-GOOD.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KINKI_K-GOOD.jpg</option>
        <option value="KIYOMATU_SD-EK-2003H-20B.jpg"<?php if($kuresent2[1] == 'KIYOMATU_SD-EK-2003H-20B.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/KIYOMATU_SD-EK-2003H-20B.jpg</option>
        <option value="MITUIKEIKINZOKU.jpg"<?php if($kuresent2[1] == 'MITUIKEIKINZOKU.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MITUIKEIKINZOKU.jpg</option>
        <option value="NAKANISI TOSTEM.jpg"<?php if($kuresent2[1] == 'NAKANISI TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM.jpg</option>    
        <option value="NAKANISI TOSTEM2.jpg"<?php if($kuresent2[1] == 'NAKANISI TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI TOSTEM2.jpg</option> 
        <option value="NAKANISI.jpg"<?php if($kuresent2[1] == 'NAKANISI.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/NAKANISI.jpg</option>
        <option value="TOSTEM.jpg"<?php if($kuresent2[1] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM.jpg</option>
        <option value="TOSTEM2.jpg"<?php if($kuresent2[1] == 'TOSTEM2.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/TOSTEM2.jpg</option>
        <option value="FUJI3.jpg"<?php if($kuresent2[1] == 'FUJI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI3.jpg</option>
        <option value="FUJI4.jpg"<?php if($kuresent2[1] == 'FUJI4'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI4.jpg</option>
        <option value="FUJI5.jpg"<?php if($kuresent2[1] == 'FUJI5'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI5.jpg</option>
        <option value="FUJI6.jpg"<?php if($kuresent2[1] == 'FUJI6.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI6.jpg</option>
        <option value="FUJI7.jpg"<?php if($kuresent2[1] == 'FUJI7.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI7.jpg</option>
        <option value="FUJI8.jpg"<?php if($kuresent2[1] == 'FUJI8.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI8.jpg</option>
        <option value="FUJI_R.jpg"<?php if($kuresent2[1] == 'FUJI_R.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R.jpg</option>
        <option value="FUJI_R-KEY.jpg"<?php if($kuresent2[1] == 'FUJI_R-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_R-KEY.jpg</option>
        <option value="FUJI_S.jpg"<?php if($kuresent2[1] == 'FUJI_S.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S.jpg</option>
        <option value="FUJI_S-KEY.jpg"<?php if($kuresent2[1] == 'FUJI_S-KEY.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_S-KEY.jpg</option>
        <option value="FUJI_haiban.jpg"<?php if($kuresent2[1] == 'FUJI_haiban.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/FUJI_haiban.jpg</option>
        <option value="MATUEI_AS-155H.jpg"<?php if($kuresent2[1] == 'MATUEI_AS-155H.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_AS-155H.jpg</option>
        <option value="MATUEI_LS-11.jpg"<?php if($kuresent2[1] == 'MATUEI_LS-11.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MATUEI_LS-11.jpg</option>
        <option value="YKK_HH-K-10757.jpg"<?php if($kuresent2[1] == 'YKK_HH-K-10757.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10757.jpg</option>
        <option value="YKK_HH-K-10759-132.jpg"<?php if($kuresent2[1] == 'YKK_HH-K-10759-132.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/YKK_HH-K-10759-132.jpg</option>
        <option value="MIWA_PB-1.jpg"<?php if($kuresent2[1] == 'MIWA_PB-1.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/MIWA_PB-1.jpg</option>
        <option value="SIBUTANI3.jpg"<?php if($kuresent2[1] == 'SIBUTANI3.jpg'){print'selected';};?>>img/buhin/thumbnail/kuresent/SIBUTANI3.jpg</option>
        </select></td></tr>
        
     
        <tr class="box"><th>追加サッシ 戸車</th>

        <td><select name="roller3">
        <option value="">img/buhin/roller/rollertop.png</option>
        <option value="8type.jpg"<?php if($roller2[0] == '8type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/8type.jpg</option>
        <option value="9type.jpg"<?php if($roller2[0] == '9type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/9type.jpg</option>
        <option value="12type.jpg"<?php if($roller2[0] == '12type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/12type.jpg</option>
        <option value="14type.jpg"<?php if($roller2[0] == '14type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/14type.jpg</option>
        <option value="FUJI_TOPACE_FR3011-L.jpg"<?php if($roller2[0] == 'FUJI_TOPACE_FR3011-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg</option>
        <option value="FUJI_TOPACE_FR3011-R.jpg"<?php if($roller2[0] == 'FUJI_TOPACE_FR3011-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg</option>
        <option value="FUJI_FR-70series-R00920NN.jpg"<?php if($roller2[0] == 'FUJI_FR-70series-R00920NN.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg</option>
        <option value="FUJI_KJ-Btype-FR0033.jpg"<?php if($roller2[0] == 'FUJI_KJ-Btype-FR0033.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg</option>
        <option value="FUJI_KJ-Btype-R00320.jpg"<?php if($roller2[0] == 'FUJI_KJ-Btype-R00320.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg</option>
        <option value="KINKI_KJ-Btype-kosimado.jpg"<?php if($roller2[0] == 'KINKI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg</option>
        <option value="KINKI_KJ-Btype-hakidasi.jpg"<?php if($roller2[0] == 'KINKI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg</option>
        <option value="KOMSTU.jpg"<?php if($roller2[0] == 'KOMSTU.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KOMSTU.jpg</option>
        <option value="NIKKEI.jpg"<?php if($roller2[0] == 'NIKKEI.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI.jpg</option>
        <option value="NIKKEI_KJ-Btype-hakidasi.jpg"<?php if($roller2[0] == 'NIKKEI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg</option>
        <option value="NIKKEI_KJ-Btype-kosimado.jpg"<?php if($roller2[0] == 'NIKKEI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg</option>
        <option value="SANKYO_B30266A-LorR.jpg"<?php if($roller2[0] == 'SANKYO_B30266A-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg</option>
        <option value="SANKYO_BF4520B-LorR.jpg"<?php if($roller2[0] == 'SANKYO_BF4520B-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg</option>
        <option value="SANKYO_BL001K-outside.jpg"<?php if($roller2[0] == 'SANKYO_BL001K-outside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg</option>
        <option value="SANKYO_BL002K-inside.jpg"<?php if($roller2[0] == 'SANKYO_BL002K-inside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg</option>
        <option value="TOSTEM_BHP-59.jpg"<?php if($roller2[0] == 'TOSTEM_BHP-59.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg</option>
        <option value="TOSTEM.jpg"<?php if($roller2[0] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM.jpg</option>
        <option value="YKK_AP_HH-2K-7515A.jpg"<?php if($roller2[0] == 'YKK_AP_HH-2K-7515A.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg</option>
        <option value="YKK_AP_HH-K-12431_32.jpg"<?php if($roller2[0] == 'YKK_AP_HH-K-12431_32.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg</option>
        <option value="YKK_AP_HH-K-15157.jpg"<?php if($roller2[0] == 'YKK_AP_HH-K-15157.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg</option>
        <option value="YKK_AP_HH-T-0029.jpg"<?php if($roller2[0] == 'YKK_AP_HH-T-0029.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg</option>
        <option value="KINKI_old.jpg"<?php if($roller2[0] == 'KINKI_old.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_old.jpg</option>
        <option value="NIKKEI_LC-86.jpg"<?php if($roller2[0] == 'NIKKEI_LC-86.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg</option>
        <option value="NIKKEI_LC-156-L.jpg"<?php if($roller2[0] == 'NIKKEI_LC-156-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg</option>
        <option value="NIKKEI_LC-156-R.jpg"<?php if($roller2[0] == 'NIKKEI_LC-156-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg</option>
        <option value="SANKYO_BL002K.jpg"<?php if($roller2[0] == 'SANKYO_BL002K.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K.jpg</option>        
        <option value="YKK_2K-17412.jpg"<?php if($roller2[0] == 'YKK_2K-17412.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_2K-17412.jpg</option>
        <option value="YOKOZUNA_EKW-0002.jpg"<?php if($roller2[0] == 'YOKOZUNA_EKW-0002.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg</option>        
        <option value="YOKOZUNA_TBM-0281.jpg"<?php if($roller2[0] == 'YOKOZUNA_TBM-0281.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg</option>
        </select></td>

        
        <td><select name="roller4">
        <option value="">img/buhin/roller/roller2top.png</option>
        <option value="8type.jpg"<?php if($roller2[1] == '8type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/8type.jpg</option>
        <option value="9type.jpg"<?php if($roller2[1] == '9type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/9type.jpg</option>
        <option value="12type.jpg"<?php if($roller2[1] == '12type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/12type.jpg</option>
        <option value="14type.jpg"<?php if($roller2[1] == '14type.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/14type.jpg</option>
        <option value="FUJI_TOPACE_FR3011-L.jpg"<?php if($roller2[1] == 'FUJI_TOPACE_FR3011-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-L.jpg</option>
        <option value="FUJI_TOPACE_FR3011-R.jpg"<?php if($roller2[1] == 'FUJI_TOPACE_FR3011-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_TOPACE_FR3011-R.jpg</option>
        <option value="FUJI_FR-70series-R00920NN.jpg"<?php if($roller2[1] == 'FUJI_FR-70series-R00920NN.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_FR-70series-R00920NN.jpg</option>
        <option value="FUJI_KJ-Btype-FR0033.jpg"<?php if($roller2[1] == 'FUJI_KJ-Btype-FR0033.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-FR0033.jpg</option>
        <option value="FUJI_KJ-Btype-R00320.jpg"<?php if($roller2[1] == 'FUJI_KJ-Btype-R00320.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/FUJI_KJ-Btype-R00320.jpg</option>
        <option value="KINKI_KJ-Btype-kosimado.jpg"<?php if($roller2[1] == 'KINKI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-kosimado.jpg</option>
        <option value="KINKI_KJ-Btype-hakidasi.jpg"<?php if($roller2[1] == 'KINKI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_KJ-Btype-hakidasi.jpg</option>
        <option value="KOMSTU.jpg"<?php if($roller2[1] == 'KOMSTU.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KOMSTU.jpg</option>
        <option value="NIKKEI.jpg"<?php if($roller2[1] == 'NIKKEI.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI.jpg</option>
        <option value="NIKKEI_KJ-Btype-hakidasi.jpg"<?php if($roller2[1] == 'NIKKEI_KJ-Btype-hakidasi.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-hakidasi.jpg</option>
        <option value="NIKKEI_KJ-Btype-kosimado.jpg"<?php if($roller2[1] == 'NIKKEI_KJ-Btype-kosimado.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_KJ-Btype-kosimado.jpg</option>
        <option value="SANKYO_B30266A-LorR.jpg"<?php if($roller2[1] == 'SANKYO_B30266A-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_B30266A-LorR.jpg</option>
        <option value="SANKYO_BF4520B-LorR.jpg"<?php if($roller2[1] == 'SANKYO_BF4520B-LorR.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BF4520B-LorR.jpg</option>
        <option value="SANKYO_BL001K-outside.jpg"<?php if($roller2[1] == 'SANKYO_BL001K-outside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL001K-outside.jpg</option>
        <option value="SANKYO_BL002K-inside.jpg"<?php if($roller2[1] == 'SANKYO_BL002K-inside.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K-inside.jpg</option>
        <option value="TOSTEM_BHP-59.jpg"<?php if($roller2[1] == 'TOSTEM_BHP-59.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM_BHP-59.jpg</option>
        <option value="TOSTEM.jpg"<?php if($roller2[1] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/TOSTEM.jpg</option>
        <option value="YKK_AP_HH-2K-7515A.jpg"<?php if($roller2[1] == 'YKK_AP_HH-2K-7515A.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-2K-7515A.jpg</option>
        <option value="YKK_AP_HH-K-12431_32.jpg"<?php if($roller2[1] == 'YKK_AP_HH-K-12431_32.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-12431_32.jpg</option>
        <option value="YKK_AP_HH-K-15157.jpg"<?php if($roller2[1] == 'YKK_AP_HH-K-15157.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-K-15157.jpg</option>
        <option value="YKK_AP_HH-T-0029.jpg"<?php if($roller2[1] == 'YKK_AP_HH-T-0029.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_AP_HH-T-0029.jpg</option>
        <option value="KINKI_old.jpg"<?php if($roller2[1] == 'KINKI_old.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/KINKI_old.jpg</option>
        <option value="NIKKEI_LC-86.jpg"<?php if($roller2[1] == 'NIKKEI_LC-86.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-86.jpg</option>
        <option value="NIKKEI_LC-156-L.jpg"<?php if($roller2[1] == 'NIKKEI_LC-156-L.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-L.jpg</option>
        <option value="NIKKEI_LC-156-R.jpg"<?php if($roller2[1] == 'NIKKEI_LC-156-R.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/NIKKEI_LC-156-R.jpg</option>
        <option value="SANKYO_BL002K.jpg"<?php if($roller2[1] == 'SANKYO_BL002K.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/SANKYO_BL002K.jpg</option>
        <option value="YKK_2K-17412.jpg"<?php if($roller2[1] == 'YKK_2K-17412.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YKK_2K-17412.jpg</option>
        <option value="YOKOZUNA_EKW-0002.jpg"<?php if($roller2[1] == 'YOKOZUNA_EKW-0002.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_EKW-0002.jpg</option>        
        <option value="YOKOZUNA_TBM-0281.jpg"<?php if($roller2[1] == 'YOKOZUNA_TBM-0281.jpg'){print'selected';};?>>img/buhin/thumbnail/roller/YOKOZUNA_TBM-0281.jpg</option>
            </select></td></tr>

<?php if(empty($kekka['sash2'] ?? '') && empty($kekka['sashother2'] ?? '') && ($kuresent2[0] ?? '') === '' && ($kuresent2[1] ?? '') === '' && ($roller2[0] ?? '') === '' && ($roller2[1] ?? '') === ''){ ?>
<script>
//'サッシ追加'のチェックボックスを自動実行
    $('#check').trigger('click');
</script>
<?php }?>
        
        <tr><th>小窓金具</th>
            <td><select name="small">
        <option value="">img/buhin/small/small_windowtop.png</option>
        <option value="FUJI_prastic.jpg"<?php if($kekka['small'] == 'FUJI_prastic.jpg'){print'selected';};?>>img/buhin/thumbnail/small/FUJI_prastic.jpg</option>
        <option value="K-GOOD_steel.jpg"<?php if($kekka['small'] == 'K-GOOD_steel.jpg'){print'selected';};?>>img/buhin/thumbnail/small/K-GOOD_steel.jpg</option>
        <option value="SANKYO1.jpg"<?php if($kekka['small'] == 'SANKYO1.jpg'){print'selected';};?>>img/buhin/thumbnail/small/SANKYO1.jpg</option>
        <option value="SANKYO2.jpg"<?php if($kekka['small'] == 'SANKYO2.jpg'){print'selected';};?>>img/buhin/thumbnail/small/SANKYO2.jpg</option>
        <option value="FUJI_sw.jpg"<?php if($kekka['small'] == 'FUJI_sw.jpg'){print'selected';};?>>img/buhin/thumbnail/small/FUJI_sw.jpg</option>
        <option value="YKK_HH-K-7307A.jpg"<?php if($kekka['small'] == 'YKK_HH-K-7307A.jpg'){print'selected';};?>>img/buhin/thumbnail/small/YKK_HH-K-7307A.jpg</option>
        <option value="YKK_K-35167.jpg"<?php if($kekka['small'] == 'YKK_K-35167.jpg'){print'selected';};?>>img/buhin/thumbnail/small/YKK_K-35167.jpg</option>
        <option value="newYKK_sw.jpg"<?php if($kekka['small'] == 'newYKK_sw.jpg'){print'selected';};?>>img/buhin/thumbnail/small/newYKK_sw.jpg</option>
        <option value="LIXILpro_sw.jpg"<?php if($kekka['small'] == 'LIXILpro_sw.jpg'){print'selected';};?>>img/buhin/thumbnail/small/LIXILpro_sw.jpg</option>
            </select></td></tr>
        
        <tr><th>内倒し金具</th>
            <td><select name="down">
        <option value="">img/buhin/down/downtop.png</option>
        <option value="K-GOOD_outside.jpg"<?php if($kekka['down'] == 'K-GOOD_outside.jpg'){print'selected';};?>>img/buhin/thumbnail/down/K-GOOD_outside.jpg</option>
        <option value="YKK_outside.jpg"<?php if($kekka['down'] == 'YKK_outside.jpg'){print'selected';};?>>img/buhin/thumbnail/down/YKK_outside.jpg</option>
        <option value="YKK_AP.jpg"<?php if($kekka['down'] == 'YKK_AP.jpg'){print'selected';};?>>img/buhin/thumbnail/down/YKK_AP.jpg</option>
        <option value="NAKANISHI_outsid.jpg"<?php if($kekka['down'] == 'NAKANISHI_outsid.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI_outsid.jpg</option>
        <option value="FUJI_KJ-B.jpg"<?php if($kekka['down'] == 'FUJI_KJ-B.jpg'){print'selected';};?>>img/buhin/thumbnail/down/FUJI_KJ-B.jpg</option>
        <option value="K-GOOD.jpg"<?php if($kekka['down'] == 'K-GOOD.jpg'){print'selected';};?>>img/buhin/thumbnail/down/K-GOOD.jpg</option>
        <option value="NAKANISHI.jpg"<?php if($kekka['down'] == 'NAKANISHI.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI.jpg</option>
        <option value="NAKANISHI2.jpg"<?php if($kekka['down'] == 'NAKANISHI2.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI2.jpg</option>
        <option value="NAKANISHI3.jpg"<?php if($kekka['down'] == 'NAKANISHI3.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI3.jpg</option>
        <option value="NAKANISHI4.jpg"<?php if($kekka['down'] == 'NAKANISHI4.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI4.jpg</option>
        <option value="NAKANISHI5.jpg"<?php if($kekka['down'] == 'NAKANISHI5.jpg'){print'selected';};?>>img/buhin/thumbnail/down/NAKANISHI5.jpg</option>
        </select></td></tr>
        
        <tr><th>中折れ金具</th>
            <td><select name="folding">
        <option value="">img/buhin/folding/foldingtop.png</option>
        <option value="newYKK_gray.jpg"<?php if($kekka['folding'] == 'newYKK_gray.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/newYKK_gray.jpg</option>
        <option value="newYKK_ivory.jpg"<?php if($kekka['folding'] == 'newYKK_ivory.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/newYKK_ivory.jpg</option>
        <option value="YKK3_gray.jpg"<?php if($kekka['folding'] == 'YKK3_gray.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/YKK3_gray.jpg</option>
        <option value="YKK3_ivory.jpg"<?php if($kekka['folding'] == 'YKK3_ivory.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/YKK3_ivory.jpg</option>
        <option value="TOSTEM.jpg"<?php if($kekka['folding'] == 'TOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/TOSTEM.jpg</option>
        <option value="HITACHI.jpg"<?php if($kekka['folding'] == 'HITACHI.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/HITACHI.jpg</option>
        <option value="oldYKK.jpg"<?php if($kekka['folding'] == 'oldYKK.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/oldYKK.jpg</option>
        <option value="topTOSTEM.jpg"<?php if($kekka['folding'] == 'topTOSTEM.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/topTOSTEM.jpg</option>
        <option value="YKK4.jpg"<?php if($kekka['folding'] == 'YKK4.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/YKK4.jpg</option>
        <option value="SANKYO.jpg"<?php if($kekka['folding'] == 'SANKYO.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/SANKYO.jpg</option>
        <option value="SANKYO2.jpg"<?php if($kekka['folding'] == 'SANKYO2.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/SANKYO2.jpg</option>
        <option value="newYKK.jpg"<?php if($kekka['folding'] == 'newYKK.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/newYKK.jpg</option>
        <option value="YKK3.jpg"<?php if($kekka['folding'] == 'YKK3.jpg'){print'selected';};?>>img/buhin/thumbnail/folding/YKK3.jpg</option>
        </select></td></tr>
     
        <tr><th>平面ハンドル<br><input type="button" value="追加" id="check2"></th>
            <td><select name="handle">
        <option value="">img/buhin/handle/handletop.png</option>
        <option value="ACE.jpg"<?php if($kekka['handle'] == 'ACE.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/ACE.jpg</option>
        <option value="PRINCE_S.jpg"<?php if($kekka['handle'] == 'PRINCE_S.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_S.jpg</option>
        <option value="PRINCE_M.jpg"<?php if($kekka['handle'] == 'PRINCE_M.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_M.jpg</option>
        <option value="PRINCE_L.jpg"<?php if($kekka['handle'] == 'PRINCE_L.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_L.jpg</option>
        <option value="SUEHIRO.jpg"<?php if($kekka['handle'] == 'SUEHIRO.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO.jpg</option>
        <option value="SUEHIRO2.jpg"<?php if($kekka['handle'] == 'SUEHIRO2.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO2.jpg</option>
        <option value="SUEHIRO3.jpg"<?php if($kekka['handle'] == 'SUEHIRO3.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO3.jpg</option>
        <option value="TAKIGEN.jpg"<?php if($kekka['handle'] == 'TAKIGEN.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/TAKIGEN.jpg</option>
        <option value="TAKIGEN_stan.jpg"<?php if($kekka['handle'] == 'TAKIGEN_stan.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/TAKIGEN_stan.jpg</option>
        <option value="T_type.jpg"<?php if($kekka['handle'] == 'T_type.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/T_type.jpg</option>
        <option value="GOAL.jpg"<?php if($kekka['handle'] == 'GOAL.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/GOAL.jpg</option>
        </select></td>
        
        
            <td class="box2"><select name="handle2">
        <option value="">img/buhin/handle/handletop.png</option>
        <option value="ACE.jpg"<?php if($kekka['handle2'] == 'ACE.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/ACE.jpg</option>
        <option value="PRINCE_S.jpg"<?php if($kekka['handle2'] == 'PRINCE_S.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_S.jpg</option>
        <option value="PRINCE_M.jpg"<?php if($kekka['handle2'] == 'PRINCE_M.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_M.jpg</option>
        <option value="PRINCE_L.jpg"<?php if($kekka['handle2'] == 'PRINCE_L.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/PRINCE_L.jpg</option>
        <option value="SUEHIRO.jpg"<?php if($kekka['handle2'] == 'SUEHIRO.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO.jpg</option>
        <option value="SUEHIRO2.jpg"<?php if($kekka['handle2'] == 'SUEHIRO2.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO2.jpg</option>
        <option value="SUEHIRO3.jpg"<?php if($kekka['handle2'] == 'SUEHIRO3.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/SUEHIRO3.jpg</option>
        <option value="TAKIGEN.jpg"<?php if($kekka['handle2'] == 'TAKIGEN.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/TAKIGEN.jpg</option>
        <option value="TAKIGEN_stan.jpg"<?php if($kekka['handle2'] == 'TAKIGEN_stan.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/TAKIGEN_stan.jpg</option>
        <option value="T_type.jpg"<?php if($kekka['handle2'] == 'T_type.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/T_type.jpg</option>
        <option value="GOAL.jpg"<?php if($kekka['handle2'] == 'GOAL.jpg'){print'selected';};?>>img/buhin/thumbnail/handle/GOAL.jpg</option>
        </select></td>
        </tr>


        <tr><th>その他部品<br><input type="button" value="追加" id="check4"></th>
            <td><select name="otherimg">
        <option value="">img/buhin/otherimg/othertop.png</option>
        <option value="TAKIGEN_key.jpg"<?php if($kekka['otherimg'] == 'TAKIGEN_key.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/TAKIGEN_key.jpg</option>
        <option value="OLD_YKK.jpg"<?php if($kekka['otherimg'] == 'OLD_YKK.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/OLD_YKK.jpg</option>
        <option value="sand.jpg"<?php if($kekka['otherimg'] == 'sand.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/sand.jpg</option>
        </select></td>
        
        
            <td class="box3"><select name="otherimg2">
        <option value="">img/buhin/otherimg/othertop.png</option>
        <option value="TAKIGEN_key.jpg"<?php if($kekka['otherimg2'] == 'TAKIGEN_key.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/TAKIGEN_key.jpg</option>
        <option value="OLD_YKK.jpg"<?php if($kekka['otherimg2'] == 'OLD_YKK.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/OLD_YKK.jpg</option>
        <option value="sand.jpg"<?php if($kekka['otherimg'] == 'sand.jpg'){print'selected';};?>>img/buhin/thumbnail/otherimg/sand.jpg</option>
        </select></td>
        </tr>



        <tr class="lowframe"><th>下枠寸法</th>
            <td>
                    <select id="material" class="select" name="material">
                    <option value="">---◇ 材質 ◇---</option>
                    <option value="スチール" <?php if($kekka['material'] == 'スチール'){print 'selected';}?>>スチール</option>
                    <option value="ステンレス" <?php if($kekka['material'] == 'ステンレス'){print 'selected';}?>>ステンレス</option>
                    </select>

            <section id="size">
                <select  class="select" name="frame" >
                    <option value="">---◇ 下枠寸法 ◇---</option>
                    <option value="1.jpg" <?php if($kekka['frame'] == '1.jpg'){print 'selected';}?>>A-12 B-40 C-25</option>  
                    <option value="6.jpg" <?php if($kekka['frame'] == '6.jpg'){print 'selected';}?>>A-12 B-40 C-38</option>
                    <option value="5.jpg" <?php if($kekka['frame'] == '5.jpg'){print 'selected';}?>>A-12 B-45 C-40</option>
                    <option value="9.jpg" <?php if($kekka['frame'] == '9.jpg'){print 'selected';}?>>A-15 B-40 C-28</option>                    
                  <option value="12.jpg" <?php if($kekka['frame'] == '12.jpg'){print 'selected';}?>>A-15 B-40 C-30</option>
                  <option value="14.jpg" <?php if($kekka['frame'] == '14.jpg'){print 'selected';}?>>A-15 B-40 C-40</option>
                    <option value="4.jpg" <?php if($kekka['frame'] == '4.jpg'){print 'selected';}?>>A-15 B-42 C-28</option>
                    <option value="8.jpg" <?php if($kekka['frame'] == '8.jpg'){print 'selected';}?>>A-15 B-42 C-36</option>
                    <option value="3.jpg" <?php if($kekka['frame'] == '3.jpg'){print 'selected';}?>>A-15 B-42 C-38</option>                    
                    <option value="2.jpg" <?php if($kekka['frame'] == '2.jpg'){print 'selected';}?>>A-25 B-40 C-25</option>
                  <option value="11.jpg" <?php if($kekka['frame'] == '11.jpg'){print 'selected';}?>>A-25 B-40 C-28</option>                    
                  <option value="15.jpg" <?php if($kekka['frame'] == '15.jpg'){print 'selected';}?>>A-25 B-40 C-38</option>
                    <option value="7.jpg" <?php if($kekka['frame'] == '7.jpg'){print 'selected';}?>>A-25 B-40 C-40</option>                  
                  <option value="10.jpg" <?php if($kekka['frame'] == '10.jpg'){print 'selected';}?>>A-25 B-42 C-25</option>
                  <option value="13.jpg" <?php if($kekka['frame'] == '13.jpg'){print 'selected';}?>>A-25 B-42 C-38</option>
                  <option value="framecs.jpg" <?php if($kekka['frame'] == 'framecs.jpg'){print 'selected';}?>>カスタム</option>
                </select>
                    <img class="frameimg" src="img/buhin/frame/ABC.jpg">
            </section>

            <!-- スチールの下枠寸法 -->
            <section id="size3">下枠寸法<br>
                <span>A&nbsp;<input class="number" type="number" size="2" name="framesizeA" value="<?php print $framesize[0];?>">
                      &nbsp;B&nbsp;<input class="number" type="number" size="2" name="framesizeB" value="<?php print $framesize[1];?>">
                      &nbsp;C&nbsp;<input class="number" type="number" size="2" name="framesizeC" value="<?php print $framesize[2];?>">
                </span>
            </section>
                
            <section id="airtight">エアータイト<br>
                <select id="airtight" class="select" name="airtight">
                  <option value="">---◇ 未選択 ◇---</option>
                  <option value="有" <?php if($kekka['airtight'] == '有'){print 'selected';}?>>有</option>
                  <option value="無" <?php if($kekka['airtight'] == '無'){print 'selected';}?>>無</option>
                </select>
            </section>    
                
            <section id="airtight2">エアータイト<br>
                <select id="airtight2" class="select" name="airtight2">
                  <option value="">---◇ 未選択 ◇---</option>
                  <option value="有" <?php if($kekka['airtight2'] == '有'){print 'selected';}?>>有</option>
                  <option value="無" <?php if($kekka['airtight2'] == '無'){print 'selected';}?>>無</option>
                </select>
                <img class="frameimg" src="img/buhin/frame/stan.jpg">
            </section>
            <!--ステンレスの下枠寸法 -->
            <section id="size2">下枠寸法<br>
                <span>&nbsp;A&nbsp;<input class="number" type="number" size="2" name="stansizeA" value="<?php print $stansize[0];?>">
                      &nbsp;&nbsp;B&nbsp;<input class="number" type="number" size="2" name="stansizeB" value="<?php print $stansize[1];?>">
                      &nbsp;&nbsp;C&nbsp;<input class="number" type="number" size="2" name="stansizeC" value="<?php print $stansize[2];?>">
                      &nbsp;&nbsp;D&nbsp;<input class="number" type="number" size="2" name="stansizeD" value="<?php print $stansize[3];?>">
                </span>
            </section>
            </td>
        </tr>
    </table>
</div>

<script>
    // 平面ハンドル追加項目(ON,OFF)
$('#check2').click(function() { 
//クリックイベントで要素をトグルさせる 
$("[class=box2]").slideToggle(0);
});

    //特殊部品追加項目(ON,OFF)
    $('#check4').click(function() { 
//クリックイベントで要素をトグルさせる 
$("[class=box3]").slideToggle(0);
});
</script>

<!--スチール、ステンレスselect項目表示(ON,OFF)    -->
<script>
  $('select[name="material"]').change(function() {
    if ($('select[name="material"] option:selected').val() == ''){
        $('#size').slideUp()
        $('#airtight').slideUp();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideUp();
        }
        else if ($('select[name="material"] option:selected').val() == 'スチール'){
        $('#size').slideDown()
        $('#airtight').slideDown();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideUp();
        }
    else if ($('select[name="material"] option:selected').val() == 'ステンレス'){
          $('#size').slideUp();
          $('#airtight').slideUp();
          $('#airtight2').slideDown();
          $('#size2').slideDown();
          $('#size3').slideUp();
        };
  });
</script>
<!--END-->

<!--スチールのselect項目でカスタムを選択した時の動作    -->
<script>
  $('select[name="frame"]').change(function() {
        if ($('select[name="frame"] option:selected').val() == 'framecs.jpg'){
        $('#size').slideDown()
        $('#airtight').slideDown();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideDown();
        }
        else {
        $('#size').slideDown()
        $('#airtight').slideDown();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideUp();
        }
  });
</script>
<!--END-->

<!--ページ遷移後のイベント処理(php{switch分で分岐})-->
<?php switch($kekka['material']){
    case ''?>
<script>
    $(document).ready(function(){
        $('#size').slideUp();
        $('#airtight').slideUp();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideUp();
    });
</script>
<?php break;
    case 'スチール';?>
<script>
    $(document).ready(function(){
        $('#size').slideDown();
        $('#airtight').slideDown();
        $('#airtight2').slideUp();
        $('#size2').slideUp();
        $('#size3').slideUp();
    });
</script>
<?php break;
    case 'ステンレス'?>
<script>
    $(document).ready(function(){
        $('#size').slideUp();
        $('#airtight').slideUp();
        $('#airtight2').slideDown();
        $('#size2').slideDown();
        $('#size3').slideUp();
    });
</script>
<?php break;
}
//<!--END-->

//スチールのselectがカスタムを選択している時に、下枠寸法を表示させる
if($kekka['frame'] == 'framecs.jpg'){ ?>
<script>
    $(document).ready(function(){
        $('#size3').slideDown();
    });
</script>
<?php }

if(empty($kekka['handle2'])){?>
<script>
//'平面ハンドル追加'のチェックボックスを自動実行
    $('#check2').trigger('click');
</script>
<?php }
if(empty($kekka['otherimg2'])){?>
<script>
//'特殊部品追加'のチェックボックスを自動実行
    $('#check4').trigger('click');
</script>
<?php }?>
                <input type='hidden' name='code' value='<?php print $code;?>'>
                <input type='hidden' name='codeno' value='<?php print $codeno;?>'>
                <input type='hidden' name='goutou' value= '<?php print $goutou;?>'>
                <input type="hidden" name="goutouvar" value= '<?php print $goutouvar;?>'>
                <input type='hidden' name='syubetu' value= '<?php print $syubetu;?>'>
                <input type='hidden' name='address' value='<?php print $address;?>'>
                <input type='hidden' name='name' value= '<?php print $name?>'>
                <input type='hidden' name='toroku' value='toroku'>
                <input  class="submit" type='submit' value='部品登録'>
      </form>

<script>
 $(function(){
    $('[class=delbtn]').click(function(){
        if(!confirm('本当に削除しますか?')){
            /*キャンセルの時の処理*/
            return false;
        }
    });
 });
</script>
            </div>
<?php
//アップロード画像部品毎にカウントする。$cntに配列でカウントを代入している
$sql9= $pdo->prepare("
    SELECT partsimg.codeno, COUNT(*) AS total, 
 SUM(CASE WHEN partsimg.nametype='other' THEN 1 ELSE 0 END) AS cnt_other,
 SUM(CASE WHEN partsimg.nametype='kuresent' THEN 1 ELSE 0 END) AS cnt_kuresent,
 SUM(CASE WHEN partsimg.nametype='roller' THEN 1 ELSE 0 END) AS cnt_roller,
 SUM(CASE WHEN partsimg.nametype='down' THEN 1 ELSE 0 END) AS cnt_down,
 SUM(CASE WHEN partsimg.nametype='folding' THEN 1 ELSE 0 END) AS cnt_folding,
 SUM(CASE WHEN partsimg.nametype='handle' THEN 1 ELSE 0 END) AS cnt_handle,
 SUM(CASE WHEN partsimg.nametype='small' THEN 1 ELSE 0 END) AS cnt_small,
 SUM(CASE WHEN partsimg.nametype='keylock' THEN 1 ELSE 0 END) AS cnt_keylock
FROM partsimg where partsimg.codeno = '$codeno'
GROUP BY partsimg.codeno
");
$sql9->execute();
$cnt= $sql9->fetch(PDO::FETCH_ASSOC);

$sql2 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql2->execute();
$sql3 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql3->execute();
$sql4 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql4->execute();
$sql5 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql5->execute();
$sql6 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql6->execute();
$sql7 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql7->execute();
$sql8 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql8->execute();
$sql10 = $pdo->prepare("SELECT * FROM partsimg WHERE codeno='$codeno'") or die ("失敗");
$sql10->execute();
//0を代入してwhileの中で1を加算する
$count = 0;?>
<strong id="result"><?php echo $result;?></strong>

</div>

<div id="loader"></div>





<script>
/*スクロールすると途中で表示される「トップへ戻るボタン」の実装。さらにフッター手前で止める場合の実装。
https://recooord.org/scroll-to-top/*/
$(function() {
    var topBtn = $('#top_scroll');
    //ボタンを非表示にする
    topBtn.hide();
    //スクロールしてページトップから100に達したらボタンを表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
　　　　　　　//フェードインで表示
            topBtn.fadeIn();
        } else {
　　　　　　　//フェードアウトで非表示
            topBtn.fadeOut();
        }
    });
    //スクロールしてトップへ戻る
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});
</script>
<div id="top_scroll"><a href="#"></a></div>

<?php
}}

 $pdo= null; ?>
        <div id="footer">
        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
        </div>
      </div>
    <script src="jquery/Lightbox/js/lightbox.min.js"></script>
    </body>
</html>