
<?php

// セッション開始
require '../db_info.php';

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
  // １．ユーザIDの入力チェック
  if (empty($_POST["userid"])) {
    $errorMessage = "ユーザIDが未入力です。";
  } else if (empty($_POST["password"])) {
    $errorMessage = "パスワードが未入力です。";
  }

  // ２．ユーザIDとパスワードが入力されていたら認証する
  if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
    // mysqlへの接続
    $mysqli = new mysqli($serv, $user, $pass);
    if ($mysqli->connect_errno) {
      print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
      exit();
    }

    // データベースの選択
    $mysqli->select_db($db_name);

    // 入力値のサニタイズ
    $userid = $mysqli->real_escape_string($_POST["userid"]);

    // クエリの実行
    $query = "SELECT * FROM db_user WHERE name = '" . $userid . "'";
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }

    while ($row = $result->fetch_assoc()) {
      // パスワード(暗号化済み）の取り出し
      $db_hashed_pwd = $row['password'];
      $passwordHash = password_hash($db_hashed_pwd, PASSWORD_DEFAULT);
    }

    $mysqli->close();

    // ３．画面から入力されたパスワードとデータベースから取得したパスワードのハッシュを比較します。
    //if ($_POST["password"] == $pw) {

      //////////////クッキー発行(有効期限 2030/1/1/0/00/00)
setcookie('ID', $_POST["userid"], [
    'expires' => time() + 1893423600,
    'path' => '/',
    'secure' => false,          // ローカルや非HTTPS環境でも動くように false にする
    'httponly' => true,
    'samesite' => 'Lax'
]);
      //////////////

      // ４．認証成功なら、セッションIDを新規に発行する
    if (password_verify($_POST['password'], $passwordHash)) {
      $_SESSION["USERID"] = $_POST["userid"];
      header("Location: ../index.php");
      exit;
      session_regenerate_id(true);
    } 
    else {
      // 認証失敗
      $errorMessage = "ユーザIDあるいはパスワードに誤りがあります。";
    } 
  } else {
    // 未入力なら何もしない
  } 
} 
 
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
  <title>ログイン画面</title>
  </head>
  <body>
<div id="contener">  
          <div id="header">
            <img src="../img/html/logo.png" alt="rogo" id="rogoimg">
          </div>
<!--<form id="loginForm" name="loginForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">-->
  <form id="loginForm" name="loginForm" action="" method="POST">

      <h1 style="margin-top: 30px;">ログインフォーム</h1>
  <div><?php echo $errorMessage ?></div>
  <label for="userid">ユーザID</label><input type="text" id="userid" name="userid" value="">
  <br>
  <label for="password">パスワード</label><input type="password" id="password" name="password" value="">
  <br>
  <input type="submit" id="login" name="login" value="ログイン">

  </form>
        <div id="footer">
         Copyright &copy; Fixhome All Rights Reserved.
        </div>
</div>
  </body>
</html>
