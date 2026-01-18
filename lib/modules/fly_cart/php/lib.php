<?php

if (!function_exists('inAjaxCart')) {
    function inAjaxCart($id){
        /*
        $shopcart = $_SESSION['shopcart'];

        if (!empty($shopcart)){
            foreach($shopcart as $key => $val){
                if (!empty($val['id']) && $val['id'] == $id){
                    return $key;
                    break;
                }    
            }
            return;
        }
        else return;
        */
        if (!empty($_SESSION['shopcart'][$id]))
            return $id;
        else
            return;
    }
}

if (!function_exists('summAjaxCart')) {
function summAjaxCart($pricemoney, $type_price, $rounded = ''){
    $cart = $_SESSION['shopcart'];
    $sum_amount = 0;
    $sum_discount = 0;
    $sum_count = 0;
    if (!empty($cart)){  
        foreach($cart as $key => $val){
            $amount = new plugin_shopAmount($val['id'], '', $type_price, $val['count'], $val['params'], $pricemoney);
            $price_new = $amount->getAmount(true);
            $price_old = $amount->getAmount(false);
            $count = $amount->getActualCount();
            unset($amount);
            $sum_amount += $price_new;
            $sum_discount += $price_old - $price_new;
            $sum_count += $count;        
        }
    }
    if ($rounded){
        $sum_amount = round($sum_amount);
        $sum_discount = round($sum_discount);    
    }
    return array('s_count' => $sum_count,
        's_amount' => se_formatMoney($sum_amount, $pricemoney), 
        's_discount' => se_formatMoney($sum_discount, $pricemoney)

    );
}
}

if (!function_exists('changeAjaxCart')) {
    function changeAjaxCart($action='', $id_goods = 0, $pricemoney, $type_price, $rounded){
        if ($action == 'add'){
            $prc = new seTable('shop_price');
            $prc->find($id_goods);
            $name = $prc->name;
            $curr = $prc->curr;
            $code = $prc->code;
            $count = $prc->presence_count;
            unset($prc);
            if ($count !== 0){
                $shopcart = new plugin_ShopCart();
                
                
                if (!empty($_SESSION['SHOP_VITRINE']['selected'][$id_goods]))
                    $add_cart_param = join(',', $_SESSION['SHOP_VITRINE']['selected'][$id_goods]);
                else                
                    $add_cart_param = getRequest('addcartparam', 3);    
    
                if (!empty($add_cart_param))
                    $add_cart_param = 'param:'.$add_cart_param;
  
                $cart_name = md5($id_goods.'_'.$add_cart_param);
                $in_cart = inAjaxCart($cart_name);

                $shopcart->addCart(array('param'=>$add_cart_param));
                
                unset($shopcart);
                if ($in_cart){
                    $count = $_SESSION['shopcart'][$in_cart]['count'];
                    $amount = new plugin_shopAmount($id_goods, '', $type_price, $count, $add_cart_param, $pricemoney);
                    $summ = $amount->getAmount(true);
                    $s_summ = $amount->showAmount(true, $rounded, " ");
                    unset($amount);
                    $arr['action'] = 'update';
                    $arr['data'] = array(
                        'id' => $in_cart,
                        'code' => $code,
                        'count' => $count,      
                        'summ' => $s_summ
                    );
                }
                else{
                    if (!empty($add_cart_param)){
                        $param = new plugin_ShopParams($add_cart_param);
                        $name .= ' ('.$param->getParamsName().')';
                        unset($param);
                    }
                    $cart_count = (getRequest('addcartcount', 1) > 0) ? getRequest('addcartcount', 1) : 1;
                    $amount = new plugin_shopAmount($id_goods, '', $type_price, $cart_count, $add_cart_param, $pricemoney);
                    $cart_count = $amount->getActualCount();
                    $price = $amount->getAmount(true);
                    $s_price = $amount->showAmount(true, $rounded, " ");
                    unset($amount);
                    $arr['action'] = 'add';
                    $arr['data'] = array(
                        'id' => $cart_name,
                        'code' => $code,
                        'name' => $name,
                        'price' => $s_price,
                        'count' => $cart_count 
                    );    
                }

                $arr['data']['sumdata'] = summAjaxCart($pricemoney, $type_price, $rounded);
            
            }
            else{
                $arr['action'] = 'empty';    
            }
            return $arr;    
        }   
        elseif($action == 'delete'){
            $in_cart = inAjaxCart($id_goods);
            if ($in_cart){
                unset($_SESSION['shopcart'][$id_goods]);
                $arr['action'] = 'remove';
                $arr['data'] = array(
                    'id' => $id_goods    
                );

                $arr['data']['sumdata'] = summAjaxCart($pricemoney, $type_price, $rounded);
            }
            else{
                $arr['action'] = 'empty';    
            }
            return $arr;        
        }
        else return;        
    }
}
?>