<?php

if (!function_exists('showModifications')) {
function showModifications($__data, $path, $param49, $id_goods, $ident = 'f') {
    $section = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><section/>');
    $viewgoods = $id_goods; 
    //append_simplexml($section->parametrs,$params);
    $section->parametrs->param49 = $param49;
    ob_start();
    include $path . '/php/subpage_modifications.php';
    include $path . '/tpl/subpage_modifications.tpl';
    $modifications = ob_get_contents(); 
    ob_end_clean();
    return $modifications;
}}
?>