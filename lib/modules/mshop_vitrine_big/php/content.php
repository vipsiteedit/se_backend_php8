<?php

//рефакторинг
$option = array(
    'unsold'=>($section->parametrs->param254=='Y'),
    'special'=>($section->parametrs->param279=='Y') ? 'special' : '',
    'limit'=>intval($section->parametrs->param64),
    'sort'=>array($section->parametrs->param15,$section->parametrs->param291),
    'is_under_group'=>($section->parametrs->param60=='Y')
);

list($pricelist,$SE_NAVIGATOR) = $psg->getGoods($option);
unset($section->objects);
$style = true;
$goodscount = count($pricelist); 
$analoggood = 0;       
?>