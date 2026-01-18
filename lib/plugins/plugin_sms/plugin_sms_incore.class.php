<?php

require_once __DIR__ . '/sms.class.php';

class plugin_sms_incore
{
	private $service_name = 'inCore Dev';
	//INSERT INTO sms_providers(name, url, settings) VALUES('inCore Dev', 'incoredevelopment.com', '{"host":{"type":"string","value":"siteedit5.incore1.ru"},"login":{"type":"string","value":""},"password":{"type":"string","value":""}}');
	
	private $host;
	private $login;
    private $password;
	
	function __construct($host, $login, $password)
	{
		$this->host = $host;
		$this->login = $login;
		$this->password = $password;
		
		if (strpos($this->host, 'https://') === false) {
			$this->host = 'https://' . $this->host;
		}
	}
	
	public function getSettings()
	{
		$t = new seTable('sms_providers');
		$t->select('settings');
		$t->where('name="?"', $this->service_name);
		//$t->andWhere('is_active');
		if ($t->fetchOne()) {
			return json_decode($t->settings, true);
		}
	}
	
	public function getBalance()
	{
		$balance = 0;
		
		$state = new \Sms\Xml\Balance($this->login, $this->password);
		$state->setUrl($this->host);
		if (!$state->send()) {
			$state->getError();
		} else {
			$data = $state->getResponse();
			$balance = se_FormatMoney($data['money'][0]['value'], $data['money'][0]['currency']);
		}
		
		$balance = strip_tags($balance);
		
		$balance = str_replace('&nbsp;', ' ', $balance);
		
		return $balance;
	}
	
	public function send($text, $phone, $sender)
	{
		$result = array();
		
		$messages = new \Sms\Xml\Messages($this->login, $this->password);
		$messages->setUrl($this->host);
		$mes = $messages->createNewMessage($sender, $text, 'sms');

		$abonent = $mes->createAbonent($phone);
		$abonent->setNumberSms(1);
		$mes->addAbonent($abonent);
		
		$messages->addMessage($mes);
		
		if (!$messages->send()) {
			$error = $messages->getError();
			$result['value'] = $error;
		} 
		else {
			//Array ( [0] => Array ( [value] => send [number_sms] => 1 [id_sms] => 1368950719 [id_turn] => 401020205 [parts] => 1 ) )
			$result = $messages->getResponse();
			$result = $result[0];
		}
		
		return $result;
	}
	
	public function getState($id_sms)
	{
		$result = array();
		
		$state = new \Sms\Xml\State($this->login, $this->password);
		$state->setUrl($this->host);
		$state->addIdSms($id_sms);

		if (!$state->send()) {
			$error = $state->getError();
			$result['value'] = $error;
		} 
		else {
			//Array ( [0] => Array ( [value] => deliver [id_sms] => 1368950719 [time] => 2017-04-07 19:56:58 [num_parts] => 1 [price] => 1.55 ) )
			//Array ( [0] => Array ( [value] => Сообщение с таким ID не принималось [id_sms] => 13689507190 [time] => [num_parts] => 0 [price] => 0 ) )
			$result = $state->getResponse();
			$result = $result[0];
		}
		
		return $result;
	}
	
}

/*
<?xml version="1.0" encoding="utf-8"?>
<request>
	<security>
		<login value="devsiteedit"/>
		<password value="d1yS9DAv5ZExGgk"/>
	</security>
	<message type="sms">
		<sender>79048445133</sender>
		<text>rrrrrrrddd</text>
		<abonent phone="79048445133" number_sms="1"/>
	</message>
</request>
*/