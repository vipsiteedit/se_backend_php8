<?php
class plugin_shopmodifications {
	
	public function getAllList($id_price, $in_sql = null) {
		if (!$id_price) return;
		$shop_modifications = new seTable('shop_modifications', 'sm');
		$shop_modifications->select('
			sm.id,
			smf.id_feature,
			smf.id_value,
			sm.id_mod_group,
			sm.`default`,
			sf.name,
			sf.type,
			sfvl.value,
			sfvl.color
		');
		$shop_modifications->innerJoin('shop_modifications_feature smf', 'sm.id = smf.id_modification');
		$shop_modifications->innerJoin('shop_feature sf', 'smf.id_feature = sf.id');
		$shop_modifications->innerJoin('shop_feature_value_list sfvl', 'smf.id_value = sfvl.id');
		$shop_modifications->innerJoin('shop_modifications_group smg', 'sm.id_mod_group = smg.id');
		$shop_modifications->where('sm.id_price = ?', $id_price);
		if (!empty($in_sql))
			$shop_modifications->andwhere('sm.id IN (?)', $in_sql);   
		$shop_modifications->orderBy('smg.sort', 0);
		$shop_modifications->addOrderBy('sf.sort', 0);
		$shop_modifications->addOrderBy('sfvl.sort', 0);
		return $shop_modifications->getList();  
	}

	public function getModifications($id_price) {
		$params = array();
		$modifications_list = $this->getAllList($id_price);
		if (!empty($modifications_list)) { 
			$selected = array();
			foreach($modifications_list as $val) {
				$id_group = $val['id_mod_group'];
				$id_feature = $val['id_feature'];
				$id_modification = $val['id'];
				$id_value = $val['id_value'];
				
				if (!isset($selected[$id_group])) {
					$selected[$id_group] = array('first' => $id_modification, 'selected' => null, 'default' => null); 
				}
				if (!$selected[$id_group]['default'] && $val['default'])
					$selected[$id_group]['default'] = $id_modification;
				
				if (!$selected[$id_group]['selected'] && isset($_SESSION['modifications'][$id_price][$id_group])) {
					if ($id_modification == $_SESSION['modifications'][$id_price][$id_group])
						$selected[$id_group]['selected'] = $id_modification;
				}       
				
				if (!isset($params[$id_group])) {
					$params[$id_group] = array('name' => 'gr_' . $id_group, 'params' => array());      
				}
				
				if (!isset($params[$id_group]['params'][$id_feature])) {
					$params[$id_group]['params'][$id_feature] = array(
						'name' => $val['name'],
						'type' => $val['type'],
						'values' => array()
					);                
				} 
				if (!isset($params[$id_group]['params'][$id_feature]['values'][$id_value])) {
					$params[$id_group]['params'][$id_feature]['values'][$id_value] = array(
						'value' => $val['value'],
						'color' => $val['color'],
						'modification' => array($val['id'])
					);
				}
				elseif (!in_array($id_modification, $params[$id_group]['params'][$id_feature]['values'][$id_value]['modification'])) {
					$params[$id_group]['params'][$id_feature]['values'][$id_value]['modification'][] = $id_modification;    
				}
			}
		}
		unset($_SESSION['modifications'][$id_price]);
		if (!empty($selected)) {
			foreach ($selected as $key => $val) {
				$sel = !empty($val['selected']) ? $val['selected'] : (!empty($val['default']) ? $val['default'] : $val['first']);
				$_SESSION['modifications'][$id_price][$key] = $sel;
			}
		}
		return $params;
	}
}