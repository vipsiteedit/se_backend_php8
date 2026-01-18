<?php

if (!empty($_SESSION['mshopcart'])) 
{
    $incart = $_SESSION['mshopcart'];
    $shcart = array();
    $summa_order = 0;
    $count_order = 0;
    $fl = true;
    
    foreach($incart as $id_cart=>$value)
    {
        if (!empty($id_cart) && $value['count'])
        {
            $summ = round( intval($value['count']) * round($value['price'], 2), 2);
            $summa_order += $summ;
            $count_order += intval($value['count']);
            if ($fl) $style = 'tableRowEven';
            else $style = 'tableRowOdd';
            $fl = !$fl;
            $shcart[] = array(
            'code'=>$id_cart,
            'article'=>$value['article'],
            'name'=>$value['name'],
            'count'=>$value['count'],
            'price'=>$value['price'],
            'summ'=>$summ,
            'style'=>$style
            );
        }    
    }
    $__data->setList($section, 'goods', $shcart);
}
?>