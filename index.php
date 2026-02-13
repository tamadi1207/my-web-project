<?php
require 'db_info.php';
require './cookie.php';
$path= './';

// ▼▼▼ セキュリティヘッダーの追加 ▼▼▼
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

//index.phpのみ左上検索バー非表示
$index= 'on';
//カテゴリーメニューを非表示(index.phpとlist.phpとuserlist.php)
$category= 'on';

$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>団地検索</title>
        <link href="css/index.css?ac" rel="stylesheet" media="all">
        <link rel="preload" href="css/index.css?ac" as="style">
        <?php require './require/header.php';?>

        <div class="searchArea">
            <h1>団地検索</h1>

<?php if($id === 'tamadi'){?>
            <a href='./manager/userlist.php'>ユーザー履歴</a><br>
<?php }?>

            <form method="POST" action="list.php">
                <ul>
                    <li><i class="fa fa-chevron-circle-right"></i>種別:
                        <label><input type="checkbox" name="toei" value="都営">都営</label>
                        <label><input type="checkbox" name="kosya" value="公社">公社</label>
                        <label><input type="checkbox" name="tomin" value="都民">都民</label>
                        <label><input type="checkbox" name="kuei" value="区営">区営</label>
                        <label><input type="checkbox" name="other" value="その他">その他</label>
                    </li>
                    <li><i class="fa fa-chevron-circle-right"></i>団地名:&nbsp;
                        <input class="text" type="search" name="name">
                    </li>
                    <li><i class="fa fa-chevron-circle-right"></i>住所:&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="text" type="search" name="jusyo">
                    </li>
                </ul>
                <input type="hidden" name="page" value="0">
                <input type="hidden" name="index" value="index">
                <div class="btns">
                    <input type="submit" value="検索" class="btn btn-send">
                </div>
            </form>
        </div>

        <?php 
        // 以前のコードにあった SELECT * FROM goutoucomment WHERE no='$no' の部分は
        // $no が未定義であり、かつループ内処理も空だったため削除しました。
        // もし必要であれば、以下のようにプレースホルダを使って復活させてください。
        /*
        if(isset($no)){
            try {
                $sql = $pdo->prepare("SELECT * FROM goutoucomment WHERE no = :no");
                $sql->bindValue(':no', $no, PDO::PARAM_INT);
                $sql->execute();
                while($row = $sql->fetch()){
                    // 表示処理
                }
            } catch (PDOException $e) {
                // エラーログ出力など
            }
        }
        */
        ?>

        <div id="footer">
            Copyright &copy; <?php echo htmlspecialchars($htcreate ?? date('Y'), ENT_QUOTES);?> All Rights Reserved.
        </div>
    </div> </body>
    
    <?php
    try {
        // 訪問回数更新 (プリペアドステートメント化)
        // count = count + 1 はSQL標準なのでそのままでOKですが、nameはバインドします
        $sqlcnt = $pdo->prepare("UPDATE db_user SET count = count + 1 WHERE name = :name");
        $sqlcnt->bindValue(':name', $id, PDO::PARAM_STR);
        $sqlcnt->execute();

        // 履歴レコード追加 (プリペアドステートメント化)
        $sql = $pdo->prepare("INSERT INTO indexhistory (user, datetime) VALUES(:user, now())");
        $sql->bindValue(':user', $id, PDO::PARAM_STR);
        $sql->execute();
        
    } catch (PDOException $e) {
        // 本番環境ではエラー詳細は表示せずログに残す
        error_log("DB Error in index.php: " . $e->getMessage());
    }
    ?>
    
    <script>
        // 検索ボタンに触れた瞬間に、list.php の CSS をプリフェッチ
        $('.btn-send').on('mouseenter touchstart', function() {
            if (!$('#preload-list').length) {
                $('<link id="preload-list" rel="prefetch" href="css/ichiran.css?df">').appendTo('head');
            }
        });
    </script>
</html>
<?php
$pdo = NULL;
} else {
    // ログインしていない場合は、ログイン画面へ飛ばす
    header("Location: ./login/login.php");
    exit;
}
?>