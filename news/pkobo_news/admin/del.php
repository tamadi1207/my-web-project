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
	
	$id = (!empty($_GET['id'])) ? h($_GET['id']) : exit('パラメータがありません');//IDをセット
	
	if(isset($_POST['del_submit'])){
		
		//トークンチェック（CSRF対策）
		if(empty($_SESSION['token']) || ($_SESSION['token'] !== $_POST['token'])){
			exit('ページ遷移エラー(トークン)');
		}
		//トークン破棄
		$_SESSION['token'] = '';
		
		$id = (!empty($_POST['id'])) ? h($_POST['id']) : exit('パラメータがありません');	
		if(!is_num($id)) exit;
		
		$lines = file($file_path);
		$fp = fopen($file_path, "r+b") or die("ファイルオープンエラー");
		
		// 俳他的ロック
		if (flock($fp, LOCK_EX)) {
			ftruncate($fp,0);
			rewind($fp);
			foreach($lines as $val){
				$lines_array = explode(",",$val);
				if($lines_array[0] != $id){
				  fwrite($fp, $val);
				}
			}
		}
		  
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);
	
		//リンクファイル削除
		for($i=0;$i<$linklFileCount;$i++){
			foreach($extensionList as $val){
				$upFilePath = $img_updir.'/'.$id.'-'.$i.'link_file.'.$val;
				if(file_exists($upFilePath)){
					unlink($upFilePath);
					break;
					
				}
			}
		}
		
		//アップファイル削除
		for($i=0;$i<$maxCommentCount;$i++){
			foreach($extensionList as $val){
				$upFilePath = $img_updir.'/'.$id.'-'.$i.'.'.$val;
				$upFilePathThumb = $img_updir.'/'.$id.'-'.$i.'s.'.$val;
				
				if(file_exists($upFilePath)){
					unlink($upFilePath);
				}
				if(file_exists($upFilePathThumb)){
					unlink($upFilePathThumb);
				}
			}
		}
		
	
	}else{
		$resDataArr = ID2Data($file_path,$id);
		if(!empty($resDataArr[1])) $up_ymd_array = explode("-",$resDataArr[1]);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>データ削除</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
</head>
<body>
<div id="container">
<div id="logoutBtn" class="linkBtn"><a href="?logout=true">ログアウト</a></div>
<div id="toPage" class="linkBtn"><a href="./">一覧へ</a></div>
  <h1>データ削除</h1>

<?php if(isset($_POST['del_submit'])){ ?>
<?php if(!empty($messe)) echo $messe; ?>
<p class="col19 big taC">削除が完了しました。</p> 
<?php }else{ ?>

<form method="post" action="">
<?php
//トークンセット
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;
?>
<input type="hidden" name="token" value="<?php echo $token;//トークン発行?>" />
<p class="taC">このデータを削除するにはクリックしてください。</p>
<p class="taC"><input type="submit" name="del_submit" value="　このデータを削除する" class="submitBtn" /></p>
<input type="hidden" name="id" value="<?php echo $id;?>" />
</form>
  <table class="borderTable01">
    <tr>
      <th>登録年月日</th>
      <td align="left"><?php echo "$up_ymd_array[0] 年 $up_ymd_array[1] 月 $up_ymd_array[2] 日 \n";?></td>
      </tr>
  <tr>
	<th style="width:20%">公開・非公開</th><td><?php echo $resDataArr[7] == 1 ? '公開':'非公開';?></td>
  </tr>
    <tr>
      <th>タイトル</th>
      <td><?php echo TextToKanma($resDataArr[2]);?></td>
    </tr>
    <?php if(!empty($categoryArr)){ ?>
    <tr>
      <th>カテゴリ</th>
      <td><?php echo TextToKanma($categoryArr[$resDataArr[3]]);?></td>
    </tr>
    <?php } ?>
      <tr>
        <th>直リンクURL</th>
        <td><?php echo (!empty($resDataArr[4])) ? '<a href="'.$resDataArr[4].'" target="_blank">'.$resDataArr[4].'</a>' : '';
		if(!empty($resDataArr[4])){
			echo '<br />リンクの開き方：';
			echo ($resDataArr[5] == 1) ? '同一ウインドウ' : '別ウインドウ';
		}
        ?>
        </td>
      </tr>
<?php 
	for($i=0;$i<$linklFileCount;$i++){
		foreach($extensionList as $val){
			$upFilePath = $img_updir.'/'.$id.'-'.$i.'link_file.'.$val;
			if(file_exists($upFilePath)){
?>
    <tr>
      <th>直リンクファイル</th>
      <td><a href="<?php echo $upFilePath;?>" target="_blank">リンクファイル</a></td>
    </tr>
<?php				
			break;
			}
		}
	}
?>
</table>

<table class="borderTable01">
<?php 
$commentArr = explode($dataSeparateStr,$resDataArr[6]);
for($i=0;$i<=$maxCommentCount;$i++){
	
	//ファイル存在判定と存在したらセット
	$upfileTag = '';
	foreach($extensionList as $val){
		$upFilePath = $img_updir.'/'.$resDataArr[0].'-'.$i.'.'.$val;
		if(file_exists($upFilePath)){
			
			if($val == 'jpg' || $val == 'png' || $val == 'gif'){
				$upfileTag .= "<img src=\"{$upFilePath}?".uniqid()."\">\n";
			}else{
				$upfileTag .= "<a href=\"{$upFilePath}\" target=\"_blank\">アップファイル</a>\n";
			}
			
			break;
		}
	}
	if(!empty($commentArr[$i]) || !empty($upfileTag)){
?>
<tr>
<th>本文</th>
<td><?php echo (!empty($commentArr[$i])) ? TextToKanma($commentArr[$i]) : '';?></td>
</tr>
<tr>
<th>ファイル</th>
<td><?php echo $upfileTag;?></td>
</tr>
<tr>
<td colspan="3" style="border:0;height:10px;padding:0"></td>
</tr>
<?php 
	} 
}
?>   
</table>
<p class="pagetop linkBtn taR"><a href="#container">PAGE TOP▲</a></p>
<?php } ?>
</div> 
</body>
</html>