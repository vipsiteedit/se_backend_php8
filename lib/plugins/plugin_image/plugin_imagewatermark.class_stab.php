<?php
class plugin_imagewatermark {
  // функция, которая сливает два исходных изображения в одно
    private $ext = '';
    private function create_fileimage($source){
	$extarr = array(0=>'',1=>'gif', 2=>'jpg', 3=>'png');
	$f_getimagesize = getimagesize($source);
	$ext = $extarr[$f_getimagesize[2]]; 
	switch ($ext){
			case "gif": $img = @imagecreatefromgif($source);break;
			case "png": $img = @imagecreatefrompng($source);break;
			default:    $img = @imagecreatefromjpeg($source);break;
		}
		return array($img, $ext);
    }
  
  private function joinimage( $main_img_obj, $ext, $watermark_img_obj, $alpha_level = 100, $pos = 'bottom-right' )
  {
	$alpha_level/= 100; // переводим значение прозрачности альфа-канала из % в десятки
	// рассчет размеров изображения (ширина и высота)
	$main_img_obj_w = imagesx( $main_img_obj );
	$main_img_obj_h = imagesy( $main_img_obj );
	$watermark_img_obj_w = imagesx( $watermark_img_obj );
	$watermark_img_obj_h = imagesy( $watermark_img_obj );
	//$dest_x = $main_img_obj_w - $watermark_img_obj_w - 5;
	//$dest_y = $main_img_obj_h - $watermark_img_obj_h - 5;
	//$this->setTransparency($main_img_obj);
	//$this->setTransparency($watermark_img_obj);
	//imagealphablending($main_img_obj, true);
	//imagealphablending($watermark_img_obj, true);

	//imagecopy($main_img_obj, $watermark_img_obj, $dest_x, $dest_y, 0, 0, $watermark_img_obj_w, $watermark_img_obj_h);
	//return $main_img_obj;
	
	
	// определение координат центра изображения
	if ($pos == 'center') {
		$main_img_obj_min_x = floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_max_x = ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_min_y = floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
		$main_img_obj_max_y = ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) ); 
	} else if ($pos == 'bottom-right') {
		$main_img_obj_min_x = ( $main_img_obj_w - $watermark_img_obj_w - 10);
		$main_img_obj_max_x = ( $main_img_obj_w - 10 );
		$main_img_obj_min_y = ( $main_img_obj_h  - $watermark_img_obj_h - 10 );
		$main_img_obj_max_y = ( $main_img_obj_h  - 10 ); 
	}
	// создание нового изображения
	$return_img = imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );
	$this->setTransparency($return_img);
	// пройдемся по исходному изображению
	for( $y = 0; $y < $main_img_obj_h; $y++ ){
		for ($x = 0; $x < $main_img_obj_w; $x++ ){
			$return_color = NULL;
			// определение истинного расположения пикселя в пределах нашего водяного знака
			$watermark_x = $x - $main_img_obj_min_x;
			$watermark_y = $y - $main_img_obj_min_y;
			// выбор информации о цвете для наших изображений
			$main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );
			// если наш пиксель водяного знака непрозрачный 
			if ($watermark_x >= 0 && $watermark_x < $watermark_img_obj_w && $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) 
			{
				$watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );
				// использование значения прозрачности альфа-канала
				$watermark_alpha = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
				$watermark_alpha = $watermark_alpha * $alpha_level;
				// расчет цвета в месте наложения картинок
				$avg_red = $this->_get_ave_color( $main_rgb['red'], $watermark_rbg['red'], $watermark_alpha );
				$avg_green = $this->_get_ave_color( $main_rgb['green'], $watermark_rbg['green'], $watermark_alpha );
				$avg_blue = $this->_get_ave_color( $main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha );
				// используя полученные данные, вычисляем индекс цвета
				$return_color = $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );
				// если же не получиться выбрать цвет, то просто возьмем копию исходного пикселя
			} else { $return_color = imagecolorat( $main_img_obj, $x, $y ); } 
			// из полученных пикселей рисуем новое изоборажение
			imagesetpixel($return_img, $x, $y, $return_color );
		}
	}
	//отображаем изображение с водяным знаком
	return $return_img;
  }

  private function jointext( $main_img_obj, $watermark_text, $color = 0x0000FF, $alpha_level = 100, $pos = 'bottom-right' )
  {
		// Сначала создаем наше изображение штампа вручную с помощью GD
		$font = 'lib/fonts/arial.ttf';
		$bbox = imagettfbbox(14, 0, $font, $watermark_text);
		//print_r($bbox);
		$stamp = imagecreatetruecolor(200, 40);
		//imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
		$this->setTransparency($stamp);
		imagefilledrectangle($stamp, 0 ,0, 200, 40, 0xFFFFFF);
		//$im = imagecreatefromjpeg('photo.jpeg');
	
		imagestring($stamp, 12, 20, 20, $watermark_text, 0x000000);
		//imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

		// Установка полей для штампа и получение высоты/ширины штампа
		$marge_right = 10;
		$marge_bottom = 10;
		$sx = imagesx($stamp);
		$sy = imagesy($stamp);

		// Слияние штампа с фотографией. Прозрачность 50%
		imagecopymerge($main_img_obj, $stamp, imagesx($main_img_obj) - $sx - $marge_right, imagesy($main_img_obj) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), $alpha_level);
		return $main_img_obj;
  }  
  
  // функция для "усреднения" цветов изображений
  private function _get_ave_color( $color_a, $color_b, $alpha_level )
  {
	return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b * $alpha_level ) ) );
  }
  
  // функция, которая находит ближайшие RGB-цвета для нового изображения
  private function _get_image_color($im, $r, $g, $b) 
  {
	$c=imagecolorexact($im, $r, $g, $b);
	if ($c!=-1) return $c;
	$c = imagecolorallocate($im, $r, $g, $b);
	if ($c!=-1) return $c;
	return imagecolorclosest($im, $r, $g, $b);
  }

  private function setTransparency($new_image){
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		//imagefilledrectangle($new_image, 0, 0, $destWidth, $destHeight, $transparent);
		imagecolortransparent($new_image, $transparent);
  }
  
  private function rasampled($img, $size, $res= 's'){
    $width = imagesx( $img );
    $height = imagesy( $img );

    if (($width >= $height && $res == 's') || $res == 'w'){
        if ($size > $width) $size = $width;
	$wdth = $size;
	$hght = intval(round($height * $wdth/$width));
    } 
    if (($width <= $height && $res == 's') || $res == 'h'){
        if ($size > $height) $size = $height;
		$hght = $size;
		$wdth = intval(round($width * $hght/$height));
    }

    if ($wdth >= $width || $hght >= $height) {
       $wdth=$width; $hght = intval($height);
    }
    //echo $size .' res:'.$res.' w:'.$width.'='. $wdth.' h:'.$height.'='.$hght.'<br>'; 

    $d_im   = imagecreatetruecolor(intval($wdth), intval($hght));
    $this->setTransparency($d_im);
    imagecopyresampled($d_im, $img,0,0,0,0,intval($wdth),intval($hght), intval($width), intval($height));
    return $d_im;
	//if ($dest == '') header ("Content-type: " . $f_getimagesize['mime']);
	//@imagepng($d_im, getcwd().'/'.$dest);
    //imagedestroy($d_im);
    //imagedestroy($img);
  }
  
  
  function create_watermark( $main_img_obj, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 100 )
  {
     $width = imagesx($main_img_obj);
     $height = imagesy($main_img_obj);
     $angle =  -rad2deg(atan2((-$height),($width)));
     $text = " ".$text." ";
     $c = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);
     $size = (($width+$height)/2)*2/strlen($text);
     $box  = imagettfbbox ( $size, $angle, $font, $text );
     $x = $width/2 - abs($box[4] - $box[0])/2;
     $y = $height/2 + abs($box[5] - $box[1])/2;
     imagettftext($main_img_obj,$size ,$angle, $x, $y, $c, $font, $text);
     return $main_img_obj;
  }
  
  
  public function execute($source, $watermark, $watermarktype='text', $color = 0x0000FF, $alpha_level = 100, $dest, $size, $res= 's', $pos = 'center'){
	list($main_img_obj, $ext) = $this->create_fileimage($source);
	if ($ext != 'gif'){
	  if ($watermarktype == 'text') {
	    $font = 'lib/fonts/arial.ttf';
	    $img_obj = $this->create_watermark( $main_img_obj, $watermark, $font, 255, 255, 255, $alpha_level);
		//$img_obj = $this->jointext( $main_img_obj, $watermark, $color, $alpha_level, $pos);
	  } else {
		if ($watermark) {
		    list($watermark_img_obj) = $this->create_fileimage($watermark);
		    $img_obj = $this->joinimage( $main_img_obj, $ext, $watermark_img_obj, 100, $pos); //$alpha_level
		}
	  }
	} else {
	    $img_obj = $main_img_obj;
	}
	$img_obj_new = $this->rasampled($img_obj, $size);
    //$img_obj_res = $img_obj;
	switch ($ext){
	case "gif": @imagegif($img_obj_new, getcwd().'/'.$dest);break;
	case "png": @imagepng($img_obj_new, getcwd().'/'.$dest);break;
	default:    @imagejpeg($img_obj_new, getcwd().'/'.$dest, 75);break;
	}
    if (isset($img_obj)) imagedestroy($img_obj);
    if (isset($main_img_obj)) imagedestroy($main_img_obj);
    if (isset($watermark_img_obj))
	imagedestroy($watermark_img_obj);
	if (file_exists(getcwd().'/'.$dest)){
		return $dest;
	}
  }

}
