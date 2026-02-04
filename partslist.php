<?php
require './db_info.php';
require './cookie.php';
$path= './';
//
//カテゴリーメニューを非表示(index.phpとlist.phpとuserlist.php)
$category= 'on';
//
$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地登録</span></a></li>");
$sort = !empty($_GET['sort']) ? htmlspecialchars($_GET['sort']) : NULL;


// ログイン状態のチェック
if ($cntid == 1) { ?>
    <!DOCTYPE html>
    <html>
        <head>
        	<title>代替え部品一覧</title>
        	<link href="./css/partslist.css?ta" rel="stylesheet" media="all">
<?php require './require/header.php';?>

<h1>代替え部品一覧</h1>

<div class="box">
<h6>サッシ一覧</h6>
<ul>
  <li><a href="#1">YKK</a></li>
  <li><a href="#2">不二サッシ</a></li>
  <li><a href="#3">トステム</a></li>
  <li><a href="#4">日軽サッシ</a></li>
  <li><a href="#5">三井軽金属</a></li>
  <li><a href="#6">キンキ</a></li>
</ul>

<ul>
  <li><a href="#7">三協アルミ</a></li>
  <li><a href="#8">三協立山アルミ</a></li>
  <li><a href="#9">立山アルミ</a></li>
  <li><a href="#10">日鐵サッシ</a></li>
  <li><a href="#11">LIXCIL</a></li>
  <li><a href="#12">新日軽</a></li>
</ul>


</div>

<div class="box2" id="1">
	<h5>YKK</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/YKK2.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/YKK3.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/YKK_AP_HH-K-15157.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/YKK_AP_HH-T-0029.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/YKK_AP_HH-K-12431_32_.jpg"></div>

			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/YKK_K-35167.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/YKK_HH-K-7307A.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>14mm</span><span>13mm</span><span>11mm</span>
			</div>
		</div>
</div>


<div class="box2" id="2">
	<h5>不二サッシ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div><br>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"><br>頻度少</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/SIBUTANI.jpg"><br>頻度少<br>(ヒヨコの代用品)</div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/FUJI_KJ-Btype-FR0033.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/FUJI_KJ-Btype-R00320.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/8type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/FUJI_FR-70series-R00920NN.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/FUJI_TOPACE_FR3011-L.jpg"></div>

			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_sw.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>9mm</span><span>14mm</span>
			</div>
		</div>
</div>


<div class="box2" id="3">
	<h5>トステム</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/TOSTEM_BHP-59.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/TOSTEM.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>14mm</span>
			</div>
		</div>
</div>


<div class="box2" id="4">
	<h5>日軽サッシ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"><br>Lアングル要</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/YKK3.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/NIKKEI_KJ-Btype-hakidasi.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/NIKKEI_KJ-Btype-kosimado.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/9type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/NIKKEI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/NIKKEI_LC-86.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>11mm</span><span>12mm</span><span>14mm</span>
			</div>
		</div>
</div>



<div class="box2" id="5">
	<h5>三井軽金属</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"><br>Lアングル要</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/KINKI_K-GOOD.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/9type.jpg"><br>下框を外し、既存戸車を撤去して9型のツメを折らずに端から差し込む</div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>11mm</span>
			</div>
		</div>
</div>



<div class="box2" id="6">
	<h5>キンキ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-H.jpg"><br>もしくはPB-2S(受けの位置でSかHになる)<br>Lアングル要</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/KINKI_K-GOOD.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/8type.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>11mm</span><span>13mm</span>
			</div>
		</div>
</div>



<div class="box2" id="7">
	<h5>三協アルミ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/SIBUTANI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>

			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/9type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/SANKYO_BF4520B-LorR.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/SANKYO_BL001K-outside.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/SANKYO_BL002K-inside.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/SANKYO1.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/SANKYO2.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>

			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>11mm</span><span>13mm</span><span>14mm</span><span>16mm</span>
			</div>
		</div>
</div>



<div class="box2" id="8">
	<h5>三協立山アルミ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					登録なし
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/SANKYO1.jpg"></div>
			</div>
		</div>
</div>


<div class="box2" id="9">
	<h5>立山アルミ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/SIBUTANI.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/9type.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_sw.jpg"></div>
			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>14mm</span>
			</div>
		</div>
</div>



<div class="box2" id="10">
	<h5>日鐵サッシ</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/SIBUTANI.jpg"><br>Lアングル要</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/MIWA_PB2-S.jpg"><br>Lアングル要</div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					<div class="imgbox"><img src="./img/buhin/roller/12type.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/roller/8type.jpg"></div>
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/K-GOOD_steel.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>9mm</span>
			</div>
		</div>
</div>



<div class="box2" id="11">
	<h5>LIXCIL</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					登録なし
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					登録なし

			</div>
		</div>
</div>



<div class="box2" id="12">
	<h5>新日軽</h5>
		<div class="partsbox">
			<div class="subbox">
				<h6>クレセント</h6>
					<div class="imgbox"><img src="./img/buhin/kuresent/FUJI.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/kuresent/CRE.jpg"></div>
			</div>
			<div class="subbox">
				<h6>戸車</h6>
					登録なし
			</div>
			<div class="subbox">
				<h6>小窓金具</h6>
					<div class="imgbox"><img src="./img/buhin/small/YKK_K-35167.jpg"></div>
					<div class="imgbox"><img src="./img/buhin/small/FUJI_prastic.jpg"></div>
			</div>
			<div class="subbox">
				<h6>ポケット</h6>
					<span>14mm</span>
			</div>
		</div>
</div>            


<script>
// jQueryでスムーススクロールを実装する方法
// https://techacademy.jp/magazine/9532
$('a[href^="#"]').click(function() {
  // スクロールの速度
  var speed = 400; // ミリ秒で記述
  var href = $(this).attr("href");
  var target = $(href == "#" || href == "" ? 'html' : href);
  var position = target.offset().top;
  $('body,html').animate({
    scrollTop: position
  }, speed, 'swing');
  return false;
});
</script>


                    <div id="footer">
                        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
                    </div>
            </div>
        </body>
    </html>
<?php
$pdo= NULL;
}