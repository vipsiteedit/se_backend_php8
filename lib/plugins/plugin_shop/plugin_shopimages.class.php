<?php

require_once("lib/lib_images.php");
/**
 * @filesource plugin_shop_images.class.php
 * @copyright EDGESTILE
 */
class plugin_ShopImages
{
	private $path_imggroup;
	private $path_imgprice;
	private $path_imgall;
	private $_page;
	private $price;
	private $type;
	private $path_imgbrand;
	private $path_imgfeature;
	private $path_imgsection;

	public function __construct($type = 'product')
	{
		$this->type = $type;
		$lang = se_GetLang();
		$this->path_imggroup = '/images/' . $lang . '/shopgroup/';
		$this->path_imgprice = '/images/' . $lang . '/shopprice/';
		$this->path_imgbrand = '/images/' . $lang . '/shopbrand/';
		$this->path_imgfeature = '/images/' . $lang . '/shopfeature/';
		$this->path_imgall = '/images/' . $lang . '/shopimg/';
		$this->path_imgsection = '/images/' . $lang . '/shopsections/';
		$this->_page = getRequest('page');
		$this->price = new seShopPrice();
		return $this;
	}

	public function getSizeStyle($size)
	{
		$size_style = '';
		if (preg_match("/([\d]+)[^\d]*([\d]*)/u", $size, $m)) {
			$width = (int)$m[1];
			$height = (int)$m[2];
			if (!empty($width))
				$size_style .= 'max-width:' . $width . 'px;';
			if (!empty($height))
				$size_style .= 'max-height:' . $height . 'px;';
		}

		return $size_style;
	}

	public function getNoImage()
	{
		if (file_exists(dirname(__FILE__) . "/no_foto.gif"))
			return "/lib/plugins/plugin_shop/no_foto.gif";
		else
			return "";
	}

	public function createImage($image, $newimage, $width = 0)
	{
		if (file_exists(getcwd() . $this->path_imgprice . $image) && $width > 0) {
			$ext = getExtFile($image);
			ThumbCreate(getcwd() . $this->path_imgprice . $image, $newimage, $ext, $width);
			return true;
		}
		return false;
	}

	private function processImage($image, $newimage, $width)
	{
		if (!file_exists(getcwd() . $newimage)) {
			if (!$this->createImage($image, $newimage, $width)) {
				return $this->getNoImage();
			}
		} elseif ($width > 0) {
			$size = getimagesize(getcwd() . $newimage);
			if ($size[0] != $width) {
				$this->createImage($image, $newimage, $width);
			}
		}
		return $newimage;
	}

	public function getFullFromImage($image)
	{
		$img_full_name = $this->path_imgprice . $image;
		if (!empty($image) && file_exists(getcwd() . $img_full_name)) {
			return $img_full_name;
		} else {
			return $this->getNoImage();
		}
	}

	public function getFullPriceImage($goods_id)
	{
		$this->price->select('img');
		$this->price->find($goods_id);
		return $this->getFullFromImage($this->price->img);
	}

	public function getMidFromImage($image, $width = 0)
	{
		if (!empty($image)) {
			return $this->processImage($image, $this->path_imgall . setPrefFile($image, '_mid'), $width);
		} else
			return $this->getNoImage();
	}


	public function getMidPriceImage($goods_id, $width = 0)
	{
		$this->price->select('img');
		$this->price->find($goods_id);
		$img_name = $this->price->img;
		return $this->getMidFromImage($this->price->img, $width);
	}

	public function getPrevFromImage($image, $width = 0)
	{
		if (!empty($image)) {
			return $this->processImage($image, $this->path_imgprice . setPrefFile($image, '_prev'), $width);
		} else
			return $this->getNoImage();
	}

	public function getPrevPriceImage($goods_id, $width = 0)
	{
		$this->price->select('img');
		$this->price->find($goods_id);
		return $this->getPrevFromImage($this->price->img, $width);
	}

	public function getPictFromImage($image, $size = 0, $res = 's', $watermark = '', $position = 'center', $waterquality = 75)
	{
		global $CONFIG;
		if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
			return $image;
		}

		$img_name = '';

		switch ($this->type) {
			case 'price':
			case 'product':
				$img_name = $this->path_imgprice;
				break;
			case 'group':
				$img_name = $this->path_imggroup;
				break;
			case 'brand':
				$img_name = $this->path_imgbrand;
				break;
			case 'feature':
				$img_name = $this->path_imgfeature;
				break;
			case 'section':
				$img_name = $this->path_imgsection;
				break;
		}

		$img_name .= $image;
		if (!empty($CONFIG['DBLink']) && !empty($image)) {
			return 'http://' . $CONFIG['DBLink'] . $img_name;
		}
		if (!file_exists(getcwd() . $img_name)) {
			$img_name = $this->path_imgall . $image;
		}
		if ($size == 0)
			return $img_name;
		if (!empty($image) && file_exists(getcwd() . $img_name)) {
			return se_getDImage($img_name, $size, $res, $watermark, 0x0000FF, $position, '', $waterquality);
		} else
			return $this->getNoImage();
	}

	public function getMorePhotoOnClick($viewgoods, $width = 700, $height = 780)
	{
		return "window.open('/lib/plugins/plugin_shop/sshop_fotos.php?shop=$this->_page&goods=$viewgoods&subg=0','Window','scrollbars=yes,toolbar=no,width=$width,height=$height,resizable=yes');";
	}
}
