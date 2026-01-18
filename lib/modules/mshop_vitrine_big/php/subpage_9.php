<?php

$option = array(
    'unsold'=>($section->parametrs->param254=='Y'),
    'special'=>($section->parametrs->param279=='Y') ? 'special' : '',
    'limit'=>intval($section->parametrs->param235),
    'sort'=>array($section->parametrs->param15,0),
    'is_under_group'=>($section->parametrs->param60=='Y')
);
unset($pricelist);
list($pricelist,$SE_NAVIGATOR) = $psg->sameGoods($option, $viewgoods, 'shop_sameprice');
$style = false;
$analogscount = count($pricelist); 
$analoggood = 1; 

?>