<?php

$id = $psg->getGroupId(trim($section->parametrs->param27));
$shop_brand = new seTable('shop_brand', 'sb');
$shop_brand->select('sb.id, sb.name, sb.code, sb.image, count(*) as cnt, sb.text');
$shop_brand->innerJoin('shop_price sp', 'sb.id=sp.id_brand');
$shop_brand->where('sp.enabled="Y"');
if (!empty($shopcatgr))
    $shop_brand->andWhere("sp.id_group IN(?)", join(',', $psg->getChildrensId($shopcatgr)));
$shop_brand->groupBy('sb.id');
if ($shop_brand->isFindField('sort'))
    $shop_brand->addOrderby('sb.sort');
$shop_brand->addOrderby('sb.name');
$brands = $shop_brand->getList();
if (!empty($brands)) {
    $link_brand = seMultiDir() . '/' . $shoppath . '/';
 
    if (isRequest('cat'))
        $link_brand .= 'cat/' . getRequest('cat') . '/';
    elseif (isRequest('show')) {
        $shop_price = new seTable('shop_price', 'sp');
        $shop_price->select('sg.code_gr');
        $shop_price->innerJoin('shop_group sg', 'sp.id_group = sg.id');
        $shop_price->where('sp.code="?"', getRequest('show', 3));
        if ($shop_price->fetchOne())
            $link_brand .= 'cat/' . $shop_price->code_gr . '/';
    }
    
    $brand_selected = isRequest('brand') ? getRequest('brand') : ''; 
      
    foreach($brands as $val) {
        if (!empty($val['image']))
            $val['image'] = '/images/rus/shopbrand/' . $val['image'];
        $val['title'] = se_db_output($val['name']);
        $val['link'] = $link_brand . 'brand/' . urlencode($val['code']) . SE_END;     
        $__data->setItemList($section, 'brands', $val);
        if ($brand_selected == $val['code']) {
            $brand_text = $val['text'];
        }
    }       
}

?>