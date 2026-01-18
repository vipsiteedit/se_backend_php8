<?php

if (!function_exists('showSpecParamList')) {
function showSpecParamList($__data, $_section, $path, $id_goods, $show_page = false) {
    $section = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><section/>');
    $viewgoods = $id_goods; 
    //append_simplexml($section->parametrs,$params);
    $section->parametrs->param49 = $_section->parametrs->param49;
    $section->parametrs->param50 = $_section->parametrs->param50;                   
    ob_start();
    include $path . "/php/subpage_modification.php";
    include $path . "/tpl/subpage_modification.tpl";
    $modifications = ob_get_contents(); 
    ob_end_clean();
    return $modifications;
}}

?>