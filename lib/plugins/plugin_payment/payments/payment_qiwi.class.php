<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
include dirname(__FILE__) . "/qiwi/IShopServerWSService.php";
/**
 * @author Dmitry Ponomarev
 * @copyright 2013
 * 
 * Плагин платежных систем
 */

define('TRACE', 1);

class Response
{
	public $updateBillResult;
}

class Param
{
	public $login;
	public $password;
	public $txn;
	public $status;
}

class TestServer
{
	public function updateBill($param)
	{
		global $qw_status, $qw_ident, $qw_login, $qw_password;
		// Выводим все принятые параметры в качестве примера и для отладки
		$qw_status = ($param->status == 60);
		$qw_ident = $param->txn;
		$qw_login = $param->login;
		$qw_password = $param->password;

		$f = fopen(getcwd() . '/phpdump.txt', 'w');
		fwrite($f, $param->login);
		fwrite($f, ', ');
		fwrite($f, $param->password);
		fwrite($f, ', ');
		fwrite($f, $param->txn);
		fwrite($f, ', ');
		fwrite($f, $param->status);
		fclose($f);

		// проверить password, login

		// В зависимости от статуса счета $param->status меняем статус заказа в магазине
		if ($param->status == 60) {
			// заказ оплачен
			// найти заказ по номеру счета ($param->txn), пометить как оплаченный
		} else if ($param->status > 100) {
			// заказ не оплачен (отменен пользователем, недостаточно средств на балансе и т.п.)
			// найти заказ по номеру счета ($param->txn), пометить как неоплаченный
		} else if ($param->status >= 50 && $param->status < 60) {
			// счет в процессе проведения
		} else {
			// неизвестный статус заказа
		}

		// формируем ответ на уведомление
		// если все операции по обновлению статуса заказа в магазине прошли успешно, отвечаем кодом 0
		// $temp->updateBillResult = 0
		// если произошли временные ошибки (например, недоступность БД), отвечаем ненулевым кодом
		// в этом случае QIWI Кошелёк будет периодически посылать повторные уведомления пока не получит код 0
		// или не пройдет 24 часа
		$temp = new Response();
		$temp->updateBillResult = 0;
		return $temp;
	}
}


class payment_qiwi extends basePayment
{

	private $service = null;
	private $soclient = null;
	private $login = '';
	private $password = '';

	private function init()
	{
		$this->service = new IShopServerWSService(dirname(__FILE__) . '/qiwi/IShopServerWS.wsdl', array('location' => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->login = $macros->execute('[PAYMENT.QW_LOGIN]');
		$this->password = $macros->execute('[PAYMENT.QW_PASSW]');
	}

	/**
	 * @param $phone (string) - номер телефона (QIWI Кошелька), на который будет выставляться счет
	 * @param $amount (string) - сумма к оплате (в формете "рубли"."копейки")
	 * @param $txn_id (string) - номер счета (уникальная в пределах магазина)
	 * @param $comment (string) - комментарий
	 * @param $lifetime (string) - срок действия счета (в формате dd.mm.yyyy HH:MM:SS)
	 * @param $alarm (int) - уведомление
	 * @param $create (bool) - выставлять незарегистрированному пользователю
	 *
	 **/
	private function createBillClient($phone, $amount, $txn_id, $comment, $lifetime = '', $alarm = 0, $create = true)
	{
		$params = new createBill();
		$params->login = $this->login; // логин
		$params->password = $this->password; // пароль
		$params->user = $phone; // пользователь, которому выставляется счет
		$params->amount = '' . $amount; // сумма
		$params->comment = $comment; // комментарий
		$params->txn = $txn_id; // номер заказа
		$params->lifetime = $lifetime; // время жизни (если пусто, используется по умолчанию 30 дней)

		// уведомлять пользователя о выставленном счете (0 - нет, 1 - послать СМС, 2 - сделать звонок)
		// уведомления платные для магазина, доступны только магазинам, зарегистрированным по схеме "Именной кошелёк"
		$params->alarm = $alarm;

		// выставлять счет незарегистрированному пользователю
		// false - возвращать ошибку в случае, если пользователь не зарегистрирован
		// true - выставлять счет всегда
		$params->create = $create;

		$res = $this->service->createBill($params);

		$rc = $res->createBillResult;
		return $rc;
	}

	/**
	 * @param $txn_id - номер отменяемого счета
	 *
	 */
	private function cancelBillClient($txn_id)
	{

		// Формирует объект-запрос
		$params = new cancelBill();
		$params->login = LOGIN;
		$params->password = PASSWORD;
		$params->txn = $txn_id;

		// вызываем метод сервиса с параметрами
		$res = $this->service->cancelBill($params);

		// выводим результат
		print($res->cancelBillResult);

		// для отладки (вывод тела запроса)
		// print($service->__getLastRequest());
	}

	public function setVars()
	{
		return array('qw_login' => 'shop_login', 'qw_passw' => 'password');
	}

	public function startform($payment_id, $text)
	{
		$macros = new plugin_macros(0, $this->order_id, $payment_id);
		return $macros->execute($text);
	}

	public function blank($pagename)
	{
		$this->init();
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$blank = "<h4 class=\"contentTitle\">Оплата QIWI</h4>";
		$order_summa = floatval($macros->execute('[ORDER.SUMMA]'));
		$count_from_sum = intval(ceil($order_summa / 15000));
		$phone = trim($macros->execute('[USER.PHONE]'));
		$phone = preg_replace('/[^\d]*/iu', '', $phone);
		if (strlen($phone) > 10) {
			$phone = substr($phone, 1, 10);
		}
		if (isset($_POST['send_payee'])) {
			if (isset($_POST['phone'])) {
				$phone = preg_replace('/[^\d]*/iu', '', $_POST['phone']);
				if (strlen($phone) > 10) {
					$phone = substr($phone, 1, 10);
				}
				if (strlen($phone) < 10) {
					return $blank . "<p>Платеж не принят платежной системой! (Ошибка в номере или неверный номер мобильного телефона)</p>";
				}
				$person = new sePerson();
				$person->update('phone', "'{$phone}'");
				$person->where('id=?', $this->user_id);
				$person->save();
			}
			for ($i = 1; $i <= $count_from_sum; $i++) {
				if ($i == $count_from_sum) {
					$summa = $order_summa - 15000 * ($i - 1);
				} else {
					$summa = 15000;
				}
				$txn_id = $macros->execute('[ORDER.ID]00' . $i . '0' . $count_from_sum);
				$comment = $macros->execute('Заказ №[ORDER.ID] платеж ' . $i . ' из ' . $count_from_sum);
				$rc = $this->createBillClient($phone, $summa, $txn_id, $comment);
				// проверить код $rc, выдать ошибку/рекомендацию пользователю в зависимости от кода
				// вывод для отладки:
				if ($rc == 0) {
					$this->newPaymentLog($txn_id);
				} else {
					return $blank . "<p>Платеж не принят платежной системой</p>";
				}
			}
			if ($rc == 0) {
				header('Location: https://w.qiwi.ru/orders.action');
				exit;
			}
		}
		$txt_platezh = ($count_from_sum > 1) ? 'выставлено <b>' . $count_from_sum . '</b> платежа' : 'выставлен платеж';
		//		$phone = (strlen($phone) < 10) ? '<input type="text" name="phone" value="'.$phone.'">' : $phone;
		$phone = '<input type="text" name="phone" value="' . $phone . '">';
		$blank .= "<form action=\"\" method=\"post\"><p>На указанный счет QIWI <b>{$phone}</b> {$txt_platezh} на общую сумму <b>[ORDER_SUMMA]</b> для оплаты заказа №[ORDER.ID]</p><br>
		<input name=\"send_payee\" class=\"buttonSend\" type=\"submit\" value=\"Перейти к оплате\">
		</form>
		";
		return $macros->execute($this->getPathPayment($blank, $pagename));
	}

	public function result()
	{
		global $qw_status, $qw_ident, $qw_login, $qw_password;
		$this->init();
		$soclient = new SoapServer(dirname(__FILE__) . '/qiwi/IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'Param', 'tns:updateBillResponse' => 'Response')));
		$soclient->setClass('TestServer');
		$soclient->handle();
		$hash = strtoupper(md5($qw_ident . strtoupper(md5($this->password))));
		if ($qw_status && $qw_ident && $qw_password == $hash) {
			$res = $this->getPaymentLog($qw_ident);
			$this->activate($res['order_id'], $res['summ'], $res['curr'], $qw_ident, 'qiwi', $this->name_payment, $qw_ident);
		}
	}

	public function success()
	{
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
