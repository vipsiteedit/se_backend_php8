<?php

error_reporting(0);
define('SE_INDEX_INCLUDED', true);
session_start();
date_default_timezone_set('Europe/Moscow');
  chdir($_SERVER['DOCUMENT_ROOT']);
  require_once "system/config_db.php";
  include "lib/lib_database.php";
  se_db_dsn('mysql');
  se_db_connect();
  define('SE_DB_ENABLE', true);

  require_once "lib/lib.php";
  $payment = new plugin_payment(0, 0);
  $payment->result();
