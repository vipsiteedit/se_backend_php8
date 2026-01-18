<?php
//BeginLib
//error_reporting(E_ALL | E_STRICT);
//date_default_timezone_set();
////////////////////////////////////////////////////////////////////////////////
// Возвращает массив словоформ данного слова
if (!function_exists('morph5')) {
    function morph5($word, $lang = 'rus')
    {
        require_once "lib/morphy/src/common.php";
        // set some options
        $opts = array('storage' => PHPMORPHY_STORAGE_FILE, 'with_gramtab' => false,
            'predict_by_suffix' => true, 'predict_by_db' => true);
        $dir = 'lib/morphy/dicts';
        // Create descriptor for dictionary located in $dir directory with russian language
        $dict_bundle = new phpMorphy_FilesBundle($dir, $lang);
        // Create phpMorphy instance
        $morphy = new phpMorphy($dict_bundle, $opts);
        if ($morphy != null)
            return array($word);
        $words = $morphy->getAllForms(strtoupper($word));
        return $words;
    }
}
if (!function_exists('search_InStr')) {
    function search_InStr($qword, $str)
    {
        //$qword - массив массивов поисковых слов
        //обрабатываем строку, в которой ищем
        $str = strtoupper(htmlspecialchars(trim($str), ENT_QUOTES));
        //Удаляем из строки символы пунктуации
        $chars = array(".", ",", "!", "\"", ";", ":", "(", ")", "-", "+", "?", "|");
        $str = str_replace($chars, " ", $str);
//        $str=iconv("cp1251","utf8",$str);
        for ($i = 1; $i <= count($qword); $i++) {
            $flag = false; //предполагаем, что вхождения нет
            for ($j = 0; $j < count($qword[$i]); $j++)
                if (!(utf8_strpos(" " . $str . " ", " " . $qword[$i][$j] . " ") === false))
                    $flag = true;
        }
        return $flag;
    }
}
;
function search_insite($nObj = 10, $emailAdmin = "", $nLog = 10)
{
    global $_POST, $SEARCH_TITLE, $SEARCH_CONTENT, $SEARCH_WARN, $steplist,
        $parametrtext,$SEARCH_COUNTS;
    $_part = getRequest('part', 1);    
    //Если не передан номер страницы, значит поиск не был запущен, запускаем
    if (!isset($_part)) {
        $query = utf8_substr(trim(htmlspecialchars($_POST['query'], ENT_QUOTES)), 0, 50);
        if (empty($query)) {
            $SEARCH_TITLE = $section->params[3]->value;
            $SEARCH_CONTENT = "";
            $SEARCH_WARN = $section->params[5]->value;
            return;
        }
        $SEARCH_TITLE = $query;
        $emailAdmin = htmlspecialchars($emailAdmin, ENT_QUOTES);
        $nLog = intval($nLog);
        //Пишем в лог
        if (!empty($emailAdmin)) {
            if (!file_exists("data/"))
                mkdir("data", 0740);
            $logstr = date(" d.m.Y   H:i:s   ") . $_SERVER['REMOTE_ADDR'];
            $logstr .= str_repeat(" ", 41 - utf8_strlen($logstr)) . "$query\n";
            $flog = se_fopen("data/searchlog.dat", "a");
            fwrite($flog, $logstr);
            fclose($flog);
            //Читаем лог-файл
            $flog = se_file("data/searchlog.dat");
            //Если количество записей больше $nLog, посылаем письмо администратору
            if (count($flog) >= $nLog) {
                $mailtext = str_replace($section->params[8]->value, $_SERVER['HTTP_HOST'], $section->params[8]->value);
                foreach ($flog as $logstr)
                    $mailtext .= $logstr;
                $mailtext .= "\n---
CMS EDGESTILE SiteEdit www.siteedit.ru";
                $subj = $section->params[7]->value . ' ' . $_SERVER['HTTP_HOST'];
                $headers = "Content-Type: text/plain; charset=Windows-1251\n";
                $headers .= "From: \"CMS EDGESTILE SiteEdit\" <support@edgestile.ru>\n";
                $headers .= "Subject: $subj\n";
                $headers .= "X-Priority: 3\n";
                $headers .= "Return-Part: <noreply>\n";
                $headers .= "Content-Transfer-Encoding: 8bit\n";
                $headers .= "Content-Type: text/plain; charset=Windows-1251\n";
                $mailtext = str_replace('\"', '"', $mailtext);
                mail($emailAdmin, "", str_replace('\r\n', "\r\n", $mailtext), $headers);
                unlink("data/searchlog.dat");
            }
        }
        $_SESSION['searchQuery'] = $query;
        //Удаляем из строки запроса символы пунктуации
        $chars = array(".", ",", "!", "\"", ";", ":", "(", ")", "-", "?", "|");
        //$query = iconv("utf-8","cp1251",$query);
        $query = str_replace($chars, " ", strtoupper($query));
        //Заменяем множественные пробелы на один
        $query = preg_replace("/[\s]+/u", " ", $query);
        //Разбиваем запрос на слова
        $qwords = explode(" ", $query);
        $i = 1;
        foreach ($qwords as $qw) {
            if (version_compare(phpversion(), '5.0.0', '>'))
                $qword[$i] = morph5($qw);
            else return;
            $i++;
        }
        //dump($qword);
        // Начало поиска
        $prevdir = getcwd();
        $choose_dir="";
        if (!is_dir("projects/".SE_DIR."searchdata")){
                  return;
        } else {
                  $choose_dir="projects/".SE_DIR."searchdata";
        }
        chdir($choose_dir);
        $d = opendir(".");
        $fCount = 1;
        $flag = true;
        $sub = 0;
        // Цикл по файлам
        while ($flag) {
            if ($sub == 0) {
                $f = readdir($d);
                if ($f === false) {
                    $flag = false;
                    continue;
                }
            } else {
                $f = readdir($d1);
                if ($f === false) {
                    $sub = 0;
                    chdir("..");
                    continue;
                }
            }
            if ($f == '.' || $f == '..')
                continue;
            if (is_dir($f)) {
                $sub = 1;
                chdir($f);
                $d1 = opendir(".");
                continue;
            }
            //Читаем файл
            $fstring = se_file($f);
            $srang = 0;
            $stext = "";
            //Ищем в заголовке страницы
            if (search_InStr($qword, $fstring[1])) {
                $srang = 10;
                $stext = $fstring[1];
            }
            //Ищем в названии страницы
            if (search_InStr($qword, $fstring[2])) {
                $srang += 7;
                if (empty($stext))
                    $stext = $fstring[2];
            }
            //Ищем в ключевых словах
            if (search_InStr($qword, $fstring[3])) {
                $srang += 7;
                if (empty($stext))
                    $stext = $fstring[3];
            }
            //Ищем в описании страницы
            if (search_InStr($qword, $fstring[4])) {
                $srang += 5;
                if (empty($stext))
                    $stext = $fstring[4];
            }
            //Ищем в теле страницы, берем по 2 записи из индексного файла
            for ($i = 5; $i < (count($fstring) - 1); $i++) {
                if (search_InStr($qword, trim($fstring[$i]) . " " . trim($fstring[$i + 1]))) {
                    $srang++;
                    if (empty($stext))
                        $stext = $fstring[$i] . " " . $fstring[$i + 1];
                }
            }
            if ($srang) {
                $rang[$fCount] = $srang;
                $searchText[$fCount] = $stext;
                $datapage = explode(chr(1), $fstring[0]);
                $link[$fCount] = $datapage[0];
                $size[$fCount] = $datapage[1];
                $date[$fCount] = $datapage[2];
                $title[$fCount] = $fstring[2];
                $fCount++;
            }
        }
        closedir($d);
        chdir($prevdir);
        //Обрабатываем полученный результат
        if (!isset($rang)) {
            $SEARCH_CONTENT = "";
            $SEARCH_WARN = $section->params[6]->value;
            return;
        }
        arsort($rang);
    } else {
        $rang = $_SESSION['searchRang'];
        $searchText = $_SESSION['searchText'];
        $link = $_SESSION['searchLink'];
        $size = $_SESSION['searchSize'];
        $date = $_SESSION['searchDate'];
        $title = $_SESSION['searchTitle'];
        $qword = $_SESSION['searchQword'];
        $SEARCH_TITLE = $_SESSION['searchQuery'];
    }
    //Вычисляем число страниц
    if (empty($_part))
        $part = 1;
    else
        $part = htmlspecialchars($_part, ENT_QUOTES);
    $nObj = htmlspecialchars($nObj, ENT_QUOTES);
    if ($nObj <= 0)
        $nObj = 1;
    $nPage = ceil(count($rang) / $nObj);
    if ($_part == "")
        $part = 1;
    else
        $part = htmlspecialchars($_part, ENT_QUOTES);
    //Выводим список страниц
    if ($nPage != 0)
        $steplist = "<span>|</span>";
    else
        $steplist = "";
    for ($i = 1; $i <= $nPage; $i++) {
        if ($part == $i)
            $steplist .= "<b class=\"currentpart\"> " . $i . " </b><span>|</span>";
        else
            $steplist .= "<a href=\"?part=$i\" class=\"otherpart\"> " . $i . " </a><span>|</span>";
    }
    $SEARCH_COUNTS = count($rang);
    $SEARCH_CONTENT = "";
    $count = 0;
    foreach ($rang as $k => $r) {
        $count++;
        if ($r == 0)
            break;
        if ($count <= $nObj * ($part - 1))
            continue;
        if ($count > $nObj * ($part - 1) + $nObj)
            break;
        //Подсветка слов
        for ($i = 1; $i <= count($qword); $i++) {
            for ($j = 0; $j < count($qword[$i]); $j++)
                $searchText[$k] = preg_replace("/\b(" . $qword[$i][$j] . ")\b/i",
                    "<b id=recWord>\\1</b>", $searchText[$k]);
            //$searchText[$k]=eregi_replace("([^[:alpha:]]+)(".$qword[$i][$j].")([^[:alpha:]]+)", "\\1<b id=recWord>\\2</b>\\3", " ".$searchText[$k]." ");
        }
        //$searchText[$k]=str_replace($qword[$i], "<b></b>", $searchText[$k]);
        if (utf8_substr($link[$k], -1) == "/")
            $linkLight = $link[$k] . "?searchlight";
        else
            $linkLight = $link[$k] . "&searchlight";
        $SEARCH_CONTENT .= "<div class=\"recResult\">
  <b class=\"recNumber\">$count</b>
  <a class=\"recTitle\" href=\"" . $link[$k] . "\" target='_blank'>" . $title[$k] . "</a>
  <div class=\"recSearchText\">" . $searchText[$k] . "</div>
  <a class=\"recLink\" href='" . $link[$k] . "' target='_blank'>" . _HOST_ . $link[$k] .
            "</a>
  <b class=\"recSize\">(" . $size[$k] . " kb)</b><b class=\"recDate\">" . $date[$k] .
            "</b><a class=\"recLightSearch\" href='$linkLight' target='_blank'>{$section->params[9]->value}</a></div>";
    }
    //$SEARCH_CONTENT = iconv("cp1251","utf-8",$SEARCH_CONTENT);
    //Регистрируем в сессии
    $_SESSION['searchRang'] = $rang;
    $_SESSION['searchText'] = $searchText;
    $_SESSION['searchLink'] = $link;
    $_SESSION['searchSize'] = $size;
    $_SESSION['searchDate'] = $date;
    $_SESSION['searchTitle'] = $title;
    $_SESSION['searchQword'] = $qword;
}
//EndLib
function module_search($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "10";
if (empty($section->params[1]->value)) $section->params[1]->value = "[%adminmail%]";
if (empty($section->params[2]->value)) $section->params[2]->value = "10";
if (empty($section->params[3]->value)) $section->params[3]->value = "<РїСѓСЃС‚РѕР№ Р·Р°РїСЂРѕСЃ>";
if (empty($section->params[4]->value)) $section->params[4]->value = "РќР°Р№РґРµРЅРѕ СЃС‚СЂР°РЅРёС†: ";
if (empty($section->params[5]->value)) $section->params[5]->value = "РџРѕРёСЃРєРѕРІР°СЏ СЃС‚СЂРѕРєР° РїСѓСЃС‚Р°СЏ!";
if (empty($section->params[6]->value)) $section->params[6]->value = "РџРѕ Р’Р°С€РµРјСѓ Р·Р°РїСЂРѕСЃСѓ РЅРёС‡РµРіРѕ РЅРµ РЅР°Р№РґРµРЅРѕ.";
if (empty($section->params[7]->value)) $section->params[7]->value = "РџРѕРёСЃРєРѕРІС‹Рµ Р·Р°РїСЂРѕСЃС‹ РЅР° СЃР°Р№С‚Рµ";
if (empty($section->params[8]->value)) $section->params[8]->value = "\"РЈРІР°Р¶Р°РµРјС‹Р№ Р°РґРјРёРЅРёСЃС‚СЂР°С‚РѕСЂ СЃР°Р№С‚Р° [SITE]!\r\n\r\nРќР° Р’Р°С€РµРј СЃР°Р№С‚Рµ СЃ РїРѕРјРѕС‰СЊСЋ СЃРёСЃС‚РµРјС‹ \"РџРѕРёСЃРє РїРѕ СЃР°Р№С‚Сѓ\" РёСЃРєР°Р»Рё СЃР»РµРґСѓСЋС‰РёРµ СЃР»РѕРІР° Рё РІС‹СЂР°Р¶РµРЅРёСЏ:\r\n\r\n    Р”Р°С‚Р°       Р’СЂРµРјСЏ       IP Р°РґСЂРµСЃ       РЎС‚СЂРѕРєР° Р·Р°РїСЂРѕСЃР°\r\n";
if (empty($section->params[9]->value)) $section->params[9]->value = "РџРѕРґСЃРІРµС‚РєР° СЃР»РѕРІ";
global $SEARCH_TITLE,$SEARCH_WARN,$SEARCH_CONTENT,$sysdate,$steplist,$SEARCH_COUNTS;
$SEARCH_TITLE=$section->params[3]->value;
search_insite($section->params[0]->value, $section->params[1]->value, $section->params[2]->value);
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content contSearch\" [part.style]>
<div class=\"contentTitle\" [part.style_title]>
  <span class=\"contentTitleTxt\">[part.title]</span>
  <b class=\"searchString\">\"$SEARCH_TITLE\"</b>
</div>
<noempty:part.image>
  <img border=\"0\" class=\"contentImage\" [part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\" title=\"[part.image_alt]\">
</noempty>
<noempty:part.text>
  <div class=\"contentText\" [part.style_text]>[part.text]</div>
</noempty>
<div class=\"searchWarn\">$SEARCH_WARN</div>
<div class=\"blockResult\">
  <div class=\"countRec\">{$section->params[4]->value}<b class=\"countRecNum\">$SEARCH_COUNTS</b></div>
<div class=\"blockObjResult\">
  $SEARCH_CONTENT
</div>
<div class=\"steplist\">
  $steplist
</div>
</div>
</div>
<!-- =============== END CONTENT ============= -->";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};