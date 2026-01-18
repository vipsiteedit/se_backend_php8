<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_webmoney_b extends basePayment
{

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/wm/test.php" :  "https://merchant.webmoney.ru/lmi/payment.asp";
		$blank = $this->blank . '
		<form method="post" action="' . $url . '"><input name="LMI_MODE" value="1" type="hidden">
		<input name="LMI_PAYEE_PURSE" value="[PAYMENT.WMB]" type="hidden">
		<input name="LMI_PAYMENT_AMOUNT" value="[ORDER.SUMMA]" type="hidden">
		<input name="LMI_PAYMENT_NO" value="[ORDER.ID]" type="hidden">
		<input name="LMI_PAYMENT_DESC_BASE64" value="BASE64ENCODE(Договор:[USER.ID]/ Заказ:[ORDER.ID])" type="hidden">
		<input name="LMI_SUCCESS_METHOD" value="1" type="hidden">
		<input name="LMI_RESULT_URL" value="[MERCHANT_RESULT]" type="hidden">
		<input name="LMI_SUCCESS_URL" value="[MERCHANT_SUCCESS]" type="hidden">
		<input name="LMI_FAIL_URL" value="[MERCHANT_FAIL]" type="hidden">
		<input name="LMI_FAIL_METHOD" value="1" type="hidden">
		<input name="LMI_SIM_MODE" value="0" type="hidden">
		<input name="PAYMENT_ID" value="[PAYMENT.ID]" type="hidden">
		<input name="ORDER_ID" value="[ORDER.ID]" type="hidden">
		<input name="PAYMENT_CURR" value="BLR" type="hidden">
		<input class="buttonSend  inpayee" value="Перейти к оплате" type="submit">
		</form>';
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{
		if (empty($_POST['LMI_HASH']) && $_POST['LMI_PREREQUEST'] == 1) {
			exit("YES");
		}
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$hash = md5($macros->execute("[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][PAYMENT.SECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]"));
		if ($hash == $_POST['LMI_HASH']) {
			if ($this->activate($this->order_id, $_POST['LMI_PAYMENT_AMOUNT'], $_SESSION['THISCURR'], $_POST['LMI_SYS_INVS_NO'], $_POST['LMI_PAYEE_PURSE'], $this->name_payment)) {
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
		//print_r($_POST);
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
