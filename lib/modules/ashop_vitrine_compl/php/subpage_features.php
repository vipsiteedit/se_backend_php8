<?php

$goods_features = $psg->getGoodsFeatures($viewgoods);
if ($features_exist = !empty($goods_features)) {
    foreach($goods_features as $key => $val) {
        if (!empty($val['features'])) {
            $feature_group = array();
            $feature_group['id'] = $key;
            $feature_group['name'] = $val['name'];
            $feature_group['description'] = $val['description'];
            $feature_group['image'] = $val['image'];  
            $__data->setItemList($section, 'feature_groups', $feature_group);
            foreach($val['features'] as $key => $val) {
                if ($val['type'] == 'bool') {
                    $val['value'] = $val['value'] ? $section->language->lang014 : $section->language->lang015;
                }
                $__data->setItemList($section, 'features' . $feature_group['id'], $val);
            }        
        }
    }
}

?>
