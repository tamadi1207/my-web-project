<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';

//
//カテゴリーメニューを非表示(index.phpとlist.phpとuserlist.php)
$category= 'on';
//
$builedit= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li class='aaa'><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");


// ログイン状態のチェック
if ($cntid == 1) {;
//訪問回数
$sqlcnt= $pdo->prepare("UPDATE db_user SET count= COUNT+1 WHERE name= '$id'") or die ("失敗");
$sqlcnt->execute();

//レコード追加
$sql= $pdo->prepare("INSERT INTO indexhistory (user,datetime) VALUES('$id',now())") or die ("失敗");
$sql->execute();
?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>団地検索</title>
            <link href="css/index.css?ac" rel="stylesheet" media="all">

<?php require '../require/header.php';?>






<style>
    img{
        margin: 5% 0 20% 15%;
        width: 60%;
        height: 60%;
    }
    li{
        margin-top: 10%;
        font-size: 1.1em;
    }
</style>

                <h1>iphoneのホーム画面にアイコンを追加する方法</h1>

<ol>
    <li><strong style='color: red;' >必ず下のトップ画面の状態で</strong>、
    下の赤いアイコンを選択します。</li>
        <img src='../img/move/icon_1.PNG'>

    <li><strong>ホーム画面に追加</strong>を選択します。</li>
        <img src='../img/move/icon_3.PNG'>

    <li>任意のタイトルを入力して追加を選択します。</li>
        <img src='../img/move/icon_4.PNG'>    

    <li>ホーム画面にアイコンが追加されました。</li>
        <img src='../img/move/icon_5.PNG'>    
</ol>
    



                    <div id="footer">
                        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
                    </div>
            </div>
        </body>
    </html>
<?php
$pdo= NULL;
}