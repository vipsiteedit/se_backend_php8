<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_webmoney extends basePayment
{

	public function setVars()
	{
		return array('wm_r' => 'Кошелек WMR', 'wm_z' => 'Кошелек WMZ', 'wm_u' => 'Кошелек WMU', 'wm_b' => 'Кошелек WMB', 'SECRET_KEY' => 'Секретный ключ');
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
		$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/wm/test.php" :  "https://merchant.webmoney.ru/lmi/payment.asp";
		$ba = new seTable('bank_accounts');
		$ba->select('codename, title');
		$ba->where('id_payment=?', $this->payment_id);
		$ba->andwhere("codename IN ('wm_r','wm_z','wm_u','wm_b','wm_e')");
		$ba->andwhere("value <> '' AND `value` IS NOT NULL");
		$slist = $ba->getlist(-1);
		$selectlist = '<option selected>&shy;&shy;&shy;&shy;</option>';
		foreach ($slist as $item) {
			if ($item['codename'])
				$selectlist .= '<option value="' . $item['codename'] . '" [SELECTED:' . $item['codename'] . ']>' . $item['title'] . '</option>';
		}
		if (empty($_POST['valuts'])) $_POST['valuts'] = 'wm_r';

		$blank = '<h3 class="contentTitle">Оплата заказа:&nbsp;[ORDER.ID]&nbsp;- WebMoney</h3>
<form method="post" name="frm">Выберите вид валюты для оплаты: <select onchange="document.frm.submit();" name="valuts">' . $selectlist . '</select>
<input name="FP" value="[POST.FP]" type="hidden">
<input name="ORDER_ID" value="[ORDER.ID]" type="hidden">
</form><br>
<br>
Сумма к оплате: [ORDER_SUMMA]<br>
<br>
Для выполения прямого платежа на кошелек продавца нажмите кнопку "Перейти к оплате"<br>';


		$blank .= '[SETCURRENCY:[IF(RUR=wm_r,USD=wm_z,EUR=wm_e,UKH=wm_u,BYR=wm_b:USD)]]
		<form method="post" action="' . $url . '"><input name="LMI_MODE" value="1" type="hidden">
		<input name="LMI_PAYEE_PURSE" value="[PAYMENT.[POST.VALUTS:wm_z]]" type="hidden">
		<input name="LMI_PAYMENT_AMOUNT" value="[ORDER.SUMMA]" type="hidden">
		<input name="LMI_PAYMENT_NO" value="[ORDER.ID]" type="hidden">
		<input name="LMI_PAYMENT_DESC_BASE64" value="BASE64ENCODE(User:[USER.ID]/Order:[ORDER.ID])" type="hidden">
		<input name="LMI_SUCCESS_METHOD" value="1" type="hidden">
		<input name="LMI_RESULT_URL" value="[MERCHANT_RESULT]" type="hidden">
		<input name="LMI_SUCCESS_URL" value="[MERCHANT_SUCCESS]" type="hidden">
		<input name="LMI_FAIL_URL" value="[MERCHANT_FAIL]" type="hidden">
		<input name="LMI_FAIL_METHOD" value="1" type="hidden">
		<input name="LMI_SIM_MODE" value="0" type="hidden">
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
		define('SANDBOX', 0);
		if (SANDBOX == true) {
			$this->logs("I get POST request: <pre>" . print_r($_POST, 1) . "</pre>");
		}
		if (empty($_POST['LMI_HASH']) && $_POST['LMI_PREREQUEST'] == 1) {
			exit("YES");
		}
		$this->order_id = intval($_POST['LMI_PAYMENT_NO']);
		$amount = $_POST['LMI_PAYMENT_AMOUNT'];
		$res = $this->getPaymentLog();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$hash = md5($macros->execute("[POST.LMI_PAYEE_PURSE][POST.LMI_PAYMENT_AMOUNT][POST.LMI_PAYMENT_NO][POST.LMI_MODE][POST.LMI_SYS_INVS_NO][POST.LMI_SYS_TRANS_NO][POST.LMI_SYS_TRANS_DATE][PAYMENT.SECRET_KEY][POST.LMI_PAYER_PURSE][POST.LMI_PAYER_WM]"));
		if (strtoupper($hash) == strtoupper($_POST['LMI_HASH'])) {
			$res['summ'] = round(se_MoneyConvert($res['summ'], 'RUR', $_POST['PAYMENT_CURR']), 2, PHP_ROUND_HALF_UP);
			$max = floatval($res['summ']) * 1.01;
			$min = floatval($res['summ']) * 0.99;
			if (($min <= $amount) && ($max >= $amount)) {
				if ($this->activate($this->order_id, false, '', $_POST['LMI_SYS_INVS_NO'], $_POST['LMI_PAYER_PURSE'] . '_' . $_POST['LMI_PAYMENT_DESC'], $this->name_payment)) {
					echo 'YES';
				} else {
					$this->logs("Some thing happened with activator");
				}
			} else {
				$this->logs("Min summa: " . $min . ", max summa: " . $max . ", summa from DB: " . $res['summ'] . ", summa from post: " . $amount);
			}
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
		$this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($_POST['LMI_SYS_INVS_NO'], $_POST['LMI_SYS_TRANS_NO'], $_POST['LMI_SYS_TRANS_DATE']), $this->success);
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->success = $macros->execute($this->success);
		return $this->success;
	}

	public function fail()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->fail);
	}

	private function logs($text)
	{
		file_put_contents(getcwd() . '/webmoney_log.txt', $text . "\r\n <br>", FILE_APPEND);
	}
}
