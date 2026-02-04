<?php
session_start();
header('Expires:');
header('Cache-Control:');
header('Pragma:');

// 1はweb
// 0はlocalhost
$web = 0;
/////db_info.php(13行目)とcookie.php(51行目)とratio/cron.php(6行目)にswitch文アリ！！！！！




switch ($web) {
	case 1;
		$db_name= 'ss222251_danchisearch'; $serv= 'mysql2.star.ne.jp'; $user= 'ss222251_ht'; $pass= 'tamadi1192';
		break;
	case 0;
		$db_name= 'ss222251_danchisearch'; $serv= '127.0.0.1'; $user= 'root'; $pass= '';
		break;
}

switch($web){
	case 1;
		$URL= 'https://www.fixhome.me/search/';
		break;
	case 0;
		$URL= 'http://localhost/search';
		break;
}

/////ratio/cron.php(2行目)とcookie.php(48行目)もユーザー名変更！！

?>