<?php

require_once dirname(__FILE__).'/SimpleImage.php';

class seImages {
   private $namefield;
   private $pathimage;
   private $outname;
   private $maxsize = 5000000;
   public $error;
   
   public function __construct($pathimage = '',  $namefield='userfile')
   {
      $this->namefield = $namefield;
      $this->pathimage = $pathimage;
      $this->error = '';
   }
   
   private function uploadimage($itemimage = 0)
   {
        $this->fileuser = '';
        $flag = true;
        if (is_array($_FILES[$this->namefield]['tmp_name'])){
    	    if (is_uploaded_file($_FILES[$this->namefield]['tmp_name'][$itemimage])){
    		$this->userfile=$_FILES[$this->namefield]['tmp_name'][$itemimage];
    		$this->userfile_size=$_FILES[$this->namefield]['size'][$itemimage];
    		$this->user=strtolower(htmlspecialchars($_FILES[$this->namefield]['name'][$itemimage], ENT_QUOTES));
    	    } else {
    	      $this->error = 'error no post';
    	      return false;
    	    }
        } else
        if (is_uploaded_file($_FILES[$this->namefield]['tmp_name'])){
    	    $this->userfile=$_FILES[$this->namefield]['tmp_name'];
    	    $this->userfile_size=$_FILES[$this->namefield]['size'];
    	    $this->user=strtolower(htmlspecialchars($_FILES[$this->namefield]['name'], ENT_QUOTES));
    	} else {
    	      $this->error = 'error no post';
    	      return false;
    	}

    	$this->sz = GetImageSize($this->userfile);
    	if (preg_match("/\.(gif|jpg|png|jpeg)$/", $this->user, $m) && ($this->sz[2]==1 || $this->sz[2]==2 || $this->sz[2]==3)) {
    		$fileextens = $m[1];
    	} else {
    	    $this->error = 'error type';
    	    return false;
    	}
    	if ($this->userfile_size > $this->maxsize){
    	    $this->error = 'error size';
    	    return false;
    	}

        return true;
    }
    
    public function set_image($outname = '', $width = 300,  $itemimage = 0){
        if (!$this->uploadimage($itemimage)) return;
	$width = intval($width);
	$filename = "";


        $fileextens     = substr($this->user, -3);
    	$uploadfile     = $this->pathimage .'/'. $outname.".".$fileextens;
    	$filename       = $outname.".".$fileextens;

    	if ($this->sz[0]>$width){
    	    $uploadfiletmp  = $this->pathimage.'/'.$outname.".temp";
            move_uploaded_file($this->userfile, $uploadfiletmp);
            $this->ThumbCreate($uploadfile,  $uploadfiletmp,  $fileextens, $width);
                @unlink($uploadfiletmp);
    	} else {
    	    move_uploaded_file($this->userfile, $uploadfile);
    	}
	return $filename;
    }

    public function set_image_prev($outname = '', $thumbwdth = 100, $width=300, $itemimage=0)
    {
        if (!$this->uploadimage($itemimage)) return;
		$width = intval($width);
		$thumbwdth = intval($thumbwdth);
		$filename = "";

	//if (!is_dir(getcwd()."/images"))
        //  mkdir(getcwd()."/images");

	//if (!is_dir(getcwd().$IMAGE_DIR))
        //  mkdir(getcwd().$IMAGE_DIR);
        $fileextens     = substr($this->user, -3);
    	$uploadfile     = $this->pathimage .'/'. $outname.".".$fileextens;
    	$uploadfileprev     = $this->pathimage .'/'. $outname."_prev.".$fileextens;
    	$filename       = $outname.".".$fileextens;


        if ($this->sz[0] > $width) {
            $uploadfiletmp  = $this->pathimage .'/'. $outname.'.temp';
            move_uploaded_file($this->userfile, $uploadfiletmp);
            $this->ImgCreate($uploadfileprev,$uploadfile,$uploadfiletmp,$fileextens, intval($width), intval($thumbwdth));
            @unlink($uploadfiletmp);
        } else {
            move_uploaded_file($this->userfile, $uploadfile);
            $this->ThumbCreate($uploadfileprev,$uploadfile,$fileextens, intval($thumbwdth));
            @unlink($uploadfiletmp);
        }
	return $filename;
    }

    public function ImgCreate($thumbdest, $dest, $source, $ext, $wdth, $twdth)
    {
		$wdth = intval($wdth);
		$twdth = intval($twdth);
		$img = new SimpleImage($source);
		$thumb = new SimpleImage($source);
		$img->fit_to_width($wdth);
		$thumb->fit_to_width($twdth);
		$img->save($dest);
		$thumb->save($thumbdest);
    }

    public function ThumbCreate($dest, $source, $ext, $wdth)
    {
	$wdth = intval($wdth);
	$extarr = array(0=>'',1=>'gif', 2=>'jpg', 3=>'png');
	$f_getimagesize = getimagesize($source);
	$ext = $extarr[$f_getimagesize[2]];
	switch ($ext){
    	    case "gif": @$img = imagecreatefromgif($source);break;
    	    case "png": @$img = imagecreatefrompng($source);break;
    	    default:    @$img = imagecreatefromjpeg($source);break;
	}

	list($width, $height) = $f_getimagesize;
	if ($wdth > $width) {
    	    $wdth=$width; $Hght = intval($height);
    	    copy($source, $dest);
	    return;
	} else {
	    $Hght = intval(round($height * $wdth/$width));
	}

	$d_im   = imagecreatetruecolor($wdth, $Hght);
    if ($f_getimagesize['mime'] == 'image/gif' || $f_getimagesize['mime'] == 'image/png') 
    { 
        // �������� ������ ����� 
        $tr = imagecolorallocate($d_im, 255, 255, 255); 
        // ������� ����������� ����� 
        imagefill($d_im, 0, 0, $tr); 
        // �������� ������ ����� 
        imagecolortransparent($d_im, $tr); 
        imagetruecolortopalette($d_im, false, 256); 
    } 
    else 
    { 
        imagecolorallocate($d_im, 0, 0, 0); 
    }
	imagecopyresampled($d_im,$img,0,0,0,0,$wdth,$Hght,$width,$height);

	switch ($ext){
    	    case "gif": @imagegif($d_im,$dest); break;
    	    case "png": @imagepng($d_im,$dest); break;
    	    default:    @imagejpeg($d_im,$dest, 75); break;
	}
	imagedestroy($d_im);
	imagedestroy($img);
    }
}
?>