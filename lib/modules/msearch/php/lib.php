<?php

//error_reporting(E_ALL | E_STRICT);
//date_default_timezone_set();

////////////////////////////////////////////////////////////////////////////////
// Возвращает массив словоформ данного слова

if (!function_exists('morph5')) {
    function morph5($word, $lang = 'rus') {
        require_once "lib/morphy/src/common.php";
       // set some options
        $opts = array('storage' => PHPMORPHY_STORAGE_FILE, 'with_gramtab' => false, 'predict_by_suffix' => true, 'predict_by_db' => true);
        $dir = 'lib/morphy/dicts';
        // Create descriptor for dictionary located in $dir directory with russian language
        $dict_bundle = new phpMorphy_FilesBundle($dir, $lang);
        // Create phpMorphy instance
        $morphy = new phpMorphy($dict_bundle, $opts);
        if ($morphy == null) {
            return array(' '.mb_strtoupper($word, 'UTF-8').' ');
        }
        $words = $morphy->getAllForms(iconv('UTF-8','CP1251',mb_strtoupper($word,'UTF-8')));
        if (empty($words)) {
            return array(' '.mb_strtoupper($word, 'UTF-8').' ');
        }
        $arr = array();
        foreach($words as $ww) $arr[] = ' '.mb_strtoupper(iconv('CP1251','UTF-8', $ww),'UTF-8').' ';
        return $arr;
    }
}

if (!function_exists('search_InStr')) {
    function search_InStr($qword, $str) {
        //$qword - массив массивов поисковых слов
        //обрабатываем строку, в которой ищем
        $str = ' '. mb_strtoupper(htmlspecialchars(trim($str), ENT_QUOTES),'UTF-8').' ';
        //Удаляем из строки символы пунктуации
        $chars = array(".", ",", "!", "\"", ";", ":", "(", ")", "-", "+", "?", "|");
        $str = str_replace($chars, " ", $str);
        
        
//        $str=iconv("cp1251","utf8",$str);
        $res = array();
        for ($i = 1; $i <= count($qword); $i++) {
            $str = str_replace($qword[$i], "[%WORD$i%]", $str);
            if (strpos($str, "[%WORD$i%]")!==false) {
                $res[] = true;
            }
        }
        return (count($res) / count($qword));
    }
}
;

function search_insite($nObj = 10, $emailAdmin = "", $nLog = 10, $section) {
    global $SEARCH_TITLE, $SEARCH_CONTENT, $SEARCH_WARN, $steplist, $parametrtext, $SEARCH_COUNTS;
    $_part = getRequest('part', 1);    
    //Если не передан номер страницы, значит поиск не был запущен, запускаем
    if (!isset($_part)) {
        $query = utf8_substr(trim(htmlspecialchars($_POST['query'], ENT_QUOTES)), 0, 50);
//        $query = utf8_substr(trim(htmlspecialchars("Генпрокуратура", ENT_QUOTES)), 0, 50);
        if (empty($query)) {
            $SEARCH_TITLE = $section->language->lang011;
            $SEARCH_CONTENT = "";
            $SEARCH_WARN = $section->language->lang013;
            return;
        }
        $SEARCH_TITLE = $query;
        $emailAdmin = htmlspecialchars($emailAdmin, ENT_QUOTES);
        $nLog = intval($nLog);
        //Пишем в лог
        if (!empty($emailAdmin)) {
            if (!file_exists("data/")) {
                mkdir("data", 0740);  
            }
            $logstr = date(" d.m.Y   H:i:s   ") . $_SERVER['REMOTE_ADDR'];
            $logstr .= str_repeat(" ", 41 - utf8_strlen($logstr)) . "$query\n";
            $flog = fopen("data/searchlog.dat", "a");
            fwrite($flog, $logstr);
            fclose($flog);
            //Читаем лог-файл
            $flog = file("data/searchlog.dat");
            //Если количество записей больше $nLog, посылаем письмо администратору
            if (count($flog) >= $nLog) {
                $mailtext = str_replace($section->parametrs->param8, $_SERVER['HTTP_HOST'], $section->parametrs->param8);
                foreach ($flog as $logstr) {
                    $mailtext .= $logstr;
                }
                $mailtext .= "\n---
CMS EDGESTILE SiteEdit www.siteedit.ru";
                $subj = $section->language->lang015 . ' ' . $_SERVER['HTTP_HOST'];
                $mailtext = str_replace('\"', '"', $mailtext);
                $from = "=?utf-8?b?" . base64_encode('CMS EDGESTILE SiteEdit') . "?= <".'noreply@'.$_SERVER['HTTP_HOST'].'>'; 
                $mailsend = new plugin_mail($subj, $emailAdmin, $from, $mailtext);
                if ($mailsend->sendfile()){
                    unlink("data/searchlog.dat");
                }
            }
        }
        $_SESSION['searchQuery'] = $query;
        //Удаляем из строки запроса символы пунктуации
        $chars = array(".", ",", "!", "\"", ";", ":", "(", ")", "-", "?", "|");
        //$query = iconv("utf-8","cp1251",$query);
        $query = str_replace($chars, " ", mb_strtoupper($query, 'UTF-8'));
        //Заменяем множественные пробелы на один
        $query = preg_replace("/[\s]+/u", " ", $query);
        //Разбиваем запрос на слова
        $qwords = explode(" ", $query);
        $i = 1;
        $qword = array();
        foreach($qwords as $qw) {
            if (version_compare(phpversion(), '5.0.0', '>')) {
                $qword[$i] = morph5($qw);
            } else {
                return;
            }
            $i++;
        }
        //dump($qword);
        // Начало поиска в DB
        $fCount = 0;
        if (SE_DB_ENABLE) {
            // Поиск в модулях
            
            
            $srch = new seTable('se_search');
            $srch->where("project='?'", str_replace('/','',SE_DIR));
            $orwhere = '';
            $modulelist = explode(',', $section->parametrs->param9);
            $searchmodules = '';
            foreach($modulelist as $module){
               $module = trim($module);
               $searchmodules .= " OR `modules` LIKE '%{$module}%'";
            }
            foreach($qword as $wrds){
              foreach($wrds as $wrd) {
                 $wrd = mb_strtolower($wrd,'UTF-8');
          //  print_r($wrd);
                if ($orwhere != '') $orwhere .= " OR ";
                $orwhere .= "(title LIKE '%{$wrd}%' OR ";
                $orwhere .= "titlepage LIKE '%{$wrd}%' OR ";
                $orwhere .= "keywords LIKE '%{$wrd}%' OR ";
                $orwhere .= "description LIKE '%{$wrd}%' OR ";
                $orwhere .= "searchtext LIKE '%{$wrd}%')";
            }}
            if ($orwhere)
                $srch->andwhere($orwhere.$searchmodules);
            
            $srchlist = $srch->getList();
            $findpage = array();
            foreach($srchlist as $srchpage) {
                if (in_array($srchpage['url'], $findpage)) continue;
                $stext = '';
                if ($srang = search_InStr($qword, $srchpage['titlepage'])) {
                    $srang = $srang * 10;
                    $stext = $srchpage['titlepage'];
                }
                //Ищем в названии страницы
                if ($src = search_InStr($qword, $srchpage['title'])) {
                    $srang += ($src * 7);
                    if (empty($stext)) {
                        $stext = $srchpage['title'];
                    }
                }
                //Ищем в ключевых словах
                if ($src = search_InStr($qword, $srchpage['keywords'])) {
                    $srang += ($src * 7);
                    if (empty($stext)) {
                        $stext = $srchpage['keywords'];
                    }
                }
                //Ищем в описании страницы
                if ($src = search_InStr($qword, $srchpage['description'])) {
                    $srang += ($src * 5);
                    if (empty($stext)) {
                        $stext = $srchpage['description'];
                    }
                }
                //Ищем в теле страницы, берем по 2 записи из индексного файла
                $fstring = str_replace("\r","", explode("\n", $srchpage['searchtext']));
                for ($i = 0; $i < (count($fstring)); $i++) {
                    if ($src = search_InStr($qword, trim($fstring[$i]) )) {
                        $srang+= ($src * 3);
                        if (empty($stext)) {
                            $stext = $fstring[$i] . " " . $fstring[$i + 1];
                        }
                    }
                }
                //Ищем в описании on-line модулей
                $page = $srchpage['page']; // Имя страницы модуля
                $modules = explode("\r\n", $srchpage['modules']);
                // $
                /*
                $__data->findModuleSearch($module);
                if ($__data->isSearch($qword)){
                   $stext = $__data->getSearch($qword);
                   $srang = 5;
                }
                */



                if ($stext) {
                    $rang[$fCount] = $srang;
                    $searchText[$fCount] = $stext;
                    
                    $link[$fCount] = $curd . $srchpage['url'];
                    $size[$fCount] = $srchpage['size'];
                    $date[$fCount] = date('d.m.Y', $srchpage['filetime']);
                    $title[$fCount] = $srchpage['title'];
                    $fCount++;
                    $findpage[] = $srchpage['url'];
                }
            }
        
        
        } else {
            $prevdir = getcwd();
            $choose_dir = "";
            if (!is_dir("projects/" . SE_DIR . "searchdata")){
                return;
            } else {
                $choose_dir = "projects/" . SE_DIR . "searchdata";
            }
            chdir($choose_dir);
        
            $d = opendir(".");
            $fCount = 1;
            $flag = true;
            $sub = 0;
            $curd = '';
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
                        $curd = '';
                        continue;
                    }
                }
                if ($f == '.' || $f == '..') {
                    continue;
                }
                if (is_dir($f)) {
                    $sub = 1;
                    chdir($f);
                    $d1 = opendir(".");
                    $curd = "$f/";
                    continue;
                }
                //Читаем файл
                $fstring = file($f);
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
                    if (empty($stext)) {
                        $stext = $fstring[2];
                    }
                }
                //Ищем в ключевых словах
                if (search_InStr($qword, $fstring[3])) {
                    $srang += 7;
                    if (empty($stext)) {
                        $stext = $fstring[3];
                    }
                }
                //Ищем в описании страницы
                if (search_InStr($qword, $fstring[4])) {
                    $srang += 5;
                    if (empty($stext)) {
                        $stext = $fstring[4];
                    }
                }
                //Ищем в теле страницы, берем по 2 записи из индексного файла
                for ($i = 5; $i < (count($fstring)); $i++) {
                    if (search_InStr($qword, trim($fstring[$i]) )) {
                        $srang++;
                        if (empty($stext)) {
                            $stext = $fstring[$i] . " " . $fstring[$i + 1];
                        }
                    }
                }
                if ($srang) {
                    $rang[$fCount] = $srang;
                    $searchText[$fCount] = $stext;
                    $datapage = explode(chr(1), $fstring[0]);
                    $link[$fCount] = $curd . $datapage[0];
                    $size[$fCount] = $datapage[1];
                    $date[$fCount] = $datapage[2];
                    $title[$fCount] = $fstring[2];
                    $fCount++;
                }
            }
            closedir($d);
            chdir($prevdir);
        }
        //Обрабатываем полученный результат
        if (!isset($rang)) {
            $SEARCH_CONTENT = "";
            $SEARCH_WARN = $section->language->lang014;
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
    if (empty($_part)) {
        $part = 1;
    } else {
        $part = htmlspecialchars($_part, ENT_QUOTES);
    }
    $nObj = htmlspecialchars($nObj, ENT_QUOTES);
    if ($nObj <= 0) {
        $nObj = 1;
    }
    $nPage = ceil(count($rang) / $nObj);
    if ($nPage > 1) {
        if ($_part == "") {
            $part = 1;
        } else {
            $part = htmlspecialchars($_part, ENT_QUOTES);
        }
        //Выводим список страниц
        if ($nPage != 0) {
            $steplist = "<span>|</span>";
        } else {
            $steplist = "";
        }
        for ($i = 1; $i <= $nPage; $i++) {
            if ($part == $i) {
                $steplist .= "<b class=\"currentpart\"> " . $i . " </b><span>|</span>";
            } else {
                $steplist .= "<a href=\"?part=$i\" class=\"otherpart\"> " . $i . " </a><span>|</span>";
            }
        }
    }
    $SEARCH_COUNTS = count($rang);
    $SEARCH_CONTENT = "";
    $count = 0;
    $larr = array();
    foreach ($rang as $k => $r) {
        $count++;
        if ($r == 0) {
            break;
        }
        if ($count <= $nObj * ($part - 1)) {
            continue;
        }
        if ($count > $nObj * ($part - 1) + $nObj) {
            break;
        }
        //Подсветка слов
        for ($i = 1; $i <= count($qword); $i++) {
            for ($j = 0; $j < count($qword[$i]); $j++) {
                $searchText[$k] = preg_replace("/\b(" . $qword[$i][$j] . ")\b/i",
                    "<b id=recWord>\\1</b>", $searchText[$k]);
            }
            //$searchText[$k]=eregi_replace("([^[:alpha:]]+)(".$qword[$i][$j].")([^[:alpha:]]+)", "\\1<b id=recWord>\\2</b>\\3", " ".$searchText[$k]." ");
        }
        //$searchText[$k]=str_replace($qword[$i], "<b></b>", $searchText[$k]);
       // print_r($link);
        if (utf8_substr($link[$k], -1) == "/") {
            $linkLight = $link[$k] . "?searchlight";
        } else {
            $linkLight = $link[$k] . "&searchlight";
        }
        $SEARCH_CONTENT .= "<div class=\"recResult\">
  <b class=\"recNumber\">$count</b>
  <a class=\"recTitle\" href=\"" . $link[$k] . "\" target='_blank'>" . $title[$k] . "</a>
  <div class=\"recSearchText\">" . $searchText[$k] . "</div>
  <a class=\"recLink\" href='" . $link[$k] . "' target='_blank'>" . _HOST_ . $link[$k] .
            "</a>
  <b class=\"recSize\">(" . $size[$k] . " kb)</b><b class=\"recDate\">" . $date[$k] .
            "</b></div>";
//            <a class=\"recLightSearch\" href='$linkLight' target='_blank'>{$section->parametrs->param9}</a></div>";
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
?>