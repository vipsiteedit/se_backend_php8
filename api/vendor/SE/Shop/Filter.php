<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

// фильтр
class Filter extends Base
{
    // получить список фильтров
    public function fetch()
    {
        try {
            $u = new DB('shop_feature', 'sf');
            $u->select('sf.id, sf.name');
			$u->innerJoin('shop_modifications_feature mgf', 'mgf.id_feature=sf.id AND mgf.id_modification IS NULL');
			$u->innerJoin('shop_price sp', 'mgf.id_price=sp.id');
			$u->innerJoin('shop_group_tree sgt', 'sgt.id_child=sp.id_group');
			$u->groupBy('sf.id');
			$u->orderBy('sf.sort, sf.name');

            $default[] = array(
                'code' => 'price',
                'name' => 'Цена',
            );
            $default[] = array(
                'code' => 'brand',
                'name' => 'Бренды',
            );
            $default[] = array(
                'code' => 'flag_hit',
                'name' => 'Хиты',
            );
            $default[] = array(
                'code' => 'flag_new',
                'name' => 'Новинки',
            );

            $items = array();
			foreach($this->input['filters'] as $filter) {
				if ($filter['field'] == 'idGroup')
					$u->where('sgt.id_parent=?', intval($filter['value']));
            }
			if ($this->search) {
                foreach ($default as $key => $val) {
                    if (mb_stripos($val['name'], $this->search) === false) {
                        unset($default[$key]);
                    }
                }
                $u->andWhere('sf.name LIKE "%?%"', $this->search);
            }
			
			//writeLog($u->getSql());

            $objects = array_merge($default, $u->getList());

            foreach ($objects as $item) {
                $filter = null;
                $filter['id'] = $item['id'];
                $filter['name'] = $item['name'];
                $filter['code'] = !empty($item['code']) ? $item['code'] : null;
                $items[] = $filter;
            }

            $this->result['count'] = sizeof($items);
            $this->result['items'] = $items;
        } catch (Exception $e) {
            $this->error = "Не удаётся получить список фильтров!";
        }


    }
}