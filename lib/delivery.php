<?php

chdir($_SERVER["DOCUMENT_ROOT"]);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require_once 'system/main/init.php';

$token = $_POST["token"];
if ($token != md5($CONFIG["DBSerial"] . $CONFIG["DBPassword"])) {
	header("HTTP/1.1 401 Unauthorized");
	echo 'Для запрошенной операции необходима авторизация!';
	exit;
}

function ddLog($text)
{
	$text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";

	$dir = getcwd() . '/system/logs/delivery';

	if (!is_dir($dir))
		mkdir($dir);

	$filename = $dir . '/' . date('Y-m-d') . '.log';

	$file = fopen($filename, 'ab');
	fwrite($file, $text);
	fclose($file);
}

if (isRequest('get_services')) {
	$p = new plugin_shopdelivery();

	$services = $p->getServices('');

	echo json_encode($services);
}

if (isRequest('get_settings')) {
	$p = new plugin_shopdelivery();

	ddLog('get - ' . print_r($_GET, 1));

	ddLog('post - ' . print_r($_POST, 1));

	$settings = $p->getSettings(getRequest('id_delivery', 1), getRequest('code'));

	foreach ($settings as &$setting) {
		if ($setting['type'] == 'city') {
			$p = new plugin_shopgeo();
			if ($city = $p->getCity($setting['value']))
				$setting['valueName'] = $city['city_name'];
		}
	}

	echo json_encode($settings);
}

if (isRequest('save_settings')) {
	$p = new plugin_shopdelivery();

	ddLog('id - ' . getRequest('id_delivery', 1));

	ddLog(getRequest('settings'));

	ddLog('get - ' . $_REQUEST['settings']);

	$settings = json_decode($_REQUEST['settings'], true);

	ddLog('s - ' . $settings);

	$result = $p->saveSettings(getRequest('id_delivery', 1), $settings);

	echo 'ok';
}

if (isRequest('get_cities')) {
	$p = new plugin_shopgeo();

	$cities = $p->getCities(urldecode(getRequest('get_cities', 3)), getRequest('limit'));

	foreach ($cities as &$city) {
		$city['city'] = strip_tags($city['city']);
	}

	echo json_encode($cities);
}
