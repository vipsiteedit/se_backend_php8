<?php

if (!function_exists('showParamList')) {
function showParamList($__data, $path, $param310, $param318, $id_goods, $show_page = false) {
    $section = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><section/>');
    $viewgoods = $id_goods; 
    //append_simplexml($section->parametrs,$params);
    $section->parametrs->param310 = $param310;
    $section->parametrs->param318 = $param318;
    ob_start();
    include $path . "/php/subpage_modifications.php";
    include $path . "/tpl/subpage_modifications.tpl";
    $modifications = ob_get_contents(); 
    ob_end_clean();
    return $modifications;
}}

//сортировка по полю
if (!function_exists('getSortFields')) {
function getSortFields($section, $sortval) {
    $field = substr($sortval, 0, 1);
    $OPTIONS = '';
    $sortarr = array(
        array('n','name',$section->language->lang052),
        array('a','article',$section->language->lang053),
        array('p','price',$section->language->lang054),
        array('c','presence_count_adopt',$section->language->lang055),
        array('r','created_at',$section->language->lang010)
    );

    foreach($sortarr as $s){
        $select = ($field == $s[0]) ? ' selected' : '';
        $OPTIONS .= '<option value="'.$s[0].'"'.$select.'>'.$s[2]."</option>\n";
    }
    return $OPTIONS;
}
}

//сортировка по направлению
if (!function_exists('getSortAsc')) {
function getSortAsc($section, $sortval){
    $asc = substr($sortval, 1, 1);
    $OPTIONS = '';
    $sortarr = array(
        array('a',$section->language->lang011),
        array('d',$section->language->lang012)
    );

    foreach($sortarr as $s){
        $select = ($asc == $s[0]) ? ' selected' : '';
        $OPTIONS .= '<option value="'.$s[0].'"'.$select.'>'.$s[1]."</option>\n";
    }
    return $OPTIONS;
}
}            
?>