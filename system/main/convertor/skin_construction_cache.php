<?php

function getMapValue($stext)
{
    while (preg_match("/\[menu.item-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[menu.item-" . $mm[1] . "]", '1', $stext);

    $res = '';
    while (preg_match("/\[(site|page|menu|img)\.(.+?)\]/i", $stext, $mm)) {
        $mm[1] = strtolower($mm[1]);
        if (($mm[1] == 'site') or ($mm[1] == 'page')) {
            $dataval = @$mm[2];
            if ($mm[1] == 'site') {
                $res = 'se_checkVars(\'prj\', \'' . $dataval . '\')';
            } else {
                $res = 'se_checkVars(\'page\', \'' . $dataval . '\')';
            }

        } else {
            $res = '';
            if ($mm[1] == 'img') {
                $res = $mm[2];
            }

            if ($mm[1] == 'menu') {
                $res = '1';
            }
        }
        $stext = str_replace("[" . $mm[1] . "." . $mm[2] . "]", $res, $stext);
    }

    while (preg_match("/\[content-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[content-" . $mm[1] . "]", '1', $stext);


    while (preg_match("/\[global-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[global-" . $mm[1] . "]", '1', $stext);

    $stext = str_replace("[NOTNAMEUSER]", '!seUserGroup()', $stext);
    $stext = str_replace("[NAMEUSER]", 'seUserName()', $stext);
    $stext = str_replace('[this_url]', _HOST_ . '/', $stext);

    return $stext;

}

function countlevel($str)
{
    list($line,) = explode('(', $str);
    return count(explode("\t", $line));
}

function r_tag($tag)
{
//echo $tag."<br>";
    while (preg_match("/href=\"([\w\d_\-^\/]+)\.html/i", $tag, $m)) {
        $se_dir = seMultiDir();
        $m[1] = $se_dir . '/' . join('/', explode('_', $m[1])) . '/';
        $tag = str_replace($m[0], 'href="' . $m[1], $tag);
    }
    $tag = str_replace('&#10;', '', str_replace('&#44;', ',', $tag));
    return ltrim(rtrim($tag));
}

function bildingValues($stext)
{
    global $techno;
    while (preg_match("/\[menu.item-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[menu.item-" . $mm[1] . "]", '<?php echo ItemsMenu(\'' . $mm[1] . '\') ?>', $stext);

    $res = '';
    while (preg_match("/\[(site|page|menu|img)\.([^\]]+)\]/i", $stext, $mm)) {
        $mm[1] = strtolower($mm[1]);
        if (($mm[1] == 'site') or ($mm[1] == 'page')) {
            $dataval = @$mm[2];
            if ($mm[1] == 'site') {
                if ($mm[2] == 'authorizeform')
                    $res = '<?php echo replace_values(seAuthorize(seData::getInstance()->prj->vars->authorizeform)) ?>';
                elseif ($mm[2] == 'sitelogotype')
                    $res = '<?php echo replace_values(seLogotype(seData::getInstance()->prj->vars->sitelogotype)) ?>';
                elseif ($mm[2] == 'technolink') {
                    $res = '<a href="//www.edgestile.ru/">Программа управления сайтами - CMS SiteEdit</a>';
                    $techno = "yes";
                } elseif ($mm[2] == 'technopict') {
                    $res = '<a href="//www.edgestile.ru/" id="imageTechnology"><img alt="Программа управления сайтами - CMS SiteEdit" src="//www.siteedit.ru/public/technology/default.gif" style="border:0;"></a>';
                    $techno = "yes";
                } elseif ($mm[2] == 'statistic')
                    $res = '<noindex><?php echo replace_values(seData::getInstance()->getVars(\'prj\',\'' . $dataval . '\')) ?></noindex>';
                else
                    $res = '<?php echo replace_values(seData::getInstance()->getVars(\'prj\',\'' . $dataval . '\')) ?>';
            } else {
                $res = '<?php echo replace_values(seData::getInstance()->getVars(\'page\',\'' . $dataval . '\')) ?>';
            }

        } else {
            $res = '';
            if ($mm[1] == 'img')
                $res = '<?php echo sePicture(\'' . $mm[2] . '\') ?>';
            if ($mm[1] == 'menu') {
                if ($mm[2] == 'mainmenu')
                    $res = '<?php echo seMenuExecute::getInstance()->getMainMenu(0); ?>';
                if ($mm[2] == 'mainhoriz')
                    $res = '<?php echo seMenuExecute::getInstance()->getMainMenu(1); ?>';
                if ($mm[2] == 'mainvert')
                    $res = '<?php echo seMenuExecute::getInstance()->getMainMenu(2); ?>';

                if ($mm[2] == 'pagemenu')
                    $res = '<?php echo seMenuExecute::getInstance()->getPageMenu() ?>';
            }
        }
        $stext = str_replace($mm[0], $res, $stext);
    }

    while (preg_match("/\[content-(\d{1,})\]/i", $stext, $mm)) {
        $stext = str_replace("[content-" . $mm[1] . "]", '<?php echo se_getContainer(' . intval($mm[1]) . ') ?>', $stext);
    }


    while (preg_match("/\[global-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[global-" . $mm[1] . "]", '<?php echo se_getContainer(' . ($mm[1] + 100) . ') ?>', $stext);

    $stext = str_replace("[NAMEUSER]", '<?php echo getUserName() ?>', $stext);
    $stext = str_replace('[this_url]', _HOST_ . "/", $stext);

    return $stext;
}


function bildingSkinCache($skinmap)
{
    // Replace css
    $SE_HTML = '';
    $skinmap = basename($skinmap, '.map');
    $se = seData::getInstance();
    $skin = '/' . $se->getSkinService();
    if (file_exists(getcwd() . '/system/main/replacecss.txt')) {
        $repcss = file(getcwd() . '/system/main/replacecss.txt');
        $handle = fopen(getcwd() . $skin . '/' . $skinmap . '.css', 'r');
        $defcss = fread($handle, filesize(getcwd() . $skin . '/' . $skinmap . '.css'));
        fclose($handle);
        foreach ($repcss as $line) {
            $line = explode(chr(9), $line);
            $defcss = str_replace($line[0], $line[1], $defcss);
            if (isset($line[2])) {
                $line[2] = explode(";", $line[2]);
                foreach ($line[2] as $replace) {
                    $replace = explode(":", $replace);
                    if (preg_match("/$line[1] \{(?:\r\n)?(?:.+?)$replace[0]:(?:.+?);.+?(?:\r\n)?\}/im", $defcss))
                        $defcss = preg_replace("/($line[1] \{(?:\r\n)?(?:.+?)$replace[0]:)(?:.+?)(;.+?(?:\r\n)?\})/im", "$1$replace[1]$2", $defcss, 1);
                    else
                        $defcss = preg_replace("/($line[1] \{(?:\r\n)?.+?;)((?:\r\n)?\})/im", "$1$replace[0]:$replace[1]$2", $defcss, 1);
                }
            }
        }

        $handle = fopen(getcwd() . $skin . '/' . $skinmap . '.css', 'w');
        fwrite($handle, $defcss);
        fclose($handle);
    }
    //end Replace css

    $nteg = array();
    $contarr = array();

    $nm = 0;
    if (file_exists(getcwd() . $skin . '/' . $skinmap . '.map'))
        $filskin = file(getcwd() . $skin . '/' . $skinmap . '.map');
    else {
        $filskin = file("system/main/default.map");
    }

    $tegarray[0] = 'body';
    $levelNext = 0;
    $level = 0;
    $flpmenu = false;
    $i = 0;
    $tegarray = array();
    $mlcount = count($filskin);

    // START
    //  foreach($filskin as $line)
    while (isset($filskin[$i])) {
        $filskin[$i] = iconv('CP1251', 'utf8', $filskin[$i]);

        $next = 0;
        preg_match("/\((.+?)\)/", $filskin[$i], $match);

        $line = $match[1];
        $expl_tag = explode(',', $line);
        $countTage = explode('@', $expl_tag[0]);

        if (empty($countTage[1]))
            $countTage = 1;
        else
            $countTage = $countTage[1];
        $seekparam = (4 + $countTage);


        $level = countLevel($filskin[$i]);
        @$nteg[$level] = trim($expl_tag[$seekparam]);

        if ($level > 1) {
            $levelPadding = str_repeat('  ', $level - 1);
        } else {
            $levelPadding = '';
        }

        if (!empty($nteg[$level])) {
            $SE_HTML .= "\n" . '<?php if(' . getMapValue($nteg[$level]) . ' || (seData::getInstance()->editorAccess() && $_SESSION[\'siteediteditor\'])): ?>';
        }


        $countTage = explode('@', $expl_tag[0]);
        if (empty($countTage[1])) {
            $countTage = 1;
        } else
            $countTage = intval($countTage[1]);

        $seekparam = $countTage;
        $tegarray[$level] = '';
        for ($k = 1; $k <= $countTage; $k++) {
            if (!empty($expl_tag[$k]) && str_replace("\n", ' ', @$expl_tag[$k]) != ')') {
                $tmp = explode(' ', $expl_tag[$k]);
                $tegarray[$level] .= $tmp[0] . ',';
            };
        }

        $class_nm = str_replace(array('&#44;', ')'), array(',', ''), rtrim(@$expl_tag[$seekparam + 1]));
        $class_id = str_replace(')', '', rtrim(@$expl_tag[$seekparam + 2]));
        @$stext = trim($expl_tag[$seekparam + 3]);
        $endpos = strrpos($stext, ')');
        if ($endpos > 0)
            $stext = trim(substr(rtrim($stext), 0, -1));
        else
            if (substr($stext, 0, 1) == ')')
                $stext = '';
        $stext = r_tag(str_replace(array('&#10;', '&#44;', '&#040;', '&#041;'), array('', ',', '(', ')'), $stext));


        if (!empty($class_nm))
            $class_nm = ' class="' . $class_nm . '"';
        if (!empty($class_id))
            $class_id = ' id="' . $class_id . '"';

        $data_wrap = '';
        preg_match_all("/\[(content|global)-(\d{1,})\]/i", $stext, $mma, PREG_SET_ORDER);
        foreach($mma as $mm) {
            $data_wrap = ' data-wrap="' . $mm[1] . $mm[2] . '"';
            $contarr[] = ($mm[1] == 'content') ? $mm[2] : 100 + $mm[2];
        }


        if (strpos(strtolower(@$expl_tag[0]), '@') === false)
            $SE_HTML .= "\n" . $levelPadding . '<' . strtolower(r_tag($stext)) . $data_wrap . $class_nm . $class_id . '>';
        else
            for ($k = 1; $k <= $countTage; $k++) {
                if (($k == $countTage) && (!empty($expl_tag[$k])) && (@$expl_tag[$k] != ')'))
                    $SE_HTML .= "\n" . $levelPadding . '<' . strtolower(r_tag($expl_tag[$seekparam])) . $data_wrap . $class_nm . $class_id . '>';
                else
                    if (!empty($expl_tag[$k]))
                        $SE_HTML .= "\n" . $levelPadding . '<' . strtolower(r_tag($expl_tag[$k])) . $data_wrap . '>';
            };

        if (!empty($stext)) {

            $SE_HTML .= bildingValues($stext);
        }


        if ($i + 1 < $mlcount) {
            $levelNext = count(explode(chr(9), $filskin[$i + 1]));
        }
        if ($levelNext <= $level - $next)
            for ($j = $level - $next; $j >= $levelNext; $j--) {
                @$tmp = explode(',', $tegarray[$j]);
                $teg_count = count($tmp);
                if ($teg_count > 0) {
                    for ($k = $teg_count; $k >= 0; $k--)
                        if ((!empty($tmp[$k])) && (str_replace("\r\n", ' ', @$tmp[$k]) != ')'))
                            $SE_HTML .= "\n" . str_repeat('  ', $j - 1) . '</' . strtolower($tmp[$k]) . '>';
                } else
                    $SE_HTML .= "\n" . str_repeat('  ', $j - 1) . '</' . strtolower($tegarray[$j]) . '>';
                if (!empty($nteg[$j])) {
                    $SE_HTML .= "\n" . '<?php endif; ?>';
                }

            };
        $i++;
    }
    if ($level > 0)
        for ($j = $level - 1; $j > 0; $j--) {
            $tmp = explode(',', $tegarray[$j]);
            $teg_count = count($tmp);
            if ($teg_count > 0) {
                for ($k = $teg_count; $k >= 0; $k--)
                    if ((!empty($tmp[$k])) && ($tmp[$k] != ')'))
                        $SE_HTML .= "\n" . str_repeat('  ', $j - 1) . '</' . strtolower($tmp[$k]) . ">";
            } else
                $SE_HTML .= "\n" . str_repeat('  ', $j - 1) . '</' . strtolower($tegarray[$j]) . ">";
            if (!empty($nteg[$j])) {
                $SE_HTML .= "\n" . '<?php endif; ?>';
            }
        }


    $SE_HTML = preg_replace("/([\"\'])(images|skin|files)\//", "$1/" . SE_DIR . "$2/", $SE_HTML);
    $SE_HTML = trim(bildingValues($SE_HTML));
    $SE_HTML = str_replace('</body>', '<?php if (!empty(seData::getInstance()->footer)) echo "\n".replace_link(join("\n", seData::getInstance()->footer)) ?></body>', $SE_HTML);


    if (!is_dir(getcwd() . '/projects/' . SE_DIR))
        mkdir(getcwd() . '/projects/' . SE_DIR);

    if (!is_dir(getcwd() . '/projects/' . SE_DIR . 'cache'))
        mkdir(getcwd() . '/projects/' . SE_DIR . 'cache');

    if (!is_dir(getcwd() . '/projects/' . SE_DIR . 'cache'))
        mkdir(getcwd() . '/projects/' . SE_DIR . 'cache');

    $cache_file = fopen(getcwd() . '/projects/' . SE_DIR . 'cache/map_' . $skinmap . '.php', "w+");

    fwrite($cache_file, str_replace("\r", '', $SE_HTML));
    fclose($cache_file);
    file_put_contents(getcwd() . '/projects/' . SE_DIR . 'cache/map_' . $skinmap . '.json', json_encode($contarr));

    if (file_exists(getcwd() . "/system/main/sitemap.php")) {
        //create sitemap
        include_once getcwd() . "/system/main/sitemap.php";
        se_sitemap();
    }
}