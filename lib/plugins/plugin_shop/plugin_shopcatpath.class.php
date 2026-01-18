<?php

/**
 * @filesource plugin_shop_catpath.class.php
 * @copyright EDGESTILE
 */
class plugin_ShopCatPath
{

	private $path_root;
	private $separator;
	private $_page;
	private $group;
	private $price;
	private $new_format;

	public function __construct($path_root, $separator, $new_format = false)
	{

		$this->path_root = $path_root;
		$this->separator = $separator;
		$this->_page = seData::getInstance()->getPageName();
		$this->group = new seShopGroup();
		$this->price = new seShopPrice();
		$this->new_format = $new_format;

		return $this;
	}


	public function getPath($viewgoods)
	{
		$this->price->select('id, id_group, enabled, name, article');
		$this->price->find($viewgoods);

		$upid = $this->price->id_group;

		$showpath = '&nbsp;<span class="goodsPathSepar">' . $this->separator . '</span>&nbsp;' .
			'<span class="goodsActivePath">' . $this->price->name . '</span>';

		while (true) {
			$this->group->select('id, upid, name, code_gr');
			$this->group->find($upid);

			if ($this->new_format) {
				$showpath = '&nbsp;<span class="goodsPathSepar">' . $this->separator . '</span>&nbsp;'
					. '<a class="goodsLinkPath" href="' . seMultiDir() . '/' . $this->_page . '/cat/' . $this->group->code_gr . '/">' . $this->group->name . '</a>' .
					$showpath;
			} else {
				$showpath = '&nbsp;<span class="goodsPathSepar">' . $this->separator . '</span>&nbsp;'
					. '<a class="goodsLinkPath" href="' . seMultiDir() . '/' . $this->_page . '/shopcatgr/' . $upid . '/">' . $this->group->name . '</a>' .
					$showpath;
			}
			$upid = $this->group->upid;
			if (!$this->group->upid)
				break;
		}

		$showpath = '<a class="goodsPathRoot" href="' . seMultiDir() . '/' . $this->_page . '">' . $this->path_root . '</a>' . $showpath;
		return $showpath;
	}
}
