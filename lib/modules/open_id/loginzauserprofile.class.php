<?php
/**
 * Р С™Р В»Р В°РЎРѓРЎРѓ LoginzaUserProfile Р С—РЎР‚Р ВµР Т‘Р Р…Р В°Р В·Р Р…Р В°РЎвЂЎР ВµР Р… Р Т‘Р В»РЎРЏ Р С–Р ВµР Р…Р ВµРЎР‚Р В°РЎвЂ Р С‘Р С‘ Р Р…Р ВµР С”Р С•РЎвЂљР С•РЎР‚РЎвЂ№РЎвЂ¦ Р С—Р С•Р В»Р ВµР в„– Р С—РЎР‚Р С•РЎвЂћР С‘Р В»РЎРЏ Р С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљР ВµР В»РЎРЏ РЎРѓР В°Р в„–РЎвЂљР В°, 
 * Р Р…Р В° Р С•РЎРѓР Р…Р С•Р Р†Р Вµ Р С—Р С•Р В»РЎС“РЎвЂЎР ВµР Р…Р Р…Р С•Р С–Р С• Р С—РЎР‚Р С•РЎвЂћР С‘Р В»РЎРЏ Р С•РЎвЂљ Loginza API (http://loginza.ru/api-overview).
 * 
 * Р СџРЎР‚Р С‘ Р С–Р ВµР Р…Р ВµРЎР‚Р В°РЎвЂ Р С‘Р С‘ Р С‘РЎРѓР С—Р С•Р В»РЎРЉР В·РЎС“РЎР‹РЎвЂљРЎРѓРЎРЏ Р Р…Р ВµРЎРѓР С”Р С•Р В»РЎРЉР С”Р С• Р С—Р С•Р В»Р ВµР в„– Р Т‘Р В°Р Р…Р Р…РЎвЂ№РЎвЂ¦, РЎвЂЎРЎвЂљР С• Р С—Р С•Р В·Р Р†Р С•Р В»РЎРЏР ВµРЎвЂљ РЎРѓР С–Р ВµР Р…Р ВµРЎР‚Р С‘РЎР‚Р С•Р Р†Р В°РЎвЂљРЎРЉ Р Р…Р ВµР С—Р ВµРЎР‚Р ВµР Т‘Р В°Р Р…Р Р…РЎвЂ№Р Вµ 
 * Р Т‘Р В°Р Р…Р Р…РЎвЂ№Р Вµ Р С—РЎР‚Р С•РЎвЂћР С‘Р В»РЎРЏ, Р Р…Р В° Р С•РЎРѓР Р…Р С•Р Р†Р Вµ Р С‘Р СР ВµРЎР‹РЎвЂ°Р С‘РЎвЂ¦РЎРѓРЎРЏ.
 * 
 * Р СњР В°Р С—РЎР‚Р С‘Р СР ВµРЎР‚: Р вЂўРЎРѓР В»Р С‘ Р Р† Р С—РЎР‚Р С•РЎвЂћР С‘Р В»Р Вµ Р С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљР ВµР В»РЎРЏ Р Р…Р Вµ Р С—Р ВµРЎР‚Р ВµР Т‘Р В°Р Р…Р С• Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р Вµ nickname, РЎвЂљР С• РЎРЊРЎвЂљР С• Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р Вµ Р СР С•Р В¶Р ВµРЎвЂљ Р В±РЎвЂ№РЎвЂљРЎРЉ
 * РЎРѓР С–Р ВµР Р…Р ВµРЎР‚Р С‘РЎР‚Р С•Р Р†Р В°Р Р…Р Р…Р С• Р Р…Р В° Р С•РЎРѓР Р…Р С•Р Р†Р Вµ email Р С‘Р В»Р С‘ full_name Р С—Р С•Р В»Р ВµР в„–.
 * 
 * Р вЂќР В°Р Р…Р Р…РЎвЂ№Р в„– Р С”Р В»Р В°РЎРѓРЎРѓ - РЎРЊРЎвЂљР С• РЎР‚Р В°Р В±Р С•РЎвЂЎР С‘Р в„– Р С—РЎР‚Р С‘Р СР ВµРЎР‚, Р С”Р С•РЎвЂљР С•РЎР‚РЎвЂ№Р в„– Р СР С•Р В¶Р Р…Р С• Р С‘РЎРѓР С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљРЎРЉ Р С”Р В°Р С” Р ВµРЎРѓРЎвЂљРЎРЉ, 
 * Р В° РЎвЂљР В°Р С” Р В¶Р Вµ Р В·Р В°Р С‘Р СРЎРѓРЎвЂљР Р†Р С•Р Р†Р В°РЎвЂљРЎРЉ Р Р† РЎРѓР С•Р В±РЎРѓРЎвЂљР Р†Р ВµР Р…Р Р…Р С•Р С Р С”Р С•Р Т‘Р Вµ Р С‘Р В»Р С‘ РЎР‚Р В°РЎРѓРЎв‚¬Р С‘РЎР‚РЎРЏРЎвЂљРЎРЉ РЎвЂљР ВµР С”РЎС“РЎвЂ°РЎС“РЎР‹ Р Р†Р ВµРЎР‚РЎРѓР С‘РЎР‹ Р С—Р С•Р Т‘ РЎРѓР Р†Р С•Р С‘ Р В·Р В°Р Т‘Р В°РЎвЂЎР С‘.
 * 
 * @link http://loginza.ru/api-overview
 * @author Sergey Arsenichev, PRO-Technologies Ltd.
 * @version 1.0
 */
class LoginzaUserProfile {
	/**
	 * Р СџРЎР‚Р С•РЎвЂћР С‘Р В»РЎРЉ
	 *
	 * @var unknown_type
	 */
	private $profile;
	
	/**
	 * Р вЂќР В°Р Р…Р Р…РЎвЂ№Р Вµ Р Т‘Р В»РЎРЏ РЎвЂљРЎР‚Р В°Р Р…РЎРѓР В»Р С‘РЎвЂљР В°
	 *
	 * @var unknown_type
	 */
	private $translate = array(
	'Р В°'=>'a', 'Р В±'=>'b', 'Р Р†'=>'v', 'Р С–'=>'g', 'Р Т‘'=>'d', 'Р Вµ'=>'e', 'Р В¶'=>'g', 'Р В·'=>'z',
	'Р С‘'=>'i', 'Р в„–'=>'y', 'Р С”'=>'k', 'Р В»'=>'l', 'Р С'=>'m', 'Р Р…'=>'n', 'Р С•'=>'o', 'Р С—'=>'p',
	'РЎР‚'=>'r', 'РЎРѓ'=>'s', 'РЎвЂљ'=>'t', 'РЎС“'=>'u', 'РЎвЂћ'=>'f', 'РЎвЂ№'=>'i', 'РЎРЊ'=>'e', 'Р С’'=>'A',
	'Р вЂ'=>'B', 'Р вЂ™'=>'V', 'Р вЂњ'=>'G', 'Р вЂќ'=>'D', 'Р вЂў'=>'E', 'Р вЂ“'=>'G', 'Р вЂ”'=>'Z', 'Р В'=>'I',
	'Р в„ў'=>'Y', 'Р С™'=>'K', 'Р вЂє'=>'L', 'Р Сљ'=>'M', 'Р Сњ'=>'N', 'Р С›'=>'O', 'Р Сџ'=>'P', 'Р В '=>'R',
	'Р РЋ'=>'S', 'Р Сћ'=>'T', 'Р Р€'=>'U', 'Р В¤'=>'F', 'Р В«'=>'I', 'Р В­'=>'E', 'РЎвЂ'=>"yo", 'РЎвЂ¦'=>"h",
	'РЎвЂ '=>"ts", 'РЎвЂЎ'=>"ch", 'РЎв‚¬'=>"sh", 'РЎвЂ°'=>"shch", 'РЎР‰'=>"", 'РЎРЉ'=>"", 'РЎР‹'=>"yu", 'РЎРЏ'=>"ya",
	'Р Рѓ'=>"YO", 'Р Тђ'=>"H", 'Р В¦'=>"TS", 'Р В§'=>"CH", 'Р РЃ'=>"SH", 'Р В©'=>"SHCH", 'Р Р„'=>"", 'Р В¬'=>"",
	'Р В®'=>"YU", 'Р Р‡'=>"YA"
	);
	
	function __construct($profile) {
		$this->profile = $profile;
	}
	
	public function genNickname () {
		if ($this->profile->nickname) {
			return $this->profile->nickname;
		} elseif (!empty($this->profile->email) && preg_match('/^(.+)\@/i', $this->profile->email, $nickname)) {
			return $nickname[1];
		} elseif ( ($fullname = $this->genFullName()) ) {
			return $this->normalize($fullname, '.');
		}
		// РЎв‚¬Р В°Р В±Р В»Р С•Р Р…РЎвЂ№ Р С—Р С• Р С”Р С•РЎвЂљР С•РЎР‚РЎвЂ№Р С Р Р†РЎвЂ№РЎвЂ Р ВµР С—Р В»РЎРЏР ВµР С Р Р…Р С‘Р С” Р С‘Р В· identity
		$patterns = array(
			'([^\.]+)\.ya\.ru',
			'openid\.mail\.ru\/[^\/]+\/([^\/?]+)',
			'openid\.yandex\.ru\/([^\/?]+)',
			'([^\.]+)\.myopenid\.com'
		);
		foreach ($patterns as $pattern) {
			if (preg_match('/^https?\:\/\/'.$pattern.'/i', $this->profile->identity, $result)) {
				return $result[1];
			}
		}
		
		return false;
	}
	
	public function genUserSite () {
		if (!empty($this->profile->web->blog)) {
			return $this->profile->web->blog;
		} elseif (!empty($this->profile->web->default)) {
			return $this->profile->web->default;
		}
		
		return $this->profile->identity;
	}
	
	public function genDisplayName () {
	 	if ( ($fullname = $this->genFullName()) ) {
			return $fullname;
		} elseif ( ($nickname = $this->genNickname()) ) {
			return $nickname;
		}
		
		$identity_component = parse_url($this->profile->identity);
		
		$result = $identity_component['host'];
		if ($identity_component['path'] != '/') {
			$result .= $identity_component['path'];
		}
		
		return $result.$identity_component['query'];
		
	}
	
	public function genFullName () {
		if ($this->profile->name->full_name) {
			return $this->profile->name->full_name;
		} elseif ( $this->profile->name->first_name || $this->profile->name->last_name ) {
			return trim($this->profile->name->first_name.' '.$this->profile->name->last_name);
		}
		
		return false;
	}
	/**
	 * Р вЂњР ВµР Р…Р ВµРЎР‚Р В°РЎвЂљР С•РЎР‚ РЎРѓР В»РЎС“РЎвЂЎР В°Р в„–Р Р…РЎвЂ№РЎвЂ¦ Р С—Р В°РЎР‚Р С•Р В»Р ВµР в„–
	 *
	 * @param unknown_type $len Р вЂќР В»Р С‘Р Р…Р В° Р С—Р В°РЎР‚Р С•Р В»РЎРЏ
	 * @param unknown_type $char_list Р РЋР С—Р С‘РЎРѓР С•Р С” Р Р…Р В°Р В±Р С•РЎР‚Р С•Р Р† РЎРѓР С‘Р СР Р†Р С•Р В»Р С•Р Р†, Р С‘РЎРѓР С—Р С•Р В»РЎРЉР В·РЎС“Р ВµР СРЎвЂ№РЎвЂ¦ Р Т‘Р В»РЎРЏ Р С–Р ВµР Р…Р ВµРЎР‚Р В°РЎвЂ Р С‘Р С‘, РЎвЂЎР ВµРЎР‚Р ВµР В· Р В·Р В°Р С—РЎРЏРЎвЂљРЎС“РЎР‹. Р СњР В°Р С—РЎР‚Р С‘Р СР ВµРЎР‚: a-z,0-9,~
	 * @return unknown
	 */
	public function genRandomPassword ($len=6, $char_list='a-z,0-9') {
		$chars = array();
		// Р С—РЎР‚Р ВµР Т‘РЎС“РЎРѓРЎвЂљР В°Р Р…Р С•Р Р†Р В»Р ВµР Р…Р Р…РЎвЂ№Р Вµ Р Р…Р В°Р В±Р С•РЎР‚РЎвЂ№ РЎРѓР С‘Р СР Р†Р С•Р В»Р С•Р Р†
		$chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
		$chars['A-Z'] = strtoupper($chars['a-z']);
		$chars['0-9'] = '0123456789';
		$chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';
		
		// Р Р…Р В°Р В±Р С•РЎР‚ РЎРѓР С‘Р СР Р†Р С•Р В»Р С•Р Р† Р Т‘Р В»РЎРЏ Р С–Р ВµР Р…Р ВµРЎР‚Р В°РЎвЂ Р С‘Р С‘
		$charset = '';
		// Р С—Р В°РЎР‚Р С•Р В»РЎРЉ
		$password = '';
		
		if (!empty($char_list)) {
			$char_types = explode(',', $char_list);
			
			foreach ($char_types as $type) {
				if (array_key_exists($type, $chars)) {
					$charset .= $chars[$type];
				} else {
					$charset .= $type;
				}
			}
		}
		
		for ($i=0; $i<$len; $i++) {
			$password .= $charset[ rand(0, strlen($charset)-1) ];
		}
		
		return $password;
	}
	
	/**
	 * Р СћРЎР‚Р В°Р Р…РЎРѓР В»Р С‘РЎвЂљ + РЎС“Р В±Р С‘РЎР‚Р В°Р ВµРЎвЂљ Р Р†РЎРѓР Вµ Р В»Р С‘РЎв‚¬Р Р…Р С‘Р Вµ РЎРѓР С‘Р СР Р†Р С•Р В»РЎвЂ№ Р В·Р В°Р СР ВµР Р…РЎРЏРЎРЏ Р Р…Р В° РЎРѓР С‘Р СР Р†Р С•Р В» $delimer
	 *
	 * @param unknown_type $string
	 * @param unknown_type $delimer
	 * @return unknown
	 */
	private function normalize ($string, $delimer='-') {
		$string = strtr($string, $this->translate);
	    return trim(preg_replace('/[^\w]+/i', $delimer, $string), $delimer);
	}
}

?>