<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_webpay extends basePayment
{

	public function setVars()
	{
		return array('webpay' => 'Код магазина WebPay', 'wp_secretkey' => 'Секретный ключ', 'wp_login' => 'Логин', 'wp_password' => 'Пароль (MD5)');
	}

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$seed = time();
		$signature = md5($seed . $macros->execute('[PAYMENT.WEBPAY][ORDER.ID]0[THISCURR][ORDER.SUMMA][PAYMENT.WP_SECRETKEY]'));
		$url = ($this->test) ? "https://secure.sandbox.webpay.by:8843" :  "https://secure.webpay.by";
		$blank = $this->blank . '
		<form method="post" action="' . $url . '">
		<input name="wsb_test" value="0" type="hidden">
		<input name=*scart type="hidden">
		<input name="wsb_storeid" value="[PAYMENT.WEBPAY]" type="hidden">
		<input name="wsb_store" value="[MAIN.SHOPNAME]" type="hidden">
		<input name="wsb_order_num" value="[ORDER.ID]" type="hidden">
		<input name="wsb_currency_id" value="[THISCURR]" type="hidden">
		<input name="wsb_language_id" value="russian" type="hidden">
		<input name="wsb_seed" value="' . $seed . '" type="hidden">
		<input name="wsb_signature" value="' . $signature . '" type="hidden">
		<input name="wsb_return_url" value="[MERCHANT_SUCCESS]" type="hidden">
		<input name="wsb_cancel_return_url" value="[MERCHANT_FAIL]" type="hidden">
		<input name="wsb_notify_url" value="[MERCHANT_RESULT]" type="hidden">
		<input name="wsb_invoice_item_name[0]" value="Оплата заказа [ORDER.ID]" type="hidden">
		<input name="wsb_invoice_item_quantity[0]" value="1" type="hidden">
		<input name="wsb_invoice_item_price[0]" value="[ORDER.SUMMA]" type="hidden">
		<input name="wsb_total" value="[ORDER.SUMMA]" type="hidden">
		<input name="wsb_email" value="[USER.USEREMAIL]" type="hidden">
		<input name="wsb_phone" value="[USER.SHORTPHONE]" type="hidden">
		<input name="ORDER_ID" value="[ORDER.ID]" type="hidden">
		<input class="buttonSend inpayee" value="Перейти к оплате" type="submit">
		</form>';
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{
		if (!$this->payment_id) return;
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		echo $macros->execute($this->result);
		exit;
	}

	public function success()
	{
		$this->order_id = $_GET['wsb_order_num'];
		$transaction_id = $_GET['wsb_tid'];
		// API Request (webpay.by)
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$postdata = '*API=&API_XML_REQUEST=' . urlencode($macros->execute('
		<?xml version="1.0" encoding="ISO-8859-1" ?>
		<wsb_api_request>
		<command>get_transaction</command>
		<authorization>
		<username>[PAYMENT.WP_LOGIN]</username>
		<password>[PAYMENT.WP_PASSWORD]</password>
		</authorization>
		<fields>
		<transaction_id>' . $transaction_id . '</transaction_id>
		</fields>
		</wsb_api_request>'));
		$url = ($this->test) ? "https://sandbox.webpay.by" :  "https://billing.webpay.by";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		$response = curl_exec($curl);
		curl_close($curl);
		if (!empty($response)) {
			$xmlDoc = new SimpleXMLElement($response);
			if ($xmlDoc->fields->transaction_id == $transaction_id) {
				if ($xmlDoc->status == 'success') {
					$this->activate($this->order_id, $xmlDoc->fields->amount, $xmlDoc->fields->currency_id, $xmlDoc->fields->rrn, $macros->execute('[PAYMENT.WEBPAY]'), $this->name_payment); //print_r($xmlDoc);
					$this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($xmlDoc->fields->order_id, $xmlDoc->fields->transaction_id, date('Y-m-d H:i:s', intval($xmlDoc->fields->batch_timestamp))), $this->success);
				}
			}
		}
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
