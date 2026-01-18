<?php

  $se_payment = $_SESSION['SE_PAYMENT'];// = array('fp'=>$FP, 'order_id'=>$ORDER_ID, 'lang'=>$lang, 'curr'=>$THISCURR);
  $payment_id = (!empty($_POST['PAYMENT_ID'])) ? intval($_POST['PAYMENT_ID']) : intval($se_payment['order_id']);
  $order_id = (!empty($_POST['ORDER_ID'])) ? intval($_POST['ORDER_ID']) : intval($se_payment['fp']);
  $payment_curr = (!empty($_POST['PAYMENT_CURR'])) ? intval($_POST['PAYMENT_CURR']) : intval($se_payment['curr']);
  

  $_SESSION['SE_PAYMENT']['fp'] = $payment_id;
  $_SESSION['SE_PAYMENT']['order_id'] = $order_id;
  $payment->find(intval($payment_id));
  $payment->result = str_replace("\r\n",'', $payment->result);
// Заполняю переменными
 
        $payment->result = se_macrocomands($payment->result, 
        $payment_id, 
        $order_id);
// Заполняю переменными

  if (preg_match("/SE_PAYEXECUTE\(([\w\W]{1,})\)/um", $payment->result, $m)){
 
    $res_ = explode(",", $m[1]);                   
    $payment->result = str_replace($m[0], '', $payment->result);

    if (($res_[0]=='1') && ($res_[1]>0) && ($res_[2]>0)) {
   // Активация заказа
        $payee = new plugin_payment_payee($order_id, $payment_id,  $basecurr, '');
        $payee->inPayment(round(se_MoneyConvert($res_[1] ,$payment_curr, $basecurr), 0), 
            $payment->name_payment . ' ' . $res_[3], $res_[2]);
        $payee->execute();
        if (preg_match("/\<RESULT\>(.*)\<\/RESULT\>/um", $payment->result, $mres)){
            echo $result = $mres[1];
        }
    } else
    if (preg_match("/\<ERROR\>(.*)\<\/ERROR\>/um", $payment->result, $mres)){
            echo $result = $mres[1];
    }
  }

exit;  

?>