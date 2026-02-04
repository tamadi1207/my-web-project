<?php 


$db_name= 'ss222251_danchisearch'; $serv= 'mysql2.star.ne.jp'; $user= 'ss222251_ht'; $pass= 'tamadi1192';


try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}


//レコード追加
$sql= $pdo->prepare("INSERT INTO z_hospital (date) VALUES (now())") or die ("失敗");
$sql->execute();

$sqlcnt= $pdo->prepare("SELECT count(date) FROM pointnote") or die ("失敗");
$sqlcnt->execute();

while ($row2= $sqlcnt->fetch())
{
	echo $row2['date'];
}
echo "登録した。"

?>