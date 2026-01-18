<?php

function showParamList($__data, $path, $param310, $param318, $id_goods, $show_page = false) {
    $section = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><section/>');
    $viewgoods = $id_goods; 
    //append_simplexml($section->parametrs,$params);
    $section->parametrs->param310 = $param310;
    $section->parametrs->param318 = $param318;
    ob_start();
    include $path . '/php/subpage_modifications.php';
    include $path . '/tpl/subpage_modifications.tpl';
    $modifications = ob_get_contents(); 
    ob_end_clean();
    return $modifications;
}

if (!function_exists('getSortFields')) {
function getSortFields($section) {
    $sortarr = array(
        array('na','Наименование (А - Я)'),
        array('nd','Наименование (Я - А)'),
        array('pa', 'Цена (по возрастанию)'),
        array('pd', 'Цена (по убыванию)'),
        array('aa', 'Артикул (А - Я)'),
        array('ad', 'Артикул (Я - А)')  
    );
    return $sortarr;
}
}         
?>