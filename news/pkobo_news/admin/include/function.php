<?php
//----------------------------------------------------------------------
// 　関数定義 (START)　
//----------------------------------------------------------------------

//HTMLエスケープ
function h($string) {
  return htmlspecialchars($string, ENT_QUOTES,'utf-8');
}
//数値チェック
function is_num($str) {
	if(preg_match("/^[0-9]+$/",$str)) {
		return true;   
	}else{
		return false;
	}
}
//本文丸め
function str2Format($sentence,$length = 170,$encodingType = 'UTF-8'){
	if(is_array($sentence)){
		$sentenceRes = '';
		foreach($sentence as $val){
			$sentenceRes .= $val;
		}
		$sentence = $sentenceRes;
	}
	if($length != 0){
		$sentence = strip_tags($sentence);
		$sentence = mb_strimwidth($sentence, 0, $length, "...", $encodingType);
		$sentence = str_replace(array("\n","\r"),"",$sentence);
	}
	return $sentence;
}
//空判定（DBデータ変数セット専用）
function resEmptyCheck(&$str){
	return !empty($str) ? $str : "";
}
//NULLバイト除去//
function sanitize02($arr){
	if(is_array($arr)){
		return array_map('sanitize02',$arr);
	}
	return str_replace("\0","",$arr);
}
if(isset($_GET)) $_GET = sanitize02($_GET);//NULLバイト除去//
if(isset($_POST)) $_POST = sanitize02($_POST);//NULLバイト除去//
if(isset($_COOKIE)) $_COOKIE = sanitize02($_COOKIE);//NULLバイト除去//

//カンマのエスケープを戻す
function TextToKanma($str){
	return str_replace("__kanma__",",",$str);
}

//パラメータチェック
function is_param($str) {
	if(preg_match("/^[a-zA-Z0-9]+$/",$str)) {
		return true;   
	}else{
		return false;   
	}
}
//NEWマーク表示処理
function newmark($base_date,$dspday=''){
	global $new_mark_dsp;
	$newDspDay = (empty($dspday)) ? 10 : $dspday;
	$now = strtotime(date('Y-m-d'));
	$set_time = strtotime("{$base_date} +{$newDspDay} day");
	if($new_mark_dsp == 1 && $now <= $set_time){
		return true;
	}else{
		return false;
	}
}
//日付フォーマットの反映
function date2FormatDate($dateType,$date){
	return date($dateType,strtotime($date));
}
//リンクタグを含む文字列からリンクタグのみを抽出する
function getHrefLink($includeLinkStr){
	$link = '';
	$tntPttrn = "<a .*?>";
	if(preg_match("/".$tntPttrn."/i",$includeLinkStr,$linkdata) !== false){
		if(!empty($linkdata[0])){
			$link = $linkdata[0];
		}
	}
	return $link;
}

//データファイルから該当データのみ抽出
function ID2Data($file_path,$id){
	if(!is_num($id)) exit('パラメータエラー');
	$lines = file($file_path);
	$existsFlag = '';
	foreach($lines as $val){
		$linesArray = explode(",",$val);
		if($id == $linesArray[0]){
			$existsFlag = 1;
			break;	
		}
	}
	return ($existsFlag == 1) ? $linesArray : exit('指定IDのデータがありません');
}

//データの並び順変更（日付順）ユーザ閲覧ページ用
function listSortUser($lines,$category){
	$linesTempArray=array();
	$index=array();
	$index2=array();
	$jj = 0;
	
	$category = (isset($_GET['cat'])) ? h($_GET['cat']) : (string)$category;
	if(isset($_GET['cat']) && !is_num($_GET['cat'])) exit('パラメータ不正');
	
	foreach($lines as $lineVal){
		$linesArray = explode(",",$lineVal);
		if($linesArray[7] == 1 && strtotime($linesArray[1]) <= strtotime(date('Y-m-d'))){//公開設定でかつ未来日付ではない場合のみ
			if($category != ''){//カテゴリ指定されていたら該当記事のみ抽出
				if($category == $linesArray[3]){
					$linesTempArray[] = $lineVal;
				}
			}
			else{
				$linesTempArray[] = $lineVal;
			}
		}
	}
	
	foreach($linesTempArray as $val){
		$linesArray = explode(",",$val);
		$index[] = strtotime($linesArray[1]);
		$index2[] = $jj++;
	}
	
	array_multisort($index,SORT_DESC,SORT_NUMERIC,$index2,SORT_ASC,SORT_NUMERIC,$linesTempArray);
	return $linesTempArray;
	
}

//ページャー関数（HTML部は変更可）
function pager($totalPage, $pageid, $pagerDispLength,$encodingType){
	global $pagerNext,$pagerPrev,$overPagerPattern;
	
	//カテゴリパラメータをセット
	$addParam = (isset($_GET['cat'])) ? '&cat='.h($_GET['cat']) : '';
	if(isset($_GET['cat']) && !is_num($_GET['cat'])) exit('パラメータ不正');
	
	$pager = '';
	$next = $pageid+1;
	$prev = $pageid-1;
	$startPage =  ($pageid-floor($pagerDispLength/2)> 0) ? ($pageid-floor($pagerDispLength/2)) : 1;
	$endPage =  ($startPage> 1) ? ($pageid+floor($pagerDispLength/2)) : $pagerDispLength;
	$startPage = ($totalPage <$endPage)? $startPage-($endPage-$totalPage):$startPage;
	if($pageid != 1 ) {
		 $pager .= '<a href="?page='.$prev.$addParam.'">'.$pagerPrev.'</a>';
	}
	if($startPage>= 2){
		$pager .= '<a href="?page=1'.$addParam.'" class="btnFirst">1</a>';
		if($startPage>= 3) $pager .= '<span class="overPagerPattern">'.$overPagerPattern.'</span>'; //ドットの表示
	}
	for($i=$startPage; $i <= $endPage ; $i++){
		$class = ($pageid == $i) ? ' class="current"':"";//現在のページ番号にclass追加
		if($i <= $totalPage && $i> 0 )//1以上最大ページ数以下の場合
			$pager .= '<a href="?page='.$i.$addParam.'"'.$class.'>'.$i.'</a>';//ページ番号リンク表示
	}
	if($totalPage> $endPage){
		if($totalPage-1> $endPage ) $pager .= '<span class="overPagerPattern">'.$overPagerPattern.'</span>'; //ドットの表示
		$pager .= '<a href="?page='.$totalPage.$addParam.'" class="btnLast">'.$totalPage.'</a>';
	}
	if($pageid <$totalPage){
		$pager .= '<a href="?page='.$next.$addParam.'">'.$pagerNext.'</a>';
	}
	if($encodingType!='UTF-8') $pager = mb_convert_encoding($pager,"$encodingType",'UTF-8');
	return $pager;
}
//ページャー取得とページャー用カウント取得
function pager_dsp($lines,$pagelength,$pagerDispLength,$encodingType="UTF-8"){
	$totalPage = ceil(count($lines)/$pagelength);// 合計ページ数
	$pageid = (isset($_GET['page'])) ? h($_GET['page']) : 1;// 現在のページを取得
	if(!is_num($pageid)) exit('パラメータが不正です');
    $pagerRes['dsp'] = pager($totalPage, $pageid,$pagerDispLength,$encodingType);//ページャー部出力
	$pagerRes['index'] = ($pageid - 1) * $pagelength;//for文用カウント
	return  $pagerRes;//連想配列で返るので注意
}


//データを最適化したフォーマットに整える
function getLines2DspData($file_path,$img_updir,$config,$id='',$category=''){
	global $dataSeparateStr,$linklFileCount,$maxCommentCount,$extensionList,$categoryArr,$weekDsp,$weekArray,$dateType,$new_mark_days;
	
	if(!empty($id) && !is_num($id)) exit('パラメータ不正');//インジェクション対策（パラメータが数値のみか）
	
	$dspTag = array();
	
	$lines = listSortUser(file($file_path),$category);
	
	foreach($lines as $key => $val){
		if(isset($config['dspNum']) && ($key+1) > $config['dspNum']) break;
		$linesArray = explode(",",$val);
		
		if(!empty($id) && $id != $linesArray[0]) continue;//ID指定の場合には処理速度を考慮し無駄なループはスキップ
		
		//カンマを置換
		$linesArray[2] = TextToKanma($linesArray[2]);
		$linesArray[4] = TextToKanma($linesArray[4]);
		$linesArray[6] = TextToKanma($linesArray[6]);
		
		//生データをセットしておく。どこかで使うかもしれないので。
		$dspTag[$key]['id'] = $linesArray[0];
		$dspTag[$key]['url'] = $linesArray[4];
		$dspTag[$key]['title_text'] = $linesArray[2];
		$dspTag[$key]['up_ymd_data'] = $linesArray[1];
		
		
		//画像パスもセットしておく（画像があった場合には強制的に詳細ページを開くため初めに処理）
		$dspTag[$key]['upfile_path'] = array();
		$dspTag[$key]['upfile_path_thumb'] = array();
		$dspTag[$key]['file_type'] = array();
		$dspTag[$key]['extension'] = array();
		
		for($i=0;$i<=$maxCommentCount;$i++){
			foreach($extensionList as $val){
				$upFilePath = $img_updir.'/'.$linesArray[0].'-'.$i.'.'.$val;
				$upFilePathThumb = $img_updir.'/'.$linesArray[0].'-'.$i.'s.'.$val;
				if(file_exists($upFilePath)){
					$dspTag[$key]['upfile_path'][$i] = $upFilePath;
					$dspTag[$key]['upfile_path_thumb'][$i] = $upFilePathThumb;
					$dspTag[$key]['extension'][$i] = $val;
					
					if($val == 'jpg' || $val == 'png' || $val == 'gif'){
						$dspTag[$key]['file_type'][$i] = 'img';
					}else{
						$dspTag[$key]['file_type'][$i] = 'file';
					}
					break;
				}
			}
		}
		
		//タイトルのセット
		if(!empty($id) || (empty($linesArray[6]) && empty($dspTag[$key]['upfile_path']))){//詳細ページか本文及び画像が無い場合にはテキストのみとする
			$dspTag[$key]['title'] = $linesArray[2];
		}
		elseif($config['popupFlag'] == 1){
			$dspTag[$key]['title'] = '<a href="javascript:openwin(\''.$config['detailFilePath'].'?id='.$linesArray[0].'\')">'.$linesArray[2].'</a>';
		}
		else{
			$dspTag[$key]['title'] = '<a href="'.$config['detailFilePath'].'?id='.$linesArray[0].'" target="_parent">'.$linesArray[2].'</a>';
		}
		
		//リンク先が指定されていたら
		if(!empty($linesArray[4])){
			$target = ($linesArray[5] == 1) ? ' target="_parent"' : ' target="_blank"';
			$dspTag[$key]['title'] = '<a href="'.$linesArray[4].'"'.$target.'>'.$linesArray[2].'</a>';
		}
		//リンクファイルがあった場合
		for($i=0;$i<$linklFileCount;$i++){
			foreach($extensionList as $val){
				$upFilePath = $img_updir.'/'.$linesArray[0].'-'.$i.'link_file.'.$val;
				if (file_exists($upFilePath)){
					$dspTag[$key]['link_file'] = $upFilePath;
					$dspTag[$key]['title'] = '<a href="'.$upFilePath.'" target="_blank">'.$linesArray[2].'</a>';
					break;
				}
			}
		}
		
		//日付セット
		$dspTag[$key]['up_ymd'] = date2FormatDate($dateType,$linesArray[1]);
		$dspTag[$key]['up_ymd'] .= ($weekDsp == 1) ?  '（'.$weekArray[date('w',strtotime($linesArray[1]))].')' : '';//曜日をセット
		
		$dspTag[$key]['week'] = '（'.$weekArray[date('w',strtotime($linesArray[1]))].')';
		
		//カテゴリ
		$dspTag[$key]['category'] = (!empty($categoryArr[$linesArray[3]])) ? $categoryArr[$linesArray[3]] : '';
		
		//カテゴリ番号
		$dspTag[$key]['categoryNum'] = $linesArray[3];
		
		//Newマーク
		$dspTag[$key]['newmark'] = 0;
		$dspTag[$key]['newmark'] = (newmark($linesArray[1],$new_mark_days)) ? 1 : 0;
		
		//詳細文
		$dspTag[$key]['comment'] = array();
		$commentArr = explode($dataSeparateStr,$linesArray[6]);
		foreach($commentArr as $commentArrVal){
			$commentArrVal = str_replace('<a href=','<a target="_blank" href=',$commentArrVal);//本文内のaタグにblankを付与
			$dspTag[$key]['comment'][] = ($config['encodingType'] != 'UTF-8') ? mb_convert_encoding($commentArrVal,$config['encodingType'],'UTF-8') : $commentArrVal;
		}
		
		//文字エンコード変更
		if($config['encodingType'] != 'UTF-8'){
			$dspTag[$key]['title'] = mb_convert_encoding($dspTag[$key]['title'],$config['encodingType'],'UTF-8');
			$dspTag[$key]['up_ymd'] = mb_convert_encoding($dspTag[$key]['up_ymd'],$config['encodingType'],'UTF-8');
			$dspTag[$key]['category'] = mb_convert_encoding($dspTag[$key]['category'],$config['encodingType'],'UTF-8');
		}
		
		//詳細ページ表示用データをセット
		if(!empty($id) && $id == $linesArray[0]){
			$dspTag = $dspTag[$key];
			break;
		}
	}
	
	return $dspTag;
}
function cffs2g($warningMesse02,$cfilePath){
	if(filesize($cfilePath) != 415 && filesize($cfilePath) != 410 && filesize($cfilePath) != 122 && filesize($cfilePath) != 117) exit($warningMesse02);//ASCIIモードでの転送にも対応
}
//サムネイルの存在チェックと表示
function dspThumb($data,$width=100){
	global $dspThumbNail;
	$imgTag = '';
	if($dspThumbNail == 1){
		if(isset($data['file_type'][0]) && isset($data['upfile_path_thumb'][0]) && $data['file_type'][0] == 'img' && file_exists($data['upfile_path_thumb'][0])){
			$imgTag = '<img src="'.$data['upfile_path_thumb'][0].'?'.uniqid().'" width="'.$width.'" class="thumbNail" id="thumb_'.$data['id'].'" />';
		}
	}
	return $imgTag;
}

//RSS配信用　?feed=rss2
function rssGen(){
	global $rss,$config,$file_path,$img_updir;
	
	if(!empty($_GET['feed']) && $_GET['feed'] == 'rss2'){
		
	$getFormatDataArr = getLines2DspData($file_path,$img_updir,$config,'',$rss['category']);
	
	//現在のURLを抽出
	$httpORhttps = (empty($_SERVER["HTTPS"])) ? "http://" : "https://";
	$correntURL = $httpORhttps.h($_SERVER["HTTP_HOST"]) . str_replace("?feed=rss2", "", h($_SERVER["REQUEST_URI"]));
	$currentDirArr = explode('/',$correntURL);
	$currentDir = str_replace(end($currentDirArr),'',$correntURL);
	
	$url = $currentDir.$config['detailFilePath'];//詳細ページの絶対URL
	
    //----------------------
    // XMLヘッダ作成
    //----------------------
$rssHeader = '<?xml version="1.0" encoding="UTF-8"?>';
$rssHeader .= <<<RSS
<rss version="2.0">
<channel>
<title>{$rss['title']}</title>
<link>{$currentDir}</link>
<description>{$rss['description']}</description>
<language>ja</language>
RSS;

    //----------------------
    // 記事作成
    //----------------------
    $item = "";
    foreach($getFormatDataArr as $data){
    	
        $item .= "<item>\n";
        $item .= "<title>" . $data['title_text']. "</title>\n";
		
		if(!empty($data['url']) && empty($data['link_file'])){
			$itemLink = h($data['url']);
		}
		elseif(!empty($data['link_file'])){
			$itemLink = $currentDir.h($data['link_file']);
		}
		elseif(empty($data['comment'])){
			$itemLink = '';
		}else{
			$itemLink = $url.'?id='.$data['id'];
		}
		
        $item .= "<link>" . $itemLink . "</link>\n";

		$itemDescription = str2Format($data['comment'],150,'UTF-8');
        
        $item .= "<description><![CDATA[" . $itemDescription . "]]></description>\n";

        $itemPubDate = $data['up_ymd_data'];
        $itemPubDate = date('D, d M Y H:i:s O', strtotime($itemPubDate));
        $item .= "<pubDate>$itemPubDate</pubDate>\n";

        $item .= "</item>\n";
        
    }
    //----------------------
    // XMLフッタ作成
    //----------------------
$rssFooter = <<<RSS
</channel>
</rss>
RSS;
    //----------------------
    // XML出力
    //----------------------
    echo $rssHeader.$item.$rssFooter;
	exit();
	}
}

//----------------------------------------------------------------------
// 　関数定義 (END)
//----------------------------------------------------------------------
?>