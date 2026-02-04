<?php
require './db_info.php';
require './cookie.php';
$path= './';
$category= 'on';
//履歴表示件数
$limit= 30;

$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
    <html>
        <head>
            <link href="css/history.css" rel="stylesheet" media="all">
            <title>団地検索</title>

<?php require './require/header.php';?>      

<div id="top_scroll"><a href="#"></a></div>
  
<div class="historybox">
    
    <section id="partshistory">
        <h5><i class="fa fa-file-text-o" aria-hidden="true"></i>部品登録履歴</h5>
<?php $sql= $pdo->prepare("SELECT partshistory.name, MAX(partshistory.datetime) as datetime, danchilist.code, danchilist.syubetu, danchilist.name, Concat(danchilist.city, danchilist.jusyo) as address, partshistory.user
FROM danchilist INNER JOIN partshistory on danchilist.code=partshistory.code 
WHERE partshistory.user = '$id'
GROUP BY partshistory.name
ORDER BY datetime DESC LIMIT $limit") or die ('失敗');
           $sql->execute();
           
            while($row= $sql->fetch()){?>
                           
        <ul>
            <li><span><?php print htmlspecialchars($row['syubetu']);?></span><span><a href='./building.php?code=<?php print htmlspecialchars($row['code']);?>&syubetu=<?php print htmlspecialchars($row['syubetu']);?>&name=<?php print htmlspecialchars($row['name']);?>&address=<?php print $row['address'];?>'><?php print htmlspecialchars($row['name']);?></a></span></li>
            <section class="historyitem">
                <li><?php print htmlspecialchars($row['address']);?></li>
                <li><span><?php print htmlspecialchars($row['datetime']);?></span><a href='./mapjump.php?code=<?php print htmlspecialchars($row['code']);?>&name=<?php print htmlspecialchars($row['name']);?>&address=<?php print $row['address'];?>'>地図</a></li>
            </section>
        </ul>
            <?php }?>
    </section>

    <section id="clickhistory">
        <h5><i class="fa fa-mouse-pointer" aria-hidden="true"></i>団地Click履歴</h5>
<?php $sql= $pdo->prepare("SELECT builhistory.name, MAX(builhistory.datetime) as datetime, danchilist.code, danchilist.syubetu, danchilist.name, Concat(danchilist.city, danchilist.jusyo) as address, builhistory.user
FROM danchilist INNER JOIN builhistory on danchilist.code=builhistory.code 
WHERE builhistory.user = '$id'
GROUP BY builhistory.name
ORDER BY datetime DESC LIMIT $limit") or die ('失敗');
           $sql->execute();
           
            while($row2= $sql->fetch()){?>
                           
        <ul>
            <li><span><?php print htmlspecialchars($row2['syubetu']);?></span><span><a href='./building.php?code=<?php print htmlspecialchars($row2['code']);?>&syubetu=<?php print htmlspecialchars($row2['syubetu']);?>&name=<?php print htmlspecialchars($row2['name']);?>&address=<?php print $row2['address'];?>'><?php print htmlspecialchars($row2['name']);?></a></span></li>
            <section class="historyitem">
                <li><?php print htmlspecialchars($row2['address']);?></li>
                <li><span><?php print htmlspecialchars($row2['datetime']);?></span><a href='./mapjump.php?code=<?php print htmlspecialchars($row2['code']);?>&name=<?php print htmlspecialchars($row2['name']);?>&address=<?php print $row2['address'];?>'>地図</a></li>
            </section>
        </ul>
            <?php }?>
    </section>
</div>

                    <div id="footer">
                        Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
                    </div>
            </div>            

        </body>
    </html>
<?php }
$pdo= NULL;