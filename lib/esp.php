<?php

chdir($_SERVER["DOCUMENT_ROOT"]);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require_once 'system/main/init.php';

$serial = $_POST["serial"];
$db_password = $_POST["db_password"];
if ($serial != $CONFIG["DBSerial"] || $db_password != $CONFIG["DBPassword"]) {
    header("HTTP/1.1 401 Unauthorized");
    echo 'Для запрошенной операции необходима авторизация!';
    exit;
}

$providerName = $_REQUEST["provider"];
$action = $_REQUEST["action"];
$parameters = $_REQUEST["parameters"];
if ($parameters)
    $parameters = json_decode($parameters, true);

$provider = new seTable('email_providers', 'ep');
$provider->where("name = '?'", $providerName);
$provider->fetchOne();
if (!$provider->isFind()) {
    echo "Не найден указанный провайдер!";
    exit;
}

$settings = json_decode($provider->settings, true);

if ($providerName == "sendpulse") {
    $apiId = $settings["ID"]["value"];
    $apiSecret = $settings["SECRET"]["value"];

    $SPApiProxy = new plugin_sendpulse($apiId, $apiSecret);

    switch ($action) {
        case 'balance':
            $result = $SPApiProxy->getBalance();
            echo $result->balance_currency;
            break;
        case 'createBook':
            $result = $SPApiProxy->createAddressBook($parameters["name"]);
            writeLog($result);
            break;
        default:
            break;
    }
}
