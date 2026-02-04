<?php
$db_name= 'ss222251_danchisearch'; $serv= 'mysql2.star.ne.jp'; $user= 'ss222251_ht'; $pass= 'tamadi1192';
//$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'aa203mj2l7'; $pass= 'wuMtvMRz';
//$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'root'; $pass= '';
try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}

////////////(部品登録率)に関するプログラム///////////////
$cityarray = array('千代田区','中央区','港区','新宿区','文京区','台東区','墨田区','江東区','品川区','目黒区','大田区','世田谷区','渋谷区',
         '中野区','杉並区','豊島区','北区','荒川区','板橋区','練馬区','足立区','葛飾区','江戸川区','八王子市','立川市','武蔵野市','三鷹市',
         '青梅市','府中市','昭島市','調布市','町田市','小金井市','小平市','日野市','東村山市','国分寺市','国立市','西東京市','福生市','狛江市',
         '東大和市','清瀬市','東久留米市','武蔵村山市','多摩市','稲城市','羽村市','瑞穂町','あきる野市','小笠原村');

foreach ($cityarray as $city){
//SELECTで出力したカラム(この場合3カラム)をINSERTする
////////////リペアハウス///////////////
$sql= $pdo->prepare("
    INSERT INTO ratio (ratio, ratio2, city, datetime)
    SELECT
     sum(case when danchilist.city='$city' then 1 else 0 end) as parts,
     (SELECT count(distinct(goutou.code)) FROM goutou JOIN danchilist ON goutou.code = danchilist.code WHERE danchilist.city='$city' AND goutou.hiduke IS NOT NULL) AS parts2, '$city', now()
    FROM goutou RIGHT JOIN danchilist ON goutou.code = danchilist.code
    WHERE goutou.hiduke
	") or die ("失敗");
$sql->execute();

////////////二件目の会社///////////////
$sql2= $pdo->prepare("
    INSERT INTO ratio2 (ratio, ratio2, city, datetime)
    SELECT
     sum(case when danchilist.city='$city' then 1 else 0 end) as parts,
     (SELECT count(distinct(goutou2.code)) FROM goutou2 JOIN danchilist ON goutou2.code = danchilist.code WHERE danchilist.city='$city' AND goutou2.hiduke IS NOT NULL) AS parts2, '$city', now()
    FROM goutou2 RIGHT JOIN danchilist ON goutou2.code = danchilist.code
    WHERE goutou2.hiduke
    ") or die ("失敗");
$sql2->execute();
}

////////////(閲覧履歴)の指定日付前のレコードを削除するプログラムを作る///////////////
?>