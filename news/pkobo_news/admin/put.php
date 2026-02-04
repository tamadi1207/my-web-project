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

	//トークンチェック（CSRF対策）
	if(empty($_SESSION['token']) || ($_SESSION['token'] !== $_POST['token'])){
		exit('ページ遷移エラー(トークン)');
	}
	//トークン破棄
	$_SESSION['token'] = '';

	$mode = (isset($_POST['data']['mode'])) ? strToCommonReplace($_POST['data']['mode']) : exit('ページ遷移エラー');
	
	//日付を連結
	$_POST['data']['up_ymd'] = date('Y-m-d',strtotime($_POST['data']['up_y'].'-'.$_POST['data']['up_m'].'-'.$_POST['data']['up_d']));
	
	//IDをセット
	if($mode == 'shinki'){
		$_POST['data']['id'] = getAutoIncreNum($file_path);//AUTO INCREMENT値取得（DBなら楽やのにな～
	}
	
	//cleditor不使用時は改行タグに変更
	$commentArr = array();
	if(isset($_POST['data']['comment'])){
		if($useEditer == 0){
			foreach($_POST['data']['comment'] as $val){
				$commentArr[] = nl2br($val);
			}
		}
		else{//エディタ使用時も改行タグのみの場合には空とみなして空にする
			foreach($_POST['data']['comment'] as $val){
				if($val == '<br>' || $val == '<br />'){
					$commentArr[] = '';
				}else{
					$commentArr[] = $val;
				}
			}
		}
	}
	
	$_POST['data']['comment'] = $commentArr;
	
	//登録データ（カラム順の変更現金。追加の場合は末尾に）
	$dbDataStructure = array('id','up_ymd','title', 'category','url','window','comment','public_flag');
	
	$data = array();
	foreach($dbDataStructure as $dbDataStructureVal){
		$data[$dbDataStructureVal] = '';
		if(isset($_POST['data'][$dbDataStructureVal])){
			if(is_array($_POST['data'][$dbDataStructureVal])){
				foreach($_POST['data'][$dbDataStructureVal] as $vv){
					$data[$dbDataStructureVal] .= ($vv != '') ? strToCommonReplace($vv).$dataSeparateStr : $dataSeparateStr;
				}
				$data[$dbDataStructureVal] = rtrim($data[$dbDataStructureVal],$dataSeparateStr);
			}
			else{
				$data[$dbDataStructureVal] = strToCommonReplace($_POST['data'][$dbDataStructureVal]);
			}
		}
	}
	
	$lines = file($file_path);
	$fp = fopen($file_path, "r+b") or die("ファイルオープンエラー");
	
	$writeData = '';
	foreach($data as $val){
		$writeData .= $val. ",";
	}
	$writeData .= "\n";
  
	// 俳他的ロック
	if (flock($fp, LOCK_EX)) {
		ftruncate($fp,0);
		rewind($fp);
		
		// 書き込み
		if($mode == "shinki"){
			fwrite($fp, $writeData);
			$messe= "登録完了しました。";
			
			foreach($lines as $val){
				fwrite($fp, $val);
			}
		}
		elseif($mode == "update"){
			foreach($lines as $val){
				$lines_array = explode(",",$val);
				if($lines_array[0] != $data['id']){
					 fwrite($fp, $val);
				}else{
					fwrite($fp, $writeData);
					$messe= "編集処理完了しました！ ";
				}
			}
		}
	}
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);
	
		
	//リンクファイル処理link_file　縮小せずに保存
	if(isset($_FILES["data"]["tmp_name"]['link_file'])){
		foreach($_FILES["data"]["tmp_name"]['link_file'] as $key => $val){
			if(is_uploaded_file($_FILES["data"]["tmp_name"]['link_file'][$key])){
				//サイズチェック
				if ($_FILES["data"]["size"]['link_file'][$key] < $maxImgSize) {
				
					$extensionArr = explode('.',$_FILES['data']["name"]["link_file"][$key]);
					$extension = end($extensionArr);
					$extension = strtolower($extension);
					
					if(!in_array($extension,$extensionList)){
						$extension_error = '許可されていない拡張子です<br />';
						exit('<center><span style="color:red;font-size:16px;">【許可されていない拡張子です】<br />記事自体はすでに投稿（編集の場合には変更）されています<br /><a href="./">戻る&gt;&gt;</a></span></center>');
					}
					
					//ファイル名を指定
					$fileName = $data['id'].'-'.$key.'link_file';
					
					$fileNameNormal = $fileName.'.'.$extension;
					
					//拡張子違いのファイルを削除
					foreach($extensionList as $extensionListval){
						$deleteFilePathNormal = $img_updir.'/'.$fileName.'.'.$extensionListval;
						if(file_exists($deleteFilePathNormal)) unlink($deleteFilePathNormal);
					}
					
					$filePathNormal = $img_updir.'/'.$fileNameNormal;//ファイルパスを指定（画像以外）
						
					move_uploaded_file($_FILES['data']['tmp_name']['link_file'][$key],$filePathNormal);
					@chmod($filePathNormal, 0666);
		
				}else{
					exit('ファイルサイズオーバーです。<br />記事自体はすでに投稿（編集の場合には変更）されていますので、事前に画像を縮小されるなどいただいてから再編集にてあらためて登録下さい。<a href="./">戻る</a>');
				}
			}
		}
	}
	
	
	//本文とセットのアップファイル処理
	if(isset($_FILES["data"]["tmp_name"]['upfile'])){
		foreach($_FILES["data"]["tmp_name"]['upfile'] as $key => $val){
			if(is_uploaded_file($_FILES["data"]["tmp_name"]['upfile'][$key])){
				//サイズチェック
				if ($_FILES["data"]["size"]['upfile'][$key] < $maxImgSize) {
				
					//ファイルタイプを取得
					$extension = '';
					$imgType = $_FILES['data']['type']['upfile'][$key];
					if ($imgType == 'image/gif') {
						$extension = 'gif';
						$image = ImageCreateFromGIF($_FILES['data']['tmp_name']['upfile'][$key]); //GIFファイルを読み込む
					} else if ($imgType == 'image/png' || $imgType == 'image/x-png') {
						$extension = 'png';
						$image = ImageCreateFromPNG($_FILES['data']['tmp_name']['upfile'][$key]); //PNGファイルを読み込む
					} else if ($imgType == 'image/jpeg' || $imgType == 'image/pjpeg') {
						$extension = 'jpg';
						$image = ImageCreateFromJPEG($_FILES['data']['tmp_name']['upfile'][$key]); //JPEGファイルを読み込む
						
						//画像の回転（iPhoneの縦写真が横写真として保存されてしまう問題の対策）
						if(function_exists('exif_read_data') && function_exists('imagerotate')){
							$exif_datas = exif_read_data($_FILES['data']['tmp_name']['upfile'][$key]);
							if(isset($exif_datas['Orientation'])){
								  if($exif_datas['Orientation'] == 6){
									 $image = imagerotate($image, 270, 0);
								  }elseif($exif_datas['Orientation'] == 3){
									 $image = imagerotate($image, 180, 0);
								  }
							}
						}
						
					} 
					else{
						
						$extensionArr = explode('.',$_FILES['data']["name"]["upfile"][$key]);
						$extension = end($extensionArr);
						$extension = strtolower($extension);
						
						if(!in_array($extension,$extensionList)){
							$extension_error = '許可されていない拡張子です<br />';
							exit('<center><span style="color:red;font-size:16px;">【許可されていない拡張子です】<br />記事自体はすでに投稿（編集の場合には変更）されています<br /><a href="./">戻る&gt;&gt;</a></span></center>');
						}
					}
					
					//ファイル名を指定
					$fileName = $data['id'].'-'.$key;
					
					$fileNameNormal = $fileName.'.'.$extension;
					$fileNameS = $fileName.'s.'.$extension;
					
					//拡張子違いのファイルを削除
					foreach($extensionList as $extensionListval){
						$deleteFilePathNormal = $img_updir.'/'.$fileName.'.'.$extensionListval;
						$deleteFilePathL = $img_updir.'/'.$fileName.'.'.$extensionListval;
						$deleteFilePathS = $img_updir.'/'.$fileName.'s.'.$extensionListval;
						if(file_exists($deleteFilePathNormal)) unlink($deleteFilePathNormal);
						if(file_exists($deleteFilePathL)) unlink($deleteFilePathL);
						if(file_exists($deleteFilePathS)) unlink($deleteFilePathS);
					}
					
					$imgFilePath = $img_updir.'/'.$fileNameNormal;//ファイルパスを指定
					$imgFilePathThumb = $img_updir.'/'.$fileNameS;//サムネイルファイルパスを指定
					
					
					if($extension == 'gif' || $extension == 'png' || $extension == 'jpg'){
					
						
						//読み込んだ画像のサイズ
						$width = ImageSX($image); //横幅（ピクセル）
						$height = ImageSY($image); //縦幅（ピクセル）
						
						if($width>$imgWidthHeight or $height>$imgWidthHeight){//画像の縦または横が$imgWidthHeightより大きい場合は縮小して保存
							if ($height < $width){//横写真の場合の処理
								$new_width = $imgWidthHeight; //幅指定px
								$rate = $new_width / $width; //縦横比を算出
								$new_height = $rate * $height;
								
								//サムネイル用処理
								$new_width_thumb = $imgWidthHeightThumb;//高さ指定px
								$rate_thumb = $new_width_thumb / $width;//縦横比を算出
								$new_height_thumb = $rate_thumb * $height;
							
							}else{//縦写真の場合の処理
								$new_height = $imgWidthHeight; //高さ指定px
								$rate = $new_height / $height; //縦横比を算出
								$new_width = $rate * $width;
								
								//サムネイル用処理
								$new_height_thumb = $imgWidthHeightThumb; //高さ指定px
								$rate_thumb = $new_height_thumb / $height; //縦横比を算出
								$new_width_thumb = $rate_thumb * $width;
							}
							
							$new_image = ImageCreateTrueColor($new_width, $new_height);
							
							//透明png、gif対策
							if($extension == 'png'){
								imagealphablending($new_image, false);//ブレンドモードを無効にする
								imagesavealpha($new_image, true);//完全なアルファチャネル情報を保存するフラグをonにする
							}
							elseif($extension == 'gif'){
								$alpha = imagecolortransparent($image);  // 元画像から透過色を取得する
								imagefill($new_image, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
								imagecolortransparent($new_image, $alpha); // 塗りつぶした色を透過色として指定する	
							}
							
							$new_image_thumb = ImageCreateTrueColor($new_width_thumb, $new_height_thumb);//サムネイル作成
							
							//透明png、gif対策
							if($extension == 'png'){
								imagealphablending($new_image_thumb, false);//ブレンドモードを無効にする
								imagesavealpha($new_image_thumb, true);//完全なアルファチャネル情報を保存するフラグをonにする
							}
							elseif($extension == 'gif'){
								$alpha = imagecolortransparent($image);  // 元画像から透過色を取得する
								imagefill($new_image_thumb, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
								imagecolortransparent($new_image_thumb, $alpha); // 塗りつぶした色を透過色として指定する	
							}
							
							ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);
							ImageCopyResampled($new_image_thumb,$image,0,0,0,0,$new_width_thumb,$new_height_thumb,$width,$height);//サムネイル作成
						  
							if($imgType == 'image/jpeg' || $imgType == 'image/pjpeg'){
								if(!@is_int($img_quality)) $img_quality = 80;//画質に数字以外の無効な文字列が指定されていた場合のデフォルト値
								ImageJPEG($new_image, $imgFilePath, $img_quality); //3つ目の引数はクオリティー（0～100）
								ImageJPEG($new_image_thumb, $imgFilePathThumb, $img_quality); //サムネイル作成
							}
							elseif ($imgType == 'image/gif') {
								ImageGIF($new_image, $imgFilePath);//環境によっては使えない
								ImageGIF($new_image_thumb, $imgFilePathThumb);//サムネイル作成
							}
							elseif ($imgType == 'image/png' || $imgType == 'image/x-png') {
								ImagePNG($new_image, $imgFilePath);
								ImagePNG($new_image_thumb, $imgFilePathThumb);//サムネイル作成
							}
							  
							//メモリを解放
							imagedestroy ($image); //イメージIDの破棄
							imagedestroy ($new_image); //元イメージIDの破棄
							imagedestroy ($new_image_thumb); //サムネイル元イメージIDの破棄
						  
						}else{//画像が$imgWidthHeightより小さい場合はそのまま保存
							move_uploaded_file($_FILES['data']['tmp_name']['upfile'][$key],$imgFilePath);
							  
							//----------------------------------------------------------------------
							//  サムネイル生成処理  (START)
							//----------------------------------------------------------------------
							if($width>$imgWidthHeightThumb or $height>$imgWidthHeightThumb){//画像の縦または横がサムネイル指定サイズより大きい場合は生成
							  if ($height < $width){//横写真の場合の処理
							  
								  $new_width_thumb = $imgWidthHeightThumb;//高さ指定px
								  $rate_thumb = $new_width_thumb / $width;//縦横比を算出
								  $new_height_thumb = $rate_thumb * $height;
							  
							  }else{//縦写真の場合の処理
							  
								  $new_height_thumb = $imgWidthHeightThumb; //高さ指定px
								  $rate_thumb = $new_height_thumb / $height; //縦横比を算出
								  $new_width_thumb = $rate_thumb * $width;
							  }
							  
							  $new_image_thumb = ImageCreateTrueColor($new_width_thumb, $new_height_thumb);//サムネイル作成
							  
								//透明png、gif対策
								if($extension == 'png'){
									imagealphablending($new_image_thumb, false);//ブレンドモードを無効にする
									imagesavealpha($new_image_thumb, true);//完全なアルファチャネル情報を保存するフラグをonにする
								}
								elseif($extension == 'gif'){
									$alpha = imagecolortransparent($image);  // 元画像から透過色を取得する
									imagefill($new_image_thumb, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
									imagecolortransparent($new_image_thumb, $alpha); // 塗りつぶした色を透過色として指定する	
								}
							  
							  ImageCopyResampled($new_image_thumb,$image,0,0,0,0,$new_width_thumb,$new_height_thumb,$width,$height);//サムネイル作成
							  
								if($imgType == 'image/jpeg' || $imgType == 'image/pjpeg'){
									if(!@is_int($img_quality)) $img_quality = 80;//画質に数字以外の無効な文字列が指定されていた場合のデフォルト値
									ImageJPEG($new_image_thumb, $imgFilePathThumb, $img_quality); //サムネイル作成
								}
								elseif($imgType == 'image/gif') {
									ImageGIF($new_image_thumb, $imgFilePathThumb);//サムネイル作成
								}
								elseif($imgType == 'image/png' || $imgType == 'image/x-png') {
									ImagePNG($new_image_thumb, $imgFilePathThumb);//サムネイル作成
								}
							  imagedestroy ($new_image_thumb); //サムネイル元イメージIDの破棄
							}else{
								//サムネイルが設定サイズより小さい場合はそのまま保存
								copy($imgFilePath,$imgFilePathThumb);
							}
							//----------------------------------------------------------------------
							//  サムネイル生成処理  (END)
							//----------------------------------------------------------------------
							  
						}
						
						@chmod($imgFilePath, 0666);
						@chmod($imgFilePathThumb, 0666);
					}
					//画像系以外の処理
					else{
						move_uploaded_file($_FILES['data']['tmp_name']['upfile'][$key],$imgFilePath);
						@chmod($imgFilePath, 0666);
					}
			
				}else{
					exit('ファイルサイズオーバーです。<br />記事自体はすでに投稿（編集の場合には変更）されていますので、事前に画像を縮小されるなどいただいてから再編集にてあらためて登録下さい。<a href="./">戻る</a>');
				}
				
			}
		}
	}
		
	//画像削除処理
	if($mode == 'update' && isset($_POST["upfile_del"])){
		foreach($_POST["upfile_del"] as $key => $val){
			if(!empty($val)){
				
				$tmpPath = str_replace($img_updir,'',$val);
				$imgDelPathS = $img_updir.str_replace('.','s.',$tmpPath);//サムネイル削除
				$imgDelPathL = $val;
				
				if(file_exists($imgDelPathS)){
					if(!unlink($imgDelPathS)) exit('s削除エラー');
				}
				if(file_exists($imgDelPathL)){
					if(!unlink($imgDelPathL)) exit('削除エラー');
				}
			}
		}
	}
	//リンクファイル削除
	if($mode == 'update' && isset($_POST["link_file_del"])){
		foreach($_POST["link_file_del"] as $key => $val){
			if($val == 'true' && !empty($key)){
				$res = (file_exists($key)) ? unlink($key) : '';
			}
		}
	}
	
	//ファイル名リネーム（更新時に順序が変わる場合があるため）
	if($mode == 'update' && !empty($_POST['upfile_name'])){
		foreach($_POST['upfile_name'] as $key => $val){
			if(!empty($val)){
				$fileName = str_replace(array($img_updir,'/'),'',$val);//4-0.jpg
				$fileNameArr = explode('.',$fileName);//$fileName[0]=4-0
				$fileNum = str_replace($data['id'].'-','',$fileNameArr[0]);//現在のNum
				
				if($fileNum != $key){
					//新しいファイル名
					$newName = $img_updir.'/'.$data['id'].'-'.$key.'.'.end($fileNameArr);
					rename($val,$newName);//リネーム
					
					//サムネイルがある場合にも
					$thumbnailPath = $img_updir.'/'.$data['id'].'-'.$fileNum.'s.'.end($fileNameArr);
					if(file_exists($thumbnailPath)){
						$newNameThumb = $img_updir.'/'.$data['id'].'-'.$key.'s.'.end($fileNameArr);
						rename($thumbnailPath,$newNameThumb);//リネーム
					
					}
				}
			}
		}
	}
	
	//バックアップ作成と削除
	if($backup_copy=='1' && $mode == "shinki"){
		backup_gen($file_path);//バックアップ作成
		$backup_del_res = backup_del($file_path);//指定月以前のバックアップファイルの削除
	}
	
	//登録完了で再送信防止のリダイレクト
	header("Location: ./complete.php?id={$data['id']}");
	exit();
	
?>