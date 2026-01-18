<?php

$option = array(
    'limit'=>intval($section->parametrs->param235),
    'sort'=>array($section->parametrs->param15,0),
    'is_under_group'=>($section->parametrs->param60=='Y')
);
list($pricelist, $SE_NAVIGATOR) = $psg->sameGoods($option, $viewgoods, 'shop_accomp');
$size_image = $section->parametrs->param316 == 'T' ? $section->parametrs->param206 : ($section->parametrs->param316 == 'V' ? $section->parametrs->param289 : $section->parametrs->param315);
//$section->parametrs->param327='N';

?>
