<?php

class plugin_shoppage
{
	private static $instance = null;
	private $pageCatalog = '';
	private $pageCart = '';


	public function __construct()
	{
		$this->pageCatalog = $this->getPageCatalog('shopvitrine');
		$this->pageCart = $this->getPageCatalog('shopcart');
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	public function getCatalog()
	{
		return $this->pageCatalog;
	}

	public function getCart()
	{
		return $this->pageCart;
	}

	private function getPageCatalog($module = 'vitrine')
	{
		$folder = SE_DIR;
		//  check pages
		$pages = simplexml_load_file('projects/' . $folder . 'pages.xml');
		foreach ($pages->page as $page) {
			$pagecontent = simplexml_load_file('projects/' . $folder . 'pages/' . $page['name'] . '.xml');
			foreach ($pagecontent->sections as $section) {
				if (strpos($section->type, $module) !== false) {
					return strval($page['name']);
				}
			}
		}
		return false;
	}
}
