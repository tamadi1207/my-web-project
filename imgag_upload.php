<?php
require "./library/class.upload.php";

function image_upload($filename){

  if($_FILES['upload']){
        $date= time();
    //-------------JPEGリサイズ作成-------------//
        $img= "4017-$date.jpg";
        $file2 = "./img/bldg/4017/$img"; // 画像保存先のパス
//フォルダ存在チェック
if(!file_exists($file2)){
        $folder= "./img/bldg/4017";
        //ファイルorフォルダかチェック
        if(!is_dir($folder)){
             //ディレクトリフォルダ作成
                 @mkdir($folder, 0777, true);
                            }
                              }

   $handle = new Upload($_FILES['file']);

  if(!$handle->uploaded)
    return $handle->error;

  //通常の大きさの画像
  $handle->file_overwrite     = true;      //ファイル上書き有効
  $handle->file_auto_rename   = false;     //ファイル名自動リネーム無効
  $handle->file_src_name_body = $filename; //ファイル名指定
  $handle->Process($folder);           //画像アップロード実行

  //サムネイル画像
  $handle->file_overwrite     = true;
  $handle->file_auto_rename   = false;
  $handle->file_src_name_body = $filename . "_thumb";
  $handle->image_resize       = true;
  $handle->image_ratio_y      = true;
  $handle->image_x            = 200;
  $handle->Process($folder);

  if (!$handle->processed)
    return $handle->error;
}
}
$hoge = 'ii';
$hoge2 = 'a';
image_upload($hoge.$hoge2);

?>