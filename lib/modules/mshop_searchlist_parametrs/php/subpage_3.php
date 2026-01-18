<?php

//if(!$flag) return;

$arr_field = getFieldsArray();  // Разворачивание списка
//print_r($arr_field);              
foreach($arr_field as $name=>$value){
    $line = array();
    $class = "tab1";
    $include = "true";
    $line['line'] = get_search_item($section, $name, $class, $include);  
    $__data->setItemList($section,'searchparam', $line); 
}

foreach($arr_field as $name=>$value){
    $lines = array();
    $class = "tab2";
    $include = true;
    $lines['line_two'] = get_search_item($section, $name, $class, $include);   
    $__data->setItemList($section,'searchparam_two', $lines);
}

$arr_rep = list_parametrs_array();   // Разворачивание дополнительных параметров

foreach($arr_rep as $name=>$value){
    $line = array();
    $class = "tab1";
    $include = "true";
    $line['param_line'] = get_search_item($section, $name, $class, $include);
    $__data->setItemList($section,'searchparametrs', $line);
} 

foreach($arr_rep as $name=>$value){
    $lines = array();
    $class = "tab2";
    $include = "true";
    $lines['param_line_two'] = get_search_item($section, $name, $class, $include);
    $__data->setItemList($section,'searchparametrs_two', $lines);
} 



if(isRequest('Save')){
    foreach($arr_field as $name=>$val){
        $class = "tab1";
        if(isRequest('check_'.$name.'_'.$class)){
            $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$name] = $name;
        }else{
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$name]);
        }
    }
    foreach($arr_rep as $name=>$val){
        $class = "tab1";
        if(isRequest('check_'.$name.'_'.$class)){
            $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$name] = $name;
        }else{
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$name]);
        }
    }
    src_store_params_tab1();
    foreach($arr_field as $name=>$val){
        $class = "tab2";
        if(isRequest('check_'.$name.'_'.$class)){
            $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$name] = $name;
        }else{
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$name]);
        }
    }
    foreach($arr_rep as $name=>$val){
        $class = "tab2";
        if(isRequest('check_'.$name.'_'.$class)){
            $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$name] = $name;
        }else{
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$name]);
        }
    }
    src_store_params_tab2();
    Header("Location: ".seMultiDir().'/'.$_page.'/');    
}

?>