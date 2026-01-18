<?php

class plugin_shopfilter
{

    private $group;
    private $selected_filter = array();
    private $idf = '';

    public function __construct($basegroup = '', $id_group = 0)
    {
        if (empty($id_group)) {
            $group = plugin_shopgroups::getInstance();
            $id_group = $group->getGroupId((string)$basegroup);
        }
        $this->group = $id_group;
        if (isRequest('f')) {
            $get = $_GET['f'];
            if (is_array($get))
                $this->selected_filter = $_GET['f'];
        }
        if (isset($_GET['idf'])) {
            $this->idf = $_GET['idf'];
        }
        $this->createCacheDb();
    }

    public function existFilters()
    {
        $filters = array();
        if (!empty($this->group)) {
            $sgf = new seTable('shop_group_filter', 'sgf');
            $sgf->select('sgf.id_feature, sgf.default_filter, sgf.expanded, sf.type, sf.name, sf.measure, 
			(SELECT max(sfvl.created_at) FROM shop_feature_value_list sfvl WHERE sfvl.id_feature=sgf.id_feature) `upd1`,
			(SELECT max(`smf`.created_at) FROM shop_modifications_feature `smf` WHERE `smf`.id_feature=sgf.id_feature) `upd2`
			');
            $sgf->leftJoin('shop_feature sf', 'sf.id=sgf.id_feature');
            $sgf->where('sgf.id_group=?', $this->group);
            $sgf->andwhere('(sgf.id_feature IS NOT NULL OR sgf.default_filter IS NOT NULL)');
            $sgf->orderBy('sgf.sort', 0);
            $list = $sgf->getList();

            if (!empty($list)) {
                foreach ($list as $val) {
                    //if (trim($val['name'])=='') continue;
                    $key = (!empty($val['id_feature'])) ? $val['id_feature'] : $val['default_filter'];
                    if (!empty($val['id_feature'])) {
                        $key = (int)$val['id_feature'];
                        if (!in_array($val['type'], array('list', 'colorlist', 'number', 'bool'))) {
                            continue;
                        }
                    } else {
                        $key = $val['default_filter'];
                        if ($key == 'flag_hit')
                            $key = 'hit';
                        elseif ($key == 'flag_new')
                            $key = 'new';
                    }

                    $filters[$key] = $val;
                }
            }
        }
        return $filters;
    }

    public function getCountFiltered()
    {
        $goods = new plugin_shopgoods();
        return $goods->getGoodsCount();
    }

    public function getSQLFiltered()
    {
        $join = $where = $select = array();
        if (!empty($this->selected_filter)) {
            $default_filter = array('price', 'brand', 'hit', 'new', 'discount', 'special');
            $base_curr = se_baseCurrency();
            $current_curr = se_getMoney();
            $i = 0;

            //$join[] = array('type'=>'left', 'table'=>'shop_modifications sm', 'on'=>'sp.id = sm.id_price');
            foreach ($this->selected_filter as $key => $val) {
                $value = $val;
                if (in_array($key, $default_filter)) {
                    switch ($key) {
                        case 'price':
                            $pspc = plugin_shop_price_cache::getInstance();
                            $price_from = se_MoneyConvert((float)$value['from'], $current_curr, $base_curr);
                            $price_to = se_MoneyConvert((float)$value['to'], $current_curr, $base_curr);
                            $join[] = array('type' => 'inner', 'table' => 'shop_price_cache spc', 'on' => 'sp.id = spc.id_price');
                            $where[] = '(spc.price BETWEEN "' . $price_from . '" AND "' . $price_to . '")';
                            $select[] = 'spc.modifications AS mod_cache';
                            break;
                        case 'brand':
                            $join[] = array('type' => 'inner', 'table' => 'shop_brand sb', 'on' => 'sp.id_brand=sb.id');
                            $value = join(',', array_map('intval', $value));
                            $where[] = "(sb.id IN ($value))";
                            break;
                        case 'hit':
                            if ($value === '1') {
                                $where[]  = "sp.flag_hit = 'Y'";
                            } elseif ($value === '0') {
                                $where[] = "sp.flag_hit = 'N'";
                            }
                            break;
                        case 'new':
                            if ($value === '1') {
                                $where[] = "sp.flag_new = 'Y'";
                            } elseif ($value === '0') {
                                $where[] = "sp.flag_new = 'N'";
                            }
                            break;
                        case 'discount':
                            if ($value === '1') {
                                //$where[] = '(SELECT DISTINCT 1 FROM shop_discount_links sdl WHERE sdl.id_price = sp.id OR sdl.id_group = sp.id_group LIMIT 1) IS NOT NULL';
                                $join[] = array('type' => 'inner', 'table' => 'shop_discount_links sdl', 'on' => 'sp.id = sdl.id_price');
                            } elseif ($value === '0') {
                                $where[] = '(SELECT DISTINCT 1 FROM shop_discount_links sdl WHERE sdl.id_price = sp.id OR sdl.id_group = sp.id_group LIMIT 1) IS NULL';
                            }
                            break;
                        case 'special':
                            if ($value === '1') {
                                $join[] = array('type' => 'inner', 'table' => 'shop_leader sl', 'on' => 'sp.id = sl.id_price');
                            } elseif ($value === '0') {
                                $join[] = array('type' => 'left', 'table' => 'shop_leader sl', 'on' => 'sp.id = sl.id_price');
                                $where[] = 'sl.id IS NULL';
                            }
                            break;
                    }
                } else {
                    $spf = 'spf' . $i++;
                    (int)$key;
                    if (is_array($value)) {
                        if (isset($value['from']) && isset($value['to'])) {
                            (float)$value['from'];
                            (float)$value['to'];
                            if (isset($value['from']) && isset($value['to'])) {
                                $join[] = array('type' => 'inner', 'table' => 'shop_modifications_feature ' . $spf, 'on' => $spf . '.id_price=sp.id');
                                $where[] = '(' . $spf . '.id_feature=' . $key . ' AND ' . $spf . '.value_number BETWEEN "' . (float)$value['from'] . '" AND "' . (float)$value['to'] . '")';
                            }
                        } else {
                            $join[] = array('type' => 'inner', 'table' => 'shop_modifications_feature ' . $spf, 'on' => "$spf.id_price=sp.id");
                            //$join[] = array('type'=>'inner', 'table'=>'shop_modifications_feature ' . $spf, 'on'=>"$spf.id_price=sp.id OR sm.id=$spf.id_modification");
                            $value = join(',', array_map('intval', $value));
                            $where[] = "({$spf}.id_feature={$key} AND {$spf}.id_value IN ({$value}))";
                        }
                    } elseif ($val === '0' || $val === '1') {
                        $join[] = array('type' => 'inner', 'table' => 'shop_modifications_feature ' . $spf, 'on' => $spf . '.id_price=sp.id');
                        $where[] = '(' . $spf . '.id_feature=' . $key . ' AND ' . $spf . '.value_bool = ' . (int)$value . ')';
                    }
                }
            }
        }
        return array($join, $where, $select);
    }

    public function getFilterValues($tree_group = null, $flLive = false)
    {
        $filter_list = $this->existFilters();

        if (empty($filter_list)) return;

        $filters = array();

        list($join, $where) = $this->getSQLFiltered();
        $request = '';
        foreach ($where as $wh) {
            $request .= '&&' . $wh;
        }
        $request = md5($request);
        foreach ($filter_list as $key => $feature) {
            $start = microtime(true);
            if (is_numeric($key)) {

                if ($feature['type'] == 'list' || $feature['type'] == 'colorlist') {
                    //$cache = $this->getCache($key, $request, $feature);
                    if (!empty($cache)) {
                        $filters[$key] = $cache;
                    } else {
                        $sf = new seTable('shop_modifications_feature', 'smf');

                        $sf->select('sfvl.value, sfvl.id, sfvl.color, sfvl.image');
                        $sf->innerJoin('shop_feature_value_list sfvl', 'sfvl.id = smf.id_value');
                        $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                        $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                        $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');

                        $sf->where('sp.enabled="Y"');
                        $sf->andWhere('sgt.id_parent=?', $this->group);
                        $sf->andWhere('smf.id_feature=?', $key);

                        // Исключаем обрабатку чекбоксов списка в текущей группе
                        //if (trim($this->idf) !== trim($key)) {
                        if (!empty($join)) {
                            foreach ($join as $it) {
                                if ($it['type'] == 'inner')
                                    $sf->innerJoin($it['table'], $it['on']);
                                else
                                    $sf->leftJoin($it['table'], $it['on']);
                            }
                        }
                        if (!empty($where)) {
                            foreach ($where as $wh) {
                                if (
                                    strpos($wh, '.id_feature=' . $this->idf . ' ') === false
                                    || trim($this->idf) !== trim($key)
                                )
                                    $sf->andWhere($wh);
                            }
                        }
                        //}

                        $sf->groupBy('sfvl.id');
                        // сортировка по полю sort
                        $sf->orderBy('sfvl.sort');
                        // сортировка по значению через БД
                        // $sf->orderBy('cast(sfvl.value as signed), sfvl.value, sfvl.sort');
                        // $sf->orderBy('sfvl.value + 0, sfvl.value, sfvl.sort');

                        $list = $sf->getList();
                        echo se_db_error();

                        // natural сортировка символьно-числовая
                        unset($tp_list, $tp_list_temp, $tp_list_test, $rnd);

                        foreach ($list as $tp_val) {
                            $rnd = '_' . rand(); // на всякий случай если будут одинаковые значения параметров
                            $tp_list[$tp_val['value'] . $rnd] = $tp_val;
                            $tp_list_temp[] = $tp_val['value'] . $rnd;
                        }
                        //natcasesort($tp_list_temp);
                        foreach ($tp_list_temp as $tp_k => $tp_v) {
                            $tp_list_test[] = $tp_list[$tp_v];
                        }
                        $list = $tp_list_test;
                        //////////////////

                        if ($list) {
                            $feature_image_dir = '/images/' . se_getLang() . '/shopfeature/';
                            $feature['values'] = array();
                            foreach ($list as $val) {
                                $check = (bool)isset($this->selected_filter[$key]) && is_array($this->selected_filter[$key]) && in_array($val['id'], $this->selected_filter[$key]);

                                $feature['values'][$val['id']] = array('value' => $val['value'], 'check' => $check);

                                if ($feature['type'] == 'colorlist') {
                                    if (!empty($val['image']) && file_exists(SE_ROOT . $feature_image_dir . $val['image']))
                                        $feature['values'][$val['id']]['image'] = $feature_image_dir . $val['image'];
                                    else
                                        $feature['values'][$val['id']]['color'] = $val['color'];
                                }
                            }
                            $this->setCache($key, $request, $feature);
                            $filters[$key] = $feature;
                        }
                    }
                } elseif ($feature['type'] == 'number') {
                    $sf = new seTable('shop_modifications_feature', 'smf');

                    $sf->select('MIN(smf.value_number) AS min_value, MAX(smf.value_number) AS max_value');
                    $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                    $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');

                    $sf->where('sp.enabled="Y"');
                    $sf->andWhere('sgt.id_parent=?', $this->group);
                    $sf->andWhere('smf.id_feature=?', $key);

                    // Обрабатываем фильтр кроме этой группы
                    //if (trim($this->idf) !== trim($key)) {
                    if (!empty($join)) {
                        foreach ($join as $it) {
                            if ($it['type'] == 'inner')
                                $sf->innerJoin($it['table'], $it['on']);
                            else
                                $sf->leftJoin($it['table'], $it['on']);
                        }
                    }
                    if (!empty($where)) {
                        foreach ($where as $wh) {
                            if (
                                strpos($wh, '.id_feature=' . $this->idf . ' ') === false
                                || trim($this->idf) !== trim($key)
                            )
                                $sf->andWhere($wh);
                        }
                    }
                    //}

                    if ($sf->fetchOne()) {
                        $feature['type'] = 'range';
                        $feature['min'] = $sf->min_value;
                        $feature['max'] = $sf->max_value;
                        if (isset($this->selected_filter[$key])) {
                            if (isset($this->selected_filter[$key]['from']))
                                $feature['from'] = (float)$this->selected_filter[$key]['from'];
                            if (isset($this->selected_filter[$key]['to']))
                                $feature['to'] = (float)$this->selected_filter[$key]['to'];
                        }
                        if ($feature['min'] != $feature['max']) {
                            $filters[$key] = $feature;
                        }
                    }
                } elseif ($feature['type'] == 'bool') {
                    $sf = new seTable('shop_modifications_feature', 'smf');

                    $sf->select('DISTINCT smf.value_bool');
                    $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                    $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');

                    $sf->where('sp.enabled="Y"');
                    $sf->andWhere('sgt.id_parent=?', $this->group);
                    $sf->andWhere('smf.id_feature=?', $key);

                    // обрабатываем фильтр кроме текущей группы
                    //if (trim($this->idf) !== trim($key)) {
                    if (!empty($join)) {
                        foreach ($join as $it) {
                            if ($it['type'] == 'inner')
                                $sf->innerJoin($it['table'], $it['on']);
                            else
                                $sf->leftJoin($it['table'], $it['on']);
                        }
                    }
                    if (!empty($where)) {
                        foreach ($where as $wh) {
                            if (
                                strpos($wh, '.id_feature=' . $this->idf . ' ') === false
                                || trim($this->idf) !== trim($key)
                            )
                                $sf->andWhere($wh);
                        }
                    }
                    //}

                    if ($sf->fetchOne()) {
                        $feature['check'] = false;
                        if (isset($this->selected_filter[$key])) {
                            if ($this->selected_filter[$key] === '1' || $this->selected_filter[$key] === '0') {
                                $feature['check'] = $this->selected_filter[$key];
                            }
                        }
                        $filters[$key] = $feature;
                    }
                }
            } else {
                if ($key == 'brand') {
                    $sp = new seTable('shop_price', 'sp');
                    $sp->select('sb.id, sb.name');
                    $sp->innerJoin('shop_brand sb', 'sb.id=sp.id_brand');
                    $sp->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sp->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    $sp->where('sp.enabled="Y"');
                    $sp->andWhere('sgt.id_parent=?', $this->group);

                    // Обрабатываем фильтр кроме текущей группы брендов
                    if (trim($this->idf) !== trim($key)) {
                        if (!empty($join)) {
                            foreach ($join as $it) {
                                if ($it['type'] == 'inner')
                                    $sp->innerJoin($it['table'], $it['on']);
                                else
                                    $sp->leftJoin($it['table'], $it['on']);
                            }
                        }
                        if (!empty($where)) {
                            foreach ($where as $wh) {
                                $sp->andWhere($wh);
                            }
                        }
                    }

                    $sp->groupBy('sb.id');
                    $sp->orderBy('sb.name', 0);

                    $list = $sp->getList();

                    if ($list) {
                        if (isRequest('brand')) {
                            $brand = getRequest('brand');
                            $sb = new seTable('shop_brand');
                            $sb->select('id');
                            $sb->where("code='?'", urldecode($brand));
                            $sb->fetchOne();
                            if ($sb->isFind())
                                $this->selected_filter[$key][] = $sb->id;
                        }
                        $feature['values'] = array();
                        $feature['type'] = 'list';
                        foreach ($list as $val) {
                            $check = (bool)isset($this->selected_filter[$key]) && is_array($this->selected_filter[$key]) && in_array($val['id'], $this->selected_filter[$key]);


                            $feature['values'][$val['id']] = array('value' => $val['name'], 'check' => $check);
                        }
                        $filters[$key] = $feature;
                    }
                } elseif ($key == 'price') {

                    $pspc = plugin_shop_price_cache::getInstance();
                    $shop_price = new seTable('shop_price', 'sp');
                    $shop_price->select('
                        MIN(spc.price) AS minprice,
                        MAX(spc.price) AS maxprice 
                    ');
                    $shop_price->innerJoin('shop_price_cache spc', 'sp.id=spc.id_price');
                    $shop_price->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $shop_price->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    $shop_price->where('sp.enabled = "Y"');
                    $shop_price->andWhere('sgt.id_parent=?', $this->group);

                    if ($shop_price->fetchOne()) {
                        $base_curr = se_baseCurrency();
                        $current_curr = se_getMoney();

                        $feature['type'] = 'range';
                        $feature['min'] = floor(se_MoneyConvert($shop_price->minprice, $base_curr, $current_curr));
                        $feature['max'] = ceil(se_MoneyConvert($shop_price->maxprice, $base_curr, $current_curr));

                        if (isset($this->selected_filter[$key])) {
                            if (isset($this->selected_filter[$key]['from']))
                                $feature['from'] = (float)$this->selected_filter[$key]['from'];
                            if (isset($this->selected_filter[$key]['to']))
                                $feature['to'] = (float)$this->selected_filter[$key]['to'];
                        }
                        if ($feature['min'] != $feature['max']) {
                            $filters[$key] = $feature;
                        }
                    }
                } elseif ($key == 'hit' || $key == 'new') {
                }
            }

            if (!$feature['expanded'] && isset($this->selected_filter[$key]) && isset($filters[$key])) {
                if ($feature['type'] == 'bool') {
                    if (($this->selected_filter[$key] === '1' || $this->selected_filter[$key] === '0'))
                        $filters[$key]['expanded'] = 1;
                } elseif ($feature['type'] == 'number' || $feature['type'] == 'range') {
                    if ($filters[$key]['from'] > $filters[$key]['min'] || $filters[$key]['to'] < $filters[$key]['max'])
                        $filters[$key]['expanded'] = 1;
                } else
                    $filters[$key]['expanded'] = 1;
            }
            if (isset($_GET['test'])) {
                echo $key . ' ' . $feature['name'] . ': ' .  (microtime(true) - $start) . "<br>";
            }
        }
        return $filters;
    }

    private function getCache($id_feature, $request, $feature)
    {
        if ($feature['upd1'] > $feature['upd2']) $feature['upd2'] = $feature['upd1'];
        se_db_query("DELETE FROM shop_filters_cache WHERE id_feature={$feature['id_feature']} AND created_at<'{$feature['upd1']}'");

        $tab = new seTable('shop_filters_cache');
        $tab->select('cache');
        $tab->where('id_group=?', intval($this->group));
        $tab->andwhere('id_feature=?', $id_feature);
        $tab->andwhere("request='?'", $request);
        $result  = $tab->fetchOne();
        if (!empty($result['cache'])) {
            return json_decode($result['cache'], true);
        }
        return array();
    }

    private function setCache($id_feature, $request, $list = array())
    {
        if (empty($list) || !count($list)) return;
        $cache = json_encode($list);
        $tab = new seTable('shop_filters_cache');
        $tab->where('id_group=?', intval($this->group));
        $tab->andwhere('id_feature=?', $id_feature);
        $tab->andwhere("request='?'", $request);
        $tab->fetchOne();
        $tab->id_group = intval($this->group);
        $tab->id_feature = $id_feature;
        $tab->request = $request;
        $tab->cache = $cache;
        return $tab->save();
    }

    private function createCacheDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `shop_filters_cache` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_group` int(10) UNSIGNED NOT NULL,
  `id_feature` int(10) UNSIGNED NOT NULL,
  `request` varchar(255) NOT NULL,
  `cache` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_feature` (`id_feature`),
  KEY `request` (`request`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `shop_filters_cache_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `shop_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_filters_cache_ibfk_2` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        se_db_query($sql);
    }
}
