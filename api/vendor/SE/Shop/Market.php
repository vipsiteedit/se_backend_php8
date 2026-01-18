<?php

namespace SE\Shop;

use SE\Exception;

class Market extends Base
{

	private function childs($list, $level = 0)
	{
		$its = array();

		foreach ($list as $id => $v) {
			$row = explode('/', $v);
			$name = $v;
			$key = join('_', $row);
			unset($row[count($row) - 1]);
			$parent = join('_', $row);
			$its[$key] = array('name' => $name, 'id' => intval($id), 'upid' => $its[$parent]['id']);
			//$this->do_array(array_flip($row),$id, array());
		}
		$items = $this->getTreeView($its);

		return $items;
	}

	private function getTreeView($items, $idParent = null)
	{
		$result = array();
		foreach ($items as $id => $item) {
			if ($item["upid"] == $idParent) {
				$item["childs"] = $this->getTreeView($items, $item['id']);
				$result[] = $item;
			}
		}
		return $result;
	}

	private function do_array($array, $value, $tmp)
	{


		if (!count($array))
			return $value;
		else {

			$keys = array_keys($array);
			$next_key = $keys[0];
			unset($array[$next_key]);
			$tmp[$next_key] = $this->do_array($array, $value, $tmp);
			return $tmp;
		}
	}

	public function Fetch()
	{
		$this->result['items'] = array();
		try {
			$treefile = DOCUMENT_ROOT . '/lib/plugins/plugin_shop/market/market_categories.tree';
			if (file_exists($treefile)) {
				$this->result['items'] = json_decode(file_get_contents($treefile), true);
			} else {
				$catfile = DOCUMENT_ROOT . '/lib/plugins/plugin_shop/market/market_categories.json';
				if (file_exists($catfile)) {
					$list = json_decode(file_get_contents($catfile), true);
					$this->result['items'] = $this->childs($list);
					$this->result['count'] = count($list);
					file_put_contents(DOCUMENT_ROOT . '/lib/plugins/plugin_shop/market/market_categories.tree', json_encode($this->result['items']));
				}
			}
		} catch (Exception $e) {
			$this->error = "Не удаётся получить список новостей!";
		}
		return $this;
	}
}
