<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */


class payment_dengionline extends basePayment
{

	public function setVars()
	{
		return array('do_project' => 'Код проекта', 'do_source' => 'Идентификатор проекта', 'do_secret_key' => 'Секретный ключ');
	}

	private function sendResponse($status, $message = '')
	{
		$response = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$response .= '<result>' . "\n";
		$response .= '<code>' . $status . '</code>' . "\n";
		$response .= '<comment>' . $message . '</comment>' . "\n";
		$response .= '</result>';
		die($response);
	}

	public function startform($payment_id, $text)
	{
		$macros = new plugin_macros(0, $this->order_id, $payment_id);
		return $macros->execute($text);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);

		//$url = ($this->test) ? "https://api.siteedit.ru:447/merchant/wm/test.php" :  "https://merchant.webmoney.ru/lmi/payment.asp";
		// сделать оплату из разных стран

		$this->newPaymentLog($macros->execute('[PAYMENT.ID]_[USER.ID]_[ORDER.ID]'));
		return $macros->execute('[SETCURRENCY:BYR]
		<form action="http://www.onlinedengi.ru/wmpaycheck.php" method="post">
		<input type="hidden" name="project" value="[PAYMENT.DO_PROJECT]">
		<input type="hidden" name="source" value="[PAYMENT.DO_SOURCE]">
		<input type="hidden" name="order_id" value="[ORDER.ID]">
		<input type="hidden" name="nickname" value="[PAYMENT.ID]_[USER.ID]_[ORDER.ID]">
		Сумма к оплате, в валюте платёжной системы: [ORDER_SUMMA] 

		<input type="hidden" name="amount" value="[ORDER.SUMMA]">
		<input type="hidden" name="paymentCurrency" value="BYR">
		Способ оплаты: <select name="mode_type">
		<option value="16">EasyPay</option>
		<option value="284">ERIP</option>
		<option value="212">iPay (операторы «МТС», «Life:)»)</option>
		<option value="280">Терминалы CredexPayNet</option>
		</select>
		<input type="submit" value="Оплатить!">
		</form>');
	}

	public function result()
	{
		$res = $this->getPaymentLog($_POST['userid']);
		$this->order_id = $res['order_id'];
		$this->payment_id = $res['payment_id'];

		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$secretKey = $macros->execute('[PAYMENT.DO_SECRET_KEY]');
		$projectHash = md5($_POST['amount'] . $_POST['userid'] . $_POST['paymentid'] . $secretKey);

		$fp = fopen("payment.log", "a+");
		fwrite($fp, 'GET: ' . $secretKey . '|' . print_r($_GET, true) . '|' . print_r($_POST, true) . '|' . print_r($res, true) . '|' . $projectHash);
		fclose($fp);

		//$secretKey = 'IT\'S_A_PROJECT_SECRET_WORD';

		if ($projectHash != $_POST['key']) {
			$this->sendResponse('NO', 'Контрольная подпись запроса неверна.');
		}
		if (floatval($_POST['amount']) == 0 && intval($_POST['paymentid']) == 0) {
			//Это запрос на проверку идентификатора пользователя или заказа
			if ($this->order_id) {
				$this->sendResponse('YES', 'Идентификатор существует');
			} else {
				$this->sendResponse('NO', 'Идентификатор не найден');
			}
		}
		if (
			filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT) &&
			filter_var($_POST['paymentid'], FILTER_VALIDATE_INT)
			&& floatval($_POST['amount']) > 0 && intval($_POST['paymentid']) > 0
			&& $this->order_id
		) {
			//Это запрос на проведение оплаты в проект
			//совершаем действия по начислению денежных средств пользователю
			//возвращаем текущий статус оплаты.
			$result = $this->activate($this->order_id, false, '',  intval($_POST['paymentid']), 'dengionline', $this->name_payment);
			if ($result == 1) {
				$this->sendResponse('YES', 'Текущий статус платежа.');
			} elseif (-1 == $result) {
				$this->sendResponse('YES', 'Платеж был успешно выполнен ранее.');
			} else {
				$this->sendResponse('NO', 'Текущий статус платежа.');
			}
		}

		exit;
	}

	public function success()
	{
		if ($xmlDoc->status == 'success') {
			$this->success = str_replace(array('[PAY.PAY_NUM]', '[PAY.TRANS_NUM]', '[PAY.TRANS_DATE]'), array($_POST['LMI_SYS_INVS_NO'], $_POST['LMI_SYS_TRANS_NO'], $_POST['LMI_SYS_TRANS_DATE']), $this->success);
		}
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->success = $macros->execute($this->success);
		return $this->success;
	}

	public function fail()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->fail .= '<br>' . iconv('cp1251', 'utf8', rawurldecode(print_r($_GET['err_msg'][0], true)));

		return $macros->execute($this->fail);
	}
}
