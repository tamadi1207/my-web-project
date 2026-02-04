<?php
require_once './db_info.php';


try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}

?>