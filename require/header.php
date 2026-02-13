<?php
// ※ db_info.php, cookie.php はこのファイルの呼び出し元または冒頭で読み込まれている前提とします
// $path が未定義の場合の初期値を設定
$path = $path ?? './';

// セッションが開始されていない場合の保険
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $path;?>img/html/builicon2.ico">
<link rel="apple-touch-icon" href="<?php echo $path;?>img/html/builicon3.ico">
<script src="<?php echo $path;?>jquery/jquerybody/jquery-2.2.0.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo $path;?>css/style.css?wa" rel="stylesheet" media="all">
<link rel="stylesheet" href="<?php echo $path;?>css/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="<?php echo $path;?>jquery/hammenu/jquery.min.js"></script>
<script src="<?php echo $path;?>jquery/hammenu/iscroll.js"></script>
<link rel="stylesheet" href="<?php echo $path;?>jquery/hammenu/drawer.min.css">
<script src="<?php echo $path;?>jquery/hammenu/drawer.min.js"></script>
<script type="text/javascript" src="<?php echo $path;?>jquery/footerFixed/footerFixed.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>

</head>
<body class="drawer drawer--left">

<div id="contener">
    <div id="header">
        <a href="<?php echo $path;?>index.php"><img src="<?php echo $path;?>img/html/logo.png" alt="rogo" id="rogoimg"></a>
        <?php if(!isset($index)){?>
        <div id="search">
            <form name="searchform2" id="searchform2" method="POST" action="<?php echo $path;?>list.php">
                <input name="name" id="keywords2" type="text" placeholder="団地を検索">
                <input type="hidden" name="page" value="0">
                <input type="hidden" name="search" value="search">
                <input type="image" src="<?php echo $path;?>img/html/btn.gif" alt="検索" id="searchBtn2">
            </form>
        </div>
        <?php }?>
    </div>

    <ul id="menu">
        <li><a href="<?php echo $path;?>index.php">TOP</a></li>
        <li>
            <a href="<?php echo $path;?>partslist.php">代替え部品一覧</a>
        </li>
        <li>
            <a href="<?php echo $path;?>ratio/allratio.php">部品登録状況</a>
        </li>
        <li>
            <a href="<?php echo $path;?>history.php">閲覧履歴</a>
        </li>

        <?php 
        // ▼▼▼ 修正: クッキーではなくセッションからIDを取得 ▼▼▼
        $safe_id = htmlspecialchars($_SESSION['USERID'] ?? '', ENT_QUOTES);
        
        // hasumi以外を表示
        if ($safe_id === 'tamadi'){ ?>
            <li>
                <a href="<?php echo $path;?>/todoist/projects.php">Todoist</a>
            </li>
        <?php }?>

        <li>
            <a class="disable"><?php if(isset($_GET['codeno'])){echo '棟編集';}else{echo '団地編集';}?><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
            <ul class="child">
                <?php 
                if(isset($builedit) && is_array($builedit)){
                    foreach ($builedit as $value){
                        echo $value;
                    }
                }
                ?>
            </ul>
        </li>

        <?php 
        // tamadi以外を表示
        if($safe_id !== "tamadi"){ ?>
            <li><a class="disable"><?= $safe_id ?><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                <ul class="child">
                    <li><a href="<?php echo $path;?>login/logout.php">ログアウト</a></li>
                </ul>
            </li>
        <?php }else{ 
            // tamadiの場合
        ?>
            <li>
                <a href="<?php echo $path;?>settings/settings.php">設定</a>
            </li>
        <?php } ?>
    </ul>

    <script src="<?php echo $path;?>jquery/dropdownmenu/dropdown.js"></script>

    <button type="button" class="drawer-toggle drawer-hamburger">
      <span class="sr-only">toggle navigation</span>
      <span class="drawer-hamburger-icon"></span>
    </button>
    <nav class="drawer-nav">
        <ul class="drawer-menu">
            <li><i class="fa fa-user-o" aria-hidden="true"></i><span><?= $safe_id ?></span></li>
            <li><a href="<?php echo $path;?>index.php"><span>TOP</span></a></li>
            <li><a href="<?php echo $path;?>partslist.php"><i class="fa fa-clone" aria-hidden="true"></i><span>代替え部品一覧</span></a>
                <a href="<?php echo $path;?>history.php#partshistory"><i class="fa fa-file-text-o" aria-hidden="true"></i><span>部品登録履歴</span></a>
                <a href="<?php echo $path;?>history.php#clickhistory"><i class="fa fa-mouse-pointer" aria-hidden="true"></i><span>団地Click履歴</span></a>

                <?php //tamadiだけ表示
                if ($safe_id === 'tamadi'){ ?>
                    <li>
                        <a href="<?php echo $path;?>todoist/todoist_token/todoist_token.php">
                            <i class="fa fa-check" aria-hidden="true"></i><span>Todoist</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $path;?>settings/settings.php">
                            <i class="fa fa-cog" aria-hidden="true"></i><span>設定</span>
                        </a>
                    </li>
                <?php } ?>

                <a href="<?php echo $path;?>ratio/allratio.php"><i class="fa fa-hourglass-half" aria-hidden="true"></i>
                    <span>部品登録状況</span></a>
            </li>
            <?php 
            if(isset($builedit2) && is_array($builedit2)){
                foreach ($builedit2 as $value2){
                    echo $value2;
                }
            }
            ?>
            <li><a href="<?php echo $path;?>login/logout.php"><span>ログアウト</span></a></li>
        </ul>
    </nav>
    <?php 
    //カテゴリーメニュー（パンくずリスト）の表示制御
    if(!isset($category)){ 
        // セッション変数の初期化
        $h_toei  = htmlspecialchars($_SESSION['toei'] ?? '', ENT_QUOTES);
        $h_kosya = htmlspecialchars($_SESSION['kosya'] ?? '', ENT_QUOTES);
        $h_tomin = htmlspecialchars($_SESSION['tomin'] ?? '', ENT_QUOTES);
        $h_kuei  = htmlspecialchars($_SESSION['kuei'] ?? '', ENT_QUOTES);
        $h_other = htmlspecialchars($_SESSION['other'] ?? '', ENT_QUOTES);
        $h_name  = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES);
        $h_jusyo = htmlspecialchars($_SESSION['jusyo'] ?? '', ENT_QUOTES);
        
        // GETパラメータの初期化
        $g_syubetu = htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);
        $g_name    = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);
        $g_address = htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);
        $g_code    = htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);
        $g_map     = htmlspecialchars($_GET['map'] ?? '', ENT_QUOTES);
        $g_goutou  = htmlspecialchars($_GET['goutou'] ?? '', ENT_QUOTES);
        $g_goutouvar = htmlspecialchars($_GET['goutouvar'] ?? '', ENT_QUOTES);
    ?>
    <div class="category">
        <span><a href="<?php echo $path;?>index.php">TOP</a> > </span>

        <form name='Form' method="POST" action="<?php echo $path;?>list.php">
            <input type="hidden" name="toei" value="<?php echo $h_toei; ?>">
            <input type="hidden" name="kosya" value="<?php echo $h_kosya; ?>">
            <input type="hidden" name="tomin" value="<?php echo $h_tomin; ?>">
            <input type="hidden" name="kuei" value="<?php echo $h_kuei; ?>">
            <input type="hidden" name="other" value="<?php echo $h_other; ?>">
            <input type="hidden" name="name" value="<?php echo $h_name; ?>">
            <input type="hidden" name="jusyo" value="<?php echo $h_jusyo; ?>">
            <input type="hidden" name="page" value="0">
            <span><a href="javascript:Form.submit()">検索結果</a> > </span>
        </form>

        <span><a href='<?php echo $path;?>building.php?syubetu=<?php echo $g_syubetu;?>&name=<?php echo $g_name;?>&address=<?php echo $g_address;?>&code=<?php echo $g_code;?>&map=<?php echo $g_map;?>'>
            <?php echo $g_name;?></a></span>
            
        <?php if(!empty($g_goutou) || !empty($g_goutouvar)){ ?> > <span><?php echo $g_goutou . $g_goutouvar; ?>号棟</span><?php } ?>
    </div>
    <?php }?>
    <div id="top_scroll"><a href="#"></a></div>