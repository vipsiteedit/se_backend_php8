<?php

class plugin_shopcompare
{

	private $compare_list;
	private $type_compare = 0;

	public function __construct($type = 0)
	{
		if (!empty($type)) {
			$_SESSION['type_compare'] = $this->type_compare = (int)$type;
		}
		$this->compare_list = isset($_SESSION['compare']) ? $_SESSION['compare'] : array();

		$this->checkCompare();
	}

	private function checkCompare()
	{
		$find_type = false;
		$last_type = isset($_SESSION['type_compare']) ? (int)$_SESSION['type_compare'] : 0;
		if (isset($_SESSION['type_compare'])) {
			unset($_SESSION['type_compare']);
		}
		if (!empty($_SESSION['test_compare'])) {
			foreach ($_SESSION['test_compare'] as $key => $val) {
				if (empty($val)) {
					unset($_SESSION['test_compare'][$key]);
					continue;
				}
				//if ($this->type_compare == $key)
				//	$find_type = true;	
				if ($last_type == $key)
					$_SESSION['type_compare'] = (int)$key;
			}
		}
		if (!$find_type)
			$this->type_compare = $this->getDefaultType();
	}

	public function getTypeCompare($id_price)
	{
		$type = 0;
		/*
		if (!empty($id_price)) {
			$shop_group = new seTable('shop_group', 'sg');
			$shop_group->select('sg.id, sg.upid, sg.compare');
			$shop_group->innerjoin('shop_price sp', 'sp.id_group = sg.id');
			$shop_group->where('sp.id=?', $id_price);
			$shop_group->fetchOne();
			while ($shop_group->isFind()) {
				if (!$shop_group->compare) {
					$gr = $shop_group->upid;
					if (!empty($gr))
						$shop_group->find($gr);
					else
						break;
				}
				else {
					$type = $shop_group->id;
					break;
				}
			} 
			
		}
		*/
		return $type;
	}

	public function inCompare($id_price)
	{
		$_SESSION['type_compare'] = $this->type_compare = (int)$this->getTypeCompare($id_price);
		return isset($_SESSION['compare']) && in_array($id_price, $_SESSION['compare']);
	}

	public function changeCompare($id_price, $limit = 0)
	{
		$action = '';
		if ($this->inCompare($id_price)) {
			$action = 'remove';
			$this->removeFromCompare($id_price);
		} else {
			$all_count = $this->getCountAllCompare();
			if (($limit > 0 && $limit <= $all_count)) {
				$action = 'remove';
			} else {
				$action = 'add';
				$this->addToCompare($id_price);
			}
		}
		return $action;
	}

	public function addToCompare($id_price)
	{
		$_SESSION['type_compare'] = $this->type_compare = (int)$this->getTypeCompare($id_price);
		if (!isset($_SESSION['test_compare'][$this->type_compare])) {
			$_SESSION['test_compare'][$this->type_compare] = array();
		}
		array_unshift($_SESSION['test_compare'][$this->type_compare], $id_price);
		$_SESSION['compare'][] = $id_price;
	}

	public function removeFromCompare($id_price)
	{
		$_SESSION['type_compare'] = $this->type_compare = (int)$this->getTypeCompare($id_price);
		if (isset($_SESSION['compare'])) {
			unset($_SESSION['compare'][array_search($id_price, $_SESSION['compare'])]);
			unset($_SESSION['test_compare'][$this->type_compare][array_search($id_price, $_SESSION['test_compare'][$this->type_compare])]);
		}
	}

	public function clearCompare()
	{
		unset($_SESSION['compare']);
		unset($_SESSION['test_compare']);
	}

	public function getCountAllCompare()
	{
		$count = isset($_SESSION['compare']) ? count($_SESSION['compare']) : 0;
		return $count;
	}

	public function getCountCompare()
	{
		$count = isset($_SESSION['compare'][$this->type_compare]) ? count($_SESSION['compare'][$this->type_compare]) : 0;
		return $count;
	}

	public function getTypesCompare($name_catalog = 'Каталог')
	{
		$list = array();
		if (!empty($_SESSION['test_compare']) && count($_SESSION['test_compare']) > 0) {
			foreach ($_SESSION['test_compare'] as $key => $val) {
				if (empty($val))
					continue;
				if (!empty($key)) {
					$shop_group = new seTable('shop_group');
					$shop_group->find($key);
					$list[$key]['name'] = $shop_group->name;
				} else
					$list[$key]['name'] = $name_catalog;
				$list[$key]['count'] = count($val);
				$list[$key]['selected'] = $key == $this->type_compare;
			}
		}
		return $list;
	}

	public function getDefaultType()
	{
		if (isset($_SESSION['type_compare']))
			return $_SESSION['type_compare'];
		elseif (!empty($_SESSION['test_compare'])) {
			return array_pop(array_keys($_SESSION['test_compare']));
		}
	}

	public function getGoodsCompare()
	{
		return $_SESSION['test_compare'][$this->type_compare];
	}

	function getCompareList($fields = array(), $modifications = false)
	{
		$id_products = join(', ', $this->getGoodsCompare());
		if (empty($id_products))
			return;
		$plugin_shopgoods = new plugin_shopgoods();

		$option = array(
			'limit' => 30,
			'sort' => '',
			'is_under_group' => 1
		);

		list($pricelist,) = $plugin_shopgoods->getGoods($option, $id_products);

		$empty_list = array();
		foreach ($_SESSION['test_compare'][$this->type_compare] as $line) {
			$head_comare[$line] = array();
			$empty_list[$line] = null;
		}

		if (!empty($pricelist)) {
			foreach ($pricelist as $val) {
				$head_comare[$val['id']] = $val;;
			}
		}

		$features = new seTable('shop_modifications_feature', 'smf');
		$features->select("DISTINCT 
			smf.id_price,
			sf.id AS fid,
			sfg.id AS gid,
			sfg.name AS gname,
			sf.name AS fname,
			sf.type,
			GROUP_CONCAT(DISTINCT CASE    
							WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value)
							WHEN (sf.type = 'number') THEN smf.value_number
							WHEN (sf.type = 'bool') THEN smf.value_bool
							WHEN (sf.type = 'string') THEN smf.value_string 
							ELSE NULL
						END SEPARATOR ', ') AS value");
		$features->innerJoin('shop_feature sf', 'sf.id=smf.id_feature');
		$features->leftJoin('shop_feature_group sfg', 'sf.id_feature_group=sfg.id');
		$features->where('smf.id_price IN (?)', $id_products);
		if (!$modifications)
			$features->andWhere('smf.id_modification IS NULL');
		$features->groupBy('smf.id_price, sf.id');
		$features->orderBy('sfg.sort', 0);
		$features->addOrderBy('sf.sort', 0);
		$feature_list = $features->getList();

		$copmpares = array();
		$allcount = count($empty_list);

		foreach ($feature_list as $line) {
			if (!isset($copmpares['g_' . $line['gid']])) {
				$copmpares['g_' . $line['gid']] = array(
					'name' => $line['gname'],
					'group' => true,
					'count' => $allcount + 1,
					'diff' => 'f-diff',
				);
			}
			if (!isset($copmpares[$line['fid']])) {
				$copmpares['g_' . $line['gid']]['diff'] = 'f-diff';
				$copmpares[$line['fid']] = array(
					'name' => $line['fname'],
					'type' => $line['type'],
					'diff' => 'f-diff',
					'values' => $empty_list,
					'count' => 0
				);
			}
			$copmpares[$line['fid']]['count']++;
			$copmpares[$line['fid']]['values'][$line['id_price']] = $line['value'];
			if ($copmpares[$line['fid']]['count'] == $allcount && count(array_unique($copmpares[$line['fid']]['values'])) == 1) {
				$copmpares[$line['fid']]['diff'] = 'f-same';
				$copmpares['g_' . $line['gid']]['diff'] = 'f-same';
			}
		}
		return array($head_comare, $copmpares);
	}
}
