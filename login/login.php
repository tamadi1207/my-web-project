<?php
// セッション開始（db_info内でsession_startされています）
require '../db_info.php';

$errorMessage = "";

// ログインボタンが押された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login"])) {
    
    $userid = $_POST["userid"] ?? '';
    $password = $_POST["password"] ?? '';

    // 1. 入力チェック
    if (empty($userid)) {
        $errorMessage = "ユーザIDが未入力です。";
    } else if (empty($password)) {
        $errorMessage = "パスワードが未入力です。";
    }

    // 2. 認証処理
    if (!empty($userid) && !empty($password)) {
        try {
            // PDO接続
            $pdo = new PDO("mysql:host=$serv;dbname=$db_name;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // プリペアドステートメントでユーザ取得
            $stmt = $pdo->prepare("SELECT * FROM db_user WHERE name = :name");
            $stmt->bindValue(':name', $userid, PDO::PARAM_STR);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $db_password = $row['password'];

                // ▼ パスワード照合 ▼
                if (password_verify($password, $db_password)) {
                    
                    // 3. 認証成功：セッションID再生成 (セキュリティ対策)
                    session_regenerate_id(true);

                    // セッションにユーザーIDを保存
                    $_SESSION["USERID"] = $userid; 
                    
                    // index.php へ移動
                    header("Location: ../index.php");
                    exit;
                } else {
                    $errorMessage = "ユーザIDあるいはパスワードに誤りがあります。";
                }
            } else {
                $errorMessage = "ユーザIDあるいはパスワードに誤りがあります。";
            }
        } catch (PDOException $e) {
            $errorMessage = "データベースエラーが発生しました。";
        }
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
    <title>ログイン画面</title>
</head>
<body>
<div id="contener">  
    <div id="header">
        <img src="../img/html/logo.png" alt="rogo" id="rogoimg">
    </div>

    <form id="loginForm" name="loginForm" action="" method="POST">
        <h1 style="margin-top: 30px;">ログインフォーム</h1>
        <div style="color:red; font-weight:bold;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        
        <label for="userid">ユーザID</label>
        <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($_POST["userid"] ?? '', ENT_QUOTES); ?>">
        <br>
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" value="">
        <br>
        <input type="submit" id="login" name="login" value="ログイン">
    </form>
    
    <div id="footer">Copyright &copy; Fixhome All Rights Reserved.</div>
</div>
</body>
</html>