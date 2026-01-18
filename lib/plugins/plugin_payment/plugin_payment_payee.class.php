<?php
/**
 * @filesource edgePayActivate.class.php
 * @copyright EDGESTILE
 */


class plugin_payment_payee {
  private $order_id;
  private $curr;
  private $message;
  private $order;
  private $bonus;
  private $payment_id = 0;
  private $startingConfig;
  private $user_id;

  

	function __construct($order_id, $payment_id = 0,  $curr = 'RUR', $message = 'Оплата с лицевого счета', $user_id = 0)
	{
 		if ($payment_id > 0 && $message == '') {
		 $payment = new seShopPayment();
    		 $payment->select('name_payment');
    		 $payment->find($payment_id);
		 $message =  $payment->name_payment;
    		}
		$this->curr = $curr;
		$this->message = $message;
		$this->order_id = $order_id;
		$this->payment_id = $payment_id;
		$this->order = new seShopOrder();
		$this->order->find($order_id);
		$this->user_id = ($user_id) ? $user_id : $this->order->id_author;
		//$this->startingConfig = simplexml_load_file(dirname(__FILE__).'/config/activate.xml');
	}
	
	/**
	 * Входной платеж
	 * @param $insumm	double	-	Сумма платежа
	 * @param $message	string	-	Сообщение о платеже
	 * @param $accountname string	Номер документа по которому пришел платеж
	 */
	public function inPayment($insumm = 0, $message = '', $accountname = '')
	{
		if ($this->order->status == 'Y') return;
				
		// Проводим заказ через счет
		if (empty($message)) $message = $this->message;
		$this->setPayee($insumm, 0, $message, 1, 0, $accountname);
		// Нужно отравить письмо о зачислении средств
		//include dirname(__FILE__).'/mail/mail_order_inpayee.php';
	}
/*	public function outPayment($outsumm = 0, $message = '', $accountname = '')
	{
		if ($this->order->status == 'Y') return;
				
		// Проводим заказ через счет
		if (empty($message)) $message = $this->message;
		$this->setPayee(0, $outsumm, 0, $message, 1, 0, $accountname);
		if ($this->order->inpayee == 'Y')
		{
			$this->order->status = 'Y';
			$this->order->save();
		}
		// Нужно отравить письмо о зачислении средств
		//include dirname(__FILE__).'/mail/mail_order_inpayee.php';
	}
*/
	
	public function getOrder()
	{
		return $this->order;
	}

	public function execute()
	{
		$payee = new seUserAccount();
		//$payee->where('user_id=?', $this->user_id)->fetchOne();
		
		// Проверяем суммы заказов
		$summaorder = $this->order->getSumm();

		if (round($payee->getSumm($this->user_id)) < round($summaorder)) return -2;
	
		//$marketing = new edgePremium($this->order->id_author);

		//echo $marketing->getAddition(450000);
	    //$this->order->data['status'];
		if ($this->order->status == 'Y') return -1;
		

		se_db_query("SET AUTOCOMMIT=0");
		se_db_query("START TRANSACTION;");
		// Оплата заказа
		if ($this->order->inpayee != 'Y'){
		    $this->setPayee(0, $summaorder, "Оплата заказа {$this->order_id}", 2);
		}
		// Делаем запись в таблицу платежей
		$tpay = new seTable('shop_order_payee');
		$tpay->id_order = $this->order->id;
		$tpay->id_author = $this->order->id_author;
		$tpay->num = 1;
		$tpay->date = date('Y-m-d H:i:s');
		$tpay->year = date('Y');
		$tpay->payment_type = $this->payment_id;
		$tpay->id_manager = 0;
		$tpay->amount = $summaorder;
		$tpay->note = $this->message;
		$tpay->save();

		$this->order->status = 'Y';
		$this->order->payee_doc = $this->message;
		$this->order->date_payee = date('Y-m-d');
		$this->order->payment_type = $this->payment_id;
		
		$this->order->transact_amount = $summaorder;
		$this->order->transact_curr = $this->curr;
		if ($this->order->save()) {
		    // Записываем в Лог
		    $this->main_log($summaorder);
		    // Нужно отравить письмо о активации заказа
		    //include dirname(__FILE__).'/mail/mail_order_activate.php';
		    if (function_exists('check_session')) check_session(false);
		
		    //error_reporting(125);
		
		    $person = new sePerson();
		    $person->select('email');
		    $person->find($this->user_id);
		    $mail = new plugin_shopmail($this->order->id, $this->payment_id, 'html');

		    if ($person->email != ''){
			$mail->sendmail('payuser', $person->email);
		    }
		    $mail->sendmail('payadm');
		    unset($mail);
		    $this->logAction();
		    if (file_exists(getcwd().'/lib/plugins/plugin_shop/plugin_shopaction.class.php')){
    			$act = new plugin_shopaction();
    		    }
    		    se_db_query("COMMIT;");
    		    se_db_query("SET AUTOCOMMIT=1");
    		    return true;
    		}
    		se_db_query("ROLLBACK;");
    		se_db_query("SET AUTOCOMMIT=1");
    		return false;
	}
	
	// Запись в таблицу shop_payee "движение платежей"
	private function setPayee($in_payee = 0, $out_payee = 0, $message, $operation = 1, $user_id = 0, $account = '')
	{
		if ($user_id == 0) 
			$user_id = $this->user_id;
		
		$payee = new seUserAccount();
		$payee->user_id = $user_id;
		$payee->id_order = $this->order_id;
		$payee->in_payee = $in_payee;
		$payee->out_payee = $out_payee;
		$payee->date_payee = date('Y-m-d');
		$payee->docum = $message;
		$payee->operation = $operation;
		$payee->account = $account;
		$payee->curr = $this->curr;
		$payee->save();
	}
	
	private function main_log($summaorder)
	{
		$mainlog = new seTable();
		$mainlog->from('main_log', 'ml');
		$mainlog->id_author = $this->user_id;
		//$mainlog->login = seUserName();
        $mainlog->datepayee = date('Y-m-d H:i:s');
        $mainlog->id_order = $this->order_id;
        $mainlog->summa = $summaorder;
        $mainlog->admin = (function_exists('seUserId')) ? seUserId() : $this->user_id;
        $mainlog->ip = $_SERVER['REMOTE_ADDR'];
        $mainlog->save();
	}
	
	private function logAction()
	{
		if (SE_DB_ENABLE) 
		{
			if (se_db_num_rows(se_db_query("SHOW COLUMNS FROM `shop_order_action`")) == 0){
				se_db_query("
				CREATE TABLE IF NOT EXISTS `shop_order_action` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`type` varchar(20) DEFAULT NULL,
				`action` varchar(250) DEFAULT NULL,
				`datestart` datetime DEFAULT NULL,
				`id_order` int(10) unsigned DEFAULT NULL,
				`period` int(11) NOT NULL,
				`active` enum('Y','N','K') DEFAULT 'N',
				`logaction` text,
				`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
				`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (`id`),
				UNIQUE KEY `action_2` (`action`,`id_order`),
				KEY `id_order` (`id_order`),
				KEY `type` (`type`),
				KEY `action` (`action`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
			}
		}
		$mainlog = new seTable('shop_order_action', 'la');
		$mainlog->where('id_order=?', $this->order_id);
		$mainlog->fetchOne();
		$mainlog->type = 'license';
		$mainlog->action = 'pay';
		$mainlog->datestart = date('Y-m-d  H:i:s');
		//$mainlog->id_author = $this->order->id_author;
        $mainlog->id_order = $this->order_id;
        //$mainlog->id_param = 0;
        $mainlog->period = 0;
        $mainlog->active = 'N';
        $mainlog->save();
	}
}
