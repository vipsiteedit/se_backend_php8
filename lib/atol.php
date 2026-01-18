<?php

chdir($_SERVER["DOCUMENT_ROOT"]);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require_once 'system/main/init.php';

$atol = plugin_shop_atol::getInstance();
