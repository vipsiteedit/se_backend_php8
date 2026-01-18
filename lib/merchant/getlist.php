<?php

error_reporting(0);
define('SE_INDEX_INCLUDED', true);
session_start();
date_default_timezone_set('Europe/Moscow');

chdir($_SERVER['DOCUMENT_ROOT'] . '/lib/plugins/plugin_payment/payments/');
$list = glob('payment_*.class.php');
foreach ($list as $item) {
   $item = str_replace(array('payment_', '.class.php'), '', $item);
   echo $item . '|';
}
