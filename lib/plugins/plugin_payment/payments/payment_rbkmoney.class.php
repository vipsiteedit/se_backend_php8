<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

class payment_rbkmoney extends basePayment
{

	public function setVars()
	{
		return array('rbkm' => 'Номер RBK money', 'rbkkey' => 'Секретный ключ');
	}

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/rbk/test.php" :  "https://rbkmoney.ru/acceptpurchase.aspx";
		$blank = '[SETCURRENCY:RUR] <h4 class="contentTitle">Оплата заказа [ORDER.ID] через платежную систему RBK Money</h4>
		Сумма заказа: [ORDER_SUMMA]<br><br> 
		<p><form method="post" name="pay" action="' . $url . '">
<label for="inner"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_rbk.png" border=0>&nbsp;<input type="radio" id="inner" value="inner" name="preference">&nbsp;Оплата с кошелька RBK Money</label><br />
<label for="bankCard"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_bank_card.png" border=0>&nbsp;<input type="radio" id="bankCard" value="bankCard" name="preference">&nbsp;Банковская карта Visa/MasterCard</label><br />
<label for="transfers"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_send_money.png" border=0>&nbsp;<input type="radio" id="transfers" value="transfers" name="preference">&nbsp;Системы денежных переводов</label><br />
<label for="terminals"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_terminal.png" border=0>&nbsp;<input type="radio" id="terminals" value="terminals" name="preference">&nbsp;Платёжные терминалы</label><br />
<label for="bank"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_bank.png" border=0>&nbsp;<input type="radio" id="bank" value="sberbank" name="preference">&nbsp;Банковский платёж</label><br />
<label for="postRus"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_post.png" border=0>&nbsp;<input type="radio" id="postRus" value="postRus" name="preference">&nbsp;Почта России</label><br />
<!--label for="atm">small_ico_terminal.png<input type="radio" id="atm" value="atm" name="preference">&nbsp;Банкоматы</label><br /-->
<label for="ibank"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_int_bank.png" border=0>&nbsp;<input type="radio" id="ibank" value="ibank" name="preference">&nbsp;Интернет банкинг</label><br />
<label for="euroset"><img src="/lib/plugins/plugin_payment/payments/rbkmoney/small_ico_euroset.png" border=0>&nbsp;<input type="radio" id="euroset" value="euroset" name="preference">&nbsp;Евросеть</label></p>

		<input name="eshopId" value="[PAYMENT.RBKM]" type="hidden">
<input name="recipientAmount" value="[ORDER.SUMMA]" type="hidden">
<input name="orderId" value="[ORDER.ID]" type="hidden">
<input name="recipientCurrency" value="RUR" type="hidden">
<input name="serviceName" value="Order No: [ORDER.ID]" type="hidden">
<input name="success_url" value="[MERCHANT_SUCCESS]" type="hidden">
<input name="fail_url" value="[MERCHANT_FAIL]" type="hidden">
<input name="user_name" value="[CLIENTNAME]" type="hidden">
<input name="user_email" value="[USER.USEREMAIL]" type="hidden"><br>
<input class="buttonSend inpayee" name="button" value="Перейти к оплате" type="submit">
</form>';
		$this->newPaymentLog();
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{
		//error_reporting(E_ALL);
		$this->order_id = intval($_POST['orderId']);
		$res = $this->getPaymentLog();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$secret_key = (!empty($_POST['secretKey'])) ? strval($_POST['secretKey']) : $macros->execute("[PAYMENT.RBKKEY]");
		$hash = md5($macros->execute("[POST.eshopId]::[POST.orderId]::[POST.serviceName]::[POST.eshopAccount]::[POST.recipientAmount]::[POST.recipientCurrency]::[POST.paymentStatus]::[POST.userName]::[POST.userEmail]::[POST.paymentData]::{$secret_key}"));
		if (strtoupper($hash) == strtoupper($_POST['hash']) && $_POST['paymentStatus'] == 5) {
			if ($this->activate($this->order_id, false, '', $_POST['orderId'], $_POST['userEmail'], $this->name_payment) > 0) {
				echo 'YES';
			} else {
				echo 'NO';
			}
			exit;
		} else {
			$fp = fopen("rbkmoney.err", "w+");
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
