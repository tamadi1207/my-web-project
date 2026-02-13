<?php
require './db_info.php';
require './cookie.php'; // ここで $id ($cntid) が作られます
$path= './';

// メニュー定義
$builedit = array(
    "<li><a href='{$path}danchihensyu/danchihensyu.php?code=" . htmlspecialchars($_GET['code'] ?? '') . "&syubetu=" . htmlspecialchars($_GET['syubetu'] ?? '') . "&name=" . htmlspecialchars($_GET['name'] ?? '') . "&address=" . htmlspecialchars($_GET['address'] ?? '') . "'>団地情報編集</a></li>",
    "<li><a href='{$path}touhensyu/newnum.php?code=" . htmlspecialchars($_GET['code'] ?? '') . "&syubetu=" . htmlspecialchars($_GET['syubetu'] ?? '') . "&name=" . htmlspecialchars($_GET['name'] ?? '') . "&address=" . htmlspecialchars($_GET['address'] ?? '') . "'>棟追加</a></li>",
    "<li><a href='{$path}danchihensyu/buildelete.php?code=" . htmlspecialchars($_GET['code'] ?? '') . "&syubetu=" . htmlspecialchars($_GET['syubetu'] ?? '') . "&name=" . htmlspecialchars($_GET['name'] ?? '') . "&address=" . htmlspecialchars($_GET['address'] ?? '') . "'>団地削除</a></li>"
);
// $builedit2 も同様にエスケープすべきですが、省略します

// ログイン状態のチェック
if ($cntid == 1) {
    // ▼▼▼ 修正箇所: クッキーではなくセッション由来の $id を使用 ▼▼▼
    $userid = $id; 
?>
<!DOCTYPE html>
<html>
<head>
    <link href="css/goutou.css?q" rel="stylesheet" media="all">
    <link rel="stylesheet" href="jquery/Lightbox/css/lightbox.css">
    <title>団地内検索</title>
    <?php require './require/header.php';

    $building_cmt = $building_cmt ?? ''; 
    $building_cmt2 = $building_cmt2 ?? '';

    // GETパラメータの取得（エスケープ）
    $code    = htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);
    $syubetu = htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);
    $name    = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);
    $address = htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);
    $map     = htmlspecialchars($_GET['map'] ?? '', ENT_QUOTES);

    // 履歴保存 (プリペアドステートメント)
    if (!empty($code) && !empty($userid)) {
        try {
            $sql = $pdo->prepare(
                "INSERT INTO builhistory (code, name, type, user, datetime)
                 VALUES (:code, :name, :type, :user, NOW())"
            );
            $sql->execute([
                ':code' => $code,
                ':name' => $name,
                ':type' => $typeid, // cookie.phpで定義済み
                ':user' => $userid
            ]);
        } catch (PDOException $e) {
            // エラー時はログに残すなど（画面には出さない）
        }
    }
    ?>

    <div class="builbox">
        <h2>
            <?php echo $syubetu;?>&nbsp;<label><?php echo $name; ?></label>&nbsp;&nbsp;&nbsp;
            <span><?php echo $address; ?>&nbsp;&nbsp;<a href='./mapjump.php?code=<?php echo $code;?>&name=<?php echo $name;?>&address=<?php echo $address;?>'>地図</a></span>
        </h2>
        <a class="commentbtn builcommentbtn" href="bldgcmt.php?code=<?php echo $code;?>&name=<?php echo $name;?>&address=<?php echo $address;?>&syubetu=<?php echo $syubetu;?>"><i class="fa fa-camera"></i><i class="fa fa-comment"></i>団地COMMENT</a>

        <?php 
        // 棟一覧取得（SQLインジェクション対策）
        // $goutb はテーブル名なのでプレースホルダにできませんが、cookie.phpで固定値が入るため安全とみなします
        $sql2 = $pdo->prepare("SELECT $goutb.code, $goutb.codeno, $goutb.goutou, $goutb.goutouvar, $goutb.hiduke, 
            count(distinct(goutoucomment.`comment`)) as `cntcmt`, 
            count(distinct(goutoucomment.img)) as cntimg, 
            count(distinct(partsimg.img)) as cnt_upimg 
            FROM $goutb LEFT JOIN goutoucomment ON $goutb.codeno = goutoucomment.codeno 
            LEFT JOIN partsimg ON $goutb.codeno = partsimg.codeno 
            WHERE $goutb.code = :code GROUP BY goutouvar, goutou");
        $sql2->bindValue(':code', $code, PDO::PARAM_INT);
        $sql2->execute();
        ?>

        <div class="boxchenge">   
            <div class="toubox">
                <h3><span>棟一覧</span></h3>
                <?php
                while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                    // 表示用変数
                    $disp_goutou = empty($row2['goutou']) ? $row2['goutouvar'] : $row2['goutou'];
                    $has_content = ($row2['cntcmt'] > 0 || $row2['cntimg'] > 0);
                ?>
                    <ul id="toulist">
                        <li style="list-style:none;">
                            <?php if ($has_content) { ?><i class="fa fa-commenting-o icon"></i><?php } ?>
                            <a class="button" href='parts.php?syubetu=<?php echo $syubetu;?>&name=<?php echo $name;?>&address=<?php echo $address;?>&code=<?php echo htmlspecialchars($row2['code']);?>&map=<?php echo $map;?>&codeno=<?php echo htmlspecialchars($row2['codeno']);?>&goutou=<?php echo htmlspecialchars($row2['goutou']);?>&goutouvar=<?php echo htmlspecialchars($row2['goutouvar']);?>&date=<?php echo htmlspecialchars($row2['hiduke']);?>'>
                                <?php echo htmlspecialchars($disp_goutou); ?>号棟
                                <span class="touhiduke"><?php echo htmlspecialchars($row2['hiduke']);?></span>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </div>

            <div class="info">
                <div class="builinfo">
                    <h4>団地コメント</h4>
                    <?php
                    // 団地コメント取得（SQLインジェクション対策）
                    // $building_cmt はWHERE句の一部ですが、固定文字列（cookie.php参照）なのでそのまま結合
                    // ただし、外部入力を混ぜないように注意
                    $sql = $pdo->prepare("SELECT * FROM danchicomment WHERE code = :code AND type = :type $building_cmt ORDER BY hiduke desc");
                    $sql->bindValue(':code', $code, PDO::PARAM_INT);
                    $sql->bindValue(':type', $typeid, PDO::PARAM_STR);
                    $sql->execute();
                    $count = $sql->rowCount();

                    if ($count == 0) {
                        echo 'コメントはありません。';
                    } else {
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $is_owner = ($row['name'] === $userid); // 自分の投稿か判定
                    ?>
                        <dl>
                            <dt><span class="hiduke"><?php echo htmlspecialchars($row['hiduke']);?></span>&nbsp;<?php echo htmlspecialchars($row['name']);?></dt>
                            <dd>
                                <div class="builcmt"><?php echo nl2br(htmlspecialchars($row['comment']));?></div>
                                <?php if (!empty($row['img'])) { ?>
                                    <a href="./img/bldg/<?php echo htmlspecialchars($row['code']);?>/<?php echo htmlspecialchars($row['img']);?>" data-lightbox="bldg" data-title="<?php echo htmlspecialchars($row['comment']);?>">
                                        <img class="builimg" onerror="this.style.display='none'" src="./img/bldg/<?php echo htmlspecialchars($row['code']);?>/<?php echo htmlspecialchars($row['img']);?>">
                                    </a>
                                <?php } ?>
                                
                                <div class="buildel">
                                    <?php if ($is_owner) { ?>
                                        <a href='bldgcmtedit.php?syubetu=<?php echo $syubetu;?>&code=<?php echo $code;?>&name=<?php echo $name;?>&address=<?php echo $address;?>&no=<?php echo $row['no'];?>'>編集</a>
                                        <a href='bldgcmtdelete.php?syubetu=<?php echo $syubetu;?>&code=<?php echo $code;?>&name=<?php echo $name;?>&address=<?php echo $address;?>&no=<?php echo $row['no'];?>&comment2=<?php echo htmlspecialchars($row['comment']);?>'>削除</a>
                                    <?php } ?>
                                </div>
                            </dd>
                        </dl>
                    <?php 
                        }
                    } 
                    ?>
                </div>

                <div class="bldginfo">
                    <h4>棟コメント</h4>
                    <?php
                    // 棟コメント取得
                    $sql4 = $pdo->prepare("SELECT * FROM goutoucomment WHERE code = :code AND type = :type $building_cmt2 ORDER BY hiduke desc");
                    $sql4->bindValue(':code', $code, PDO::PARAM_INT);
                    $sql4->bindValue(':type', $typeid, PDO::PARAM_STR);
                    $sql4->execute();
                    $count2 = $sql4->rowCount();

                    if ($count2 == 0) {
                        echo 'コメントはありません。';
                    } else {
                        while ($row3 = $sql4->fetch(PDO::FETCH_ASSOC)) {
                            $is_owner = ($row3['name'] === $userid);
                    ?>
                        <dl class='toucmt'>
                            <dt><span class="hiduke"><?php echo htmlspecialchars($row3['hiduke']);?></span>&nbsp;<?php echo htmlspecialchars($row3['name']);?>&nbsp;(<?php echo htmlspecialchars($row3['goutou']);?>号棟)</dt>
                            <dd>
                                <div class="builcmt"><?php echo nl2br(htmlspecialchars($row3['comment']));?></div>
                                <?php if (!empty($row3['img'])) { ?>
                                    <a href="./img/building/<?php echo htmlspecialchars($row3['code']);?>/<?php echo htmlspecialchars($row3['img']);?>" data-lightbox="building" data-title="<?php echo htmlspecialchars($row3['comment']);?>">
                                        <img class="builimg" onerror="this.style.display='none'" src="./img/building/<?php echo htmlspecialchars($row3['code']);?>/<?php echo htmlspecialchars($row3['img']);?>">
                                    </a>
                                <?php } ?>

                                <div class="buildel">
                                    <?php if ($is_owner) { ?>
                                        <a href='buildingcmtedit.php?syubetu=<?php echo $syubetu;?>&code=<?php echo $code;?>&codeno=<?php echo $row3['codeno'];?>&goutou=<?php echo $row3['goutou'];?>&name=<?php echo $name;?>&address=<?php echo $address;?>&no=<?php echo $row3['no'];?>'>編集</a>
                                        <a href='buildingcmtdelete.php?syubetu=<?php echo $syubetu;?>&code=<?php echo $code;?>&codeno=<?php echo $row3['codeno'];?>&goutou=<?php echo $row3['goutou'];?>&name=<?php echo $name;?>&address=<?php echo $address;?>&no=<?php echo $row3['no'];?>&comment2=<?php echo htmlspecialchars($row3['comment']);?>'>削除</a>
                                    <?php } ?>
                                </div>
                            </dd>
                        </dl>
                    <?php 
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <style>
    @media screen and (max-width: 414px) {
        .boxchenge { display:flex; flex-flow: row wrap; }
        .toubox { order: 2; }
        .info { order: 1; }
        .builinfo { display: <?php echo ($count > 0) ? 'block' : 'none'; ?>; }
        .bldginfo { display: <?php echo ($count2 > 0) ? 'block' : 'none'; ?>; }
    }
    </style>

    <div id="footer">
        Copyright &copy; <?php echo htmlspecialchars($htcreate ?? '', ENT_QUOTES);?> Rights Reserved.
    </div>
    <script src="jquery/Lightbox/js/lightbox.min.js"></script>
</body>
</html>
<?php 
} else {
    // ログインしていない場合
    header("Location: ./login/login.php");
    exit;
}
?>