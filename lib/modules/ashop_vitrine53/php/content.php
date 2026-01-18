<?php

unset($section->objects);

if (preg_match_all('/[1-9]\d*/', $section->parametrs->param64, $arr)) {
    if (!empty($arr[0])) {
        if ($limit_list = count($arr[0]) > 1) {
            //sort($arr[0]);
            //$limit = isRequest('limit') && in_array(getRequest('limit', 1), $arr[0]) ? getRequest('limit', 1) : $arr[0][0]; 
            
            if (isRequest('limit'))
                $limit = $_SESSION['product_page_limit'] = getRequest('limit', 1);
            elseif (!empty($_SESSION['product_page_limit']))
                $limit = $_SESSION['product_page_limit'];
                
            if (empty($limit) || !in_array($limit, $arr[0]))
                $limit = $arr[0][0];   
            
            foreach($arr[0] as $val) {
                $__data->setItemList($section, 'limits', array('value' => $val, 'selected' => ($val == $limit)));  
            }
        }
        if (empty($limit))
            $limit = array_shift($arr[0]);
    }
}
else
    $limit = (int)$section->parametrs->param64;


$option = array(
    'limit' => $limit,
    'sort' => $section->parametrs->param15,   
    'in_stock' => $section->parametrs->param338 == 'Y',
    'is_under_group' => ($section->parametrs->param60=='Y' || !empty($_GET['q']) || !empty($_GET['f']))
);

list($pricelist, $SE_NAVIGATOR) = $psg->getGoods($option);

$goodscount = count($pricelist); 

$sort_list = getSortFields($section); 

if (!empty($sort_list)) {
    $sort = $psg->getSortVal();
    foreach($sort_list as $val) {   
        $__data->setItemList($section, 'sorts', array('value' => $val[0], 'name' => $val[1], 'selected' => $val[0]==$sort));  
    }
}

if (isRequest('sheet') && ($page_num = getRequest('sheet', 1)) > 1){
    $noindex = true;                
}

if (isRequest('q')) {
    $pss = new plugin_shopsearch();
    if (method_exists($pss, 'getSearchText')) {
        $search_text = $pss->getSearchText();
    }
}

?>
