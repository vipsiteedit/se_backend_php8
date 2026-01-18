<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_zpayment extends basePayment
{

	public function setVars()
	{
		return array('zpay' => 'Номер счета z-payment', 'zpsecret_key' => 'Секретный ключ');
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
		$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/wm/test.php" :  "https://z-payment.com/merchant.php";
		$blank = '
		[SETCURRENCY:RUR] <h3 class="contentTitle">Оплата заказа:&nbsp;Z-PAYMENT</h3>
		<p>Номер заказа: <b>[ORDER.ID]</b></p>
		<p>Сумма платежа: <b>[ORDER_SUMMA]</b></p> 
		<form method="post" action="' . $url . '">';
		if (!$macros->execute('[USER.USEREMAIL]')) {
			$blank .= '		<label name="CLIENT_MAIL">Ваш e-mail:</label><input name="CLIENT_MAIL" maxLength="120" size="25">';
		} else {
			$blank .= '		<input name="CLIENT_MAIL" type="hidden" value="' . $macros->execute('[USER.USEREMAIL]') . '">';
		}
		$blank .= '
		<input name="LMI_PAYEE_PURSE" value="[PAYMENT.ZPAY]" type="hidden">
		<input name="LMI_PAYMENT_AMOUNT" value="[ORDER.SUMMA]" type="hidden">
		<input name="LMI_PAYMENT_NO" value="[ORDER.ID]" type="hidden">
		<input name="LMI_PAYMENT_DESC" value="Contract:[USER.ID]/ Order:[ORDER.ID]" type="hidden">
		<input name="LMI_RESULT_URL" value="[MERCHANT_RESULT]" type="hidden">
		<input name="LMI_SUCCESS_URL" value="[MERCHANT_SUCCESS]" type="hidden">
		<input name="LMI_FAIL_URL" value="[MERCHANT_FAIL]" type="hidden">
		<input name="LMI_FAIL_METHOD" value="1" type="hidden">
		<input name="PAYMENT_ID" value="[PAYMENT.ID]" type="hidden">
		<input name="ORDER_ID" value="[ORDER.ID]" type="hidden">
		<input name="PAYMENT_CURR" value="[PAYMENT.CURR]" type="hidden">
		<input class="buttonSend  inpayee" value="Перейти к оплате" type="submit">
		</form>';
		$this->newPaymentLog();
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{
		if (empty($_POST['LMI_HASH']) && $_POST['LMI_PREREQUEST'] == 1) {
			exit("YES");
		}
		$this->order_id = intval($_POST['LMI_PAYMENT_NO']);
		$res = $this->getPaymentLog();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$hash = md5($macros->execute("[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][PAYMENT.ZPSECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]"));
		if (strtoupper($hash) == strtoupper($_POST['LMI_HASH'])) {
			if ($this->activate($this->order_id, false, '', $_POST['LMI_SYS_INVS_NO'], $_POST['LMI_PAYER_PURSE'], $this->name_payment)) {
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
		if ($_POST['LMI_PAYMENT_NO'] == $this->order_id) {
			$this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($_POST['LMI_SYS_INVS_NO'], $_POST['LMI_SYS_TRANS_NO'], $_POST['LMI_SYS_TRANS_DATE']), $this->success);
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
