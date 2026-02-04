<?php
require './db_info.php';
require './cookie.php';
$path= './';
//カテゴリーメニューを非表示(index.phpとlist.phpのみ)
$category= 'on';
//

// ログイン状態のチェック
if ($cntid == 1) {

// SQLが始まる前に追加
$list_cmt = $list_cmt ?? '';
$list_cmt2 = $list_cmt2 ?? '';
$typeid = $typeid ?? 1; // もし $typeid もエラーが出るなら追加

$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");
?>
<!DOCTYPE html>

    <html>
        <head>
            <title>団地一覧</title>
            <link href="css/ichiran.css?df" rel="stylesheet" media="all">

<?php require './require/header.php';

                $toei = !empty($_POST['toei']) ? htmlspecialchars($_POST['toei']) : NULL;
                $kosya = !empty($_POST['kosya']) ? htmlspecialchars($_POST['kosya']) : NULL;
                $tomin = !empty($_POST['tomin']) ? htmlspecialchars($_POST['tomin']) : NULL;
                $kuei = !empty($_POST['kuei']) ? htmlspecialchars($_POST['kuei']) : NULL;
                $other = !empty($_POST['other']) ? htmlspecialchars($_POST['other']) : NULL;
                $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : NULL;
                $jusyo = isset($_POST['jusyo']) ? htmlspecialchars($_POST['jusyo']) : NULL;
                $page = isset($_POST['page']) ? htmlspecialchars($_POST['page']) : NULL;
                $page2 = isset($_POST['page2']) ? htmlspecialchars($_POST['page2']) : NULL;
                $count = isset($_POST['count']) ? htmlspecialchars($_POST['count']) : NULL;
                $prev = isset($_POST['prev']) ? htmlspecialchars($_POST['prev']) : NULL;
                
                $_SESSION['toei'] = !empty($_POST['toei']) ? htmlspecialchars($_POST['toei']) : NULL;
                $_SESSION['kosya'] = !empty($_POST['kosya']) ? htmlspecialchars($_POST['kosya']) : NULL;
                $_SESSION['tomin'] = !empty($_POST['tomin']) ? htmlspecialchars($_POST['tomin']) : NULL;
                $_SESSION['kuei'] = !empty($_POST['kuei']) ? htmlspecialchars($_POST['kuei']) : NULL;
                $_SESSION['other'] = !empty($_POST['other']) ? htmlspecialchars($_POST['other']) : NULL;
                $_SESSION['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : NULL;
                $_SESSION['jusyo'] = isset($_POST['jusyo']) ? htmlspecialchars($_POST['jusyo']) : NULL;
                $_SESSION['page'] = isset($_POST['page']) ? htmlspecialchars($_POST['page']) : NULL;

                $other2 = NULL;
                $other3 = NULL;
                
                $search= array('　','－','１','２','３','４','５','６','７','８','９','０','高校','1丁目','2丁目','3丁目','4丁目','5丁目','6丁目','7丁目','8丁目','9丁目','１丁目','２丁目','３丁目','４丁目','５丁目','６丁目','７丁目','８丁目','９丁目','第一','第二','第三','第四','第五','第六','第七','第八','第九');
                $replace= array(' ','-','1','2','3','4','5','6','7','8','9','0','高等学校','一丁目','二丁目','三丁目','四丁目','五丁目','六丁目','七丁目','八丁目','九丁目','一丁目','二丁目','三丁目','四丁目','五丁目','六丁目','七丁目','八丁目','九丁目','第1','第2','第3','第4','第5','第6','第7','第8','第9');
                
                if(isset($other))
                    {
                      $value = array("水道局","水道局住宅","高校","教育庁住宅","交通局住宅","下水道局住宅","総務局住宅","消防宿舎");
                      $other2 = implode("','", $value);
                      $other3 = implode("' and danchilist.syubetu LIKE '%", $value);
                    }
if ($toei . $kosya . $tomin . $kuei . $name . $jusyo . $other == "") {
                    echo  "<br><br><br><span id='listcmt'>入力して下さい。</span>";
                } else{
                if (!empty($toei) || !empty($kosya) || !empty($tomin) || !empty($kuei) || !empty($other) || !empty($other2)) {
                    //絞り込み検索
                    $where3= NULL;
                    $where4= NULL;

                    if(isset($name)){
                        if(strlen($name)>0){
                             $sql5= str_replace($search, $replace, $name);
                             $array3= explode(" ", $sql5);

                             for($i3= 0; $i3 <count($array3);$i3++){
                                 $where3 .= "(danchilist.name LIKE '%$array3[$i3]%')";

                                if($i3 <count($array3) -0){
                                     $where3 .= " AND ";
                    }}}}
                    if(isset($jusyo)){
                        if(strlen($jusyo)>0){
                             $sql6= str_replace($search, $replace, $jusyo);
                             $array4= explode(" ", $sql6);

                             for($i4= 0; $i4 <count($array4);$i4++){
                                 $where4 .= " AND (Concat(danchilist.city, danchilist.jusyo) Like '%$array4[$i4]%')";
                                      }}}
                     //END
                    $page *500;
                    $sql = ("Select SQL_CALC_FOUND_ROWS danchilist.code, danchilist.syubetu, danchilist.name,
  Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, Count(distinct($goutb.codeno)), Count($goutb.hiduke),
  Count($list_cmt danchicomment.type = '$typeid' or NULL) as 'dcmt', Count($list_cmt2 goutoucomment.type = '$typeid' or NULL) as 'tcmt'
From danchilist 
Right Join $goutb On danchilist.code = $goutb.code 
Left Join danchicomment On danchilist.code = danchicomment.code 
Left Join goutoucomment On danchilist.code = goutoucomment.code 
Where $where3 danchilist.syubetu in('$toei','$kosya','$tomin','$kuei','$other2') $where4 
Group By danchilist.code, danchilist.syubetu, danchilist.name,
  Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, $goutb.code
  Order By code limit $page, 500") or die("失敗");
                }else{
                    //絞り込み検索
                    $where= NULL;
                    $where2= NULL;
                    if(isset($name)){
                        if(strlen($name)>0){
                             $sql3= str_replace($search, $replace, $name);
                             $array= explode(" ", $sql3);

                             for($i= 0; $i <count($array);$i++){
                                 $where .= "(danchilist.name LIKE '%$array[$i]%')";

                                if($i <count($array) -0){
                                     $where .= " AND ";
                                            }}}}
                    if(isset($jusyo)){
                        if(strlen($jusyo)>0){
                             $sql4= str_replace($search, $replace, $jusyo);
                             $array2= explode(" ", $sql4);

                             for($i2= 0; $i2 <count($array2);$i2++){
                                 $where2 .= " AND (Concat(danchilist.city, danchilist.jusyo) Like '%$array2[$i2]%')";
                                      }}}
                     //END
                    $page *500;
                    $sql = ("Select SQL_CALC_FOUND_ROWS danchilist.code, danchilist.syubetu, danchilist.name,
  Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, Count(distinct($goutb.codeno)), Count($goutb.hiduke),
  Count($list_cmt danchicomment.type = '$typeid' or NULL) as 'dcmt', Count($list_cmt2 goutoucomment.type = '$typeid' or NULL) as 'tcmt'
From danchilist 
Right Join $goutb On danchilist.code = $goutb.code 
Left Join danchicomment On danchilist.code = danchicomment.code 
Left Join goutoucomment On danchilist.code = goutoucomment.code 
Where danchilist.syubetu LIKE '%$toei' and danchilist.syubetu LIKE '%$kosya' and danchilist.syubetu LIKE '%$tomin' and danchilist.syubetu LIKE '%$kuei' and $where danchilist.syubetu LIKE '%$other3' $where2
Group By danchilist.code, danchilist.syubetu, danchilist.name,
  Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, $goutb.code
  Order By code limit $page, 500") or die("失敗");
                }
                if(empty($spl)){
                    //検索結果行数代入
                    $stmt = $pdo->query($sql);
                    $stmt->execute();

                if ($page == 0 && !isset($prev)) {
                    $cnt_query= $pdo->query('SELECT FOUND_ROWS()');
                    list($count)= $cnt_query->fetch();
                }
                    //sqlのlimitのみカウント
                    //$count2 = $stmt->rowcount();
                    ?>
    <div class="listbox">
        <h1>検索結果</h1>
                    <?php
                    if ($count == 0) {
                        echo "検索に一致する団地はありませんでした。";
                    } elseif (1 <= $count) {
                        //検索件数
                        echo $count . "件ヒットしました。";

    //ページ表示
    if($count > 500)
        ?><div class='nextpage'><?php if($count > 500){echo  ceil($count / 500);?>ページ中の<?php echo $page2 + 1;?> ページ目を表示<?php }?></div>
                <div id='iconlist'><span><i class="fa fa-commenting-o icon2"></i> 団地コメ　</span><span><i class="fa fa-commenting-o icon3"></i> 棟コメ　</span><span><i class="fa fa-file-text-o icon"></i> 部品登録済　</span><span><i class="fa fa-file-text" aria-hidden="true"></i> 全棟部品登録済</span></div>
    </div>
                <div id='scroll'>
                        <table border="1" align="center">
                            <tr>
                                <th>種別</th>
                                <th>団地名</th>
                                <th>MAP</th>
                                <th>棟数</th>
                                <th>住所</th>
                                <th>建築年度</th>
                                <th>地図帳ページ</th>
                            </tr>
            <?php
            $cnt = 0;
            while ($row = $stmt->fetch()) {
                if ($cnt % 2 == 0) {
                    $tr_col = "<tr>";
                } else {
                    $tr_col = "<tr style=\"background: #e4e4e4;\">";
                }

                echo $tr_col;
                ?>
                                <td><?php echo htmlspecialchars($row[1]); ?></td>
                                <td class='buil'><?php 
                                if($row[1] == '高校')
                                    {
                                    echo htmlspecialchars($row[2]);
                                    }
                                else{?>
                                    <a class='buil' href='building.php?code=<?php echo htmlspecialchars($row[0]); ?>&syubetu=<?php echo $row[1]; ?>&name=<?php echo $row[2]; ?>&address=<?php echo $row[3]; ?>&map=<?php echo $row[5];?>'><?php echo $row[2];}?></a>
                                    <?php if($row[6] === $row[7]){?><i class="fa fa-file-text" aria-hidden="true"></i><?php ;}elseif($row[7] > 0){?><i class="fa fa-file-text-o icon"></i><?php ;}?><?php if($row['dcmt'] > 0){?><i class="fa fa-commenting-o icon2"></i><?php ;}?><?php if($row['tcmt'] > 0){?><i class="fa fa-commenting-o icon3"></i><?php ;}?></td>
                                <td><a href='./mapjump.php?code=<?php echo htmlspecialchars($row[0]);?>&name=<?php echo htmlspecialchars($row[2]);?>&address=<?php echo $row[3];?>'>地図</a></td>
                                <td><?php echo htmlspecialchars($row[6]); ?></td>
                                <td><?php echo htmlspecialchars($row[3]); ?></td>
                                <td><?php 
                                        // S, H, R を 昭和, 平成, 令和 に一括変換
                                        $nendo_search  = array('S', 'H', 'R');
                                        $nendo_replace = array('昭和', '平成', '令和');
                                        $nendo_jp = str_replace($nendo_search, $nendo_replace, $row[4] ?? '');
                                        echo htmlspecialchars($nendo_jp); ?>
                                </td>

                                <?php ////地図帳の数字部分だけを分割
                                        $map_str = explode(' ', $row[5] ?? '');?>

                                <td><a href="./pdf/mapbook_multi/<?php echo $map_str[0];?>.jpg" target="_blank">
        <?php echo htmlspecialchars($row[5] ?? ''); ?>
    </a></td></tr>

                <?php
                $cnt++;
            }
            ?>
                                </table>
        </div>
    <?php   
    //TOPへ戻る
    if (!$page == 0) {?>
                    <form class='page top' name='Form3' method="POST" action="list.php">
                        <input type="hidden" name="page" value=0>
                        <input type="hidden" name="toei" value="<?php echo $toei;?>">
                        <input type="hidden" name="kosya" value="<?php echo $kosya;?>">
                        <input type="hidden" name="tomin" value="<?php echo $tomin;?>">
                        <input type="hidden" name="kuei" value="<?php echo $kuei;?>">
                        <input type="hidden" name="other" value="<?php echo $other;?>">
                        <input type="hidden" name="name" value="<?php echo $name;?>">
                        <input type="hidden" name="jusyo" value="<?php echo $jusyo;?>">
                        <input type="hidden" name="count" value="<?php echo $count;?>">
                        <input type="hidden" name="prev" value=1>
                        <a class="pagebtn" href="javascript:Form3.submit()">TOP</a>
                    </form>
    <?php }
    //前の500件
    if ($page2 != 0) {?>
                    <form class='page prev' name='Form1' method="POST" action="list.php">
                        <input type="hidden" name="page" value="<?php echo $page -500;?>">
                        <input type="hidden" name="page2" value="<?php echo $page2 -1;?>">
                        <input type="hidden" name="toei" value="<?php echo $toei;?>">
                        <input type="hidden" name="kosya" value="<?php echo $kosya;?>">
                        <input type="hidden" name="tomin" value="<?php echo $tomin;?>">
                        <input type="hidden" name="kuei" value="<?php echo $kuei;?>">
                        <input type="hidden" name="other" value="<?php echo $other;?>">
                        <input type="hidden" name="name" value="<?php echo $name;?>">
                        <input type="hidden" name="jusyo" value="<?php echo $jusyo;?>">
                        <input type="hidden" name="count" value="<?php echo $count;?>">
                        <input type="hidden" name="prev" value=1>
                        <a class="pagebtn" href="javascript:Form1.submit()">&laquo; 前へ</a>
                    </form>
    <?php }
    //次の500件
    if(($page2 + 1)*500 < $count) { ?>
                    <form  class= 'page next' name='Form2' method="POST" action="list.php">
                        <input type="hidden" name="page" value="<?php echo $page +500;?>">
                        <input type="hidden" name="page2" value="<?php echo $page2 +1;?>"> 
                        <input type="hidden" name="toei" value="<?php echo $toei;?>">
                        <input type="hidden" name="kosya" value="<?php echo $kosya;?>">
                        <input type="hidden" name="tomin" value="<?php echo $tomin;?>">
                        <input type="hidden" name="kuei" value="<?php echo $kuei;?>">
                        <input type="hidden" name="other" value="<?php echo $other;?>">
                        <input type="hidden" name="name" value="<?php echo $name;?>">
                        <input type="hidden" name="jusyo" value="<?php echo $jusyo;?>">
                        <input type="hidden" name="count" value="<?php echo $count;?>">
                        <a class='pagebtn' href="javascript:Form2.submit()">次へ &raquo;</a>
                    </form>
    <?php }

                }
                    }}
                
                $pdo = NULL;
                ?>
            <div id="footer">
                Copyright &copy; <?php echo $htcreate;?> All Rights Reserved.
            </div>
        </div>
    </body>
</html><?php }?>