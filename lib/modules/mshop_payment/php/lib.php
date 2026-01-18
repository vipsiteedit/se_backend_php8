<?php

/**
* Функция isAllowedOrder()
* 
* Функция возвращает true в случае,
* если заказ не содержит недоступных 
* артикулов, и false в противном случае.
* 
* @text недопустимые артикулы в виде тектовой строки, разделенные \r\n
* @order_id номер заказа
* @return bool
*/
if (!function_exists('isAllowedOrder')){
    function isAllowedOrder($text, $order_id) { 
       $article_list = explode("\r\n",trim($text));
       
       $order = new seTable('shop_tovarorder');
       $order->select('article');
       $order->where('id_order=?',$order_id);
       $check_article = $order-> getList();
       $is_allowed = true;
       
       if (!empty($check_article)){       
            foreach($check_article as $article) {
                   if(!in_array(trim($article['article']), $article_list, false)) {              
                        $is_allowed = false;
                        break;
                    }
            }
       }
       return $is_allowed;
    }
}

if(!function_exists('getSummForPay')){
function getSummForPay($user_id){
           $basecurr = se_baseCurrency();
           $summ = 0;
           $payees = new seUserAccount();
           $payees->select('`in_payee`, `out_payee`, `curr`, `operation`, `entbalanse`, date_payee');
           $payees->where('user_id=?', $user_id);
           $payees->orderby('date_payee', 1);
           $payeelist = $payees->getList();                 
           foreach($payeelist as $line) {
             $k = se_Money_Convert(1, $line['curr'], $basecurr, $line['date_payee']);
             if ($line['operation']==0) {
                $summ += $line['entbalanse'] * $k;
                break;
             }
             $summ += ($line['in_payee'] - $line['out_payee']) * $k;
           }
           unset($payees); 
           return $summ;
}}

if(!function_exists('getPathPayment')){
function getPathPayment($str, $page){
    $str = str_replace('[MERCHANT_RESULT]', 'http://'.$_SERVER['HTTP_HOST'].'/'.$page.'/merchant/result/', $str);
    $str = str_replace('[MERCHANT_SUCCESS]', 'http://'.$_SERVER['HTTP_HOST'].'/'.$page.'/merchant/success/', $str);
    $str = str_replace('[MERCHANT_FAIL]', 'http://'.$_SERVER['HTTP_HOST'].'/'.$page.'/merchant/fail/', $str);
    return $str;
}}
?>