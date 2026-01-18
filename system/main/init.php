<?php
ini_set('zend.ze1_compatibility_mode', 0);
if (!defined('SE_INDEX_INCLUDED')) {
    Header('404 Not Found', true, 404);
    exit();
}

if (!empty($_SERVER['REDIRECT_HTTPS']) && $_SERVER['REDIRECT_HTTPS'] == 'on')
    define('_HTTP_', 'https://');
else
    if (!empty($_SERVER['REQUEST_SCHEME'])) {
        define('_HTTP_', $_SERVER['REQUEST_SCHEME'].'://');
    } else {
        define('_HTTP_', ((!$_SERVER['HTTPS'] || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://'));
    }


define('_HOST_', _HTTP_ . $_SERVER['HTTP_HOST']);

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    define('SE_ROOT', '');
} else {
    $se_root = (substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'] . '/';
    define('SE_ROOT', $se_root);
}
define('SE_LIBS', SE_ROOT . 'lib/');
define('SE_MODULES', SE_LIBS . 'modules/');
define('SE_CORE', SE_ROOT . 'system/main/');
define('SE_JS_LIBS', SE_LIBS . 'js/');
define('SE_PRJ_FOLDER', '');
define('SE_SAFE', '');
define('SE_WWWDATA', '');
define('SE_ALL_SERVICES', true);
define('URL_END', '/');


// {use database}
if (file_exists("system/config_db.php")) {
    define('SE_LOGS', SE_ROOT . 'system/logs');
    if (!is_dir(SE_LOGS)) mkdir(SE_LOGS);

    define('SE_DB_ENABLE', true);
    require "system/config_db.php";
    require SE_LIBS . 'lib_database.php';
    //  {database type}
    se_db_dsn('mysql');
    //  {start database}
    //Update version db
    se_db_connect($CONFIG);
} else {
    define('SE_DB_ENABLE', false);
}


// external request handler}
require SE_CORE . 'serequests.php';    // service core functions


// languages and projects switcher
require SE_CORE . 'manager.php';

// authorization
require SE_CORE . 'auth.php';

require SE_CORE . 'function.php';
// librarys
if (file_exists(SE_LIBS . 'lib.php')) {
    require SE_LIBS . 'lib.php';
}

// static data handler
require SE_CORE . 'classes/seData.class.php';

if (file_exists(SE_ROOT . "modules/modules.php")) {
    require_once SE_ROOT . "modules/modules.php";
}
if (SE_DB_ENABLE && file_exists(SE_LIBS . "rss.php"))
    require_once SE_LIBS . "rss.php";	
