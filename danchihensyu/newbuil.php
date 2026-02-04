<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
$category= 'on';
$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>

<html>
    <head>
        <title>団地登録</title>

<?php require '../require/header.php';?>        

    <div class="block">
    <h1>団地登録</h1>
<?php
     $maxcode = isset($_GET['maxcode']) ? htmlspecialchars($_GET['maxcode']) : null;
     $syubetu = isset($_GET['syubetu']) ? htmlspecialchars($_GET['syubetu']) : null;
     $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : null;
     $bldg = isset($_GET['bldg']) ? htmlspecialchars($_GET['bldg']) : null;
     $city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : null;
     $address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : null;
     $year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : null;
     $mappage = isset($_GET['mappage']) ? htmlspecialchars($_GET['mappage']) : null;


if(isset($_GET['redirect'])){
              if(empty($name and $bldg and $address)){
        print '必須箇所をを入力して下さい。';
        print '<br/><br/>';
        print "<strong>入力ページへ自動的に戻ります...</strong>";?>
<SCRIPT>

function autoLink()
 {
 location.href="javascript:history.back();";
 }
 setTimeout("autoLink()",1500);

 </SCRIPT>
<?php }else{
    //下の種別以外(水道局など)はcodeを一括に管理しているのでcodeのMAX値で指定している
   $syuarray = array('都営','公社','都民','区営');
    if(in_array("$syubetu",(array)$syuarray)){
        $sql= ("SELECT MAX(danchilist.code) FROM danchilist WHERE syubetu='$syubetu' AND city='$city'") or die("失敗");
    }else{
        $sql= ("SELECT MAX(danchilist.code) FROM danchilist") or die("失敗");
    }
    $stmt = $pdo->query($sql);
    $stmt->execute();
   while ($row = $stmt->fetch())
{
      //団地codeの最大値に1を足す
       $maxcode= $row[0];
}
       $maxcode++;
       //////////////////////////

    $sql2= $pdo->prepare("INSERT INTO danchilist (code, syubetu, name, city, jusyo, nendo, map) VALUES ($maxcode, '$syubetu', '$name', '$city', '$address', '$year', '$mappage')") or die ("失敗");
    $sql2->execute();

if(ctype_digit($bldg)){
    $sql3= $pdo->prepare("INSERT INTO goutou (code, codeno, name, goutou) VALUES ($maxcode, $maxcode$bldg, '$name', $bldg)") or die ("失敗");
    $sql3->execute();
    $sql4= $pdo->prepare("INSERT INTO goutou2 (code, codeno, name, goutou) VALUES ($maxcode, $maxcode$bldg, '$name', $bldg)") or die ("失敗");
    $sql4->execute();
}else{
    $sql3= $pdo->prepare("INSERT INTO goutou (code, codeno, name, goutouvar) VALUES ($maxcode, '$maxcode$bldg', '$name', '$bldg')") or die ("失敗");
    $sql3->execute();
    $sql4= $pdo->prepare("INSERT INTO goutou2 (code, codeno, name, goutouvar) VALUES ($maxcode, '$maxcode$bldg', '$name', '$bldg')") or die ("失敗");
    $sql4->execute();
}
   print "登録しました";?>
   <SCRIPT>
 <!--
function autoLink()
 {
 location.href="../index.php";
 }
 setTimeout("autoLink()",2000); 
 // -->
 </SCRIPT>
<?php }}else{?>
 <div>
    <form method="GET" action="newbuil.php">
        <dl class="inner">
            <dt>種別<span class="required">必須</span></dt>
            <dd>
                <select name="syubetu">
                    <option value="都営">都営</option>
                    <option value="公社">公社</option>
                    <option value="都民">都民</option>
                    <option value="区営">区営</option>
                    <option value="水道局">水道局</option>
                    <option value="教育庁住宅">教育庁</option>
                    <option value="交通局住宅">交通局</option>
                    <option value="下水道局住宅">下水道局</option>
                    <option value="総務局住宅">総務局</option>
                    <option value="高校">高校</option>
                    <option value="消防宿舎">消防宿舎</option>
                </select>
            </dd>
        </dl>
        
        <dl class="inner">
            <dt>団地名<span class="required">必須</span></dt>
            <dd><input type="text" name="name" placeholder=" 団地名" required></dd>
        </dl>
        
        <dl class="inner">
            <dt>市区町村<span class="required">必須</span></dt>
            <dd>
                <select name="city">
                    <option value="千代田区">千代田区</option>
                    <option value="中央区">中央区</option>
                    <option value="港区">港区</option>
                    <option value="新宿区">新宿区</option>
                    <option value="文京区">文京区</option>
                    <option value="台東区">台東区</option>
                    <option value="墨田区">墨田区</option>
                    <option value="江東区">江東区</option>
                    <option value="品川区">品川区</option>
                    <option value="目黒区">目黒区</option>
                    <option value="大田区">大田区</option>
                    <option value="世田谷区">世田谷区</option>
                    <option value="渋谷区">渋谷区</option>
                    <option value="中野区">中野区</option>
                    <option value="杉並区">杉並区</option>
                    <option value="豊島区">豊島区</option>
                    <option value="北区">北区</option>
                    <option value="荒川区">荒川区</option>
                    <option value="板橋区">板橋区</option>
                    <option value="練馬区">練馬区</option>
                    <option value="足立区">足立区</option>
                    <option value="葛飾区">葛飾区</option>
                    <option value="江戸川区">江戸川区</option>
                    <option value="八王子市">八王子市</option>
                    <option value="立川市">立川市</option>
                    <option value="武蔵野市">武蔵野市</option>
                    <option value="三鷹市">三鷹市</option>
                    <option value="青梅市">青梅市</option>
                    <option value="府中市">府中市</option>
                    <option value="昭島市">昭島市</option>
                    <option value="調布市">調布市</option>
                    <option value="町田市">町田市</option>
                    <option value="小金井市">小金井市</option>
                    <option value="小平市">小平市</option>
                    <option value="日野市">日野市</option>
                    <option value="東村山市">東村山市</option>
                    <option value="国分寺市">国分寺市</option>
                    <option value="国立市">国立市</option>
                    <option value="西東京市">西東京市</option>
                    <option value="福生市">福生市</option>
                    <option value="狛江市">狛江市</option>
                    <option value="東大和市">東大和市</option>
                    <option value="清瀬市">清瀬市</option>
                    <option value="東久留米市">東久留米市</option>
                    <option value="武蔵村山市">武蔵村山市</option>
                    <option value="多摩市">多摩市</option>
                    <option value="稲城市">稲城市</option>
                    <option value="羽村市">羽村市</option>
                    <option value="瑞穂町">瑞穂町</option>
                    <option value="小笠原村">小笠原村</option>
                </select>
            </dd>
        </dl>
        <dl class="inner">
          <dt>住所<span class="required">必須</span></dt>
              <dd><input type="text" name="address" placeholder=" (例)六木3-1" required></dd>
        </dl>
        
        <dl class="inner">
            <dt>棟ナンバー<span class="required">必須</span>(半角英数字)</dt>
            <dd><input type="text" name="bldg" placeholder=" (例)1" pattern="^[0-9A-Za-z]+$" required value="1"></dd>
        </dl>
        
        <dl class="inner">
            <dt>建築年度<span class="location">任意</span></dt>
            <dd><input type="text" name="year" placeholder=" 建築年度"></dd>
        </dl>
        
        <dl class="inner">
            <dt>地図帳ページ<span class="location">任意</span></dt>
            <dd><input type="text" name="mappage" placeholder=" 地図帳ページ"></dd>
        </dl>
        
            <input type="hidden" name="maxcode" value="<?php print $maxcode;?>">
            <input type="hidden" name="redirect" value="redirect">
            <input id='regist' class='registbtn' type="submit" value="登録">
    </form>
 </div></div>
<?php
}}
$pdo = NULL;
?>
<script>
        $('#regist').click(function(){
            if(!confirm('登録しますか?')){
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