<?php
require './db_info.php';
require './cookie.php';

// Ajax通信ではなく、直接URLを叩かれた場合はエラーメッセージを表示
if (
    !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
    && (!empty($_SERVER['SCRIPT_FILENAME']) && 'json.php' === basename($_SERVER['SCRIPT_FILENAME']))
    ) 
{
    die ('このページは直接ロードしないでください。');
}

try{
    
$sql= $pdo->prepare("SELECT * FROM danchicomment WHERE code=1001") or die ('失敗4');
$sql->execute();

while($row= $sql->fetchObject())
{
        $row2[] = array(
            'code'=> $row->code
            ,'name' => $row->name
            );
}
    // JSON形式で出力する
    header('Content-Type: application/json');
    echo json_encode( $row2 );
    exit;
}
catch (PDOException $e)
{
    // 例外処理
    die('Error:' . $e->getMessage());
}