<?php

$spec = new seTable('shop_price_specification','ssp');
$spec->select('ss.name,ssp.value,ss.measure,ssp.sort');
$spec->innerjoin('shop_specification ss', 'ss.id=ssp.specification_id');
$spec->where('ssp.price_id=?', $id_goods);
$spec->orderby('sort');
$speclist = $spec->getList();
$style = true;
if (!empty($speclist))
foreach($speclist as $item){
    $item['style'] = ($style = !$style) ? "tableRowOdd" : "tableRowEven"; // четные
    $__data->setItemList($section, 'specification', $item);
}

?>