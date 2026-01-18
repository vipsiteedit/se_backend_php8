<?php
require_once "lib/lib_utf8.php";

$language = '';
$lang_enable = false;
$langswitch = '';
$se_hostname = array();
$se_sitelang = 'rus';
if (file_exists('hostname.dat')) {
    //содержимое hostname.dat сразу записывается в переменну - для последующего использования
    //без обращения к файлу
    $datastr = @file('hostname.dat');
    foreach ($datastr as $line) {
        list($host, $site) = explode("\t", $line);
        if (empty($host)) $se_hostname[] = trim($site);
        else $se_hostname[trim($host)] = trim($site); //пробелы обрезаются - в дальнейшей части кода удалены многократные вызовы функции
    }
}
if (file_exists('sitelang.dat')) {
    // содержимое sitelang.dat сразу записывается в переменну - для последующего использования
    //без обращения к файлу. Также переменная изначально имеет значение rus
    list($def) = file('sitelang.dat');
    if (is_dir(getcwd() . '/projects/' . trim($def))) $se_sitelang = trim($def);
}
if (!empty($se_hostname)) {
    $lang_enable = true;
    if (defined('SE_FORCE_HOST') && defined('SE_ADMIN_INCLUDED')) {
        $langswitch = $language = SE_FORCE_HOST;
        $lang_enable = true;
    } else {
        foreach ($se_hostname as $host => $site) {
            if (is_numeric($host)) {
                if (isRequest('site-lang')) {
                    $lang = getRequest('site-lang');
                    if (file_exists('./projects/' . $lang . '/project.xml')) {
                        $langswitch = $lang;
                    }
                } elseif (empty($langswitch)) {
                    $langswitch = $se_sitelang;
                }
            } elseif ($_SERVER['HTTP_HOST'] == $host || $_SERVER['HTTP_HOST'] == 'www.' . $host) {
                $lang_enable = false;
                $langswitch = $site;
                break;
            }
        }
        if (!empty($langswitch)) {
            $language = trim($langswitch);
        } else {
            $langswitch = $se_sitelang;
            $language = $langswitch;
        }
    }
} else {
    $langswitch = '';
}

$images = "/" . $language . "/images";
$se_dir = (empty($langswitch)) ? '' : $language . '/';
define('SE_DIR', $se_dir);
unset($se_dir);
$mlink = '';
if ($lang_enable) {
    define('SE_PROJECT_DIR', '/' . $language);
    if ($se_sitelang != $language) $mlink = SE_PROJECT_DIR;
}
define('SE_MULTI_DIR', $mlink);

define('SE_MULTI_SITE', $lang_enable);

/* содержимое функции seMultiDir() заменено - теперь она возвращает константу
* это позволяет избежать постоянного чтения файла sitelang.dat
* т. к. файл manager.php подключается после serequests.php, функция перенесена в него для обеспечания совместимости
*/

function seMultiDir()
{
    return SE_MULTI_DIR;
}

//сброс всех данных, кроме необходимых в дальнейшем
unset($mlink, $lang_enable, $redirect, $host, $site, $def, $langswitch);
