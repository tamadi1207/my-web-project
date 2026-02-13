<?php
require './db_info.php';
require './cookie.php';
$path= './';

// ▼▼▼ 追加対策1: セキュリティヘッダーの出力 ▼▼▼
// HTMLが出力される前にヘッダーを送出します
header('X-Frame-Options: SAMEORIGIN'); // クリックジャッキング対策
header('X-Content-Type-Options: nosniff'); // MIMEタイプスニフィング対策
header('X-XSS-Protection: 1; mode=block'); // XSSフィルター有効化

// 未定義変数の初期化（Warning回避）
$list_cmt = $list_cmt ?? ''; 
$list_cmt2 = $list_cmt2 ?? '';
$typeid = $typeid ?? 1;

$builedit= array("<li><a href='{$path}danchihensyu/newbuil.php'>団地新規登録</a></li>");
$builedit2= array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地新規登録</span></a></li>");

// ログイン状態のチェック
if ($cntid == 1) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>団地一覧</title>
    <link href="css/ichiran.css?df" rel="stylesheet" media="all">
<?php require './require/header.php';

    // POSTデータの取得（存在しない場合はNULL）
    $toei = $_POST['toei'] ?? NULL;
    $kosya = $_POST['kosya'] ?? NULL;
    $tomin = $_POST['tomin'] ?? NULL;
    $kuei = $_POST['kuei'] ?? NULL;
    $other = $_POST['other'] ?? NULL;
    
    // ▼▼▼ 追加対策2: 入力文字数の制限（DoS対策） ▼▼▼
    // 極端に長い検索ワードはカットする（例: 100文字まで）
    $name = isset($_POST['name']) ? mb_substr($_POST['name'], 0, 100) : NULL;
    $jusyo = isset($_POST['jusyo']) ? mb_substr($_POST['jusyo'], 0, 100) : NULL;
    
    // ページネーション用（数値型にキャストして安全にする）
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
    $page2 = isset($_POST['page2']) ? (int)$_POST['page2'] : 0;
    $count = isset($_POST['count']) ? (int)$_POST['count'] : NULL;
    $prev = $_POST['prev'] ?? NULL;
    
    // セッションへの保存（表示用にhtmlspecialcharsを通す）
    $_SESSION['toei'] = !empty($toei) ? htmlspecialchars($toei, ENT_QUOTES) : NULL;
    $_SESSION['kosya'] = !empty($kosya) ? htmlspecialchars($kosya, ENT_QUOTES) : NULL;
    $_SESSION['tomin'] = !empty($tomin) ? htmlspecialchars($tomin, ENT_QUOTES) : NULL;
    $_SESSION['kuei'] = !empty($kuei) ? htmlspecialchars($kuei, ENT_QUOTES) : NULL;
    $_SESSION['other'] = !empty($other) ? htmlspecialchars($other, ENT_QUOTES) : NULL;
    $_SESSION['name'] = !empty($name) ? htmlspecialchars($name, ENT_QUOTES) : NULL;
    $_SESSION['jusyo'] = !empty($jusyo) ? htmlspecialchars($jusyo, ENT_QUOTES) : NULL;
    $_SESSION['page'] = $page;

    $other2_array = [];
    
    // 全角数字などを半角に変換する配列
    $search= array('　','－','１','２','３','４','５','６','７','８','９','０','高校','1丁目','2丁目','3丁目','4丁目','5丁目','6丁目','7丁目','8丁目','9丁目','１丁目','２丁目','３丁目','４丁目','５丁目','６丁目','７丁目','８丁目','９丁目','第一','第二','第三','第四','第五','第六','第七','第八','第九');
    $replace= array(' ','-','1','2','3','4','5','6','7','8','9','0','高等学校','一丁目','二丁目','三丁目','四丁目','五丁目','六丁目','七丁目','八丁目','九丁目','一丁目','二丁目','三丁目','四丁目','五丁目','六丁目','七丁目','八丁目','九丁目','第1','第2','第3','第4','第5','第6','第7','第8','第9');
    
    if(isset($other)) {
        // $other2用（IN句用）
        $other2_array = array("水道局","水道局住宅","高校","教育庁住宅","交通局住宅","下水道局住宅","総務局住宅","消防宿舎");
    }

    // 何も入力がない場合
    if (empty($toei) && empty($kosya) && empty($tomin) && empty($kuei) && empty($name) && empty($jusyo) && empty($other)) {
        echo "<br><br><br><span id='listcmt'>入力して下さい。</span>";
    } else {
        
        // SQLパラメータ用配列
        $sql_params = [];
        $where_clauses = [];
        
        // 共通のSELECT文（プレースホルダ ? を使用）
        $base_sql_select = "SELECT SQL_CALC_FOUND_ROWS danchilist.code, danchilist.syubetu, danchilist.name,
            Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, Count(distinct($goutb.codeno)), Count($goutb.hiduke),
            Count($list_cmt danchicomment.type = ? or NULL) as 'dcmt', Count($list_cmt2 goutoucomment.type = ? or NULL) as 'tcmt'
            From danchilist 
            Right Join $goutb On danchilist.code = $goutb.code 
            Left Join danchicomment On danchilist.code = danchicomment.code 
            Left Join goutoucomment On danchilist.code = goutoucomment.code ";
        
        // カウント用のtypeidをパラメータに追加 (SELECT句内の分)
        $sql_params[] = $typeid;
        $sql_params[] = $typeid;

        // 名前検索のWHERE句構築
        if(!empty($name)){
            $sql5 = str_replace($search, $replace, $name);
            $array3 = explode(" ", $sql5);
            $name_wheres = [];
            foreach($array3 as $val){
                if($val !== ""){
                    $name_wheres[] = "(danchilist.name LIKE ?)";
                    $sql_params[] = "%{$val}%";
                }
            }
            if(!empty($name_wheres)){
                $where_clauses[] = "(" . implode(" AND ", $name_wheres) . ")";
            }
        }

        // 住所検索のWHERE句構築
        if(!empty($jusyo)){
            $sql6 = str_replace($search, $replace, $jusyo);
            $array4 = explode(" ", $sql6);
            $jusyo_wheres = [];
            foreach($array4 as $val){
                if($val !== ""){
                    $jusyo_wheres[] = "(Concat(danchilist.city, danchilist.jusyo) Like ?)";
                    $sql_params[] = "%{$val}%";
                }
            }
            if(!empty($jusyo_wheres)){
                $where_clauses[] = "(" . implode(" AND ", $jusyo_wheres) . ")";
            }
        }

        // 分岐処理（完全一致 IN検索 か LIKE検索か）
        if (!empty($toei) || !empty($kosya) || !empty($tomin) || !empty($kuei) || !empty($other) || !empty($other2_array)) {
            // パターンA: チェックボックス等で種別指定がある場合 -> IN句で高速化
            $in_values = [];
            if(!empty($toei)) $in_values[] = $toei;
            if(!empty($kosya)) $in_values[] = $kosya;
            if(!empty($tomin)) $in_values[] = $tomin;
            if(!empty($kuei))  $in_values[] = $kuei;
            if(!empty($other2_array)) {
                foreach($other2_array as $v) $in_values[] = $v;
            }
            
            if(!empty($in_values)){
                $placeholders = implode(',', array_fill(0, count($in_values), '?'));
                $where_clauses[] = "danchilist.syubetu IN ($placeholders)";
                foreach($in_values as $v) $sql_params[] = $v;
            }

        } else {
            // パターンB: 種別を LIKE で検索する場合（入力値がない場合など）
            $where_clauses[] = "danchilist.syubetu LIKE ?";
            $sql_params[] = "%" . ($toei ?? '');
            
            $where_clauses[] = "danchilist.syubetu LIKE ?";
            $sql_params[] = "%" . ($kosya ?? '');
            
            $where_clauses[] = "danchilist.syubetu LIKE ?";
            $sql_params[] = "%" . ($tomin ?? '');
            
            $where_clauses[] = "danchilist.syubetu LIKE ?";
            $sql_params[] = "%" . ($kuei ?? '');
            
            if (isset($other)) {
                $other_vals = ["水道局","水道局住宅","高校","教育庁住宅","交通局住宅","下水道局住宅","総務局住宅","消防宿舎"];
                foreach ($other_vals as $ov) {
                    $where_clauses[] = "danchilist.syubetu LIKE ?";
                    $sql_params[] = "%" . $ov;
                }
            }
        }

        // WHERE句の結合
        $sql_where = "";
        if(!empty($where_clauses)){
            $sql_where = " WHERE " . implode(" AND ", $where_clauses);
        }

        // GROUP BY と ORDER BY と LIMIT
        $sql_footer = " Group By danchilist.code, danchilist.syubetu, danchilist.name,
            Concat(danchilist.city, danchilist.jusyo), danchilist.nendo, danchilist.map, $goutb.code
            Order By code LIMIT ?, 500";
        
        $final_sql = $base_sql_select . $sql_where . $sql_footer;

        try {
            $stmt = $pdo->prepare($final_sql);
            
            // パラメータのバインド (1から順に)
            $param_index = 1;
            foreach ($sql_params as $val) {
                $stmt->bindValue($param_index, $val, PDO::PARAM_STR);
                $param_index++;
            }
            // 最後にLIMITのオフセットを整数でバインド
            $stmt->bindValue($param_index, $page, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // 件数取得
            if ($page == 0 && !isset($prev)) {
                $cnt_query = $pdo->query('SELECT FOUND_ROWS()');
                $count = $cnt_query->fetchColumn();
            }

?>
    <div class="listbox">
        <h1>検索結果</h1>
<?php
            if ($count == 0) {
                echo "検索に一致する団地はありませんでした。";
            } elseif (1 <= $count) {
                //検索件数
                echo htmlspecialchars($count, ENT_QUOTES) . "件ヒットしました。";

                //ページ表示
                if($count > 500){
?>
        <div class='nextpage'>
            <?php echo ceil($count / 500);?>ページ中の<?php echo $page2 + 1;?> ページ目を表示
        </div>
<?php 
                } 
?>
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
            // PDO::FETCH_NUM でインデックス番号で取得
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                if ($cnt % 2 == 0) {
                    $tr_col = "<tr>";
                } else {
                    $tr_col = "<tr style=\"background: #e4e4e4;\">";
                }
                echo $tr_col;
?>
                <td><?php echo htmlspecialchars($row[1] ?? '', ENT_QUOTES); ?></td>
                <td class='buil'>
                    <?php if(($row[1] ?? '') == '高校'){
                        echo htmlspecialchars($row[2] ?? '', ENT_QUOTES);
                    } else { ?>
                        <a class='buil' href='building.php?code=<?php echo htmlspecialchars($row[0] ?? '', ENT_QUOTES); ?>&syubetu=<?php echo htmlspecialchars($row[1] ?? '', ENT_QUOTES); ?>&name=<?php echo htmlspecialchars($row[2] ?? '', ENT_QUOTES); ?>&address=<?php echo htmlspecialchars($row[3] ?? '', ENT_QUOTES); ?>&map=<?php echo htmlspecialchars($row[5] ?? '', ENT_QUOTES);?>'><?php echo htmlspecialchars($row[2] ?? '', ENT_QUOTES); ?></a>
                    <?php } 
                    
                    // アイコン表示判定
                    $tou_count = $row[6] ?? 0;
                    $hiduke_count = $row[7] ?? 0;
                    $dcmt_count = $row['dcmt'] ?? 0;
                    $tcmt_count = $row['tcmt'] ?? 0;

                    if($tou_count === $hiduke_count && $tou_count > 0){?><i class="fa fa-file-text" aria-hidden="true"></i><?php ;}
                    elseif($hiduke_count > 0){?><i class="fa fa-file-text-o icon"></i><?php ;}
                    
                    if($dcmt_count > 0){?><i class="fa fa-commenting-o icon2"></i><?php ;}
                    if($tcmt_count > 0){?><i class="fa fa-commenting-o icon3"></i><?php ;}
                    ?>
                </td>
                <td><a href='./mapjump.php?code=<?php echo htmlspecialchars($row[0] ?? '', ENT_QUOTES);?>&name=<?php echo htmlspecialchars($row[2] ?? '', ENT_QUOTES);?>&address=<?php echo htmlspecialchars($row[3] ?? '', ENT_QUOTES);?>'>地図</a></td>
                <td><?php echo htmlspecialchars($row[6] ?? '', ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($row[3] ?? '', ENT_QUOTES); ?></td>
                <td><?php 
                        // S, H, R を 昭和, 平成, 令和 に一括変換
                        $nendo_search  = array('S', 'H', 'R');
                        $nendo_replace = array('昭和', '平成', '令和');
                        $nendo_jp = str_replace($nendo_search, $nendo_replace, $row[4] ?? '');
                        echo htmlspecialchars($nendo_jp, ENT_QUOTES); ?>
                </td>
                <?php 
                    //地図帳の数字部分だけを分割
                    $map_str = explode(' ', $row[5] ?? '');
                ?>
                <td>
                    <a href="./pdf/mapbook_multi/<?php echo htmlspecialchars($map_str[0] ?? '', ENT_QUOTES);?>.jpg" target="_blank">
                        <?php echo htmlspecialchars($row[5] ?? '', ENT_QUOTES); ?>
                    </a>
                </td>
            </tr>
<?php
                $cnt++;
            }
?>
        </table>
    </div>
<?php   
            //TOPへ戻る
            if ($page != 0) {?>
                <form class='page top' name='Form3' method="POST" action="list.php">
                    <input type="hidden" name="page" value=0>
                    <input type="hidden" name="toei" value="<?php echo htmlspecialchars($toei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kosya" value="<?php echo htmlspecialchars($kosya ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="tomin" value="<?php echo htmlspecialchars($tomin ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kuei" value="<?php echo htmlspecialchars($kuei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="other" value="<?php echo htmlspecialchars($other ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="jusyo" value="<?php echo htmlspecialchars($jusyo ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="count" value="<?php echo htmlspecialchars($count, ENT_QUOTES);?>">
                    <input type="hidden" name="prev" value=1>
                    <a class="pagebtn" href="javascript:Form3.submit()">TOP</a>
                </form>
            <?php }
            //前の500件
            if ($page2 != 0) {?>
                <form class='page prev' name='Form1' method="POST" action="list.php">
                    <input type="hidden" name="page" value="<?php echo $page -500;?>">
                    <input type="hidden" name="page2" value="<?php echo $page2 -1;?>">
                    <input type="hidden" name="toei" value="<?php echo htmlspecialchars($toei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kosya" value="<?php echo htmlspecialchars($kosya ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="tomin" value="<?php echo htmlspecialchars($tomin ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kuei" value="<?php echo htmlspecialchars($kuei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="other" value="<?php echo htmlspecialchars($other ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="jusyo" value="<?php echo htmlspecialchars($jusyo ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="count" value="<?php echo htmlspecialchars($count, ENT_QUOTES);?>">
                    <input type="hidden" name="prev" value=1>
                    <a class="pagebtn" href="javascript:Form1.submit()">&laquo; 前へ</a>
                </form>
            <?php }
            //次の500件
            if(($page2 + 1)*500 < $count) { ?>
                <form  class= 'page next' name='Form2' method="POST" action="list.php">
                    <input type="hidden" name="page" value="<?php echo $page +500;?>">
                    <input type="hidden" name="page2" value="<?php echo $page2 +1;?>"> 
                    <input type="hidden" name="toei" value="<?php echo htmlspecialchars($toei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kosya" value="<?php echo htmlspecialchars($kosya ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="tomin" value="<?php echo htmlspecialchars($tomin ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="kuei" value="<?php echo htmlspecialchars($kuei ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="other" value="<?php echo htmlspecialchars($other ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="jusyo" value="<?php echo htmlspecialchars($jusyo ?? '', ENT_QUOTES);?>">
                    <input type="hidden" name="count" value="<?php echo htmlspecialchars($count, ENT_QUOTES);?>">
                    <a class='pagebtn' href="javascript:Form2.submit()">次へ &raquo;</a>
                </form>
            <?php }

            } // elseif end
        } catch (PDOException $e) {
            // ▼▼▼ 追加対策3: エラー情報の隠蔽 ▼▼▼
            // 本番環境では詳細なエラーを表示せず、ログに記録するだけにします
            error_log("DB Error: " . $e->getMessage());
            echo "システムエラーが発生しました。管理者にお問い合わせください。";
        }
    } // else end
    
    $pdo = NULL;
?>
    <div id="footer">
        Copyright &copy; <?php echo $htcreate ?? date('Y');?> All Rights Reserved.
    </div>
</div>
</body>
</html>
<?php } ?>