<?php
/**
 * Р С™Р В»Р В°РЎРѓРЎРѓР В° РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂ№ РЎРѓ Loginza API (http://loginza.ru/api-overview).
 * 
 * Р вЂќР В°Р Р…Р Р…РЎвЂ№Р в„– Р С”Р В»Р В°РЎРѓРЎРѓ - РЎРЊРЎвЂљР С• РЎР‚Р В°Р В±Р С•РЎвЂЎР С‘Р в„– Р С—РЎР‚Р С‘Р СР ВµРЎР‚, Р С”Р С•РЎвЂљР С•РЎР‚РЎвЂ№Р в„– Р СР С•Р В¶Р Р…Р С• Р С‘РЎРѓР С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљРЎРЉ Р С”Р В°Р С” Р ВµРЎРѓРЎвЂљРЎРЉ, 
 * Р В° РЎвЂљР В°Р С” Р В¶Р Вµ Р В·Р В°Р С‘Р СРЎРѓРЎвЂљР Р†Р С•Р Р†Р В°РЎвЂљРЎРЉ Р Р† РЎРѓР С•Р В±РЎРѓРЎвЂљР Р†Р ВµР Р…Р Р…Р С•Р С Р С”Р С•Р Т‘Р Вµ Р С‘Р В»Р С‘ РЎР‚Р В°РЎРѓРЎв‚¬Р С‘РЎР‚РЎРЏРЎвЂљРЎРЉ РЎвЂљР ВµР С”РЎС“РЎвЂ°РЎС“РЎР‹ Р Р†Р ВµРЎР‚РЎРѓР С‘РЎР‹ Р С—Р С•Р Т‘ РЎРѓР Р†Р С•Р С‘ Р В·Р В°Р Т‘Р В°РЎвЂЎР С‘.
 * 
 * Р СћРЎР‚Р ВµР В±РЎС“Р ВµРЎвЂљРЎРѓРЎРЏ PHP 5, Р В° РЎвЂљР В°Р С” Р В¶Р Вµ CURL Р С‘Р В»Р С‘ РЎР‚Р В°Р В·РЎР‚Р ВµРЎв‚¬Р ВµР Р…Р С‘Р Вµ РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂ№ c РЎР‚Р ВµРЎРѓРЎС“РЎР‚РЎРѓР В°Р СР С‘ http:// Р Т‘Р В»РЎРЏ file_get_contents.
 * 
 * @link http://loginza.ru/api-overview
 * @author Sergey Arsenichev, PRO-Technologies Ltd.
 * @version 1.0
 */
class LoginzaAPI {
	/**
	 * Р вЂ™Р ВµРЎР‚РЎРѓР С‘РЎРЏ Р С”Р В»Р В°РЎРѓРЎРѓР В°
	 *
	 */
	const VERSION = '1.0';
	/**
	 * URL Р Т‘Р В»РЎРЏ Р Р†Р В·Р В°Р С‘Р СР С•Р Т‘Р ВµР в„–РЎРѓРЎвЂљР Р†Р С‘РЎРЏ РЎРѓ API loginza
	 *
	 */
	const API_URL = 'http://loginza.ru/api/%method%';
	/**
	 * URL Р Р†Р С‘Р Т‘Р В¶Р ВµРЎвЂљР В° Loginza
	 *
	 */
	const WIDGET_URL = 'https://loginza.ru/api/widget';
	
	/**
	 * Р СџР С•Р В»РЎС“РЎвЂЎР С‘РЎвЂљРЎРЉ Р С‘Р Р…РЎвЂћР С•РЎР‚Р СР В°РЎвЂ Р С‘РЎР‹ Р С—РЎР‚Р С•РЎвЂћР С‘Р В»РЎРЏ Р В°Р Р†РЎвЂљР С•РЎР‚Р С‘Р В·Р С•Р Р†Р В°Р Р…Р Р…Р С•Р С–Р С• Р С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљР ВµР В»РЎРЏ
	 *
	 * @param string $token Р СћР С•Р С”Р ВµР Р… Р С”Р В»РЎР‹РЎвЂЎ Р В°Р Р†РЎвЂљР С•РЎР‚Р С‘Р В·Р С•Р Р†Р В°Р Р…Р Р…Р С•Р С–Р С• Р С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљР ВµР В»РЎРЏ
	 * @return mixed
	 */
	public function getAuthInfo ($token) {
		return $this->apiRequert('authinfo', array('token' => $token));
	}
	
	/**
	 * Р СџР С•Р В»РЎС“РЎвЂЎР В°Р ВµРЎвЂљ Р В°Р Т‘РЎР‚Р ВµРЎРѓ РЎРѓРЎРѓРЎвЂ№Р В»Р С”Р С‘ Р Р†Р С‘Р Т‘Р В¶Р ВµРЎвЂљР В° Loginza
	 *
	 * @param string $return_url Р РЋРЎРѓРЎвЂ№Р В»Р С”Р В° Р Р†Р С•Р В·Р Р†РЎР‚Р В°РЎвЂљР В°, Р С”РЎС“Р Т‘Р В° Р В±РЎС“Р Т‘Р ВµРЎвЂљ Р Р†Р С•Р В·Р Р†РЎР‚Р В°РЎвЂ°Р ВµР Р… Р С—Р С•Р В»РЎРЉР В·Р С•Р Р†Р В°РЎвЂљР ВµР В»РЎРЏ Р С—Р С•РЎРѓР В»Р Вµ Р В°Р Р†РЎвЂљР С•РЎР‚Р С‘Р В·Р В°РЎвЂ Р С‘Р С‘
	 * @param string $provider Р СџРЎР‚Р С•Р Р†Р В°Р в„–Р Т‘Р ВµРЎР‚ Р С—Р С• РЎС“Р СР С•Р В»РЎвЂЎР В°Р Р…Р С‘РЎР‹ Р С‘Р В· РЎРѓР С—Р С‘РЎРѓР С”Р В°: google, yandex, mailru, vkontakte, facebook, twitter, loginza, myopenid, webmoney, rambler, mailruapi:, flickr, verisign, aol
	 * @param string $overlay Р СћР С‘Р С— Р Р†РЎРѓРЎвЂљРЎР‚Р В°Р С‘Р Р†Р В°Р Р…Р С‘РЎРЏ Р Р†Р С‘Р Т‘Р В¶Р ВµРЎвЂљР В°: true, wp_plugin, loginza
	 * @return string
	 */
	public function getWidgetUrl ($return_url=null, $provider=null, $overlay='') {
		$params = array();
		
		if (!$return_url) {
			$params['token_url'] = $this->currentUrl();
		} else {
			$params['token_url'] = $return_url;
		}
		
		if ($provider) {
			$params['provider'] = $provider;
		}
		
		if ($overlay) {
			$params['overlay'] = $overlay;
		}
		
		return self::WIDGET_URL.'?'.http_build_query($params);
	}
	
	/**
	 * Р вЂ™Р С•Р В·Р Р†РЎР‚Р В°РЎвЂ°Р В°Р ВµРЎвЂљ РЎРѓРЎРѓРЎвЂ№Р В»Р С”РЎС“ Р Р…Р В° РЎвЂљР ВµР С”РЎС“РЎвЂ°РЎС“РЎР‹ РЎРѓРЎвЂљРЎР‚Р В°Р Р…Р С‘РЎвЂ РЎС“
	 *
	 * @return string
	 */
	private function currentUrl () {
		$url = array();
		// Р С—РЎР‚Р С•Р Р†Р ВµРЎР‚Р С”Р В° https
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
			$url['sheme'] = "https";
			$url['port'] = '443';
		} else {
			$url['sheme'] = 'http';
			$url['port'] = '80';
		}
		// РЎвЂ¦Р С•РЎРѓРЎвЂљ
		$url['host'] = $_SERVER['HTTP_HOST'];
		// Р ВµРЎРѓР В»Р С‘ Р Р…Р Вµ РЎРѓРЎвЂљР В°Р Р…Р Т‘Р В°РЎР‚РЎвЂљР Р…РЎвЂ№Р в„– Р С—Р С•РЎР‚РЎвЂљ
		if (strpos($url['host'], ':') === false && $_SERVER['SERVER_PORT'] != $url['port']) {
			$url['host'] .= ':'.$_SERVER['SERVER_PORT'];
		}
		// РЎРѓРЎвЂљРЎР‚Р С•Р С”Р В° Р В·Р В°Р С—РЎР‚Р С•РЎРѓР В°
		if (isset($_SERVER['REQUEST_URI'])) {
			$url['request'] = $_SERVER['REQUEST_URI'];
		} else {
			$url['request'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
			$query = $_SERVER['QUERY_STRING'];
			if (isset($query)) {
			  $url['request'] .= '?'.$query;
			}
		}
		
		return $url['sheme'].'://'.$url['host'].$url['request'];
	}
	
	/**
	 * Р вЂќР ВµР В»Р В°Р ВµРЎвЂљ Р В·Р В°Р С—РЎР‚Р С•РЎРѓ Р Р…Р В° API loginza
	 *
	 * @param string $method
	 * @param array $params
	 * @return string
	 */
	private function apiRequert($method, $params) {
		// url Р В·Р В°Р С—РЎР‚Р С•РЎРѓ
		$url = str_replace('%method%', $method, self::API_URL).'?'.http_build_query($params);
		
		if ( function_exists('curl_init') ) {
			$curl = curl_init($url);
			$user_agent = 'LoginzaAPI'.self::VERSION.'/php'.phpversion();
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$raw_data = curl_exec($curl);
			curl_close($curl);
			$responce = $raw_data;
		} else {
			$responce = file_get_contents($url);
		}
		
		// Р С•Р В±РЎР‚Р В°Р В±Р С•РЎвЂљР С”Р В° JSON Р С•РЎвЂљР Р†Р ВµРЎвЂљР В° API
		return $this->decodeJSON($responce);
	}
	
	/**
	 * Р СџР В°РЎР‚РЎРѓР С‘Р С JSON Р Т‘Р В°Р Р…Р Р…РЎвЂ№Р Вµ
	 *
	 * @param string $data
	 * @return object
	 */
	private function decodeJSON ($data) {
		if ( function_exists('json_decode') ) {
			return json_decode ($data);
		}
		
		// Р В·Р В°Р С–РЎР‚РЎС“Р В¶Р В°Р ВµР С Р В±Р С‘Р В±Р В»Р С‘Р С•РЎвЂљР ВµР С”РЎС“ РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂ№ РЎРѓ JSON Р ВµРЎРѓР В»Р С‘ Р С•Р Р…Р В° Р Р…Р ВµР С•Р В±РЎвЂ¦Р С•Р Т‘Р С‘Р СР В°
		if (!class_exists('Services_JSON')) {
			require_once( dirname( __FILE__ ) . '/JSON.php' );
		}
		
		$json = new Services_JSON();	
		return $json->decode($data);
	}
	
	public function debugPrint ($responceData, $recursive=false) {
		if (!$recursive){
			echo "<h3>Debug print:</h3>";
		}
		echo "<table border>";
		foreach ($responceData as $key => $value) {
			if (!is_array($value) && !is_object($value)) {
				echo "<tr><td>$key</td> <td><b>$value</b></td></tr>";
			} else {
				echo "<tr><td>$key</td> <td>";
				$this->debugPrint($value, true);
				echo "</td></tr>";
			}
		}
		echo "</table>";
	}
}

?>