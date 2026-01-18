<?php

class plugin_shopfilter {
    
    private $group;
    private $selected_filter = array();
    
    public function __construct($id_group = 0) {
        $this->group = $id_group;
        if (isset($_GET['f']))
            $this->selected_filter = $_GET['f'];   
    }
    
    public function existFilters() {
        $filters = array();
        $shop_filter = new seTable('shop_group_filter');
        $shop_filter->select('id_feature, default_filter, sort');
        $shop_filter->where('id_group=?', $this->group);
        $shop_filter->andwhere('(id_feature IS NOT NULL OR default_filter IS NOT NULL)');
        $filters = $shop_filter->getList();
        
        return $filters; 
    } 

    public function getFilter0($tree_group) {
        $filter_list = array();
        $price_feature = new seTable('shop_price_feature', 'spf');
        $price_feature->select("DISTINCT
            spf.id_feature,
            spf.id_value,
            sf.type,
            sf.name,
            sf.measure,
            CASE sf.type
                WHEN 'list' THEN (SELECT sfv.value FROM shop_feature_value sfv WHERE sfv.id = spf.id_value LIMIT 1)
                WHEN 'number' THEN spf.value_number
                WHEN 'bool' THEN spf.value_bool
            END AS value
        ");
        $price_feature->innerJoin('shop_feature sf', 'spf.id_feature = sf.id');
        $price_feature->innerJoin('shop_group_filter sgf', 'spf.id_feature = sgf.id_feature');
        $price_feature->where("(spf.id_price IN (SELECT sp.id FROM shop_price sp WHERE id_group IN ({$tree_group})) 
            OR spf.id_modification IN (SELECT sm.id FROM shop_modifications sm INNER JOIN shop_price sp WHERE sp.id_group IN ({$tree_group})))");
        $price_feature->andWhere("sf.type IN ('list', 'number', 'bool')");
        $price_feature->andWhere('sgf.id_group = ?', $this->group);
        $filter_list = array();
        $filter_list['price'] = array(
            'type' => 'range', 
            'name' => 'Цена', 
            'measure' => 'р.', 
            //'from' => (float)$this->selected_filter['price']['from'], 
            //'to' => (float)$this->selected_filter['price']['to'],
            'min' => floor(se_MoneyConvert(10, $base_curr, $current_curr)), 
            'max' => ceil(se_MoneyConvert(32000, $base_curr, $current_curr)) 
        );
        if (isset($this->selected_filter['price']['from']))
            $filter_list['price']['from'] = (float)$this->selected_filter['price']['from']; 
        if (isset($this->selected_filter['price']['to']))
            $filter_list['price']['to'] = (float)$this->selected_filter['price']['to'];    
        $filter_list['brand'] = array('type' => 'list', 'name' => 'Бренд', 'measure' => '', 'values' => array(1 => array('value' => 'Samsung'), 2 =>  array('value' => 'LG')));
        //$filter_list['hit'] = array('type' => 'bool', 'name' => 'Хит продаж', 'measure' => '', 'true' => 'Да', 'false' => 'Нет', 'check' => false);
        //$filter_list['new'] = array('type' => 'bool', 'name' => 'Новинка', 'measure' => '', 'true' => 'Да', 'false' => 'Нет', 'check' => false);

        $feature_list = $price_feature->getList();

        foreach ($feature_list as $val) {
            $id = $val['id_feature'];
            if (!isset($filter_list[$id])) {
                $filter_list[$id] = array();
                $filter_list[$id]['count'] = 0;
                $filter_list[$id]['name'] = $val['name'];
                $filter_list[$id]['type'] = $val['type'];
                $filter_list[$id]['measure'] = $val['measure'];
                if ($val['type'] == 'list') {
                    $filter_list[$id]['values'] = array();        
                }
                elseif ($val['type'] == 'bool') {
                    $filter_list[$id]['true'] = 'Да';
                    $filter_list[$id]['false'] = 'Нет';
                    $filter_list[$id]['check'] = false;  
                    if (isset($this->selected_filter[$id])) {
                        if ($this->selected_filter[$id] === '1' || $this->selected_filter[$id] === '0') {
                            $filter_list[$id]['check'] = $this->selected_filter[$id];
                        }
                    }    
                }
                elseif ($val['type'] == 'number') {
                    $filter_list[$id]['type'] = 'range';
                    $filter_list[$id]['min'] = $val['value'];
                    $filter_list[$id]['max'] = $val['value'];
                    if (isset($this->selected_filter[$id])) {
                        if (isset($this->selected_filter[$id]['from']))
                            $filter_list[$id]['from'] = (float)$this->selected_filter[$id]['from'];
                        if (isset($this->selected_filter[$id]['to']))
                            $filter_list[$id]['to'] = (float)$this->selected_filter[$id]['to']; 
                    }
                }
            }
            $filter_list[$id]['count']++;
            if ($val['type'] == 'list') {
                $check = (bool)isset($this->selected_filter[$id]) && is_array($this->selected_filter[$id]) && in_array($val['id_value'], $this->selected_filter[$id]);
                $filter_list[$id]['values'][$val['id_value']] = array('value' => $val['value'], 'check' => $check);   
            }
            elseif ($val['type'] == 'number') {
                if ($val['value'] < $filter_list[$id]['min']) {
                    $filter_list[$id]['min'] = $val['value'];
                }
                if ($val['value'] > $filter_list[$id]['max']) {
                    $filter_list[$id]['max'] = $val['value'];  
                }    
            }   
        }
        return $filter_list;
    }
    
    public function getCountFiltered($tree_group) {
        $count_price_found = 0;
        if (!empty($this->selected_filter)) {
            $lang = se_getLang(); 
            $default_filter = array('price', 'brand', 'hit', 'new', 'discount');
            $base_curr = se_baseCurrency();
            $current_curr = se_getMoney(); 
            
            $shop_price = new seTable();
            $shop_price->select('DISTINCT sp.id');
            $shop_price->from('shop_price', 'sp');
            $shop_price->leftJoin('shop_modifications sm', 'sp.id = sm.id_price');
            $shop_price->where("sp.lang = '?'", $lang);
            $shop_price->andwhere('sp.id_group IN (?)', $tree_group);
            $shop_price->andwhere("sp.enabled='Y'");
            $i=0;
            $pr=0;
            foreach($this->selected_filter as $key => $val) {
                $value = $val;
                if (in_array($key, $default_filter)) {
                    switch ($key) {
                        case 'price': 
                            $pr++;
                            $price_from = se_MoneyConvert((float)$value['from'], $current_curr, $base_curr);
                            $price_to = se_MoneyConvert((float)$value['to'], $current_curr, $base_curr);
                            $shop_price->andWhere('(sp.price * (SELECT m.kurs FROM `money` `m` WHERE m.name = sp.curr ORDER BY m.date_replace DESC LIMIT 1) BETWEEN "' . $price_from . '" AND "' . $price_to . '")');
                            break;
                        case 'brand':
                            $pr++;
                            $shop_price->innerJoin('shop_brand sb', 'sp.id_brand=sb.id');
                            $shop_price->andwhere('sb.id IN (?)', join(',', $value)); 
                            break;
                        case 'hit': 
                            $pr++;
                            if ($value === '1') {
                                $shop_price->andwhere("sp.flag_hit = 'Y'");    
                            }
                            elseif ($value === '0') {
                                $shop_price->andwhere("sp.flag_hit = 'N'"); 
                            }
                            break;
                        case 'new':
                            $pr++;
                            if ($value === '1') {
                                $shop_price->andwhere("sp.flag_new = 'Y'");    
                            }
                            elseif ($value === '0') {
                                $shop_price->andwhere("sp.flag_new = 'N'"); 
                            } 
                            break;
                        case 'discount': 
                            break;     
                    }
                }
                else {
                    $spf = 'spf' . $i++;
                    (int)$key;
                    if (is_array($value)) {
                        if (isset($value['from']) && isset($value['to'])) {
                            (float)$value['from'];
                            (float)$value['to'];
                            if (!empty($value['from']) && !empty($value['to'])) {
                                $pr++;
                                $shop_price->innerJoin('shop_modifications_feature ' . $spf, $spf .'.id_price=sp.id');
                                $shop_price->andWhere('('.$spf.'.id_feature='.$key.' AND '.$spf.'.value_number BETWEEN "' . (float)$value['from']. '" AND "' . (float)$value['to'] . '")');
                            }
                        } 
                        else {
                            $pr++;
                            $shop_price->innerJoin('shop_modifications_feature ' . $spf, "$spf.id_price=sp.id OR sm.id=$spf.id_modification");
                            $shop_price->andWhere('('.$spf.'.id_feature='.$key.' AND '.$spf.'.id_value IN (?))', join(',', $value));
                        }   
                    }  
                    elseif ($val === '0' || $val === '1') {
                        $pr++;
                        $shop_price->innerJoin('shop_modifications_feature ' . $spf, $spf .'.id_price=sp.id');
                        $shop_price->andWhere('('.$spf.'.id_feature='.$key.' AND '.$spf.'.value_bool = ?)', (int)$value);
                    } 
                }      
            } 
            if ($pr) {
               // echo $sql = $shop_price->getSQL();
                $count_price_found = count($shop_price->getList());
            }
        }
        return $count_price_found;
    }
    
    public function getFilterValues($tree_group) {
        $filter_list = array();      
        $base_curr = se_baseCurrency();
        $current_curr = se_getMoney(); 
        $price_feature = new seTable('shop_feature', 'sf');
        $price_feature->select("
            sf.id,
            sf.type,
            sf.name,
            sf.measure, 
            GROUP_CONCAT(DISTINCT CASE
                WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT CONCAT(sfv.value, '##', sfv.id) FROM shop_feature_value_list sfv WHERE sfv.id = spf.id_value LIMIT 1)
                WHEN (sf.type = 'number') THEN spf.value_number  
                WHEN (sf.type = 'bool') THEN spf.value_bool
            END SEPARATOR '~~') AS value
        ");
        $price_feature->innerJoin('shop_modifications_feature spf', 'spf.id_feature = sf.id');
        $price_feature->innerJoin('shop_group_filter sgf', 'sgf.id_feature = sf.id');
        $price_feature->where("(spf.id_price IN (SELECT sp.id FROM shop_price sp WHERE sp.enabled='Y' AND id_group IN ({$tree_group})) 
            OR spf.id_modification IN (SELECT sm.id FROM shop_modifications sm INNER JOIN shop_price sp WHERE sp.enabled='Y' AND sp.id_group IN ({$tree_group})))");
        $price_feature->andWhere("sf.type IN ('colorlist', 'list', 'number', 'bool')");
        $price_feature->andWhere('sgf.id_group = ?', $this->group);
        $price_feature->groupBy('sf.id');
        $price_feature->having('COUNT(DISTINCT spf.id_value) + COUNT(DISTINCT spf.value_number) + COUNT(DISTINCT spf.value_bool) > 1');
        //$price_feature->orderBy('sf.name');
        $feature_list = $price_feature->getList();
        $filter_list['price'] = array(
            'type' => 'range', 
            'name' => 'Цена', 
            'measure' => 'р.', 
            //'from' => (float)$this->selected_filter['price']['from'], 
            //'to' => (float)$this->selected_filter['price']['to'],
            'min' => floor(se_MoneyConvert(15, $base_curr, $current_curr)), 
            'max' => ceil(se_MoneyConvert(20000, $base_curr, $current_curr)) 
        );
        
        if (!empty($feature_list)) {
            foreach ($feature_list as $val) {
                $id = $val['id'];
                $filter = array(
                    'name' => $val['name'],
                    'type' => $val['type'],
                    'measure' => $val['measure']
                );
                
                if ($val['type'] == 'bool') {
                    $filter_list[$id]['check'] = false;  
                    if (isset($this->selected_filter[$id])) {
                        if ($this->selected_filter[$id] === '1' || $this->selected_filter[$id] === '0') {
                            $filter['check'] = $this->selected_filter[$id];
                        }
                    }    
                }
                elseif ($val['type'] == 'list' || $val['type'] == 'colorlist') {
                    $filter['values'] = array();
                    $filter['type'] = 'list';
                    if (!empty($val['value'])) {
                        $value_list = explode('~~', $val['value']);
                        sort($value_list);
                        foreach($value_list as $line) {
                            list($value, $id_value) = explode('##', $line);
                            $check = (bool)isset($this->selected_filter[$id]) && is_array($this->selected_filter[$id]) && in_array($id_value, $this->selected_filter[$id]);
                            $filter['values'][$id_value] = array('value' => $value, 'check' => $check);    
                        }
                    }   
                }
                elseif ($val['type'] == 'number') {
                    $filter['type'] = 'range';
                    $value_list = explode('~~', $val['value']);
                    $filter['min'] = min($value_list);
                    $filter['max'] = max($value_list);     
                }
                
                $filter_list[$id] = $filter;        
            }
        }
        
        return $filter_list;       
    }

}