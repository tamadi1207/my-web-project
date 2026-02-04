<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
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
        	<title>部品登録状況</title>
        	<link href="../css/ratio.css?aa" rel="stylesheet" media="all">
<?php require '../require/header.php';

//団地(code)の総数
$sql= $pdo->prepare("
SELECT count(danchilist.code) as code FROM danchilist
	")or die ("失敗");
$sql->execute();
$result= $sql->fetch(PDO::FETCH_ASSOC);

//団地(code)部品の登録している団地数
$sql= $pdo->prepare("
SELECT count(distinct(danchilist.code)) as buil
 FROM $goutb JOIN danchilist ON $goutb.code = danchilist.code WHERE $goutb.hiduke IS NOT NULL
    ")or die ("失敗");
$sql->execute();
$result6= $sql->fetch(PDO::FETCH_ASSOC);

//goutouテーブル(codeno)の総数
$sql= $pdo->prepare("
SELECT count(codeno) as codeno FROM $goutb
	")or die ("失敗");
$sql->execute();
$result2= $sql->fetch(PDO::FETCH_ASSOC);

//goutouテーブル(hiduke)null以外の総数
$sql= $pdo->prepare("
SELECT count(hiduke) as hiduke FROM $goutb
	")or die ("失敗");
$sql->execute();
$result3= $sql->fetch(PDO::FETCH_ASSOC);

//リロードした時に並び替えるカラム名を置き換える
switch ($sort) {
	case 'sort1':
		$order= 'buil';
		break;
    case 'sort2':
        $order= 'bldg';
        break;
    case 'sort3':
        $order= 'max';
        break;
    case 'sort4':
        $order= 'waribuil';
        break;
    case 'sort5':
        $order= 'waribldg';
        break;
        default:
        $order= 'buil';
    }
//市区町村別(city)に団地、棟の総数と、ratioテーブル一か月分のmin値とmax値を取得
$sql= "
SELECT 
  danchilist.city,
  COUNT(DISTINCT danchilist.code) AS buil,
  COUNT(DISTINCT $goutb.codeno) AS bldg,
  MIN($ratiotb.ratio2) AS builmin,
  MAX($ratiotb.ratio2) AS builmax,
  MIN($ratiotb.ratio) AS min,
  MAX($ratiotb.ratio) AS max,
  MAX($ratiotb.ratio2) / COUNT(DISTINCT $goutb.code) * 100 AS waribuil,
  MAX($ratiotb.ratio) / COUNT(DISTINCT $goutb.codeno) * 100 AS waribldg
FROM 
  $goutb
JOIN 
  danchilist ON $goutb.code = danchilist.code
JOIN 
  $ratiotb ON danchilist.city = $ratiotb.city
WHERE 
  $ratiotb.datetime BETWEEN (NOW() - INTERVAL 10 MONTH) AND NOW()
GROUP BY 
  danchilist.city
ORDER BY 
  $order DESC


	";
$result4= $pdo->query($sql);


//市区町村別に最新から一ヶ月の部品登録数を取得(min)~(max)
$sql= "
SELECT $ratiotb.city, min($ratiotb.ratio) as min, max($ratiotb.ratio) as max
  FROM $ratiotb
WHERE $ratiotb.datetime BETWEEN (NOW() - INTERVAL 10 MONTH) AND NOW()
GROUP BY city
    ";
$result5= $pdo->query($sql);

//比率 = 比くらべられる量りょう ÷ もとにする量りょう × 100//
//hidukeカラム総比率
$ratioall= $result3['hiduke'] / $result2['codeno'] * 100;
$ratioall2= $result6['buil'] / $result['code'] * 100;?>

<br><br><br><br>
 <h1>部品登録状況</h1>
<table id='ratio' class="ratio">
	<tr><th>団地総数</th><th>団地登録数</th><th>団地登録率</th><th>総棟数</th><th>部品総登録数</th><th>棟登録率</th><th>一ヶ月比</th></tr>
	<tr><td><?php echo $result['code'];?></td><td><?php echo $result6['buil'];?></td><td><?php echo round($ratioall2, 1);echo '%';?></td><td><?php echo $result2['codeno'];?></td><td><?php echo $result3['hiduke'];?></td>
		<td><?php echo round($ratioall, 1);echo '%';?></td>
		<td>
	<?php
	//最新一ヶ月のトータル登録件数を取得(配列の中身を全て足してmax値からmin値を引く)
	$min= 0;
	$max= 0;
	    foreach ($result5 as $row) {
        	    $min += $row['min'];
        	    $max += $row['max'];
        }
        $total = $max - $min;
        echo "+$total";
		?>
		</td>
	</tr>
</table>




    <!-- (並び替えソート)セレクトボックスを選択した時にリンクへ飛ぶイベント -->
<script>
    function dropsort(){
        var browser= document.sort_form.sort.value;
        location.href= browser;
    }
</script>



<form name="sort_form">
<select name="sort" onchange="dropsort()">
    <option value="./allratio.php">--並び順--</option>
    <option value="./allratio.php?sort=<?php echo 'sort1';?>"<?php if($sort == 'sort1'){echo 'selected';}?>>団地総数多い順</option>
    <option value="./allratio.php?sort=<?php echo 'sort2';?>"<?php if($sort == 'sort2'){echo 'selected';}?>>総棟数多い順</option>
    <option value="./allratio.php?sort=<?php echo 'sort3';?>"<?php if($sort == 'sort3'){echo 'selected';}?>>棟部品登録数多い順</option>
    <option value="./allratio.php?sort=<?php echo 'sort5';?>"<?php if($sort == 'sort5'){echo 'selected';}?>>棟部品登録割合多い順</option>

</select>
</form>
<!-- END -->
<table id='ratio' class="ratio2">
    <tr><th>市区町村</th><th>団地総数<span>(市区町村別)</span></th><th>団地登録数</th><th>団地登録率</th><th>総棟数</th><th>部品登録棟数</th><th>棟登録率</th><th>一ヶ月比</th></tr>
<?php foreach($result4 as $row){?>
    <tr><td><?php echo $row['city'];?></td>
        <td><?php echo $row['buil'];?></td>
        <td><?php echo $row['builmax']?></td>
        <td><?php echo round($row['waribuil'], 0);echo '%';?></td>
        <td><?php echo $row['bldg'];?></td>
        <td><?php echo $row['max'];?></td>
        <td><?php echo round($row['waribldg'], 0);echo '%';?></td>
        <td>
    <?php
    //最新一ヶ月の市区町村別に登録件数を取得(配列の中身を全て足してmax値からmin値を引く)
    $hiduke = $row['max'] - $row['min'];
        if($hiduke > 0){
        echo "+$hiduke";
    }elseif($hiduke == 0){
    	echo "<style>#ratio span:last-child{color: black;}</style><span>$hiduke</span>";
    }
    ?></td></tr>
<?php }?>
</table>





<!-- 並び替えて<td>に色を付ける -->
<script>
    var sort = '<?php echo $sort;?>';

    switch(sort){
        case 'sort1':
        $('.ratio2 td:nth-child(2)').css('background-color','#b4ff7b');
        break;
        case 'sort2':
        $('.ratio2 td:nth-child(5)').css('background-color','#b4ff7b');
        break;
        case 'sort3':
        $('.ratio2 td:nth-child(6)').css('background-color','#b4ff7b');
        break;
        case 'sort5':
        $('.ratio2 td:nth-child(7)').css('background-color','#b4ff7b');
        break;
    }
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