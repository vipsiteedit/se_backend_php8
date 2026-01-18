<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

$tmpdir = getcwd();
chdir('../');
global $CONFIG;
require_once "system/config_db.php";
include "lib/lib_database.php";
se_db_dsn($CONFIG['DBDsn']);
se_db_connect();
require_once "lib/lib.php";
$action = new plugin_shopaction();
