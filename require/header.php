<link rel="icon" type="image/vnd.microsoft.icon" href="<?php print $path;?>img/html/builicon2.ico">
            <link rel="apple-touch-icon" href="<?php print $path;?>img/html/builicon3.ico">
            <script src="<?php print $path;?>jquery/jquerybody/jquery-2.2.0.js"></script>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="<?php print $path;?>css/style.css?wa" rel="stylesheet" media="all">
            <link rel="stylesheet" href="<?php print $path;?>css/font-awesome-4.7.0/css/font-awesome.min.css">
            <script src="<?php print $path;?>jquery/hammenu/jquery.min.js"></script>
            <script src="<?php print $path;?>jquery/hammenu/iscroll.js"></script>
            <link rel="stylesheet" href="<?php print $path;?>jquery/hammenu/drawer.min.css">
            <script src="<?php print $path;?>jquery/hammenu/drawer.min.js"></script>
            <script type="text/javascript" src="<?php print $path;?>jquery/footerFixed/footerFixed.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>

        </head>
        <body class="drawer drawer--left">

        <div id="contener">
                <div id="header">
                  <a href="<?php print $path;?>index.php"><img src="<?php print $path;?>img/html/logo.png" alt="rogo" id="rogoimg"></a>
                    <?php if(!isset($index)){?>
                    <div id="search">
                        <form name="searchform2" id="searchform2" method="POST" action="<?php print $path;?>list.php">
                            <input name="name" id="keywords2" type="text" placeholder="団地を検索">
                            <input type="hidden" name="page" value="0">
                            <input type="hidden" name="search" value="search">
                            <input type="image" src="<?php print $path;?>img/html/btn.gif" alt="検索" id="searchBtn2">
                        </form>
                    </div>
                    <?php }?>
                </div>

<ul id="menu">
        <li><a href="<?php print $path;?>index.php">TOP</a></li>
                <li>
                    <a href="<?php print $path;?>partslist.php">代替え部品一覧</a>
                </li>
                <li>
                    <a href="<?php print $path;?>ratio/allratio.php">部品登録状況</a>
                </li>
                <li>
                    <a href="<?php print $path;?>history.php">閲覧履歴</a>
                </li>


<?php //hasumi以外を表示
if ($id !== 'hasumi'){ ?>
                <li>
                    <a href="<?php print $path;?>/todoist/projects.php">Todoist</a>
                </li>
<?php }?>

                <li>
                    <a class="disable"><?php if(isset($_GET['codeno'])){print '棟編集';}else{print '団地編集';}?><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="child">
                        <?php foreach ($builedit as $value){
                         print $value;
                        }?>
                    </ul>
                </li>

<?php 
       //tamadi以外を表示(elseでtamadiが表示される)
            if($id !== "tamadi"){ ?>
                <li><a class="disable"><?= htmlspecialchars($_COOKIE["ID"] ?? '', ENT_QUOTES); ?><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    <ul class="child">
                        <li><a href="<?php print $path;?>login/logout.php">ログアウト</a></li>
                    </ul>
                </li>
                   
    </ul> <?php }else{?>

                <li>
                    <a href="<?php print $path;?>settings/settings.php">
                        設定
                    </a>
                </li>
          <?php } ?>
    </ul>

<script src="<?php print $path;?>jquery/dropdownmenu/dropdown.js"></script>

<button type="button" class="drawer-toggle drawer-hamburger">
  <span class="sr-only">toggle navigation</span>
  <span class="drawer-hamburger-icon"></span>
</button>
            <nav class="drawer-nav">
                <ul class="drawer-menu">
                    <li><i class="fa fa-user-o" aria-hidden="true"></i><span><?= htmlspecialchars($_COOKIE["ID"] ?? '', ENT_QUOTES); ?></span></li>
                    <li><a href="<?php print $path;?>index.php"><span>TOP</span></a></li>
                        <li><a href="<?php print $path;?>partslist.php"><i class="fa fa-clone" aria-hidden="true"></i><span>代替え部品一覧</span></a>
                        <a href="<?php print $path;?>history.php#partshistory"><i class="fa fa-file-text-o" aria-hidden="true"></i><span>部品登録履歴</span></a>
                        <a href="<?php print $path;?>history.php#clickhistory"><i class="fa fa-mouse-pointer" aria-hidden="true"></i><span>団地Click履歴</span></a>

<?php //tamadiだけ表示(後でhasumiが以外追加)
        if ($id === 'tamadi'){ ?>
                <li>
                    <a href="<?php print $path;?>todoist/todoist_token/todoist_token.php">
                        <i class="fa fa-check" aria-hidden="true"></i><span>Todoist</span>
                    </a>
                </li>
                <li>
                    <a href="<?php print $path;?>settings/settings.php">
                        <i class="fa fa-cog" aria-hidden="true"></i><span>設定</span>
                    </a>
                </li>
<?php } ?>

<a href="<?php print $path;?>ratio/allratio.php"><i class="fa fa-hourglass-half" aria-hidden="true"></i>
                            <span>部品登録状況</span></a>
                    </li>
                    <?php foreach ($builedit2 as $value2){
                              print $value2;
                           }?>
                    <li><a href="<?php print $path;?>login/logout.php"><span>ログアウト</span></a></li>
                </ul>
            </nav>
<?php 
//カテゴリーメニューを非表示(index.phpとlist.phpとheader.phpとtodoistフォルダに入ってるファイルのみかな(他にあるかも))
if(!isset($category)){ 
//
?>
    <div class="category">
        <span><a href="<?php print $path;?>index.php">TOP</a> > </span>

        <form name='Form' method="POST" action="<?php print $path;?>list.php">
<input type="hidden" name="toei" value="<?php echo htmlspecialchars($_SESSION['toei'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="kosya" value="<?php echo htmlspecialchars($_SESSION['kosya'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="tomin" value="<?php echo htmlspecialchars($_SESSION['tomin'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="kuei" value="<?php echo htmlspecialchars($_SESSION['kuei'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="other" value="<?php echo htmlspecialchars($_SESSION['other'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES); ?>">
<input type="hidden" name="jusyo" value="<?php echo htmlspecialchars($_SESSION['jusyo'] ?? '', ENT_QUOTES); ?>">
            <input type="hidden" name="page" value="0">
            <span><a href="javascript:Form.submit()">検索結果</a> > </span>
        </form>

        <span><a href='<?php print $path;?>building.php?syubetu=<?php echo htmlspecialchars($_GET['syubetu'] ?? '', ENT_QUOTES);?>&name=<?php echo htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);?>&address=<?php echo htmlspecialchars($_GET['address'] ?? '', ENT_QUOTES);?>&code=<?php echo htmlspecialchars($_GET['code'] ?? '', ENT_QUOTES);?>&map=<?php echo htmlspecialchars($_GET['map'] ?? '', ENT_QUOTES);?>'>
            <?php echo htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES);?></a></span>
            
        <?php if(isset($_GET['goutou'])){ ?> > <span><?php echo htmlspecialchars($_GET['goutouvar'] ?? $_GET['goutou'] ?? '', ENT_QUOTES); ?>号棟</span><?php } ?>
    </div>
<?php }?>
<div id="top_scroll"><a href="#"></a></div>