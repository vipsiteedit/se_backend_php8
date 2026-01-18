<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Базовый модуль платежных систем
 */
if (!class_exists('CurlAbstract')) {
	require_once dirname(__FILE__) . '/curlAbstract.class.php';
}

class basePayment extends CurlAbstract
{
	protected $lang = 'rus';
	protected $order_id = 0;
	public $user_id = 0;
	protected $email = '';
	protected $type = '';
	protected $ordersumm = 0;
	protected $ordercurr = 'RUR';
	protected $status = 'N';
	protected $articles = '';
	protected $payment = null;
	protected $basecurr = '';
	protected $payment_id = 0;
	protected $startform = '';
	protected $blank = '';
	protected $result = '';
	protected $success = '';
	protected $fail = '';
	protected $ident = '';
	public $name_payment = '';
	protected $test = 0;
	public $authorize = 'N';
	private $debug = false;
	private $usersumm;


	public function setVars()
	{
		return array();
	}

	public function __construct($order_id = 0, $payment_id = 0)
	{
		$this->payment_id = $payment_id;
		$this->order_id = $order_id;
		$this->lang = se_getLang();
		$this->basecurr = $this->getBaseCurr($this->lang);
		if (!empty($order_id)) {
			$order = new seShopOrder();
			$order->select("so.discount, so.delivery_payee, so.curr, p.id AS user_id, p.email, so.status, (SELECT GROUP_CONCAT(`article`) FROM shop_tovarorder WHERE id_order=so.id) AS articles");
			$order->innerjoin('person p', 'so.id_author=p.id');
			$order->where('so.id=?', $this->order_id);
			$order->fetchOne();
			$this->articles = explode(',', $order->articles);
			$this->email = $order->email;
			$this->user_id = $order->user_id;
			$this->status = $order->status;
			$this->ordersumm = $order->getSumm($order_id);
			$this->ordercurr = $order->curr;
		}
		if (!$this->user_id && function_exists('seUserId')) {
			$this->user_id = seUserId();
		}
		if ($this->user_id) {
			$account = new seUserAccount();
			$this->usersumm = $account->getSumm($this->user_id);
		}
	}

	public function setParams($payment)
	{
		$this->type = $payment['type'];
		$this->startform = $payment['startform'];
		$this->blank = $payment['blank'];
		$this->result = $payment['result'];
		$this->success = $payment['success'];
		if (empty($this->success)) {
			$this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4>
<div>Ваш заказ № [ORDER.ID] оплачен <table class="tableTable" border="0">
<tbody class="tableBody">
<tr><td>Номер счета [PAYMENT.NAME]:</td><td>[PAY.PAY_NUM]</td></tr>
<tr><td>Номер платежа:</td><td>[PAY.TRANS_NUM]</td></tr>
<tr><td>Дата и время платежа:</td><td>[PAY.TRANS_DATE]</td></tr>
</tbody></table></div><br><br>';
		}

		$this->fail = $payment['fail'];
		if (empty($this->fail)) {
			$this->fail = '<h4 class="contentTitle">Ошибка платежа [PAYMENT.NAME]</h4>
<div>Заказ № [ORDER.ID] не оплачен.</div><br><br>';
		}

		$this->authorize = $payment['authorize'];
		$this->ident = $payment['ident'];
		$this->lang = $payment['lang'];
		$this->name_payment = $payment['name_payment'];
		$this->test = ($payment['is_test'] == 'Y');
		$this->basecurr = $this->getBaseCurr($this->lang);
	}

	public function getBaseCurr($lang)
	{
		$main = new seTable('main');
		$main->select('basecurr');
		$main->where("lang='?'", $lang);
		$main->fetchOne();
		return $main->basecurr;
	}

	public function startform()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->startform);
	}

	public function blank($pagename)
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->newPaymentLog();
		return $macros->execute($this->getPathPayment($this->blank, $pagename));
	}

	public function result()
	{
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$result = $macros->execute($this->result);
		$payment_curr = ($_SESSION['THISCURR']) ? $_SESSION['THISCURR'] : $this->$this->basecurr;

		if ($this->debug) {
			$fp = fopen('system/merchant.log', "w+");
			fwrite($fp, $result);
		}

		if (preg_match("/SE_PAYEXECUTE\(([\w\W]{1,})\)/um", $result, $m)) {
			list($pay_status, $pay_amount, $pay_num, $pay_purse) = explode(",", $m[1]);
			$result = str_replace($m[0], '', $result);


			if (($pay_status == '1') && ($pay_amount > 0) && ($pay_num > 0)) {
				// Активация заказа
				$status = $this->activate($this->order_id, $pay_amount, $payment_curr, $pay_num, $pay_purse);
				if ($this->debug) {
					fwrite($fp, 'Order:' . $this->order_id . ', Payment:' . $this->payment_id . ', Summ:' . round(se_MoneyConvert($pay_amount, $payment_curr, $this->basecurr), 0) . ', Name:' .
						$this->name_payment . ' ' . $pay_purse . ', ' . $pay_num . "\r\n");
				}

				if ($status > 0) {
					if ($this->debug) {
						fwrite($fp, 'OK');
					}
					if (preg_match("/\<RESULT\>(.*)\<\/RESULT\>/um", $this->payment->result, $mres)) {
						echo $result = $mres[1];
					}
				} else {
					if (preg_match("/\<ERROR\>(.*)\<\/ERROR\>/um", $this->payment->result, $mres)) {
						echo $result; // = $mres[1];
					}
				}
			} else
			if (preg_match("/\<ERROR\>(.*)\<\/ERROR\>/um", $this->payment->result, $mres)) {
				echo $result; // = $mres[1];
			}
		}
		if ($this->debug) {
			fclose($fp);
		}
		exit;
	}

	public function success()
	{
		if (!$this->payment_id) return;
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->payment->success);
	}

	public function fail()
	{
		if (!$this->payment_id) return;
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->payment->fail);
	}


	public function getUserId()
	{
		return $this->user_id;
	}

	public function getEmail()
	{
		return $this->email;
	}


	public function blankType()
	{
		return $this->type;
	}

	protected function getPathPayment($str, $pagename)
	{
		$multidir = (function_exists('seMultiDir')) ? seMultiDir() : '';
		if ($this->ident) {
			$str = str_replace('[MERCHANT_RESULT]', _HTTP_ . $_SERVER['HTTP_HOST'] . '/lib/merchant/result.php?payment=' . $this->ident, $str);
		} else {
			$str = str_replace('[MERCHANT_RESULT]', _HTTP_ . $_SERVER['HTTP_HOST'] . '/' . $multidir . $pagename . '/merchant/success/', $str);
		}
		$str = str_replace('[MERCHANT_SUCCESS]', _HTTP_ . $_SERVER['HTTP_HOST'] . '/' . $multidir . $pagename . '/merchant/success/', $str);
		$str = str_replace('[MERCHANT_FAIL]', _HTTP_ . $_SERVER['HTTP_HOST'] . '/' . $multidir . $pagename . '/merchant/fail/', $str);
		return $str;
	}

	public function sendMail($template, $arr = array(), $filename = '')
	{
	}

	protected function activate($order_id, $amount = false, $curr = '', $pay_num = 0, $pay_purse = '', $name_payment = '', $ident = '')
	{
		$curr = ($curr) ? $curr : $this->basecurr;
		$payee = new plugin_payment_payee($order_id, $this->payment_id,  $this->basecurr, trim($name_payment . ' ' . $pay_purse));
		if (!$amount) {
			$order = new seShopOrder();
			$order->select('discount, delivery_payee, curr');
			$order->find($order_id);
			$curr = $order->curr;
			$amount = $order->getSumm($order_id);
		}
		$this->setPaymentLog(se_MoneyConvert($amount, $curr, $this->basecurr), $pay_purse, date('Y-m-d H:i:s'), $ident);
		$payee->inPayment(round(se_MoneyConvert($amount, $curr, $this->basecurr), 4),  trim($name_payment) . ' ' . $pay_purse, $pay_num);
		return $payee->execute();
	}

	public function newPaymentLog($ident = '')
	{
		//if ($this->type == 'p') return;
		if (!file_exists('system/logs/paymentlog.upd')) {
			se_db_query("DROP TABLE IF EXISTS `shop_payment_merchant`");
			se_db_query("CREATE TABLE IF NOT EXISTS `shop_payment_merchant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ident` varchar(40) default NULL,
  `deflang` char(3) NOT NULL DEFAULT 'rus',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `summ` double(10,2) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `curr` char(3) NOT NULL DEFAULT 'RUR',
  `payer` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `date_payee` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
			if (!is_dir('system/logs')) mkdir('system/logs');
			$fp = fopen('system/logs/paymentlog.upd', "w+");
		}
		$tab = new seTable('shop_payment_merchant');
		$tab->addField('ident', 'string');
		if ($ident) {
			$tab->where("ident='?'", $ident);
		} else {
			$tab->where('payment_id=?', $this->payment_id);
			$tab->andwhere('order_id=?', $this->order_id);
		}
		$tab->fetchOne();
		$tab->ident = $ident;
		$tab->deflang = se_getlang();
		$tab->payment_id = $this->payment_id;
		$tab->order_id = $this->order_id;
		$tab->summ = str_replace(',', '.', $this->ordersumm);
		$tab->curr = $this->ordercurr;
		$tab->host = $_SERVER['HTTP_HOST'];
		$tab->save();
		echo se_db_error();
	}

	public function getPaymentLog($ident = '')
	{
		//if ($this->type == 'p') return;
		$tab = new seTable('shop_payment_merchant');
		if ($ident) {
			$tab->where("ident='?'", $ident);
		} else {
			$tab->where('payment_id=?', $this->payment_id);
			$tab->andwhere('order_id=?', $this->order_id);
		}
		$tab->fetchOne();
		if (!defined('DEFAULT_LANG')) {
			define('DEFAULT_LANG', $tab->deflang);
		}
		return array('order_id' => $tab->order_id, 'payment_id' => $tab->payment_id, 'lang' => $tab->deflang, 'summ' => $tab->summ, 'curr' => $tab->curr);
	}

	public function setPaymentLog($amount, $payer, $date, $ident = '', $status = 'pay')
	{
		//if ($this->type == 'p') return;
		$tab = new seTable('shop_payment_merchant');
		if ($ident) {
			$tab->where("ident='?'", $ident);
		} else {
			$tab->where('payment_id=?', $this->payment_id);
			$tab->andwhere('order_id=?', $this->order_id);
		}
		$tab->fetchOne();
		if ($tab->isFind()) {
			$tab->amount = str_replace(',', '.', $amount);
			$tab->payer = $payer;
			$tab->date_payee = $date;
			if (!se_db_is_field('shop_payment_merchant', 'status'))
				$tab->addfield('status', "ENUM('pay', 'wait', 'not' ) NOT NULL DEFAULT  'not' AFTER  `date_payee`");
			$tab->status = $status;
			$tab->save();
		}
	}
}
