<!DOCTYPE html>

<html>
    <head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8">  
        <title>備考</title></head>
<body> 
<?php

$code= $_GET['code'];
$syubetu= $_GET['syubetu'];
$name= $_GET['name'];
?>
    <table border="1" height="30">
    <tr><td><?php print $code;?></td>
    <td width="60"><?php print $syubetu;?></td>
    <td width="260"><?php print $name;?></td></tr>
    </table>
<?php
require_once './db_info.php';

try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}


$sql = $pdo->prepare("SELECT * FROM danchilist WHERE code='$code'") or die ("失敗");
$sql->execute();


while ($row= $sql->fetch())
{
?>     
    <table border="1" algin="center">
    <form method='GET' action='building.php'>
    メモ
    <br clear="right">
    <textarea name='memo' rows='20' cols='40' placeholder="団地情報入力"><?php print htmlspecialchars($row[7]);?></textarea>
    </br>    
    <input type='hidden' name='code' value='<?php print $code;?>'>
        <input type='hidden' name='name' value='<?php print $name;?>'>
        <input type='hidden' name='syubetu' value='<?php print $syubetu;?>'>
    <input type='submit' value=更新>
    </form>
    </table>  

<?php

}
$pdo = null;
?>
</body>
</html>