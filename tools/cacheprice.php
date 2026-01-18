#! /usr/bin/php
<?php

define('SE_INDEX_INCLUDED', true);
chdir(__DIR__.'/..');
require 'system/main/init.php';
error_reporting(E_ALL);

$psp =  new plugin_shop_price_cache();
$psp->checkCache();