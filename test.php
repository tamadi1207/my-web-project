<?php
$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'aa203mj2l7'; $pass= 'wuMtvMRz';
//$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'root'; $pass= '';

try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}

//レコードがなければINSERT,あればUPDATEをする
$sql= $pdo->prepare("UPDATE test SET name='鹿児島' WHERE name='青森'") or die ("失敗");
$sql->execute();
?>
