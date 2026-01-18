<?php

/**
 * @filesource plugin_shop_cart.class.php
 * @copyright EDGESTILE
 */
class plugin_ShopParams
{

	private $data;
	private $paramname;
	public $notnull = false;

	public function __construct($paramlist = '')
	{
		$this->paramname = $paramlist;
		if (strpos($paramlist, 'param:') !== false) list(, $paramlist) = explode('param:', $paramlist);
		if (!empty($paramlist)) {
			$this->data = $this->gettree($paramlist);
			$this->paramname = '';
		} else $this->data = array();
	}

	private function gettree($paramlist)
	{
		$spp = new seTable('shop_price_param', 'spp');
		$spp->select('sp.nameparam, spp.value, spp.parent_id, spp.price, spp.count, spp.price_id');
		$spp->innerjoin('shop_param sp', 'spp.param_id=sp.id');
		$spp->where('spp.id IN (?)', $paramlist);
		$list = $spp->getList();
		$treedata = array();
		foreach ($list as $line) {
			if ($this->notnull && $line['count'] === 0) continue;
			if ($line['parent_id']) {
				$tree = $this->gettree($line['parent_id']);
			} else $tree = array();
			$treedata[] = array(
				'name' => $line['nameparam'], 'value' => $line['value'],
				'price' => $line['price'], 'count' => $line['count'], 'node' => $tree
			);
		}
		return $treedata;
	}

	public function getParamsName($parent = '')
	{
		if (!empty($this->paramname)) return $this->paramname;

		if (empty($parent)) {
			$flparent = false;
			$parent = $this->data;
		} else $flparent = true;
		$i = 0;
		$name = '';
		$names = '';
		foreach ($parent as $line) {
			if ($this->notnull && $line['count'] === 0) continue;
			if (!empty($line['node'])) {
				$names = $this->getParamsName($line['node']) . '->';
			} else $names = '';

			if (!$flparent) {
				if ($i == 0) {
					$name = $names . $line['name'] . ': ' . $line['value'];
				} else {
					$name .= "; " . $names . $line['name'] . ': ' . $line['value'];
				}
			} else {
				return $line['name'] . ': ' . $line['value'];
			}

			$i++;
		}
		return $name;
	}

	public function getRootPrice($parent = '')
	{
		if (empty($parent)) {
			$flparent = false;
			$parent = $this->data;
		} else $flparent = true;
		$prc = 0;
		$cnt = '';
		foreach ($parent as $line) {
			if ($this->notnull && $line['count'] === 0) continue;
			if (!$flparent) {
				$prc += $line['price'];
			} else {
				if ($line['price'])
					$prc = $line['price'];
			}
			if (!empty($line['node'])) {
				list($prcf, $cnt) = $this->getRootPrice($line['node']);
				if ($prc == 0) $prc = $prcf;
				else $prc += $prcf;
			}


			if ($line['count'] != -1 && $line['count'] != '') {
				if ($cnt > $line['count'] || $cnt < 1) {
					$cnt = $line['count'];
				}
			}
		}
		return array($prc, $cnt);
	}

	public function getTreeList()
	{
		return $this->data;
	}

	public function getPreviousParamId($param_id, $level)
	{
		$arr = array();
		$spp = new seTable('shop_price_param');
		$n = $param_id;
		while ($n) {
			$arr[] = $n;
			$spp->select('parent_id');
			$spp->Find($n);
			$n = $spp->parent_id;
		}
		$arr = array_reverse($arr);
		unset($spp);
		return $arr[$level];
	}
}
