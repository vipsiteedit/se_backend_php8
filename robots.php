<?php
/*
//
//	manager.php
//	adapted for robots.txt output
//	24.02.2019
//
*/
ini_set('display_errors', 'off');
error_reporting(0);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require 'system/main/init.php';

function getHost()
{
    if (SE_DB_ENABLE) {
        $main = new seTable('main');
        $main->select('domain');
        $main->where("`lang`='rus'");
        $main->fetchOne();
        $thisdomain = $main->domain;
        $base = $_SERVER['HTTP_HOST'];
        if (empty($thisdomain)) {
            $thisdomain = trim(file_get_contents('system/domain.dat'));
            list(, $base) = explode('//', $thisdomain);
        } 
    } else {
        $thisdomain = _HTTP_ . $_SERVER['HTTP_HOST'];
    }
    $newdomain = (!$_SERVER['HTTP_HOST'] != $base) ? _HTTP_ . $_SERVER['HTTP_HOST'] : $thisdomain;
    return array($newdomain, $thisdomain);
}

function output($getfile)
{
    list($newdomain, $thisdomain) = getHost();
    $ls = @file($getfile);
    foreach ($ls as $line) {
        if (strpos($getfile, 'robots.txt') !== false) {
            $pr = explode('://', $newdomain);
            if (strpos($line, 'Host:') !== false && $pr[0] == 'http') {
                echo str_replace(array($thisdomain, '{host}'), $pr[1], $line);
            } else {
                echo str_replace(array($thisdomain, '{host}'), $newdomain, $line);
            }
        } else {
            echo str_replace(array($thisdomain, '{host}'), $newdomain, $line);
        }
    }
}
$language = '';
$sitedir = '';
//$filename = preg_replace('\/','',$_GET['file']);
if (!isset($_GET['file'])) exit;

$filename = end(explode('/', $_GET['file']));

if (file_exists('hostname.dat') && filesize('hostname.dat') > 0) {
    if ($datastr = @file('hostname.dat')) {
        if (file_exists('sitelang.dat')) {
            list($def) = file('sitelang.dat');
        } else $def = 'rus';

        foreach ($datastr as $line) {
            if (trim($line) == '') continue;
            list($host, $site) = explode("\t", $line);
            if (trim($host) == '') {
                $langswitch = trim($def);
            }
            if ($_SERVER['HTTP_HOST'] == trim($host) || $_SERVER['HTTP_HOST'] == 'www.' . trim($host)) {
                $langswitch = trim($site);
                break;
            }
        }
        if (!empty($langswitch)) {
            $sitedir = trim($langswitch) . '/';
        } else {
            $sitedir = trim($def) . '/';
        }
    }
} else {
    $langswitch = '';
}
$restrict = array('php', 'dat', 'htaccess', 'tar', 'gz', 'zip', 'tpl');
$ext = end(explode('.', $filename));

$getfile = 0;
if (in_array($ext, $restrict)) {
    echo 'access denied';
    exit;
}
if (file_exists($sitedir . $filename)) $getfile = $sitedir . $filename;
elseif (file_exists($filename)) $getfile = $filename;
//elseif ($ext!='html') exit;
switch ($ext) {
    case 'txt':
        header("Content-type: text/plain");
        output($getfile);
        break;
    case 'ico':
        header("Content-type: image/x-icon");
        output($getfile);
        break;
    case 'xml':
        $fname = explode('.', $filename);
        if (strpos($fname[0], 'sitemap') !== false) {
            header("Content-type: text/xml");
            output($getfile);
        } else echo 'access denied';
        exit;
        break;
    case 'html':
        if ($getfile) {
            output($getfile);
        } else {
            $fname = explode('.', $filename);
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: /' . $fname[0] . '/');
        }
        break;
    default:
        header("Content-type: text/html");
        break;
}
