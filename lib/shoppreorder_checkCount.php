<?php

define('SE_INDEX_INCLUDED', true);
chdir($_SERVER["DOCUMENT_ROOT"]);
require "system/main/init.php";

$plugin = new plugin_shoppreorder;
$plugin->checkProductCount($_GET["id"]);
