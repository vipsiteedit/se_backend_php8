<?php
ini_set('display_errors', 'on');
require 'system/main/inc.php';
error_reporting(0);

if (!empty($_SERVER['REDIRECT_HTTPS']) && $_SERVER['REDIRECT_HTTPS'] == 'on')
    define('_HTTP_', 'https://');
else {
    if (!empty($_SERVER['REQUEST_SCHEME'])) {
        define('_HTTP_', $_SERVER['REQUEST_SCHEME'] . '://');
    } else {
        define('_HTTP_', ((!$_SERVER['HTTPS'] || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://'));
    }
}

$domain = _HTTP_ . $_SERVER['HTTP_HOST'];
$file_turbo = SE_ROOT . SE_DIR . 'turbo_' . $_SERVER['HTTP_HOST'] . '.yml';

if (!file_exists($file_turbo) || filemtime($file_turbo) + 300 < time()) {
    //chdir(__DIR__);
    include_once('system/main/classes/seTurbo.class.php');
    new yaTurboPage(SE_DIR, $file_turbo);
}
//exit();
header("Content-type: text/xml");
echo join('', file($file_turbo));
