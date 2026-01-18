<?php
require_once 'lib_currency.php';

if (!function_exists('se_sqs')) {
    function se_sqs($var, $val)
    {
        // -------- формируем строку запроса - shape query string (sqs)
        // -------- если несколько переменных, то разделяем их ";"  se_stat_sqs("var1;var2;var3", "val1;val2;val3")
        // -------- if several variables then share their by ";"

        $link = array();
        $remove = array('part', 'rec', 'archive', 'sub', 'page', 'razdel');
        foreach ($_GET as $k => $v) if (!in_array($k, $remove) && intval($k) == 0 && $v) $link[$k] = $k . '=' . $v;

        if (!empty($var) && !empty($var)) {
            $arrvar = explode(";", $var);
            $arrval = explode(";", $val);
            foreach ($arrvar as $k => $v) if (isset($arrval[$k]) && $v) $link[$v] = $v . '=' . $arrval[$k];
        }
        $SE_VARS['get'] = join('&', $link);
        return $SE_VARS['get'];
    }
}

// Многостраничность установка лимита
// Multipages set limit
if (!function_exists('se_DivPagesLimit')) {
    function se_DivPagesLimit($pagen = 30)
    {
        //if ($razdel > 0)

        if ($pagen == 0) return;
        if (isset($_GET['sheet'])) {
            $sheet = intval($_GET['sheet']);
            $_SESSION['sheetpage'] = $sheet;
        } elseif (isset($_SESSION['sheetpage'])) {
            $sheet = intval($_SESSION['sheetpage']);
            $_GET['sheet'] = $sheet;
        }

        if ($sheet < 1) {
            $sheet = 1;
        }

        if ($sheet > 1)
            return 'LIMIT ' . ($pagen * $sheet - $pagen) . ',' . $pagen;
        else
            return 'LIMIT ' . $pagen;
    }
}


// -------------------------------------------------------------- //
// Формат вывода чисел (тысячи разделяются пробелом, сотые после точки)
// The output format of numbers (thousands are separated by a space, hundredth after dot)
// se_FormatNumber(число)
// se_FormatNumber(number)
if (!function_exists('se_FormatNumber')) {
    function se_FormatNumber($num, $separator = '&nbsp;')
    {
        $rnum = strstr(str_replace(",", ".", $num), '.');
        if (trim($rnum) == '') $rnum = ".00";
        if (strlen(trim($rnum)) < 3) $rnum .= "0";
        $num = strval(floor($num));
        $res = "";
        $l = strlen($num) - 1;
        $c = 0;
        for ($i = $l; $i >= 0; $i--) {
            $c++;
            $res = $num[$i] . $res;
            if ($c >= 3) {
                $res = " " . $res;
                $c = 0;
            }
        }
        return str_replace(" ", $separator, trim($res . $rnum));
    }
}

// -------------------------------------------------------------- //
// Формат вывода денежных едениц (использует формат вывода чисел функции se_FormatNumber)
// One unit of money output format (which uses the output format of numbers function se_FormatNumber)
// se_FormatMoney(сумма, валюта)
// se_FormatMoney(sum, Currency)
if (!function_exists('se_formatMoney')) {
    function se_formatMoney($price, $curr, $separator = '&nbsp;', $round = false)
    {
        $currency = seCurrency::getInstance(se_getlang());
        $res_setcurr = $currency->getCurrData($curr);
        if (empty($res_setcurr['minsum'])) $res_setcurr['minsum'] = 0.01;
        $price = round($price / $res_setcurr['minsum']) * $res_setcurr['minsum'];
        if ($res_setcurr['minsum'] >= 1) $round = true;

        $num = (!$round) ? round($price, 2) : round($price);


        $rnum = strstr(str_replace(",", ".", $num), '.');
        if (!$round && trim($rnum) == '') $rnum = ".00";

        if (!$round && strlen(trim($rnum)) < 3) $rnum .= "0";
        $num = strval(floor($num));
        $res = "";
        $l = strlen($num) - 1;
        $c = 0;
        for ($i = $l; $i >= 0; $i--) {
            $c++;
            $res = $num[$i] . $res;
            if ($c >= 3) {
                $res = " " . $res;
                $c = 0;
            }
        }
        $price = str_replace(" ", $separator, trim($res . $rnum));


        //$price = se_formatNumber($price);
        //$res_setcurr = mysql_fetch_array(mysql_query("SELECT `name_front`, `name_flang`  FROM `money_title` WHERE (`name` = '".$curr."') LIMIT 1"),MYSQL_BOTH);
        if (!empty($res_setcurr['name_front']))
            return '<span class="fMoneyFront">' . $res_setcurr['name_front'] . '</span>&nbsp;' . trim($price);
        elseif (!empty($res_setcurr['name_flang'])) {
            $rubl = (in_array($curr, array('RUR', 'RUB'))) ? ' rubl' : '';
            $nameflang = (in_array($curr, array('RUR', 'RUB'))) ? 'руб.' : $res_setcurr['name_flang'];
            return trim($price) . '&nbsp;<span class="fMoneyFlang' . $rubl . '">' . $nameflang . '</span>';
        } else
            return trim($price);
    }
}

// -------------------------------------------------------------- //
// Конвертирование валют денежных едениц
// Currence unit conversion
// se_MoneyConvert(сумма, валюта_на_входе, валюта_на_выходе, дата_на_которую_конвертируется_валюта(по умолч сегодняшний день))
// se_MoneyConvert(sum, Currency_on_in, Currency_on_out , date_on_which_the_converted_currency(default today))

if (!function_exists('se_Money_Convert')) {
    function se_Money_Convert($summa, $setvalut, $getvalut, $date_rep = '')
    {
        return se_MoneyConvert($summa, $setvalut, $getvalut, $date_rep);
    }
}

if (!function_exists('se_MoneyConvert')) {
    function se_MoneyConvert($summa, $setvalut, $getvalut, $date_rep = '')
    {
        // конвертирование цен
        // price conversion
        if (empty($setvalut) || empty($getvalut)) {
            return $summa;
        }
        if (!se_manual_curr_rate()) {
            if ($setvalut == $getvalut) return $summa;
            $curs1 = getCurrencyValues($setvalut);
            $curs1 = (!empty($curs1['Value'])) ? str_replace(',', '.', $curs1['Value']) / $curs1['Nominal'] : 1.00;
            $curs2 = getCurrencyValues($getvalut);
            $curs2 = (!empty($curs2['Value'])) ? str_replace(',', '.', $curs2['Value']) / $curs2['Nominal'] : 1.00;
            return $summa * ($curs1 / $curs2);
        } else {
            if (empty($date_rep)) {
                $date_rep = date("Y-m-d");
            }
            $currency = seCurrency::getInstance(se_getlang());
            return $currency->convert($summa, $setvalut, $getvalut, $date_rep);
        }
    }
}


// -------------------------------------------------------------- //
// Проверка почтового ящика на валидность
// Validating e-mail
// se_CheckMail(имя_электронного_ящика)
// se_CheckMail(e-mail)
if (!function_exists('se_CheckMail')) {
    function se_CheckMail($str_email)
    {
        // Разделяем адрес
        // split address
        list($username, $domain) = explode("@", $str_email);
        // Проверка валидности
        // Validating
        $se_check_dns = $se_check_mx = true;
        // Проверка правильности написания адреса
        //
        //    $se_strings_isemail = ereg('^([a-zA-Z0-9_\-]|-|.)+'.'@'.'([a-zA-Z0-9_\-]|-|.)+'.'[a-zA-Z]{2,4}$',$str_email);
        $se_strings_isemail = preg_match('/^(?:[\w\d\-\.]+)@(?:[\w\d\-\.]+)$/', $str_email);

        // Проверка MX записи домена в DNS
        // Checking MX records in DNS domain
        //  if(!function_exists('getmxrr') or getmxrr($domain, $mxhost)) $se_check_mx = TRUE;
        //  else $se_check_mx = FALSE;

        // Проверка A записи домена в DNS
        // Checking A record in DNS domain
        // Эта функция неприменима на системах Windows
        // This feature is not available on Windows systems
        // if(!function_exists('checkdnsrr') or checkdnsrr($domain.'.', "A")) $se_check_dns = TRUE;
        // else $se_check_dns = FALSE;

        if ($se_strings_isemail && $se_check_mx && $se_check_dns) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

// -------------------------------------------------------------- //
// Выводит часть строки до завершения слова
// Prints part of the line until complete the word
// se_LimitString(строка, количество_выводимых_символов, конец_строки)
// se_LimitString(string, printed_symbols_number, line_end)
if (!function_exists('se_LimitString')) {
    function se_LimitString($text, $len = 150, $endchars = '...')
    {
        $text = utf8_substr($text, 0, $len);
        if (preg_match('/^(.+|\n)\W/ius', $text, $matches)) return rtrim($matches[1]) . " " . $endchars;
        else return $text . " " . $endchars;
    }
}

function se_iconv($outcharser, $text)
{
    global $SE_PAGEVAL, $sitearray;

    if (!empty($SE_PAGEVAL['localcharset']))
        $charset = $SE_PAGEVAL['localcharset'];
    elseif (!empty($sitearray["globalcharset"]))
        $charset = $sitearray["globalcharset"];
    else  $charset = 'CP1251';
    if (strtolower($charset) != strtolower($outcharser))
        $text = iconv($charset, $outcharser, $text);
    return $text;
}

// Возвращает текущий язык проекта
// Returns the current language of project
function se_getlang()
{
    if (defined('DEFAULT_LANG') && DEFAULT_LANG) {
        $lang = DEFAULT_LANG;
    } else $lang = "rus";
    if (strpos($lang, '.')) list(, $lang) = explode('.', $lang);
    return $lang;
}


function se_correct_path($path)
{
    $preg = "/([\'\"(]{1,2}})(skin|files|images)\//";
    while (preg_match($preg, $path, $m)) {
        $path = str_replace($m[0], $m[1] . '/' . SE_DIR . $m[2] . '/', $path);
    }

    $preg = "/\A(skin|files|images)\//";
    while (preg_match($preg, $path, $m)) {
        $path = str_replace($m[0], '/' . SE_DIR . $m[1] . '/', $path);
    }
    return $path;
}

function se_manual_curr_rate()
{
    if (!SE_DB_ENABLE) return;
    if (defined('SE_MANUAL_CURR_RATE')) return SE_MANUAL_CURR_RATE;
    $main = new seTable('main');
    $main->select('is_manual_curr_rate');
    $main->where("lang='?'", se_getlang());
    $main->fetchOne();
    define('SE_MANUAL_CURR_RATE', $main->is_manual_curr_rate);
    return $main->is_manual_curr_rate;
}

function se_BaseCurrency()
{
    if (!SE_DB_ENABLE) return;
    if (defined('SE_BASE_CURR')) return SE_BASE_CURR;
    $main = new seTable('main');
    $main->select('basecurr');
    $main->where("lang='?'", se_getlang());
    $main->fetchOne();
    $basecurr = $main->basecurr;
    if (empty($basecurr)) $basecurr = 'RUR';
    define('SE_BASE_CURR', $basecurr);
    return $basecurr;
}


function se_getMoney()
{
    if (isset($_POST['pricemoney'])) {
        $pricemoney = $_SESSION[SE_DIR . 'pricemoney'] = substr(strval($_POST['pricemoney']), 0, 3);
    } elseif (empty($_SESSION[SE_DIR . 'pricemoney'])) {
        $_SESSION[SE_DIR . 'pricemoney'] = $basecurr = se_baseCurrency();
        $pricemoney = $basecurr;
    } else {
        $pricemoney = $_SESSION[SE_DIR . 'pricemoney'];
    }
    return (trim($pricemoney) != '') ? $pricemoney : 'RUR';
}


function se_getAdmin($select = '')
{
    if (!SE_DB_ENABLE) return;
    $main = new seTable('main');
    $main->select($select);
    $main->where("lang='?'", se_getlang());
    $result = $main->fetchOne();
    if (count($result) < 2) return $result[$select];
    else return $result;
}

if (!function_exists('se_getVersion')) {
    function se_getVersion()
    {
        $file = trim(current(file(dirname(__FILE__) . '/version')));
        list(, $version) = explode(':', $file);
        if (empty($version)) $version = '5.1';
        return $version;
    }
}

function se_getMainId()
{
    if (!SE_DB_ENABLE) return;
    if (!defined('SE_SHOP_ID')) {
        $main = new seTable('main');
        $main->select('id');
        $main->where("(folder='?')", SE_DIR);
        $main->fetchOne();
        $shop_id = (intval($main->id)) ? intval($main->id) : 1;
        define('SE_SHOP_ID', $shop_id);
        return $shop_id;
    } else {
        return SE_SHOP_ID;
    }
}

function seUserTypePrice()
{
    if (!SE_DB_ENABLE) return;
    if (!defined('USER_TYPEPRICE')) {
        $price_type = 0;
        if ($id_user = seUserId()) {
            $p = new seTable('person');
            $p->select('price_type');
            $p->find($id_user);
            $price_type = (int)$p->price_type;
        }
        define('USER_TYPEPRICE', $price_type);
    }

    return USER_TYPEPRICE;
}
