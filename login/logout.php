<?php
session_start();

if (isset($_COOKIE["ID"])) {
  $errorMessage = "ログアウトしました。";
}
else {
  $errorMessage = "ログインして下さい。";//"セッションがタイムアウトしました。";
}
// セッション変数のクリア
$_SESSION = array();
// クッキーの破棄は不要
//if (ini_get("session.use_cookies")) {
//    $params = session_get_cookie_params();
//    setcookie(session_name(), '', time() - 42000,
//        $params["path"], $params["domain"],
//        $params["secure"], $params["httponly"]
//    );
//}
// セッションクリア
@session_destroy();
  // クッキー破棄(現在時刻からマイナスの時間で指定する(例:60))
  setcookie('ID', $_COOKIE['ID'], time() - 60, '/');
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../css/style.css" rel="stylesheet" media="all">
    <link rel="icon" type="image/vnd.microsoft.icon" href="../img/html/builicon2.ico">
    <link rel="apple-touch-icon" href="img/html/builicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery/footerFixed/footerFixed.js"></script>
    <title>ログアウト</title>
  </head>
  <body>
      <div id="contener">
        <div id="header">
            <img src="../img/html/logo.png" alt="rogo" id="rogoimg">

        </div>
          <div style="margin-top: 30px;"><?php echo $errorMessage; ?></div>
  <ul>
  <li><a href="login.php">ログイン画面に戻る</a></li>
  </ul>
        <div id="footer">
          Copyright &copy;  Repair house All Rights Reserved.
        </div>
        </div>      
  </body>
</html>
