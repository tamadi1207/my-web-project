<?php
//$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'aa203mj2l7'; $pass= 'wuMtvMRz';
$db_name= 'aa203mj2l7_rhouse'; $serv= '127.0.0.1'; $user= 'root'; $pass= '';

try{
    $pdo = new PDO("mysql:dbname=$db_name;host=$serv","$user","$pass",
    array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'UTF8'"));
} catch (Exception $ex) {
    die($e->getMessage());
}

$flag = !empty($_POST['flag']) ? htmlspecialchars($_POST['flag']) : NULL;

try
{

    // 'users' テーブルのデータを取得する
    $sql = "select * from danchilist  where danchilist.city = '$flag'";
    $stmt = $pdo->query($sql);

    // 取得したデータを配列に格納
    while ($row = $stmt->fetchObject())
    {
        $users[] = array(
            'code'=> $row->code
            ,'name' => $row->name
            ,'city' => $row->city
            );
    }

    if(empty($users)){$users = array();}//配列の結果が0件の場合、配列を初期化
    // JSON形式で出力する

    //
    header(header('Access-Control-Allow-Origin: *'));
    echo json_encode( $users, JSON_UNESCAPED_UNICODE);
    exit;
}
catch (PDOException $e)
{
    // 例外処理
    die('Error:' . $e->getMessage());}
    ?>