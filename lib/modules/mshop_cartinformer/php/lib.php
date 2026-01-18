<?php

if (!function_exists('si_unserializeShopCart')){ 
    function si_unserializeShopCart() 
    {
        $filename = getCwd().'/projects/session/'.getRequest('idcart').'.ser';
        if (file_exists($filename)) {
            $ser = se_file($filename);
            $scart = $_SESSION['shopcart'];
            $newcart = unserialize($ser[0]);
       
            if (!empty($newcart)) {
                foreach($newcart as $id=>$item) {
                    if (!in_array($item, $scart)) {
                        echo $id;
                        $scart[$id] = $item;
                    }
                }
            }
            $_SESSION['shopcart'] = $scart; //array_merge_recursive (unserialize($ser[0]), $_SESSION['shopcart']);
            unlink($filename);
        } 
    }   
}    
  
if (!function_exists('si_acceptUserPrice')){
    function si_acceptUserPrice($incart, $lineKey, $user_price) // Назначает пользовательскую цену
    {
        // Проверяет ограничение минимальной цены, и, если слишком низкая, увеличивает до установленного минимума
        $minimal_price = $incart[$lineKey]['original_price'] - 
                         ($incart[$lineKey]['original_price'] * $incart[$lineKey]['max_discount']) / 100;
                  
        if ($user_price >= $minimal_price)
            $incart[$lineKey]['price'] = $user_price;
        else
            $incart[$lineKey]['price'] = $minimal_price;    
        
        return $incart;  
    }
}
    
if (!function_exists('si_recalculate_cartline')){
    function si_recalculate_cartline($cartline, $priceType, $basecurr) { 
        if(empty($cartline)) 
            return array();
        if (empty($cartline['params'])) $cartline['params'] = '';    
        $plugin_amount = new plugin_shopAmount($cartline['id'], '', $priceType, 
                                               $cartline['count'], $cartline['params'], 
                                               $basecurr);
                                                                
        if (isset($cartline['flagUnsold']) && $cartline['flagUnsold']) // == ЗАЛЕЖАВШИЙСЯ ТОВАР ==
        {
            if ($cartline['flagUserChangedPrice'])
            {                               // Пользователь изменил цену
                $cartline['discount'] = 0;
                $cartline['sum'] = $cartline['price'] * $cartline['count'];       
            }
            else
            {                               // Пользователь не менял цену
                $cartline['price'] = $plugin_amount->getPrice(false);
                $cartline['discount'] = 0;
                $cartline['sum'] = $plugin_amount->getAmount(false);
            }           
        }
        else                             // == ОБЫЧНЫЙ НОРМАЛЬНЫЙ ТОВАР ==
        {
            $cartline['price'] = $plugin_amount->getPrice(false);
            $cartline['discount'] = $plugin_amount->getDiscount(); 
            $cartline['sum'] = $plugin_amount->getAmount();         
        }        
        
        unset($plugin_amount);
        
        return $cartline;
    }
}
    
if (!function_exists('si_recalculate_all')){
    function si_recalculate_all($incart, $priceType, $basecurr, $lineKey = '')
    {
        // Пересчитывает всю корзину, возвращает сумму покупки
        if (!isset($incart) || empty($incart)) 
        {
            return 0;
        }
        else    
        {          
            if (strval($lineKey) == '') { // пересчитываем все строки заказа
                foreach($incart as $key => $line) 
                    $incart[$key] = si_recalculate_cartline($incart[$key], $priceType, $basecurr); 
            }
            else {           
                $incart[$lineKey] = si_recalculate_cartline($incart[$lineKey], $priceType, $basecurr);
            }

            //ИТОГО:
            $subtotal = $totalDiscount = 0; // Обнуляем
                      
            foreach($incart as $key => $value) {
                $subtotal += $incart[$key]['sum']; 
                //$totalDiscount += ($incart[$key]['discount'] * $incart[$key]['count']);
            }    
        
            return $subtotal;
        }
    }
}

if (!function_exists('si_getItemsCount')){
    function si_getItemsCount($incart)
    {
        $count = 0;
        if (!empty($incart)) {
            foreach ($incart as $key => $line) {
                if(!empty($incart[$key]['count'])){
                    $count += $incart[$key]['count'];
                }
            }
        }        
        return $count;
    }
}
    
if (!function_exists('si_update_cartline')){
    function si_update_cartline($incart, $lineKey, $id, $params, $count, $user_price) 
    {
        $plugin_amount = new plugin_shopAmount($id, '', $priceType, $count, $params, $basecurr);
        $incart[$lineKey]['count'] = $plugin_amount->getActualCount();
        unset ($plugin_amount);
    
        $incart[$lineKey]['id'] = $id;
        $incart[$lineKey]['params'] = $params;
        
        // -- Вот здесь определяем значение flagUserChangedPrice --
        $incart[$lineKey]['flagUserChangedPrice'] = (($user_price != $incart[$lineKey]['original_price'])
                                                   && $incart[$lineKey]['flagUnsold']); 
        
        if ($incart[$lineKey]['flagUserChangedPrice'])
            $incart = si_acceptUserPrice($incart, $lineKey, $user_price);  
        
        return $incart;
    }    
}
  
if (!function_exists('si_reloadShopCart')) {
    function si_reloadShopCart($incart) 
    {
        if (isset($_POST['countitem'])) {   
            foreach ($_POST['countitem'] as $key => $value) 
            {                                              
                if (!isset($value['price']))
                    $value['price'] = 0;     
                $incart = si_update_cartline($incart, $key, 
                                             $value['id'], $value['params'], $value['count'], $value['price']);
            }
        }
        return $incart;
    }
}
    
if (!function_exists('si_delete_cartline')) {
    function si_delete_cartline($incart, $key) 
    {
        unset($incart[$key]);
        return $incart; 
    }
}     

if (!function_exists('myFormatMoneyInfo')) {
    function myFormatMoneyInfo($section, $money, $name) 
    {
        $tbl = new seTable('money_title', 'mt');
        $tbl->where("mt.name = '$name'");
        $tbl->fetchOne();

        if ($section->parametrs->param10 == 'Y') // Округлять цену до целых
            $money = round($money);
                    
        $submoney = $money - intval($money);
        if ($submoney > 0) {
            return $tbl->name_front . ' ' . number_format($money, 2, '.', ' ') . ' ' . $tbl->name_flang;
        } else {
            return $tbl->name_front . ' ' . number_format($money, 0, '', ' ') . ' ' . $tbl->name_flang;
        }
    }
}
?>