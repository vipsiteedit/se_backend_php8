<?php

$option = array(
    'limit'=>intval($section->parametrs->param235),
    'sort'=>array($section->parametrs->param15,0),
    'is_under_group'=>($section->parametrs->param60=='Y')
);
unset($pricelist);
list($pricelist,$SE_NAVIGATOR) = $psg->sameGoods($option, $viewgoods, 'shop_sameprice');

?>