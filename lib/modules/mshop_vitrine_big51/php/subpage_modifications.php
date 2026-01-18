<?php

$plugin_modifications = new plugin_shopmodifications($viewgoods, $section->parametrs->param318 == 'Y');
$params_list = $plugin_modifications->getModifications(false);
if ($modifications = !empty($params_list)) {
    foreach ($params_list as $key => $val) {
        $modifications_group = array();
        $modifications_group['id'] = $key;
        $modifications_group['name'] = $val['name'];
        $parent_feature = true;
        $available_features1 = null;
        $available_features2 = null;
        foreach ($val['params'] as $key => $val) {
            $modifications_feature = array();
            $modifications_feature['id'] = $key;
            $modifications_feature['name'] = $val['name'];
            $modifications_feature['type'] = $val['type'];
            $modifications_feature['description'] = $val['description'];
            $modifications_feature['line'] = $modifications_group['id'] . '_' . $key;
            $count_values = 0;
            
            if (!empty($available_features2)) {
                if ($available_features1 === null)
                    $available_features1 = $available_features2;    
                else
                    $available_features1 = array_intersect($available_features1, $available_features2);
            }
            
            $available_features2 = null;

            foreach ($val['values'] as $key => $val) {
                if (!empty($available_features1)) {
                    $result = array_intersect($available_features1, $val['modification']); 
                    if (empty($result)) continue;      
                }
                $count_values++;
                $modifications_value = array();
                $modifications_value['id'] = $key;
                $modifications_value['value'] = $val['value'];
                
                if ($modifications_feature['type']=='colorlist')
                    $modifications_value['color'] = $val['color'];
                
                $modifications_value['checked'] = '';
                
                if (in_array($_SESSION['modifications'][$viewgoods][$modifications_group['id']], $val['modification'])) {
                    if ($section->parametrs->param310=='radio')
                        $modifications_value['checked'] = 'checked';
                    else
                        $modifications_value['checked'] = 'selected';
                    if (empty($available_features2))
                        $available_features2 = $val['modification'];
                }
                
                $__data->setItemList($section, 'modifications_value_' . $modifications_feature['line'], $modifications_value);
            }
            
            if ($count_values) { 
                $__data->setItemList($section, 'modifications_feature_' . $modifications_group['id'], $modifications_feature);
            } 
            $parent_feature = false; 
        }
        $__data->setItemList($section, 'modifications_group', $modifications_group);    
    }
}
unset($params_list);

?>