<?php
	require_once('./include/admin_inc.php');
	require_once('./include/config.php');
	require_once('./include/admin_function.php');
//----------------------------------------------------------------------
//  ログイン認証処理 (START)
//----------------------------------------------------------------------
	session_start();
	authAdmin($userid,$password);
//----------------------------------------------------------------------
//  ログイン認証処理 (END)
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//  ページ独自処理 (START)
//----------------------------------------------------------------------
	//パーミッションチェック
	$permissionError = '';
	if(!is_writable($file_path)) $permissionError .= "【パーミッションエラー】<br />data/data.datファイルのパーミッションが適切でありません。666等書き込み可能なものへ変更下さい（適切な値はサーバーマニュアル参照）<br /><br />";
	if(!is_writable($img_updir)) $permissionError .= "【パーミッションエラー】<br />upload/ディレクトリのパーミッションが適切でありません。777等書き込み可能なものへ変更下さい（適切な値はサーバーマニュアル参照）<br /><br />";
	if($backup_copy == 1 && !is_writable($backupDataDir)) $permissionError .= "【パーミッションエラー】<br />data/backup/ディレクトリのパーミッションが適切でありません。777等書き込み可能なものへ変更下さい（適切な値はサーバーマニュアル参照）<br /><br />";

	//1ページあたりの表示数（投稿数がこれを超えるとページングが自動で表示されます）
	$pagelength = 100;
	
	//ページングの表示数 ※数値は奇数のみ可（現在のページ番号の前後に均等数のナビを表示するため）
	//ナビゲーション数が超えた場合デフォルトは省略文字「...」を表示。文字列は以下で変更可。
	$pagerDispLength = 5;
	
	//ナビゲーションの省略箇所のテキスト
	$overPagerPattern = '...';
	
	//ナビゲーションの「次へ」のテキスト
	$pagerNext = 'Next &raquo;';
	
	//ナビゲーションの「前へ」のテキスト
	$pagerPrev = '&laquo; Prev';
	
	$lines = listSortAdmin(file($file_path));
	$totalLines = count($lines);cffs2g($warningMesse02,$cfilePath);
	$pagerRes = pager_dsp($lines,$pagelength,$pagerDispLength);//ページャー生成
	$pagerDsp = '<p class="pager">'.$pagerRes['dsp'].'</p>';//ページャー用html
//----------------------------------------------------------------------
//  ページ独自処理 (END)
//----------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>一覧画面</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
</head>
<body>
<div id="container">

<?php if(!empty($_GET['dspmode']) && $_GET['dspmode'] == 'phpini'){//php.ini表示モード?>
<div id="logoutBtn" class="linkBtn"><a href="?logout=true">ログアウト</a></div>
<div id="toRegist" class="linkBtn"><a href="regist.php">新規登録</a></div>
<div id="toPHPINI" class="linkBtn"><a href="./">管理画面トップ</a></div>

<h1>php.ini設定値確認（システム設置者専用画面です）</h1>
<?php if(!function_exists('ini_get')) exit('このサーバーではphp.iniの設定値を確認することが許可されていないため表示できませんでした。。。'); ?>

<p>ファイルアップロードに関連する現在設置中のサーバーの設定値を確認します。<br />
  ※ファイルアップロードはPHPプログラム側以外にサーバー側の設定が強く影響する（優先される）ためになります。<br />
※単位は「M」となっていることがありますが、「MB」と読み替えてください。</p>
<table class="borderTable03">
<tr>
<th style="width:50%">upload_max_filesize<br />（1ファイルあたりのアップできるサイズの上限値）<br /></th>
<td><span class="col19 big"><?php echo ($res = ini_get('upload_max_filesize')) ? $res : '取得できませんでした';?></span><br />
当システムの設定ファイルでのデフォルトは5MBに設定していますが、実際にはサーバーの値（要するにこの値）が優先されるため、たとえば、サーバーの設定値が2MBの場合には2MB以上のファイルはアップできないということになります。（php.iniのデフォルトは2MBですが、サーバーにより設定値が異なります。たとえばさくらサーバはデフォルトで2MBですが、エックスサーバーでは30MBに設定されています）</td>
</tr>
<tr>
<th>post_max_size<br />（アップファイルすべての合計サイズの上限値）<br />
要するに少なくとも↑の値より大きい必要があります。<br />
またはいずれも必要十分な値（たとえば100MBなど）。</th>
<td><span class="col19 big"><?php echo ($res = ini_get('post_max_size')) ? $res : '取得できませんでした';?></span><br />
ファイルアップ時の合計サイズがこのサイズを超える場合にはアップロードに失敗します。問題がある場合はサーバーの設定値を変更されるか複数回に分けてアップ下さい。</td>
</tr>
<tr>
<th>memory_limit<br />
（メモリ使用量の上限）</th>
<td><span class="col19 big"><?php echo ($res = ini_get('memory_limit')) ? $res : '取得できませんでした';?></span><br />これの判断は難しいところですが、PHP5.3でのデフォルト値が128MBになっているため、この値が最適かと思いますし、必要十分かと思います。<br />
ただし、これは設置サーバーのPCスペック等にも依存する部分になるため、必ずしも問題が起きないということではありません。
</td>
</tr>
</table>

<p>今の設定値が低くそのままでは問題が出る可能性がある場合には少々ややこしいかもしれませんが、要するに選択肢は2つです。<br />
1，サーバーの設定値以下のファイルのみをアップするようにする<br />
2，サーバーの設定値を変更する（面倒な場合にはすべて100MBなどに設定すれば十分でしょう）※変更方法は下記を参照下さい。<br />
※ただし、サーバーによっては変更が許されていないこともありますのでご了承下さい。プログラム側ではどうしようもありません。
</p>

<p>たとえばupload_max_filesizeの設定値が2MBの場合で、それ以上のファイルをアップされたい場合にはサーバーのphp.iniの変更が必要になります。<br />
これらの設定はPHPファイル側では変更できません。<br />
変更方法、詳細などは以下ページをご参考下さい。<br />
<a href="http://www.php-factory.net/trivia/05.php" target="_blank">http://www.php-factory.net/trivia/05.php</a><br /><br />

こちらについてはサポート外となりますのでご了承下さい。</p>
<?php exit();} //php.ini表示モードEND?>


<div id="logoutBtn" class="linkBtn"><a href="?logout=true">ログアウト</a></div>
<div id="toRegist" class="linkBtn"><a href="regist.php">新規登録</a></div>
<?php if($dspServerInfo == 1){ ?>
<div id="toPHPINI" class="linkBtn"><a href="?dspmode=phpini">サーバー情報確認</a></div>
<?php } ?>
<h1>お知らせ・ニュースCMS管理画面トップ</h1>


<h2>一覧画面</h2>

<?php echo (!empty($permissionError)) ? exit('<p class="col19">'.$permissionError.'※ファイルとディレクトリのパーミッションの値は基本的に違います。<br /><br />パーミッションエラーが消えない限りシステムは動作しません。<br />一般的なレンタルサーバーであれば必ず解決できますので消えるまで頑張ってみてください。<br />パーミッション変更方法が分からない場合にはWebで検索するか、<a href="http://www.kens-web.com/2011/07/1295" target="_blank">http://www.kens-web.com/2011/07/1295</a>などをご参考下さい。<br /><br /><a href="./">パーミッションを変更したのでチェックしてみる</a></p>') : '';?>

<p>並び順は登録日順です。<?php echo ($new_mark_dsp == 1) ? '（現在の設定では登録日から<span class="col19">'.$new_mark_days.'</span>日間はタイトルに「<span class="newMark">New!</span>」が表示されます）' : '';?></p>
<p class="taR">登録件数 [ <?php echo $totalLines;?> ]</p>

<?php echo ($totalLines > $pagelength) ? $pagerDsp : '';//ページャー表示?>
<table class="borderTable03">
	   <tr>
	   <th style="width:10%">公開状態</th>
	   <th style="width:14%">登録日</th>
	   <th>タイトル</th>
	   <th class="buttonArea taC">編集・削除</th>
    </tr>
<?php
if(!$copyright){echo $warningMesse;exit;}else{for($i = $pagerRes['index']; ($i-$pagerRes['index']) < $pagelength; $i++){
		if(!empty($lines[$i])){
		$linesArray = explode(",",$lines[$i]);
		$dspMode = ($linesArray[7] != 1) ? 'noDsp':'';
?>
	   <tr class="<?php echo $dspMode;?>">
	   <td nowrap="nowrap"><?php echo ($linesArray[7] == 1) ? '公開':'非公開';?></td>
	   <td><?php echo date2FormatDate($dateType,$linesArray[1]);?><?php echo ($weekDsp == 1) ? '（'.$weekArray[date('w',strtotime($linesArray[1]))].')' : '';?></td>
	   <td><?php echo TextToKanma($linesArray[2]);?><?php if(newmark($linesArray[1],$new_mark_days)) echo ' <span class="newMark">New!</span>';?></td>
	   <td class="buttonArea taC" nowrap="nowrap"><a href="edit.php?id=<?php echo $linesArray[0];?>">変更</a><a href="del.php?id=<?php echo $linesArray[0];?>&mode=delete">削除</a></td>
    </tr>
<?php  
		}
	}
?>
</table>
<?php echo ($totalLines > $pagelength) ? $pagerDsp : '';//ページャー表示?>
<p class="pagetop linkBtn taR"><a href="#container">PAGE TOP▲</a></p>
<?php echo $copyright;}//著作権表記削除不可?>
</div>
</body>
</html>