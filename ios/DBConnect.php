<?php
class DbConnect {
    private $conn;

    function __construct(){
    }

    /*
    *データベースへの接続
    */
    function connect(){
        require_once './config.php';

        //Mysql DataBaseに接続
        $this -> conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //DB接続時のエラーがないか確認
        if(mysqli_connect_errno()){
            echo "データベースに接続されませんでした" . mysqli_connect_errno();
        }
        //接続リソースの保持
        return $this -> conn;