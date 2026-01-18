<?php

/**
 * Класс предназначен для отправки СМС через систему QuickTelecom
 */
class plugin_qtsms
{
	private $user      = '0';				// ваш логин в системе
	private $pass      = '0';				// ваш пароль в системе
	private $hostname  = 'https://go.qtelecom.ru/public/http/';		// адрес сервера указанный в меню "Поддержка -> протокол HTTP"

	private $path      = '/public/http/';
	private $on_ssl    = 1; 					// 1 - использовать HTTPS соединение, 0 - HTTP
	private $post_data = array();				// данные передаваемые на сервер
	private $multipost = false;					// множественный запрос по умолчанию false

	/**
	 * Конструктор
	 * @param bool|false $user
	 * @param bool|false $pass
	 * @param bool|false $hostname
	 */
	public function __construct($user = false, $pass = false, $hostname = false)
	{
		if ($user)     $this->user     = $user;
		if ($pass)     $this->pass     = $pass;
		if ($hostname) $this->hostname = $hostname;
	}

	/**
	 * отправка сообщения
	 *
	 * @param            $mes			текст сообщения
	 * @param            $target		Список адресатов через запятую. (Н-р: «+70010001212, 80009990000»)
	 * @param            $phl_codename	Кодовое имя контакт-листа в системе https://go.qtelecom.ru
	 * @param            $sender		Имя отправителя, зарегистрированного для Вас в системе https://go.qtelecom.ru
	 * @param null       $post_id		пользовательский ID рассылки, необязательный параметр, возвращается обратно в неизменном виде
	 * @param bool|false $period        Время жизни сообщения в секундах. Необязательный параметр. Максимальное время,
	 *                                  в течение которого сообщение должно быть доставлено на телефон.
	 *
	 * @return mixed|string
	 */
	public function post_sms($mes, $target = '', $phl_codename = '', $sender = null, $post_id = null, $period = false)
	{
		$in = array(
			'action'  => 'post_sms',
			'message' => $mes,
			'sender'  => $sender
		);
		if (!empty($target))    $in['target']       = $target;
		else if ($phl_codename) $in['phl_codename'] = $phl_codename;
		if ($post_id)           $in['post_id']      = $post_id;
		if ($period)            $in['period']       = $period;
		if ($this->multipost)   $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * статус по номеру сообщения (соответствует значению атрибута ID тэга SMS, возращаемого при отправке сообщения)
	 *
	 * @param $sms_id
	 *
	 * @return mixed|string
	 */
	public function status_sms_id($sms_id)
	{
		return $this->status_sms(false, false, false, false, $sms_id);
	}

	/**
	 * по номеру рассылки (соответствует значению атрибута sms_group_id тэга result, возращаемого при отправке сообщений)
	 *
	 * @param $sms_group_id
	 *
	 * @return mixed|string
	 */
	public function status_sms_group_id($sms_group_id)
	{
		return $this->status_sms(false, false, false, $sms_group_id, false);
	}

	/**
	 * по всем сообщениям за период времени от date_from до date_to
	 *
	 * @param        $date_from
	 * @param        $date_to
	 * @param string $smstype
	 *
	 * @return mixed|string
	 */
	public function status_sms_date($date_from, $date_to, $smstype = 'SENDSMS')
	{
		return $this->status_sms($date_from, $date_to, $smstype, false, false);
	}

	/**
	 * стату смс сообщения
	 *
	 * @param $date_from    от даты
	 * @param $date_to      до даты
	 * @param $smstype
	 * @param $sms_group_id Номер рассылки
	 * @param $sms_id       Номер сообщения
	 *
	 * @return mixed|string queued — сообщение в очереди отправки
	 *                      wait — передано оператору на отправку
	 *                      accepted — сообщение принято оператором
	 *                      delivered — сообщение доставлено
	 *                      not_delivered — сообщение не доставлено
	 * 						failed — ошибка при работе по сообщению
	 */
	private function status_sms($date_from, $date_to, $smstype, $sms_group_id, $sms_id)
	{
		$in = array('action' => 'status');
		if ($date_from)		 $in['date_from']    = $date_from;
		if ($date_to)		 $in['date_to']      = $date_to;
		if ($smstype)		 $in['smstype']      = $smstype;
		if ($sms_group_id)	 $in['sms_group_id'] = $sms_group_id;
		if ($sms_id)			 $in['sms_id']       = (string)$sms_id;
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * вывод черного списка
	 *
	 * @param bool|false $perp   количество записей на страницу
	 * @param bool|false $page   номер страницы, с которой начинать вывод
	 * @param bool|false $search будут показаны только телефоны, удовлетворяющие условию %W%
	 *
	 * @return mixed|string
	 */
	public function get_blacklist($perp = false, $page = false, $search = false)
	{
		$in = array('action' => 'blacklist');
		if ($perp)   $in['perp']   = $perp;
		if ($page)   $in['page']   = $page;
		if ($search) $in['search'] = $search;
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * добавление телефонов в черный список
	 *
	 * @param array $phones список телефонов в виде массива, или строки, разделенной запятыми
	 *
	 * @return mixed|string
	 */
	public function add_blacklist($phones = array())
	{
		$in = array('action' => 'blacklist_add');
		$in['phones'] = $phones;
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * удаление телефонов из черного списка
	 *
	 * @param array $phones список телефонов в виде массива, или строки, разделенной запятыми
	 *
	 * @return mixed|string
	 */
	public function del_blacklist($phones = array())
	{
		$in = array('action' => 'blacklist_delete');
		$in['phones'] = $phones;
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * получить баланс
	 * @return mixed|string
	 */
	public function get_balance()
	{
		$in = array('action' => 'balance');
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * входящие смс
	 *
	 * @param bool|false $new_only  1- только новые непрочитанные сообщения
	 *                              0- все сообщения (по-умолчанию)
	 * @param bool|false $sib_num	Номер входящего ящика ("ID ящика" в закладке "Настройки")
	 * @param bool|false $date_from сообщениям за период времен (дд.мм.гггг чч:ми:сс)
	 * @param bool|false $date_to   сообщениям за период времен (дд.мм.гггг чч:ми:сс)
	 * @param bool|false $phone
	 * @param bool|false $prefix
	 *
	 * @return mixed|string
	 */
	public function inbox_sms($new_only = false, $sib_num = false, $date_from = false, $date_to = false, $phone = false, $prefix = false)
	{
		$in = array('action' => 'inbox');
		if ($new_only)  $in['new_only']  = $new_only;
		if ($sib_num)   $in['sib_num']   = $sib_num;
		if ($date_from) $in['date_from'] = $date_from;
		if ($date_to)   $in['date_to']   = $date_to;
		if ($phone)     $in['phone']     = $phone;
		if ($prefix)    $in['prefix']    = $prefix;
		if ($this->multipost) $this->to_multipost($in);
		else return $this->get_post_request($in);
	}

	/**
	 * запрос к серверу
	 * @param array $invars
	 *
	 * @return mixed|string
	 */
	private function get_post_request($invars = array())
	{
		$invars['user']                 = $this->user;
		$invars['pass']                 = $this->pass;
		$invars['CLIENTADR']            = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
		$invars['HTTP_ACCEPT_LANGUAGE'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : false;
		$invars                         = http_build_query($invars);
		$len                            = strlen($invars);

		$ch = curl_init($this->hostname);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $invars);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Content-Length: ' . $len, 'User-Agent: AISMS PHP class'));
		$result = curl_exec($ch);
		curl_close($ch);

		list($headers, $body) = explode("\r\n\r\n", $result, 2);
		$headers = $this->decode_header($headers);
		$result  = $this->decode_body($headers, $body);
		$result  = json_encode(simplexml_load_string($result));
		$result  = json_decode($result, 1);

		return $result;
	}


	################################################
	// запрос на сервер и получение результата для MMS
	//  не рабочий вариант, отправка ММС не предпалгается
	private function get_multipost_request($invars, $file_data)
	{
		define("CRLF", "\r\n");
		define("DCRLF", CRLF . CRLF);
		$boundary = "---------------------" . substr(md5(rand(0, 32000)), 0, 10);
		$fieldsData = "";

		$invars['user'] = $this->user;
		$invars['pass'] = $this->pass;
		$invars['CLIENTADR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
		$invars['HTTP_ACCEPT_LANGUAGE'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : false;
		foreach ($invars as $field => $value) {
			$fieldsData .=  "--" . $boundary . CRLF;
			$fieldsData .=  "Content-Disposition: form-data; name=\"" . $field . "\"" . DCRLF;
			$fieldsData .=  $value . CRLF;
		}
		if (isset($file_data["name"]) && $file_data["name"] != "") {
			$fileHeaders = "--" . $boundary . CRLF;
			$fileHeaders .= "Content-Disposition: form-data; name=\"files\"; filename=\"" . $file_data["name"] . "\"" . CRLF;
			$fileHeaders .= "Content-Type: text/plain" . DCRLF;
			$fileHeadersTail = CRLF . "--" . $boundary . "--" . CRLF;
		} else $fileHeaders = "";

		$filesize = isset($file_data["name"]) && $file_data["name"] != "" ? filesize($file_data["path"]) : 0;
		$contentLength = strlen($fieldsData) + strlen($fileHeaders) + $filesize + strlen($fileHeadersTail);

		$headers  = "POST " . $this->path . " HTTP/1.0" . CRLF;
		$headers .= "Host: " . $this->hostname . CRLF;
		$headers .= "Referer: " . $this->hostname . CRLF;
		$headers .= "Content-type: multipart/form-data, boundary=" . $boundary . CRLF;
		$headers .= "Content-length: " . $contentLength . CRLF;
		$headers .= "User-Agent: AISMS PHP class" . DCRLF;
		$headers .= $fieldsData;
		$headers .= $fileHeaders;

		$ch = curl_init($this->hostname);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $invars);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: multipart/form-data',
			//													   'Content-Length: '.$len,
			'User-Agent: AISMS PHP class'
		));
		$result = curl_exec($ch);
		curl_close($ch);

		list($headers, $body) = explode("\r\n\r\n", $result, 2);
		$headers = $this->decode_header($headers);
		$result  = $this->decode_body($headers, $body);
		$result  = json_encode(simplexml_load_string($result));
		$result  = json_decode($result, 1);

		return $result;
	}

	/**
	 * парсим заголовок
	 * @param $str
	 *
	 * @return array
	 */
	private function decode_header($str)
	{
		$part = preg_split("/\r?\n/", $str, -1, PREG_SPLIT_NO_EMPTY);
		$out = array();
		for ($h = 0; $h < sizeof($part); $h++) {
			if ($h != 0) {
				$pos = strpos($part[$h], ':');
				$k = strtolower(str_replace(' ', '', substr($part[$h], 0, $pos)));
				$v = trim(substr($part[$h], ($pos + 1)));
			} else {
				$k = 'status';
				$v = explode(' ', $part[$h]);
				$v = $v[1];
			}
			if ($k == 'set-cookie') {
				$out['cookies'][] = $v;
			} else
					if ($k == 'content-type') {
				if (($cs = strpos($v, ';')) !== false) {
					$out[$k] = substr($v, 0, $cs);
				} else {
					$out[$k] = $v;
				}
			} else {
				$out[$k] = $v;
			}
		}
		return $out;
	}

	/**
	 * парсим тело ответа
	 * @param        $info
	 * @param        $str
	 * @param string $eol
	 *
	 * @return string
	 */
	private function decode_body($info, $str, $eol = "\r\n")
	{
		$tmp = $str;
		$add = strlen($eol);
		if (isset($info['transfer-encoding']) && $info['transfer-encoding'] == 'chunked') {
			$str = '';
			do {
				$tmp = ltrim($tmp);
				$pos = strpos($tmp, $eol);
				$len = hexdec(substr($tmp, 0, $pos));
				if (isset($info['content-encoding'])) {
					$str .= gzinflate(substr($tmp, ($pos + $add + 10), $len));
				} else {
					$str .= substr($tmp, ($pos + $add), $len);
				}
				$tmp   = substr($tmp, ($len + $pos + $add));
				$check = trim($tmp);
			} while (!empty($check));
		} elseif (isset($info['content-encoding'])) {
			$str = gzinflate(substr($tmp, 10));
		}
		return $str;
	}


	/*  здесь то, что пока не используется */

	################# post_mms_message
	// рассылка MMS [mes] по телефонам [target] с возвратом результата XML
	private function post_mms_message($subj, $mes, $f_data, $target, $post_id = null)
	{
		if (is_array($target))	$target = implode(',', $target);
		return $this->post_mms($subj, $mes, $f_data, $target, false);
	}
	// рассылка смс [mes] по кодовому имени контакт листа [phl_codename]
	private function post_mms_message_phl($subj, $mes, $f_data, $phl_codename, $post_id = null)
	{
		return $this->post_mms($subj, $mes, $f_data, false, $phl_codename);
	}

	private function post_mms($subject, $txt, $file_data, $target, $phl_codename = null, $post_id = null)
	{
		$in = array(
			'action' => 'post_mms',
			'message' => $txt,
			'subject' => $subject
		);
		if ($target) $in['target'] = $target;
		if ($phl_codename) $in['phl_codename'] = $phl_codename;
		if ($post_id) $in['post_id'] = $post_id;
		return $this->get_multipost_request($in, $file_data);
	}

	// команда на начало мульти запроса
	private function start_multipost()
	{
		$this->multipost = true;
	}
	// сбор данных запроса
	private function to_multipost($inv)
	{
		$this->post_data['data'][] = $inv;
	}
	// результирующий запрос на сервер и получение результата
	private function process()
	{
		return $this->get_post_request($this->post_data);
	}
}
