<?php
require 'db_info.php';
require './cookie.php';
$path= './';
//index.phpのみ左上検索バー非表示
$index= 'on';
//
//カテゴリーメニューを非表示(index.phpとlist.phpとuserlist.php)
$category= 'on';
//
$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");


// ログイン状態のチェック
if ($cntid == 1) {;
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

<?php    if($id === 'tamadi'){?>
        <a href='./manager/userlist.php'>ユーザー履歴aiia</a><br>
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
                                <input class="text" type="search" name="name"></li>
                            <li><i class="fa fa-chevron-circle-right"></i>住所:&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="text" type="search" name="jusyo">
                        </ul>
                            <input type="hidden" name="page" value="0">
                            <input type="hidden" name="index" value="index">
                        <div class="btns">
                            <input type="submit" value="検索" class="btn btn-send">
                            </form>
                        </div>
                        <!-- <div id="scroll">    
                            <iframe src="news/sp/top-umekomi.php" width="100%" height="500" frameborder="0" scrolling="auto" style="max-width: 700px; display: block; margin: 0 auto;">
                            </iframe>                        
                        </div> -->



<!-- <br><br>
<a href='./move/home_icon.php'>iphoneのホーム画面にショートカットアイコンに追加する方法</a>
-->

            </div>

          <?php @$sql= $pdo->prepare("SELECT * FROM goutoucomment WHERE no='$no'") or die ('失敗');
                @$sql->execute();

                while($row= $sql->fetch()){

}?>
                    <div id="footer">
                        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
                    </div>
            </div>

        </body>
        <?php
            //訪問回数
            $sqlcnt= $pdo->prepare("UPDATE db_user SET count= COUNT+1 WHERE name= '$id'") or die ("失敗");
            $sqlcnt->execute();

            //レコード追加
            $sql= $pdo->prepare("INSERT INTO indexhistory (user,datetime) VALUES('$id',now())") or die ("失敗");
            $sql->execute();
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
    // クッキーがない、またはDBにユーザーがいない場合
    echo "みれない";
    // デバッグ用：何が原因か表示させる場合は以下をコメント解除
    var_dump($cntid);

}
// ここにあった余計な } を削除
?>