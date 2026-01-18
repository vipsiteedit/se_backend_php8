<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_acquiropay extends basePayment
{

  public function setVars()
  {
    return array('acq_id' => 'Merchant ID', 'acq_prodid' => 'Product ID', 'acq_secret' => 'Secret Word');
  }

  public function startform()
  {
    $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
    return $macros->execute($this->startform);
  }

  public function blank($pagename)
  {
    //$this->blank = preg_replace("/\<payform\>(.+?)\<\/payform\>/imus",'', $this->blank);
    $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
    $url = "https://secure.acquiropay.com/";
    $merchant_id = $macros->execute('[PAYMENT.ACQ_ID]');
    $product_id = $macros->execute('[PAYMENT.ACQ_PRODID]');
    $amount = $macros->execute('[SETCURRENCY:RUB][ORDER.SUMMA]');
    $cf = $macros->execute('[ORDER.ID]');
    $lastname = $macros->execute('[USER.LASTNAME]');
    $firstname = $macros->execute('[USER.FIRSTNAME]');
    if (strpos($firstname, ' ') !== false) {
      if (!$lastname) {
        list($lastname, $firstname) = explode(' ', $firstname);
      }
    }

    $cf2 = $cf3 = '';
    $secret_word = $macros->execute('[PAYMENT.ACQ_SECRET]');

    $token = md5($merchant_id . $product_id  . $amount . $cf . $cf2 . $cf3 . $secret_word);
    $blank = '<h3 class="contentTitle">Оплата заказа:&nbsp;[ORDER.ID]&nbsp;- Acquiropay</h3>
    <br>
    Сумма к оплате: [ORDER_SUMMA]<br>
    <br>
    Для выполения прямого платежа на счет продавца нажмите кнопку "Перейти к оплате"<br>';


    $blank .= '<form method="POST" action="' . $url . '">
    <input name="product_id" value="[PAYMENT.ACQ_PRODID]" type="hidden">
    <input name="token" value="' . $token . '" type="hidden">
    <input name="amount" value="[ORDER.SUMMA]" type="hidden">
    <input name="product_name" value="Contract:[USER.ID]/ Order:[ORDER.ID]" type="hidden">
    <input name="cf" value="[ORDER.ID]" type="hidden">
    <input name="first_name" value="' . $firstname . '" type="hidden">
    <input name="last_name" value="' . $lastname . '" type="hidden">
    <input name="zip" value="[USER.POSTCODE]" type="hidden">
    <input name="country" value="RUS" type="hidden">
    <input name="region" value="" type="hidden">
    <input name="city" value="" type="hidden">
    <input name="address" value="" type="hidden">
    <input name="email" value="[USER.USEREMAIL]" type="hidden">
    <input name="phone" value="[USER.PHONE]" type="hidden">
    <input name="cb_url" value="[MERCHANT_RESULT]" type="hidden">
    <input name="ok_url" value="[MERCHANT_SUCCESS]" type="hidden">
    <input name="ko_url" value="[MERCHANT_FAIL]" type="hidden">';
    //if ($this->test) $blank .= '<input name="pp_identity" value="mobpay" type="hidden">';
    $blank .= '<input name="language" value="ru" type="hidden">
    <input class="buttonSend  inpayee" value="Перейти к оплате" type="submit">
    </form>';
    $this->newPaymentLog();
    return $macros->execute($this->getPathPayment($blank, $pagename));
  }

  public function result()
  {
    $this->order_id = intval($_POST['cf']);
    $res = $this->getPaymentLog();
    $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
    //MD5(merchaint_id + payment_id + status8 + cf + cf2 + cf3 + secret_word)
    $hash = md5($macros->execute("[PAYMENT.ACQ_ID]{$_POST['payment_id']}{$_POST['status']}{$_POST['cf']}{$_POST['cf2']}{$_POST['cf3']}[PAYMENT.ACQ_SECRET]"));
    if (strtoupper($hash) == strtoupper($_POST['sign']) && $_POST['status'] == 'OK') {
      if ($this->activate($this->order_id, $_POST['amount'], '', $_POST['transaction_id'], $_POST['email'], $this->name_payment)) {
        echo 'YES';
      } else {
        echo 'NO';
      }
      exit;
    }
    echo 'NO';
    exit;
  }

  public function success()
  {
    if (empty($this->success)) {
      $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><br>
        Ваш заказ № [ORDER.ID] оплачен <table class="tableTable" border="0">
        <tbody class="tableBody">
        <tr><td>Продукт:</td><td>[PAY.PAY_NUM]</td></tr>
        <tr><td>Номер платежа:</td><td>[PAY.TRANS_NUM]</td></tr>
        <tr><td>Дата и время платежа:</td><td>[PAY.TRANS_DATE]</td></tr>
        </tbody></table>';
    }
    $this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($_POST['product_name'], $_POST['transactionid'], date('Y.m.d H:i:s', strtotime($_POST['date']))), $this->success);
    $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
    $this->success = $macros->execute($this->success);
    return $this->success;
  }

  public function fail()
  {
    $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
    return $macros->execute($this->fail);
  }
}
