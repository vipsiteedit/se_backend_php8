<?php

class plugin_shopgeo
{

	public function __construct()
	{
		//unset($_SESSION['user_region']);
		if (empty($_SESSION['user_region']['id_city'])) {
			$this->getCity(0, true);
		} else {
			//print_r($_SESSION['user_region']);
			$this->getCity($_SESSION['user_region']['id_city'], true);
		}
	}

	public function confirmCity()
	{
		$_SESSION['user_region']['confirm'] = true;
	}

	public function getSelected()
	{
		return $_SESSION['user_region'];
	}

	private function getDeliveryCountries()
	{
		$countries = '20';

		$shop_delivery_region = new seTable('shop_delivery_region', 'sdr');
		$shop_delivery_region->select('GROUP_CONCAT(DISTINCT sdr.id_country) AS countries');

		if ($shop_delivery_region->fetchOne() && $shop_delivery_region->countries != '') {
			$countries = $shop_delivery_region->countries;
		}

		return $countries;
	}

	public function getCities($search = '', $limit = 10, $countries = '')
	{
		$cities = array();
		$query_string = '?f=' . $search . '&idCountry=' . $this->getDeliveryCountries();
		if ($limit > 0)
			$query_string .= '&limit=' . $limit;
		if (!empty($countries)) {
			$query_string .= '&country=' . $countries;
		}
		$data = $this->getData($query_string);

		if (!empty($data)) {
			foreach ($data as $val) {
				$cities[] = array(
					'city' => preg_replace("/($search)/ui", "<strong>\${1}</strong>", $val['city_name']),
					'region' => $val['region_name'],
					'country' => $val['country_name'],
					'id' => $val['id'],
					'code' => mb_strtolower($val['country'])
				);
			}
		}
		return $cities;
	}

	public function getCity($id_city = 0, $save = false)
	{
		$city = '';

		if ($id_city) {
			$query_string = '?id=' . $id_city;
		} else {
			$query_string = '?ip=' . $this->getIp();
		}

		$query_string .= '&idCountry=' . $this->getDeliveryCountries();

		if (seData::getInstance()->prj->language == 'eng') {
			$query_string .= '&lang=eng';
		}

		$data = $this->getData($query_string);

		if (!empty($data)) {
			$city = current($data);
			if ($save) {
				$_SESSION['user_region'] = array(
					'id_city' => $city['id'],
					'id_region' => $city['region_id'],
					'id_country' => $city['country_id'],
					'city' => $city['city_name'],
					'region' => $city['region_name'],
					'country' => $city['country_name'],
					'code_country' => mb_strtolower($city['country'])
				);
			}
		}
		return $city;
	}

	private function getData($query_string = '')
	{
		$url = 'http://e-stile.ru/geo/getCity.php' . $query_string;

		$content = '';
		if (extension_loaded('curl')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			$content = curl_exec($ch);
			curl_close($ch);
		} else {
			$content = file_get_contents($url);
		}

		if (!empty($content)) {
			$content = json_decode($content, true);
		}
		return $content;
	}

	public function getIp()
	{
		$ip = $_SERVER['HTTP_X_REAL_IP'];
		if (empty($ip)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
