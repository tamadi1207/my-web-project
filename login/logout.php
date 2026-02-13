<?php
require '../db_info.php'; // セッション開始のため

// セッション変数を空にする
$_SESSION = array();

// セッションクッキーを削除（ブラウザ側のセッションID破棄）
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッション自体の破壊
session_destroy();

// 以前使っていたログイン用クッキーも念のため削除
setcookie('ID', '', time() - 3600, '/');

$errorMessage = "ログアウトしました。";
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../css/style.css" rel="stylesheet" media="all">
    <title>ログアウト</title>
  </head>
  <body>
      <div id="contener">
        <div id="header">
            <img src="../img/html/logo.png" alt="rogo" id="rogoimg">
        </div>
        <div style="margin-top: 30px; font-weight:bold;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        <ul>
            <li><a href="login.php">ログイン画面に戻る</a></li>
        </ul>
        <div id="footer">
          Copyright &copy; Repair house All Rights Reserved.
        </div>
      </div>      
  </body>
</html>