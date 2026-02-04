<?php
/*
*実際にDBに値を挿入する処理をする
*/

// 返信が配列になるようにする
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){

    //値の取得
    $teamName = $_POST['name'];
    $memberCount = $_POST['old'];

    //DBファイルの操作
    require_once './DbOparation.php';
    $db = new DbOparation();

    //値の挿入
    if($db->saveBoy($name,$Old)){
        $response['error']=false;
        $response['message']= '登録が完了しました';
    }else{
        $response['error'] = true;
        $response['message'] = '登録できませんでした';
    }

}else{
    $response['error'] = true;
    $response['messgae'] = 'あなたは承認されていません';
}
echo json_encode($responce);