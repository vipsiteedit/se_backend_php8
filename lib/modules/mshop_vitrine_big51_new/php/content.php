<?php

//рефакторинг 
$option = array(
    'limit' => intval($section->parametrs->param64),
    'sort' => array($section->parametrs->param15, $section->parametrs->param291),
    'in_stock' => $section->parametrs->param332 == 'Y',
    'is_under_group' => ($section->parametrs->param60=='Y' || !empty($_GET['q']) || !empty($_GET['f']))
);

list($pricelist, $SE_NAVIGATOR) = $psg->getGoods($option);
unset($section->objects);
$style = true;
$goodscount = count($pricelist); 
$analoggood = 0;

$sortval = $psg->getSortVal();
$sort_options = getSortFields($section, $sortval);
$sort_direction = getSortAsc($section, $sortval);   

if (isRequest('sheet') && ($page_num = getRequest('sheet', 1)) > 1){
    $noindex = true;                
}    
?>