<?php
require_once dirname(__FILE__) . "/payments/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */
// error_reporting(E_ALL);
class plugin_payment
{
	public $pay;
	private $order_id = 0;

	public function __construct($order_id = 0, $payment_id = 0, $is_test = 0)
	{
		if (isset($_GET['payment'])) {
			//$_POST['label'] = 7;
			$payment = $this->getPaymentIdent(htmlspecialchars($_GET['payment']));
			$payment_id = $payment['id'];
		} else {
			if (!$order_id)
				$order_id = $_SESSION['payment']['order_id'];
			$this->order_id = $order_id;
			if (!$payment_id)
				$payment_id = $_SESSION['payment']['payment_id'];
			if (!$payment_id) return;
			$payment = $this->getPayment($payment_id);
		}

		$result = null;
		if (trim($payment['ident']) != '') {
			if (file_exists(dirname(__FILE__) . '/payments/payment_' . $payment['ident'] . '.class.php')) {
				require_once(dirname(__FILE__) . '/payments/payment_' . $payment['ident'] . '.class.php');
				eval('$result = new payment_' . $payment['ident'] . '($order_id, $payment_id);');
			}
		}

		if (empty($result)) {
			if (file_exists(dirname(__FILE__) . '/payments/basePayment.class.php')) {
				require_once(dirname(__FILE__) . '/payments/basePayment.class.php');
				$result = new basePayment($order_id, $payment_id);
			}
		}

		if (!empty($result)) {
			$result->setParams($payment);
			$vars = $result->setvars();
			$table = new setable('bank_accounts');
			foreach ($vars as $name => $title) {  // Добавляем переменные в базу, если их нет
				$table->select('id');
				$table->where('id_payment=?', $payment_id);
				$table->andwhere("codename='?'", $name);
				$table->fetchOne();
				if (!$table->isFind()) {
					$table->id_payment = $payment_id;
					$table->codename = $name;
					$table->title = $title;
					$table->save();
				}
			}
		}
		$this->pay = $result;
		$this->user_id = $this->pay->user_id;
	}

	public function isAuthorize()
	{
		return $this->pay->authorize == 'Y';
	}

	public function startform()
	{
		if (!$this->payment_id) return;
		return $this->pay->startform();
	}

	public function blank($pagename = '')
	{
		if (!$this->payment_id) return;

		if (!$pagename) {
			$pagename = getRequest('page');
		}
		$_SESSION['payment'] = array('payment_id' => $this->payment_id, 'order_id' => $this->order_id);
		return $this->pay->blank($pagename);
	}

	public function result()
	{
		if (!$this->payment_id) return;
		return $this->pay->result();
	}

	public function success()
	{
		if (!$this->payment_id) return;
		return $this->pay->success();
	}

	public function fail()
	{
		if (!$this->payment_id) return;
		return $this->pay->fail();
	}


	private function getPayment($payment_id)
	{
		$this->payment_id = $payment_id;
		$payment = new seTable('shop_payment', 'sp');
		$payment->select('name_payment, startform, blank, result, success, fail, type, ident, lang, is_test, authorize');
		return $payment->find($payment_id);
	}

	private function getPaymentIdent($ident)
	{
		$payment = new seTable('shop_payment');
		$payment->select('id, name_payment, startform, blank, result, success, fail, type, ident, lang, is_test, authorize');
		$payment->where("ident='?'", $ident);
		$result =  $payment->fetchOne();
		$this->payment_id = intval($result['id']);
		return $result;
	}

	private function initpayment($payname)
	{
		if (empty($payname)) return false;
		if (file_exists(dirname(__FILE__) . '/payments/payment_' . $payname . '.class.php')) {
			require_once(dirname(__FILE__) . '/payments/payment_' . $payname . '.class.php');
			eval('$payment = new payment_' . $payname . '($this->order_id);');
			return $payment;
		}
	}

	public function isAllowedOrder($text)
	{
		if (!$this->order_id) return false;
		$article_list = explode("\r\n", trim($text));
		$is_allowed = true;
		if (!empty($this->articles)) {
			foreach ($this->articles as $article) {
				if (!in_array(trim($article['article']), $article_list, false)) {
					$is_allowed = false;
					break;
				}
			}
		}
		return $is_allowed;
	}

	public function fromAccount($user_id = 0, $comment = 'Оплата с лицевого счета')
	{
		$order = new seShopOrder();
		$order->select('id_author');
		$order->find($this->order_id);

		$this->ordersumm = $order->getSumm($this->order_id);
		//echo seUserId().'=='.$order->id_author.'# ';
		if (!$user_id && seUserId() != $order->id_author) return false;
		$user_id = ($user_id) ? $user_id : seUserId();
		$acc = new seUserAccount();
		$this->usersumm = $acc->getSumm($user_id);

		if ($this->status == 'Y') return -1;
		if ($this->ordersumm == 0) return -3;
		$this->detailsumm = ($this->ordersumm - $this->usersumm);
		if ($this->detailsumm < 0) $this->detailsumm = 0;
		if ($this->usersumm < $this->ordersumm) return -2;
		$payee = new plugin_payment_payee($this->order_id, 0,  $basecurr, $comment, $user_id);
		//$payment->name_payment . ' ' . $res_[3], $res_[2]);
		return $payee->execute();
	}

	public function getList($delivery_id = 0, $after_payment = false, $fullshow = false)       // постоплата
	{
		if ($fullshow) {
			$select = ', sp.type, sp.authorize';
		} else {
			$select = '';
		}
		$lang = se_getLang();
		$payment = new seTable('shop_payment', 'sp'); //seShopPayment();
		$payment->select('sp.id, sp.logoimg, sp.startform, sp.name_payment, sp.hosts, sp.filters, sp.way_payment, is_test' . $select);
		$payment->where("sp.lang = '?'", $lang);
		$payment->andWhere("sp.active = 'Y'");
		if ($delivery_id) {
			$payment->innerjoin('shop_delivery_payment sdp', 'sdp.id_payment=sp.id');
			$payment->andWhere("sdp.id_delivery=?", $delivery_id);
		} else {
			if ($after_payment) {
				$payment->andWhere("sp.way_payment='?'", 'a');
			} else {
				$payment->andWhere("sp.way_payment='?'", 'b');
			}
		}
		$payment->orderby("sort", 0);
		//        print_r($payment->getSql());
		$paylist = $payment->getlist();
		//        print_r(mysql_error());
		//unset($payment);
		$list = array();
		$nn = 0;
		$is_admin = (seUserGroup() == 3);
		foreach ($paylist as $v) {
			if (!empty($v['filters'])) {
				if (!$this->isAllowedOrder($v['filters'])) {
					continue;
				}
			}
			if (!$is_admin && $v['is_test'] == 'Y') continue;


			if (!empty($v['hosts'])) {
				$host_ok = false;
				$v['hosts'] = explode("\r\n", $v['hosts']);
				foreach ($v['hosts'] as $host) {
					if ($host == $_SERVER['HTTP_HOST'] || 'www.' . $host == $_SERVER['HTTP_HOST']) {
						$host_ok = true;
					}
				}
				if (!$host_ok) {
					continue;
				}
			}
			$v['nn'] = ++$nn;

			$v['title_img'] = (!empty($v['name_payment'])) ? htmlspecialchars($v['name_payment'], ENT_QUOTES) : '';
			if (!empty($v['startform'])) {
				$macro = new plugin_macros(0, $this->order_id, $v['id']);
				$v['note'] = $macro->execute(str_replace('\r\n', '<br>', $v['startform']));
				unset($macro);
			}

			$v['order_payee'] = $this->order_id;
			$list[] = $v;
		}
		return $list;
	}

	public function blankType()
	{
		return $this->pay->blankType();
	}
}
