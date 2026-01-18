<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

class payment_liqpay extends basePayment
{

	public function setVars()
	{
		return array('liq_ID' => 'ID Мерчанта', 'liq_passw' => 'Пароль мерчанта');
	}

	public function startform($payment_id, $text)
	{
		$macros = new plugin_macros(0, $this->order_id, $payment_id);
		return $macros->execute($text);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		if (isset($_POST['send_payee'])) {
			if (isset($_POST['phone'])) {
				$phone = $_POST['phone'];

				$xml = "<request>      
      <version>1.2</version>
      <merchant_id>[PAYMENT.LIQ_ID]</merchant_id>
      <result_url>[MERCHANT_SUCCESS]</result_url>
      <server_url>[MERCHANT_RESULT]</server_url>
      <order_id>[ORDER.ID]</order_id>
      <amount>[ORDER.SUMMA]</amount>
      <currency>UAH</currency>
      <description>User:[USER.ID] ORDER:[ORDER.ID]</description>
      <default_phone>{$phone}</default_phone>
      <pay_way>card, liqpay</pay_way>
</request>";
				$xml = $macros->execute($this->getPathPayment($xml, $pagename));
				$merc_sign = $macros->execute("[PAYMENT.LIQ_PASSW]");
				$sign = base64_encode(sha1($merc_sign . $xml . $merc_sign, 1));
				$xml_encoded = base64_encode($xml);
				$res = $this->getPaymentLog($qw_ident);

				$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/liqpay/test.php" :  "https://www.liqpay.com/?do=clickNbuy";

				$blank = '<form name="formligpay" action="' . $url . '" method="POST">
          <input type="hidden" name="operation_xml" value="' . $xml_encoded . '">
          <input type="hidden" name="signature" value="' . $sign . '">
		  <script type="text/javascript"> formligpay.submit(); </script>
		</form>';
				$person = new sePerson();
				$person->update('phone', "'{$phone}'");
				$person->where('id=?', $this->user_id);
				$person->save();
				$this->newPaymentLog();
				return $blank;
			}
		}

		$phone = $macros->execute("[USER.PHONE]");

		$phone = preg_replace('/[^\d]*/iu', '', $phone);
		if (substr($phone, 0, 1) == 8) $phone = '+7' . substr($phone, 1);
		else $phone = '+' . $phone;
		$blank = "<h4 class=\"contentTitle\">Оплата LiqPay</h4>";
		$phone = '<input type="text" name="phone" value="' . $phone . '">';
		$blank .= "[SETCURRENCY:UKH]<form action=\"\" method=\"post\"><p>На указанный счет LiqPay <b>{$phone}</b> будет сделан платеж на общую сумму <b>[ORDER_SUMMA]</b> для оплаты заказа №[ORDER.ID]</p><br>
		<input name=\"send_payee\" class=\"buttonSend\" type=\"submit\" value=\"Перейти к оплате\">
		</form>";
		return $macros->execute($blank);
	}

	public function result()
	{

		error_reporting(E_ALL);
		$xml = $_POST['operation_xml'];

		$signature = $_POST['signature'];
		$xml_decoded = base64_decode($xml);

		$xmlDoc = new SimpleXMLElement($xml_decoded);

		$this->order_id = $xmlDoc->order_id;
		$res = $this->getPaymentLog();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$merc_sig = $macros->execute("[PAYMENT.LIQ_PASSW]");

		$sign = base64_encode(sha1($merc_sig . $xml . $merc_sig, 1));
		$transaction_id = $xmlDoc->transaction_id;
		$status = $xmlDoc->status;
		if ($transaction_id && $status == 'success' && $sign == $signature) {
			$this->activate($this->order_id, false, '', $transaction_id, 'LiqPay', $this->name_payment, $transaction_id);
			echo 'YES';
		} elseif ($transaction_id && $status == 'wait_secure' && $sign == $signature) {
			// Ожидает проверку
			$this->setPaymentLog($res['summ'], $xmlDoc->default_phone, date('Y-m-d H:i:s'), '', 'wait');
			echo 'WAIT';
		} else
			echo 'NO';
	}

	public function success()
	{
		$xml_decoded = base64_decode($_POST['operation_xml']);
		$xmlDoc = new SimpleXMLElement($xml_decoded);
		$this->success = str_replace('[PAY.PAY_NUM]', $xmlDoc->merchant_id, $this->success);
		$this->success = str_replace('[PAY.TRANS_NUM]', $xmlDoc->transaction_id, $this->success);
		$this->success = str_replace('[PAY.TRANS_DATE]', date('Y-m-d H:i:s'), $this->success);


		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->success = $macros->execute($this->success);
		return $this->success . '<br>Внимание! Если Вы первый раз оплачиваете картой в системе Liqpay, активация заказа может быть отложена до проверки платежа!<br><br>';
	}

	public function fail()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->fail);
	}
}
