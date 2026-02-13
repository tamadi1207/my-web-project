<?php
require '../db_info.php';
require '../cookie.php';
$path = '../';

// XSS対策用関数
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// カテゴリーメニューを非表示
$category = 'on';

$builedit = array("<li><a href='{$path}danchihensyu/newbuil.php'>団地登録</a></li>");
$builedit2 = array("<li><a href='{$path}danchihensyu/newbuil.php'><span>団地登録</span></a></li>");
$sort = !empty($_GET['sort']) ? $_GET['sort'] : NULL;

// ログイン状態のチェック
if ($cntid == 1) {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>部品登録状況</title>
        <link href="../css/ratio.css?aa" rel="stylesheet" media="all">
        <?php require '../require/header.php';

        // ---------------------------------------------------------
        // 1. 全体の集計データを1回のクエリでまとめて取得
        // ---------------------------------------------------------
        try {
            $sql_summary = "
                SELECT
                    (SELECT COUNT(code) FROM danchilist) AS total_code,
                    (SELECT COUNT(DISTINCT danchilist.code) 
                     FROM $goutb 
                     JOIN danchilist ON $goutb.code = danchilist.code 
                     WHERE $goutb.hiduke IS NOT NULL) AS registered_buil,
                    (SELECT COUNT(codeno) FROM $goutb) AS total_codeno,
                    (SELECT COUNT(hiduke) FROM $goutb) AS registered_hiduke
            ";
            $stmt = $pdo->query($sql_summary);
            $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("データ取得失敗: " . h($e->getMessage()));
        }

        // ---------------------------------------------------------
        // 2. ソート順の決定（ホワイトリスト方式）
        // ---------------------------------------------------------
        switch ($sort) {
            case 'sort1': $order = 'buil'; break;
            case 'sort2': $order = 'bldg'; break;
            case 'sort3': $order = 'max'; break;
            case 'sort4': $order = 'waribuil'; break;
            case 'sort5': $order = 'waribldg'; break;
            default:      $order = 'buil'; break;
        }

        // ---------------------------------------------------------
        // 3. 市区町村別データの取得（計算もSQLで完結させる）
        // ---------------------------------------------------------
        // $result5で行っていた計算（max - min）もここで一括で行います。
        // これにより、重いクエリを1回減らし、PHP側でのループ計算を排除します。
        
        $sql_city = "
            SELECT 
                danchilist.city,
                COUNT(DISTINCT danchilist.code) AS buil,
                COUNT(DISTINCT $goutb.codeno) AS bldg,
                MAX($ratiotb.ratio2) AS builmax,
                MAX($ratiotb.ratio) AS max,
                MIN($ratiotb.ratio) AS min,
                (MAX($ratiotb.ratio) - MIN($ratiotb.ratio)) AS diff,  -- 一ヶ月比の計算
                MAX($ratiotb.ratio2) / COUNT(DISTINCT $goutb.code) * 100 AS waribuil,
                MAX($ratiotb.ratio) / COUNT(DISTINCT $goutb.codeno) * 100 AS waribldg
            FROM 
                $goutb
            JOIN 
                danchilist ON $goutb.code = danchilist.code
            JOIN 
                $ratiotb ON danchilist.city = $ratiotb.city
            WHERE 
                $ratiotb.datetime BETWEEN (NOW() - INTERVAL 10 MONTH) AND NOW()
            GROUP BY 
                danchilist.city
            ORDER BY 
                $order DESC
        ";
        
        try {
            $stmt_city = $pdo->query($sql_city);
            $city_data = $stmt_city->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("詳細データ取得失敗: " . h($e->getMessage()));
        }

        // ---------------------------------------------------------
        // 4. 比率の計算（ゼロ除算回避）
        // ---------------------------------------------------------
        $ratioall = ($summary['total_codeno'] > 0) ? ($summary['registered_hiduke'] / $summary['total_codeno'] * 100) : 0;
        $ratioall2 = ($summary['total_code'] > 0) ? ($summary['registered_buil'] / $summary['total_code'] * 100) : 0;
        
        // 全体の「一ヶ月比」合計を計算
        $total_diff = 0;
        foreach ($city_data as $row) {
            $total_diff += $row['diff'];
        }
        ?>

        <br><br><br><br>
        <h1>部品登録状況</h1>
        
        <table id='ratio' class="ratio">
            <tr>
                <th>団地総数</th>
                <th>団地登録数</th>
                <th>団地登録率</th>
                <th>総棟数</th>
                <th>部品総登録数</th>
                <th>棟登録率</th>
                <th>一ヶ月比</th>
            </tr>
            <tr>
                <td><?php echo h($summary['total_code']); ?></td>
                <td><?php echo h($summary['registered_buil']); ?></td>
                <td><?php echo round($ratioall2, 1); ?>%</td>
                <td><?php echo h($summary['total_codeno']); ?></td>
                <td><?php echo h($summary['registered_hiduke']); ?></td>
                <td><?php echo round($ratioall, 1); ?>%</td>
                <td><?php echo ($total_diff > 0 ? '+' : '') . h($total_diff); ?></td>
            </tr>
        </table>

        <script>
            function dropsort() {
                var browser = document.sort_form.sort.value;
                location.href = browser;
            }
        </script>

        <form name="sort_form">
            <select name="sort" onchange="dropsort()">
                <option value="./allratio.php">--並び順--</option>
                <option value="./allratio.php?sort=sort1" <?php if ($sort == 'sort1') echo 'selected'; ?>>団地総数多い順</option>
                <option value="./allratio.php?sort=sort2" <?php if ($sort == 'sort2') echo 'selected'; ?>>総棟数多い順</option>
                <option value="./allratio.php?sort=sort3" <?php if ($sort == 'sort3') echo 'selected'; ?>>棟部品登録数多い順</option>
                <option value="./allratio.php?sort=sort5" <?php if ($sort == 'sort5') echo 'selected'; ?>>棟部品登録割合多い順</option>
            </select>
        </form>

        <table id='ratio' class="ratio2">
            <tr>
                <th>市区町村</th>
                <th>団地総数<span>(市区町村別)</span></th>
                <th>団地登録数</th>
                <th>団地登録率</th>
                <th>総棟数</th>
                <th>部品登録棟数</th>
                <th>棟登録率</th>
                <th>一ヶ月比</th>
            </tr>
            <?php foreach ($city_data as $row) { ?>
                <tr>
                    <td><?php echo h($row['city']); ?></td>
                    <td><?php echo h($row['buil']); ?></td>
                    <td><?php echo h($row['builmax']); ?></td>
                    <td><?php echo round($row['waribuil'], 0); ?>%</td>
                    <td><?php echo h($row['bldg']); ?></td>
                    <td><?php echo h($row['max']); ?></td>
                    <td><?php echo round($row['waribldg'], 0); ?>%</td>
                    <td>
                        <?php
                        if ($row['diff'] > 0) {
                            echo "+" . h($row['diff']);
                        } elseif ($row['diff'] == 0) {
                            // CSSハックの代わりにインラインスタイル推奨ですが、元の挙動を維持
                            echo "<span style='color: black;'>0</span>";
                        } else {
                            echo h($row['diff']);
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script>
            var sort = '<?php echo h($sort); ?>';

            switch (sort) {
                case 'sort1':
                    $('.ratio2 td:nth-child(2)').css('background-color', '#b4ff7b');
                    break;
                case 'sort2':
                    $('.ratio2 td:nth-child(5)').css('background-color', '#b4ff7b');
                    break;
                case 'sort3':
                    $('.ratio2 td:nth-child(6)').css('background-color', '#b4ff7b');
                    break;
                case 'sort5':
                    $('.ratio2 td:nth-child(7)').css('background-color', '#b4ff7b');
                    break;
            }
        </script>

        <div id="footer">
            Copyright &copy; <?php echo h($htcreate ?? ''); ?> All Rights Reserved.
        </div>
    </body>
    </html>
<?php
    $pdo = NULL;
}
?>