<?php
/*
*DBに対する指示ファイル
*/

class DbOparation {
    private $conn;

    //コンストラクター
    function __construct(){
        require_once dirname(__FILE__).'./config.php';
        require_once dirname(__FILE__).'./DbConnect.php';
        //DBに接続
        $db = new DbConnect();
        $this -> conn = $db->connect();
    }

    //新しいユーザーを作成する機能
    public function saveBoy($name, $Old) 
    {
        $stmt = $this->conn->prepare("INSERT INTO boy(name, old) values(?, ?)");
        $stmt -> bind_param("ss", $name, $Old);
        $result = $stmt -> execute();
        $stmt -> close();
        if($result) {
            return true;
        }else{
            return false;
        }
    }