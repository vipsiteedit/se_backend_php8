<?php

require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2017
 * 
 * Плагин платежных систем ПромСвязьБанк Акваринг
 */

class payment_psbank extends basePayment
{

	public function setVars()
	{
		return array('psbterminal' => 'TERMINAL', 'psbname' => 'НАЗВАНИЕ МАГАЗИНА(22 симв.)', 'psbmerchant' => 'MERCHANT', 'psbkey' => 'KEY');
	}

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$timestamp = date('YdmHis');
		$nonce = strtoupper(md5($_SERVER['HTTP_HOST']));

		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$mstr = $macros->execute(
			'[ORDER.SUMMA]RUB[ORDER.ID][PAYMENT.PSBNAME][PAYMENT.PSBTERMINAL][USER.USEREMAIL]1' . $timestamp
				. $nonce . '[MERCHANT_SUCCESS]'
		);
		$hmac = hash_hmac('sha1', $mstr, pack('H*', $macros->execute('[PAYMENT.PSBKEY]')));

		//AMOUNT,CURRENCY,ORDER,MERCH_NAME,MERCHANT,TERMINAL,EMAIL,TRTYPE,TIMESTAMP,NONCE,BACKREF

		$url = ($this->test) ? "https://test.3ds.payment.ru/cgi-bin/cgi_link" :  "https://3ds.payment.ru/cgi-bin/cgi_link";
		$blank = '[SETCURRENCY:RUR] <h4 class="contentTitle">Оплата заказа [ORDER.ID]</h4>
		Сумма заказа: [ORDER_SUMMA]<br><br> 
		<form method="post" name="pay" action="' . $url . '">
		<input type="hidden" name="AMOUNT" value="[ORDER.SUMMA]">
		<input type="hidden" name="CURRENCY" id="CURRENCY" value="RUB">
		<input type="hidden" name="ORDER" id="ORDER" value="[ORDER.ID]">
		<input type="hidden" name="DESC" id="DESC" value="Order No: [ORDER.ID]">
		<input type="hidden" name="TERMINAL" value="[PAYMENT.PSBTERMINAL]">
		<input type="hidden" name="TRTYPE" id="TRTYPE" value="1">

		<input type="hidden" name="MERCH_NAME" value="[PAYMENT.PSBNAME]">
		<input type="hidden" name="MERCHANT" value="[PAYMENT.PSBMERCHANT]">
		<input type="hidden" name="EMAIL" value="[USER.USEREMAIL]">
		<input type="hidden" name="BACKREF" value="[MERCHANT_SUCCESS]">
		<input type="hidden" name="TIMESTAMP" value="' . $timestamp . '">
		<input type="hidden" name="NONCE" value="' . $nonce . '">
		<input type="hidden" name="P_SIGN" value="' . $hmac . '">
        <input class="buttonSend inpayee" name="button" value="Перейти к оплате" type="submit">
        </form>';
		$this->newPaymentLog();
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{

		$input = $_POST;

		//AMOUNT, CURRENCY, ORDER, DESC, TERMINAL, TRTYPE, MERCH_NAME, MERCHANT, EMAIL, TIMESTAMP, NONCE, BACKREF,  RESULT, RC, RCTEXT, AUTHCODE, RRN, INT_REF, P_SIGN, NAME, CARD

		$this->order_id = intval($input['ORDER']);
		$res = $this->getPaymentLog();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$mstr = $input['AMOUNT'] . $input['CURRENCY'] . $input['ORDER'] . $input['MERCH_NAME'] . $input['MERCHANT'] .
			$input['TERMINAL'] . $input['EMAIL'] . $input['TRTYPE'] . $input['TIMESTAMP'] . $input['NONCE'] . $input['BACKREF'] .
			$input['RESULT'] . $input['RC'] . $input['RCTEXT'] . $input['AUTHCODE'] . $input['RRN'] . $input['INT_REF'];

		$hmac = hash_hmac('sha1', $mstr, pack('H*', $macros->execute('[PAYMENT.PSBKEY]')));
		if (strtoupper($input['P_SIGN']) == strtoupper($hmac) && $input['TRTYPE'] == 1 && $input['RESULT'] == 0) {
			if ($this->activate(
				$this->order_id,
				$input['AMOUNT'],
				'',
				$_POST['ORDER'],
				$_POST['EMAIL'],
				$this->name_payment . ' ' . $input['CARD'] . ' ' . $input['NAME']
			) > 0) {
				echo 'YES';
			} else {
				echo 'NO';
			}
			exit;
		} else {
			$fp = fopen("psb.err", "w+");
			fwrite($fp, print_r($_POST, true));
			fclose($fp);
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
<tr><td>Номер счета:</td><td>[PAY.PAY_NUM]</td></tr>
<tr><td>Номер платежа:</td><td>[PAY.TRANS_NUM]</td></tr>
<tr><td>Дата и время платежа:</td><td>[PAY.TRANS_DATE]</td></tr>
</tbody></table>';
		}
		$this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($_POST['orderId'], $_POST['userEmail'], $_POST['paymentData']), $this->success);
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
