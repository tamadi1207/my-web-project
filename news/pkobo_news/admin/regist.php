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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>新規登録</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="./js/common.js"></script>
<link rel="stylesheet" type="text/css" href="./js/editor/cleditor.css" />
<script type="text/javascript" src="./js/editor/cleditor.js"></script>
<script type="text/javascript" src="./js/editor/function.js"></script>
<script type="text/javascript">
$(function() {
	cleditorExe();
	
	$(".validateForm").submit(function(){
		if($('input[name="data[title]"]').val() == ''){
			alert('タイトルを入力して下さい');
			return false;
		}else{
			return true;	
		}
	});
});
</script>
</head>
<body>
<div id="container" class="clearfix">
  <div id="logoutBtn" class="linkBtn"><a href="?logout=true">ログアウト</a></div>
  <div id="toPage" class="linkBtn"><a href="./">一覧へ</a></div>
  <h1>お知らせ・ニュース管理画面</h1>
  <h2>新規登録</h2>
  <form method="post" action="put.php" enctype="multipart/form-data" class="validateForm">
  
<?php
//トークンセット
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;
?>
<input type="hidden" name="token" value="<?php echo $token;//トークン発行?>" />
<input type="hidden" name="data[category]" value="" />
  
    <table class="borderTable01">
      <tr>
        <th>登録年月日</th>
        <td><?php echo registYmdList();//日付プルダウン表示?> ※未来の日付にした場合、設定日の0時～表示されます（表示予約機能）</td>
      </tr>
      <tr>
        <th> 公開・非公開</th>
        <td><input type="hidden" name="data[public_flag]" value="0" />
          <input type="checkbox" name="data[public_flag]" value="1" checked="checked" />
          （チェックで「公開」になります）</td>
      </tr>
      <tr>
        <th>タイトル<br /></th>
        <td><input type="text" size="40" name="data[title]" value="" /></td>
      </tr>
      
      <?php if(!empty($categoryArr)){ ?>
      <tr>
        <th>カテゴリ</th>
        <td>
        
		<?php
		foreach($categoryArr as $categoryKey => $categoryVal){
			$checkedFlag = '';
			if((isset($_POST['category']) && $_POST['category'] == $categoryKey) || (!isset($_POST['category']) && $categoryKey == 0)){
				$checkedFlag = ' checked="checked"';
			}
		?>
          <input type="radio" size="50" name="data[category]" value="<?php echo $categoryKey;?>"<?php echo $checkedFlag;?> />
          <?php echo $categoryVal;?> 　
          <?php
		}
		?></td>
      </tr>
      
      <?php } ?>
      <tr>
        <th>直リンクURL</th>
        <td><input type="text" name="data[url]" size="40" value="" />
          <br />
          リンクの開き方：
          <input type="radio" name="data[window]" value="1" checked="checked" />
          同一ウインドウ　
          <input type="radio" name="data[window]" value="2" />
          別ウインドウ <br />
          （タイトルから直接リンクしますので詳細ページは無効になります）</td>
      </tr>
      <tr>
        <th>直リンクファイル<br />（5MB以内）</th>
        <td><input type="file" name="data[link_file][]" value="" />
          <br />
          （タイトルからファイルに直接リンクしますので詳細ページは無効になります）<br />
          ※<?php echo $permissionExtension;?>のみ。縮小はされません。</td>
      </tr>
    </table>


<h3>本文入力・ファイルアップロード</h3>

<p>※写真は自動で縮小、保存されます。（縦横比を維持したまま横写真は幅<?php echo $imgWidthHeight;?>px、縦写真は高さ<?php echo $imgWidthHeight;?>pxに）※設定ファイルで変更可<br />
※<?php echo $permissionExtension;?>のみ （アニメーションGIFは不可）</p>

<?php echo $detailText;?>
<?php     
//挿入するhtmlを定義
$tempHTML = <<<EOF

<tr class="lines'+arInput+'">
<th>本文</th>
<td><textarea name="data[comment][]"{$cleditorClass} rows="5" cols="60"></textarea></td>
<td rowspan="2" style="vertical-align:middle"><input type="button" onclick="delLine('+arInput+'); return false;" value=" × 削除 " class="addAndDelBtn" /></td>
</tr>
<tr class="lines'+arInput+'">
<th>ファイル（5MB以内）</th>
<td><input type="file" name="data[upfile][]" /></td>
</tr>
<tr class="lines'+arInput+'">
<td colspan="3" style="border:0;height:10px;padding:0"></td>
</tr>

EOF;
$tempHTML = str_replace(array("\n","\r",'"'),array('','','\"'),$tempHTML);
?>

<script type="text/javascript">
var arInput = 1; //初期化
function addInput() {
	arInput ++;
	if(arInput <= <?php echo $maxCommentCount;?>){
		$("#lineList").append('<?php echo $tempHTML;?>\n');
		cleditorExe();
	}else{
		$('#addBtn').prop('disabled',true);
	}
}

function delLine(str) { //削除処理
	if (confirm("このセットを削除してよろしいですか？")) {
		$("tr.lines"+str).fadeOut('fast', function() { $(this).remove(); });
	} else {
		alert("キャンセルしました");
	}
}
</script>

<table id="lineList" class="borderTable01">
<tbody>
      <tr class="lines1">
        <th>本文</th>
        <td><textarea name="data[comment][]"<?php echo $cleditorClass;?> rows="5" cols="60"></textarea></td>
        <td rowspan="2" style="vertical-align:middle"><input type="button" onclick="delLine(1); return false;" value=" × 削除 " disabled="disabled" /></td>
       
      </tr>
      <tr class="lines1">
        <th>ファイル（5MB以内）</th>
        <td><input type="file" name="data[upfile][]" /></td>
      </tr>
      <tr class="lines1">
      <td colspan="3" style="border:0;height:10px;padding:0"></td>
      </tr>
</tbody>
</table>

<p class="taC mb20"><input type="button" onclick="addInput()" value="　アップファイル、本文入力欄のセットを追加　" class="addAndDelBtn submitBtn" id="addBtn" /></p>

<table class="borderTable01">
<tfoot> 
      <tr>
        <td colspan="2" align="center" valign="middle">アップロードと縮小処理を同時に行いますので、時間がかかることもありますが、そのままで待ってください。<br />
          <br />
          <input type="hidden" name="data[mode]" value="shinki" />
          <input type="submit" value="登録" class="submitBtn" /></td>
      </tr>
      
</tfoot>      
</table>
    <br />
  </form>
 <p class="pagetop linkBtn taR"><a href="#container">PAGE TOP▲</a></p>
</div>
</body>
</html>