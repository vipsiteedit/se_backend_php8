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
        array('cd', $section->language->lang055),
        array('rd', $section->language->lang122),
        array('na', $section->language->lang123),
        array('nd', $section->language->lang124),
        array('pa', $section->language->lang125),
        array('pd', $section->language->lang126),
        array('aa', $section->language->lang127),
        array('ad', $section->language->lang128),
        array('sd', $section->language->lang129)  
    );
    return $sortarr;
}
}         
?>
