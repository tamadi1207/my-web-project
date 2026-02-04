<?php
require '../db_info.php';
require '../cookie.php';
$path= '../';
//カテゴリーメニューを非表示(index.phpとlist.phpとuserlist.php)
$category= 'on';
//
$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>    
    <!DOCTYPE html>
    <html>
        <head>
            <title>ユーザー履歴</title>

<?php require '../require/header.php';

$username= array('md','rui','kawanago','hasumi','pre','iida','kotake');
$sqlrear= "ORDER BY datetime DESC LIMIT 40";

for($i = 0; $i< count($username); $i++){
    @$where .= "indexhistory.user = '$username[$i]'";
    @$where2 .= "builhistory.user = '$username[$i]'";
    @$where3 .= "maphistory.user = '$username[$i]'";
    @$where4 .= "partshistory.user = '$username[$i]'";
    if($i <count($username) -1){
       $where .= " OR ";
       $where2 .= " OR ";
       $where3 .= " OR ";
       $where4 .= " OR ";
}}
?>
<a href="javascript:history.back();">戻る</a>
        <h1>ユーザー履歴</h1>
    <div id='userbox'>
        
        <section class='userhistory'>
            <p><strong>indexhistory</strong></p>
<?php $sql= $pdo->prepare("SELECT * FROM indexhistory WHERE $where $sqlrear") or die ('失敗');
       $sql->execute();
                   while($row= $sql->fetch()){
                       echo $row['user'],'　';
                       //echoだとhtml出力にカンマを付加すればエラーにならない
                       echo '<span>',date('Y年n月j日G時i分', strtotime($row['datetime'])),'</span><br>';
                   }?>
       </section>
        
       <section class='userhistory'>
           <p><strong>builhistory</strong></p>
<?php $sql= $pdo->prepare("SELECT * FROM builhistory WHERE $where2 $sqlrear") or die ('失敗');
       $sql->execute();
                   while($row= $sql->fetch()){
                       echo $row['user'],'　';
                       echo '<span>',date('Y年n月j日G時i分', strtotime($row['datetime'])),'</span><br>';
                   }?>
       </section>
        
       <section class='userhistory'>        
           <p><strong>maphistory</strong></p>
<?php $sql= $pdo->prepare("SELECT * FROM maphistory WHERE $where3 $sqlrear") or die ('失敗');
       $sql->execute();
                   while($row= $sql->fetch()){
                       echo $row['user'],'　';
                       echo '<span>',date('Y年n月j日G時i分', strtotime($row['datetime'])),'</span><br>';
                   }?>
       </section>
        
       <section class='userhistory'>
           <p><strong>partshistory</strong></p>
<?php $sql= $pdo->prepare("SELECT * FROM partshistory WHERE $where4 $sqlrear") or die ('失敗');
       $sql->execute();
                   while($row= $sql->fetch()){
                       echo $row['user'],'　';
                       echo '<span>',date('Y年n月j日G時i分', strtotime($row['datetime'])),'</span><br>';
                   }?>
       </section>        
    </div>
                    <div id="footer">
                        Copyright &copy;  Repair house All Rights Reserved.
                    </div>
            </div>            

        </body>
    </html>
<?php
$pdo= NULL;
}