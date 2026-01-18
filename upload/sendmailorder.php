<?php
error_reporting(0);
chdir(dirname(__FILE__) . '/../');
date_default_timezone_set("Europe/Moscow");
define('SE_INDEX_INCLUDED', true);
require_once getcwd() . "/system/main/init.php";

$order_id = $_POST['idorder'];
$codemail = $_POST['codemail'];
$lang = (!empty($_POST['lang'])) ? $_POST['lang'] : 'rus';
define('DEFAULT_LANG', $lang);

//$email = 'vip@edgestile.ru';
if ($order_id && $codemail) {

  $tord = new seTable('shop_order', 'so');
  $tord->select('p.id, p.email');
  $tord->innerjoin('person p', 'p.id=so.id_author');
  $tord->where('so.id=?', $order_id);
  $tord->fetchOne();
  $vars = array(); //$this->mailtemplate();
  // письмо клиенту
  $mails = new plugin_shopmail($order_id, 0, 'html', $tord->id);
  //error_reporting(E_ALL);
  $mails->sendmail($codemail, $tord->email, $vars);
  echo 'ok';
} else echo 'no';
