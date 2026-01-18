<?php

$user_fields = getUserFields();
if (!empty($user_fields)) {
    foreach ($user_fields as $field) {
        if (isRequest('field_' . $field['code']))
            $field['val'] = $_REQUEST['field_' . $field['code']];
        elseif (isset($_SESSION['userfields'][$field['code']]))
            $field['val'] = $_SESSION['userfields'][$field['code']]; 
        $__data->setItemList($section, 'userfields', $field); 
        if (in_array($field['type'], array('select', 'checkbox', 'radio')) && !empty($field['values'])) {
            $values = explode(',', $field['values']);
            foreach ($values as $val) {
                $checked = false;
                if (!empty($field['val'])) {
                    if (is_array($field['val']) && in_array($val, $field['val'])) {
                        $checked = true;    
                    }
                    elseif ($val == $field['val'])
                        $checked = true;
                        
                }
                $field_values = array(
                    'name' => $val,
                    'check' => $checked
                );
                
                $__data->setItemList($section, 'values' . $field['id'], $field_values);
            }
        }       
    }
}

?>