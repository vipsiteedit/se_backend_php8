<?php

$options = false;
if (method_exists($psg, 'getProductOptions')) {
    $options = $psg->getProductOptions($viewgoods);
}

if (!empty($options)) {
    foreach ($options as $val) {
        if (!empty($val['options'])) {
            $option_group = array(
                'id' => $val['id'],
                'name' => $val['name'],
                'description' => $val['description'],
            );
            
            $__data->setItemList($section, 'option_group', $option_group);
            
            foreach($val['options'] as $option) {
                
                foreach ($option['values'] as $val) {
                    $__data->setItemList($section, 'option_value' . $option['id'], $val);    
                }
                
                $__data->setItemList($section, 'option' . $option_group['id'], $option);
            }        
        }
    }
}

?>
