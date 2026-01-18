<?php


require 'system/main/inc.php';
require 'system/main/reindex.php';
if (isRequest('reindex') || !is_dir(getcwd() . '/projects/' . SE_DIR . 'searchdata')) {
    reindexsite();
}

if (!isset($_SESSION['SITE_REFERER'])) {
    $_SESSION['SITE_REFERER'] = $_SERVER['HTTP_REFERER'];
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (getRequest('market') == 'reindex' && file_exists(getcwd() . "/system/main/yandexmarket.php") && filemtime('market.yml') < time() - 60) {
    //create yandexmarket
    include_once getcwd() . "/system/main/yandexmarket.php";
    se_yandexmarket();
}

if (
    file_exists(getcwd() . "/system/main/classes/sitemap.class.php") &&
    (
        getRequest('sitemap') == 'reindex'
        || (
            file_exists(SE_DIR . 'sitemap.xml')
            && filemtime(SE_DIR . 'sitemap.xml') < filemtime(SE_SAFE . 'projects/' . SE_DIR . 'project.xml')
        )
    )
) {
    //create yandexmarket
    include_once getcwd() . "/system/main/classes/sitemap.class.php";
    $sitemap = new sitemap();
    $sitemap->execute();
    unset($sitemap);
}

$reindexpage = getRequest('page');
se_ReIndexPage($reindexpage . ".xml");

if (isRequest('err_rep')) {
    error_reporting(E_ALL);
}

if (isRequest('ed')) {
    $system_page = getRequest('ed');
    $system_object = getRequest('object');
}


// рефералы
if (isRequest('rf')) {
    setcookie("referal", getRequest('rf', 1), time() + 7862400);
    $_SESSION['REFER'] = getRequest('rf', 1);
} else
    if (!isset($_SESSION['REFER']) && isset($_COOKIE['referal'])) {
    $_SESSION['REFER'] = $_COOKIE['referal'];
}

$fl_messerr = false;
check_session(false);

if (!file_exists('projects/' . SE_DIR . 'project.xml')) {
    //header('Location: https://ewebcms.ru', 302);
    exit();
}

$se = seData::getInstance(getRequest('page'), SE_DIR);
$se->execute();


// if ($se->error) {
//     if (function_exists('mod_stat')) {
//         $stat_idlog = mod_stat(false);
//     } else $stat_idlog = 0;
// }
header("Cache-Control: public");
header("Expires: Fri, 01 Jan 2014 05:00:00 GMT");

//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

header("Last-Modified: " . gmdate("D, d M Y H:i:s", $se->lastmodif) . " GMT");
//header("Cache-Control: no-cache, must-revalidate");
//header("Cache-Control: post-check=0,pre-check=0", false);
//header("Cache-Control: max-age=0", false);
//header("Pragma: no-cache");
//header("Expires: {$date_mod} GMT");


// Определение пользователя для статистики
// if (function_exists('mod_stat')) {
//     $stat_idlog = mod_stat(true);
// } else $stat_idlog = 0;

//$date_mod = date("D, d M Y H:i:00");

include "system/main/skin_construction.php";
include "system/main/sitestat.php";

// ########################################################
if ($fl_messerr) seAuthorizeError();
