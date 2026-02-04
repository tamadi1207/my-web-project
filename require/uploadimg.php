<?php
if(!empty($nametype)){

if(!empty($_FILES['upload'])){
    $img = NULL;

    if($_FILES['upload']['type'] == "image/jpeg"){
        $date= time();
    //-------------JPEGリサイズ作成-------------//
        $img= "$code-$date.jpg";
        $file2 = "./img/partsregist/$nametype/$img"; // 画像保存先のパス
//フォルダ存在チェック
if(!file_exists($file2)){
        $folder= "./img/partsregist/$nametype";
        //ファイルorフォルダかチェック
        if(!is_dir($folder)){
             //ディレクトリフォルダ作成
                 @mkdir($folder, 0777, true);
                            }
                              }
if (is_uploaded_file($_FILES["upload"]["tmp_name"])) {
        $file1 = $_FILES["upload"]["tmp_name"]; // 元画像ファイル

//iphoneの向き取得
$exif_data = exif_read_data($file1);
/////////////////////
if(empty($exif_data['Orientation'])){
          move_uploaded_file($file1, $file2);
}else{
//ユーザー定義関数

// 画像の左右反転
function image_flop($image){
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w,$h);
    // 逆側から色を取得
    for($i=($w-1);$i>=0;$i--){
        for($j=0;$j<$h;$j++){
            $color_index = imagecolorat($image,$i,$j);
            $colors = imagecolorsforindex($image,$color_index);
            imagesetpixel($destImage,abs($i-$w+1),$j,imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
        }
    }
    return $destImage;
}
// 上下反転
function image_flip($image){
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w,$h);
    // 逆側から色を取得
    for($i=0;$i<$w;$i++){
        for($j=($h-1);$j>=0;$j--){
            $color_index = imagecolorat($image,$i,$j);
            $colors = imagecolorsforindex($image,$color_index);
            imagesetpixel($destImage,$i,abs($j-$h+1),imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
        }
    }
    return $destImage;
}
// 画像を回転
function image_rotate($image, $angle, $bgd_color){
     return imagerotate($image, $angle, $bgd_color, 0);
}
/////////////////////////

// 画像の方向を正す
function orientationFixedImage($file2,$file1){
    $image = ImageCreateFromJPEG($file1);
    $exif_datas = @exif_read_data($file1);
    if(isset($exif_datas['Orientation'])){
          $orientation = $exif_datas['Orientation'];
          if($image){
                  // 未定義
                  if($orientation == 0){
                  // 通常
                  }else if($orientation == 1){
                        $rotate= image_rotate($image,0, 0);
                  // 左右反転
                  }else if($orientation == 2){
                        $rotate= image_flop($image);
                  // 180°回転
                  }else if($orientation == 3){
                        $rotate= image_rotate($image,180, 0);
                  // 上下反転
                  }else if($orientation == 4){
                        $rotate= image_Flip($image);
                  // 反時計回りに90°回転 上下反転
                  }else if($orientation == 5){
                        image_rotate($image,270, 0);
                        $rotate= image_flip($image);
                  // 時計回りに90°回転
                  }else if($orientation == 6){
                        $rotate= image_rotate($image,270, 0);
                  // 時計回りに90°回転 上下反転
                  }else if($orientation == 7){
                        image_rotate($image,90, 0);
                        $rotate= image_flip($image);
                  // 反時計回りに90°回転
                  }else if($orientation == 8){
                        $rotate= image_rotate($image,90, 0);
                  }
          }
    }
    // 画像の書き出し
    ImageJPEG($rotate ,$file2);
    return false;
}
// 画像の補正
orientationFixedImage($file2,$file1);
/////////////////////

        $in = ImageCreateFromJPEG($file2); // 元画像ファイル読み込み
        $width = ImageSx($in); // 画像の幅を取得
        $height = ImageSy($in); // 画像の高さを取得
        $min_width = 600; // 幅の最低サイズ
        $min_height = 600; // 高さの最低サイズ
        $image_type = exif_imagetype($file2); // 画像タイプ判定用

            if($width >= $min_width|$height >= $min_height){
                if($width == $height) {
                    $new_width = $min_width;
                    $new_height = $min_height;
                } else if($width > $height) {//横長の場合
                    $new_width = $min_width;
                    $new_height = $height*($min_width/$width);
                } else if($width < $height) {//縦長の場合
                    $new_width = $width*($min_height/$height);
                    $new_height = $min_height;
                }
                //　画像生成
                $out = ImageCreateTrueColor($new_width , $new_height);
                ImageCopyResampled($out, $in,0,0,0,0, $new_width, $new_height, $width, $height);
                ImageJPEG($out, $file2);
            echo 'アップロード完了しました。';    
            }
}
}
}elseif($_FILES['upload']['type'] == "image/png"){
        $date= time();
    //-------------PNGリサイズ作成-------------//
        $img= "$code-$date.png";
        $file2 = "./img/partsregist/$nametype/$img"; // 画像保存先のパス
//フォルダ存在チェック
if(!file_exists($file2)){
        $folder= "./img/partsregist/$nametype";
        //ファイルorフォルダかチェック
        if(!is_dir($folder)){
             //ディレクトリフォルダ作成
                 @mkdir($folder, 0777, true);
                            }
                              }
if (is_uploaded_file($_FILES["upload"]["tmp_name"])) {
        $file1 = $_FILES["upload"]["tmp_name"]; // 元画像ファイル

        $in = ImageCreateFrompng($file1); // 元画像ファイル読み込み
        $width = ImageSx($in); // 画像の幅を取得
        $height = ImageSy($in); // 画像の高さを取得
        $min_width = 600; // 幅の最低サイズ
        $min_height = 600; // 高さの最低サイズ
        $image_type = exif_imagetype($file1); // 画像タイプ判定用

            if($width >= $min_width|$height >= $min_height){
                if($width == $height) {
                    $new_width = $min_width;
                    $new_height = $min_height;
                } else if($width > $height) {//横長の場合
                    $new_width = $min_width;
                    $new_height = $height*($min_width/$width);
                } else if($width < $height) {//縦長の場合
                    $new_width = $width*($min_height/$height);
                    $new_height = $min_height;
                }
                //　画像生成
                $out = ImageCreateTrueColor($new_width , $new_height);
                ImageCopyResampled($out, $in,0,0,0,0, $new_width, $new_height, $width, $height);
                Imagepng($out, $file2);
}
                }
              }

}
}