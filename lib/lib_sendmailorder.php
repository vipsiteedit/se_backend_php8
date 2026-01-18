<?php

/**
 * Класс создания заказа
 * 
 */
error_reporting(0);
date_default_timezone_set('Europe/Moscow');
chdir($_SERVER['DOCUMENT_ROOT']);
require_once "lib/lib.php";
require_once "system/main/serequests.php";
if (file_exists("system/config_db.php")) {
    require_once "system/config_db.php";
} elseif (file_exists("system/conf_mysql.php")) {
    require_once "system/conf_mysql.php";
    if (!defined('DBDsn')) define('DBDsn', 'mysql');
}
require_once "lib/lib_database.php";
se_db_dsn(DBDsn);
se_db_connect();

$order_id = $_POST['idorder'];
$codemail = $_POST['codemail'];
$lang = $_POST['lang'];

$tord = new seTable('shop_order', 'so');
$tord->select('p.id, p.email');
$tord->innerjoin('person p', 'p.id=so.id_author');
$tord->where('so.id=?', $order_id);
$tord->fetchOne();
$vars = array(); //$this->mailtemplate();

// письмо клиенту
$mails = new plugin_shopmail($order_id, 0, 'html', $tord->id);
//error_reporting(E_ALL);
if ($mails->sendmail($codemail, $tord->email, $vars)) echo 'ok';
