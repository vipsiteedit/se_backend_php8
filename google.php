<?php

session_start();
error_reporting(0);
define('SE_DB_ENABLE', true);
require_once "system/config_db.php";
require_once "lib/lib_database.php";
se_db_dsn();
se_db_connect();
require_once "system/main/googlemarket.php";

//Header("'Content-type: application/xml'");
header("Content-Type: text/xml");
header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
echo se_googlemarket();
//Header("Location: modules/download/$sid/$filename");

?>