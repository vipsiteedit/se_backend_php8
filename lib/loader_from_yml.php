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

$file_market = file_get_contents($_POST["url_yml"]);

if (empty($file_market)) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Отсутствуют данные для обработки";
    exit;
}

include_once('lib/plugins/plugin_shop/plugin_yandex_market_loader.class.php');
new yandex_market_loader($file_market);
if (!se_db_error())
    echo "ok";
else echo se_db_error();
