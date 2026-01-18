<?php
require_once dirname(__FILE__) . "/siteauthorize.php";

function se_page_no_cache()
{
    $se = seData::getInstance();
    $head = $se->page->head;

    //print_r($se->getPagename());
    if (!preg_match('/content=\"no-cache\"/im', $head)) {
        $head .= '
        <meta name="Document-state" content="Dynamic">
        <meta http-equiv="Pragma" content="no-cache">';
        $se->page->head = $head;
        //$se->setHead($head);
    }
}

function se_goto_subpage($razdel, $subname)
{
    global $_razdel, $_sub;
    $_GET['razdel'] = $razdel;
    $_razdel = $razdel;
    $_GET['sub'] = $subname;
    $_sub = $subname;
    unset($_GET['object']);
}

function authorization()
{
    global $mes_authorized,
    $mes_login_back,
    $mes_login_next,
    $mes_login,
    $mes_password;
        //$mes_noauthor;

    echo '<center>',
    '<div class="base_auth">',
    '<table border=0 class="pswtable">',
    '<tbody class="tableBody">',
    '<tr class="tableRow" id="tableHeader" valign="top">',
    '<td colspan="2" width="250">', $mes_authorized, '</td>',
    '<form action="" method="post">',
    '<tr class="tableRow" id="tableRowEven" valign="top">',
    '<td width="100">', $mes_login, '</td>',
    '<td><input type="text" name="authorlogin" value=""></td></tr>',
    '<tr class="tableRow" id="tableRowEven" valign="top">',
    '<td>', $mes_password, '</td>',
    '<td><input type="password" name="authorpassword" value=""></td></tr>',
    '<tr class="tableRow" id="tableRowEven" valign="top">',
    '<td colspan="2"><input type="submit" class="contentForm" id="buttonSend" name="authorize" value="', $mes_login_next, '>',
    '<input class="contentForm" id="buttonSend" onclick="window.history.back();" type="button" value="', $mes_login_back, '"></td></tr>',
    '</form></tbody></table></div></center>';
}

function setimages($imgfile, $width, $flobj)
{

    if (empty($imgfile))
        return;

    $imgfile = str_replace('//', '/', $imgfile);
    $imgfile = preg_replace("/(images|skin|files)\//", SE_DIR . "$1/", $imgfile);
    $ss = explode('(', $imgfile);


    if (!empty($ss[1])) {
        list($r,) = explode(')', $ss[1]);
        list($dr1, $dr2) = explode(',', $r);

        $s = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'
            . ' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"'
            . ' width="' . $dr1 . '" height="' . $dr2 . '">'
            . ' <param name="movie" value="' . $ss[0] . '">'
            . ' <param name="quality" value="high">';

        if (!empty($r[2])) {
            $s .= ' <param name="BGCOLOR" value="' . $r[2] . '">';
        }

        $s .= ' <embed src="' . $ss[0] . '" quality="high"'
            . ' pluginspage="http://www.macromedia.com/go/getflashplayer"'
            . ' stype="application/x-shockwave-flash"';

        $k = $r[0];
        if (($k != 0) && ($width != 0)) {
            $dd = $r[1];
            if ($k != 0) {
                $k = ($width * $dd) / $k;
                list($k) = explode(".", $k);
            } else {
                $k = 100;
            }

            $s .= ' width="' . $width . '" height="' . $k . '"></embed></object>';
        } else {
            $s .= ' width="' . $dr1 . '" height="' . $dr2 . '"></embed></object>';
        };
    } elseif ($flobj == 1) {
        $s = '<img alt="" border="0" class="objectImage" src="' . $imgfile . '">';
    } else {
        $s = '<img alt="" border="0" class="contentImage" src="' . $imgfile . '">';
    }

    return $s;
};

function previmg($img)
{
    return (str_replace(".", "_prev.", $img));
}


function GetCountDesk($file)
{
    return (file_exists($file)) ? filesize($file) : 0;
}

function BackPage($page)
{
    if (file_exists('back'))
        $test = file('back');

    if ($test[0] == $page)
        return true;
    else {
        $fp = fopen('back', "w+");
        fwrite($fp, $page);
        fclose($fp);
        return false;
    }
}

function GetSearhString($text, $searhtext)
{
    $i = 0;
    $d1 = explode("\n", $text);

    while (!empty($d1[$i])) {
        if ($d1[$i] == $searhtext)
            return true;
        $i++;
    }
    return false;
}

function SE_PARTSELECTOR($razdel, $count, $limit, $item, $sel)
{

    if ($limit < 1) return;
    $_page = ''; //getRequest('page');
    if ($_page == '') {
        if (class_exists('seData')) {
            $__data = seData::getInstance();
            $_page = $__data->getPageName();
        }
    }
    if (!empty($_SESSION['SE'])) {
        foreach ($_SESSION['SE'] as $page => $val) {
            list($page_,) = explode('_', $page);
            if ($page_ != $_page) {
                unset($_SESSION['SE'][$page]);
            }
        }
    }

    $item = 1;
    $sel = '';
    if ($__data->req->razdel == $razdel) {
        $_SESSION['SE'][$_page . '_' . $razdel . '_item'] = $item = getRequest('item');
        $_SESSION['SE'][$_page . '_' . $razdel . '_sel'] = $sel = getRequest('sel');
    } elseif (!empty($_SESSION['SE'])) {
        $it = $_SESSION['SE'];
        $item = $it[$_page . '_' . $razdel . '_item'];
        $sel = $it[$_page . '_' . $razdel . '_sel'];
    }

    $link = seMultiDir() . '/' . $_page . '/' . $razdel . '/';

    if ($__data->req->sub) $link .= 'sub' . $__data->req->sub . '/';
    if (isRequest('arhiv')) $link .= 'arhiv/';

    if ($count > $limit) {
        if ($item < 1)
            $item = 1;

        $step = floor(($item - 1) / 10);
        $j = ($step * 10 + 1);

        if ($sel != '')
            $sel = '&sel=' . $sel;

        $links = '<a name="sm' . $razdel . '"></a><div id="navPart">';

        $listcount = ($limit > 0) ? ceil($count / $limit) : 0;

        if ($step > 0) {
            $links .= '<a class="fnts" id="Beg" href="' . $link . '?item=1' . $sel . '#sm' . $razdel . '">1</a>'
                . '&nbsp;&nbsp;'
                . '<a class="fnts" id="Back" href="' . $link . '?item=' . (($step - 1) * 10 + 1) . $sel
                . '#sm' . $razdel . '">&nbsp;...</a>'
                . '&nbsp;';
        }

        $j = $step * 10 + 1;

        while ($j <= $listcount) {
            if (($j - $step * 10) > 10)
                break;

            if ($j == $item)
                $links .= '<b class="Active">' . $j . '</b>&nbsp;';
            else
                $links .= '<a class="links" href="' . $link . '?item=' . $j . $sel . '#sm' . $razdel . '">' . $j . '</a>&nbsp;';
            $j++;
        }

        if ((($step + 1) * 10 * $limit) < $count) {
            $links .= '&nbsp;'
                . '<a class="fnts" id="Next" href="' . $link . '?item=' . (($step + 1) * 10 + 1) . $sel
                . '#sm' . $razdel . '">...&nbsp;</a>'
                . '&nbsp;&nbsp;'
                . '<a class="fnts" id="End" href="' . $link . '?item=' . $listcount . $sel . '#sm' . $razdel . '">' . $listcount . '</a>';
        }

        $links .= '</div>';

        return $links;
    } else
        return;
}

function logic($val)
{

    $val = str_replace(array("\r\n", '&gt;', '&lt;'), array('', '>', '<'), $val);
    $larr = array('==', '!=', '>', '>=', '<', '<=');

    $result = false;

    $val = strtolower(str_replace(array('"', '\''), array('', ''), $val));
    if ((trim($val) == '') or ($val == '0') or ($val == 'no') or ($val == 'false'))
        return;
    if (($val == '1') or ($val == 'yes') or ($val == 'true')) {
        return true;
    }

    $id_larr = 255;

    for ($i = 0; $i < 6; $i++) {
        if (strpos($val, $larr[$i]))
            $id_larr = $i;
    }

    if ($id_larr == 255)
        return false;
    $lar = explode($larr[$id_larr], $val);

    switch ($id_larr) {
        case 0:
            if ($lar[0] == $lar[1])
                return true;
            break;
        case 1:
            if ($lar[0] != $lar[1])
                return true;
            break;
        case 2:
            if ($lar[0] > $lar[1])
                return true;
            break;
        case 3:
            if ($lar[0] >= $lar[1])
                return true;
            break;
        case 4:
            if ($lar[0] < $lar[1])
                return true;
            break;
        case 5:
            if ($lar[0] <= $lar[1])
                return true;
    }
    return false;
}

function conditions($strin)
{
    //$strin = str_replace("\r\n",'',$strin);
    while (preg_match("/@notif\(([^\}]{2,})\}/im", $strin, $m)) {
        // $m[1] = str_replace("\r\n",'',$m[1]);
        if (!empty($m[1])) {
            list($val) = explode(')', $m[1]);
            list(, $res) = explode('{', $m[1]);
            list($res) = explode('}', $res);

            if (!logic($val)) {
                $strin = str_replace($m[0], $res, $strin);
            } else
                $strin = str_replace($m[0], '', $strin);
        }
    }


    while (preg_match("/@if\(([^\}]{2,})\}/im", $strin, $m)) {

        //$m[1] = str_replace("\r\n",'',$m[1]);
        if (!empty($m[1])) {

            list($val) = explode(')', $m[1]);
            list(, $res) = explode('{', $m[1]);
            list($res) = explode('}', $res);

            if (logic($val))
                $strin = str_replace($m[0], $res, $strin);
            else
                $strin = str_replace($m[0], '', $strin);
        }
    }
    while (preg_match("/<if:([^\>]+)>(.+?)<\/if>/usm", $strin, $m)) {
        if ($m[1] != '' && logic(trim($m[1])))
            @list($m[2],) = explode('<else>', $m[2]);
        else
            @list(, $m[2]) = explode('<else>', $m[2]);
        $strin = str_replace($m[0], $m[2], $strin);
    }


    return str_replace(array('&#123;', '&#125;'), array('{', '}'), $strin);
}


function sePicture($imgfile)
{
    list($flname, $flwidth, $flheight, $flcolor) = explode(":", $imgfile, 4);

    if (strpos($flname, ".swf") !== false) {
        $s = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'
            . 'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"'
            . 'width="' . $flwidth . '" height="' . $flheight . '">'
            . '<param name="allowScriptAccess" value="sameDomain" />'
            . '<param name="movie" value="' . $flname . '" /><param name="quality" value="high" />'
            . '<param name="quality" value="high" />';

        if (!empty($flcolor) > 0)
            $s .= '<param name="bgcolor" value="' . $flcolor . '" />';

        $s .= '<embed src="' . $flname . '" quality="high" bgcolor="' . $flcolor . '"'
            . 'width="' . $flwidth . '" height="' . $flheight
            . '" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
    } else
        $s = '<img alt="" border=0 class="contentImage" src="' . $imgfile . '">';

    return ($s);
}

function replace_link($stext)
{
    $se = seData::getInstance();
    while (preg_match("/([\"\'=])([\w\d\-_]+)\.html/u", $stext, $m)) {
        if ($m[2] == $se->startpage) {
            $stext = str_replace($m[0], $m[1] . seMultiDir() . URL_END, $stext);
        } else {
            $stext = str_replace($m[0], $m[1] . seMultiDir() . '/' . $m[2] . URL_END, $stext);
        }
    }
    //$stext = preg_replace("/([\"\'=])([\w\d\-_]+)\.html/u", "$1".seMultiDir()."/$2/", $stext);
    $stext = str_replace('[system.path]', '/lib/', $stext);
    $stext = str_replace(array('&#124;', '&#10;', "\n\n"), array('|', "\n", "\n"), $stext);
    $stext = preg_replace("/([\"\'\(])(images|skin|files)\//", "$1/" . SE_DIR . "$2/", $stext);

    while (preg_match("/\[%(site[\w\d]{1,})%\]/i", $stext, $mm)) {
        $val = (!empty($se->prj->vars->{$mm[1]})) ? strval($se->prj->vars->{$mm[1]}) : '';
        $stext = str_replace($mm[0], $val, $stext);
    }

    if (SE_DB_ENABLE && file_exists(SE_LIBS . 'plugins/plugin_geo/plugin_geovalues.class.php')) {
        $gval = plugin_geovalues::getInstance();
        $stext = $gval->parseValues($stext);
    }
    return $stext;
}

function seLogotype($logo)
{
    if (empty($logo))
        return;

    $se = seData::getInstance();
    if (!empty($se->prj->vars->sitetitle))
        $title = str_replace('"', '&quot;', strip_tags($se->prj->vars->sitetitle));

    list($fimg) = explode('"', $logo);

    if (utf8_substr($fimg, 0, 1) != '/')
        $fimg = "/" . SE_DIR . $fimg;

    $ss = explode("(", $fimg);

    if (strpos(@$ss[0], ".swf") !== false) {
        $imgs = '<div id="siteLogotype" onclick="location.href=\'' . seMultiDir() . '/\';"'
            . '>' . setimages($fimg, 0, 1)
            . '</div>';
    } else {
        $imgs = '<a href="' . seMultiDir() . '/"><img id="siteLogotype" src="' . $ss[0] . '" border="0" alt="' . $title . '"></a>';
    }

    return $imgs;
}

function skin_news($news, $id = 'news')
{
    if (empty($news))
        return;

    if (utf8_strpos($news, '&#8;') !== false)
        $newstitle = explode('&#8;', $news);
    elseif (utf8_strpos($news, '&#124;') !== false)
        $newstitle = explode('&#124;', $news);
    elseif (utf8_strpos($news, chr(8)) !== false)
        $newstitle = explode(chr(8), $news);

    return '<h2 id="' . $id . 'Title">' . @$newstitle[0] . '</h2>'
        . '<div id="' . $id . 'Text id="newstitle">' . @$newstitle[1] . '</div>';
}

function parseTemplateMenu($text, $items, $sub = '')
{
    if (strpos($text, 'base64:') !== false) {
        $text = base64_decode(str_replace('base64:', '', $text));
    }
    while (preg_match("/<repeat:{$sub}items>(.+?)<\/repeat:{$sub}items>/umis", $text, $mm)) {
        $m0 = $mm[0];
        $m1 = $mm[1];
        $res = '';
        if (preg_match("/<repeat:{$sub}subitems>(.+?)<\/repeat:{$sub}subitems>/umis", $m1, $mm)) {
            $stek = $mm[0];
            $m1 = preg_replace("/<repeat:{$sub}subitems>(.+?)<\/repeat:{$sub}subitems>/umis", '[REPEATSUBITEMS]', $m1);
        }
        foreach ($items as $item) {
            $mr = $m1;
            while (preg_match("/<noempty:\[?menuitem\.([\w\d\-\_\.]+)\]?>(.+?)<\/noempty>/umis", $mr, $mm)) {
                if (empty($item->{$mm[1]})) {
                    $mr = str_replace($mm[0], '', $mr);
                }
            }
            while (preg_match("/<empty:\[?menuitem\.([\w\d\-\_\.]+)\]?>(.+?)<\/empty>/umis", $mr, $mm)) {
                if (!empty($item->{$mm[1]})) {
                    $mr = str_replace($mm[0], '', $mr);
                }
            }
            $mr = str_replace('[menuitem.name]', $item->name, $mr);
            $mr = str_replace('[menuitem.url]', $item->url, $mr);
            $mr = str_replace('[menuitem.title]', $item->title, $mr);
            $mr = str_replace('[menuitem.level]', $item->level, $mr);
            $mr = str_replace('[menuitem.image]', $item->image, $mr);
            $mr = str_replace('[menuitem.ids]', $sub, $mr);

            if (!empty($item->item)) {
                $mr = str_replace('[menuitem.items]', '1', $mr);
            } else {
                $mr = str_replace('[menuitem.items]', '0', $mr);
            }

            if (!empty($item->item)) {
                $mr = str_replace('[REPEATSUBITEMS]', parseTemplateMenu($stek, $item->item, $sub . 'sub'), $mr);
            } else {
                $mr = str_replace('[REPEATSUBITEMS]', '', $mr);
            }

            $res .= conditions($mr);
        }
        $text = str_replace($m0, $res, $text);
    }
    return $text;
}


function mainMenu($typemenu)
{
    return fmainmenu($typemenu);
}

function pageMenu()
{
    $se = seData::getInstance();
    $ncss = '/' . $se->getSkinService() . '/' . $se->page->css . ".stk";

    $flag_nodat = true;

    if (file_exists(getcwd() . $ncss)) {
        $pmenu = file(getcwd() . $ncss);
        foreach ($pmenu as $line) {
            @list($line, $drivemenu) = explode(':', $line);
            $drivemenu = (!empty($drivemenu));

            if (preg_match("/\bpagemenu-(.+?)\b/i", $line, $m)) {
                if ($m[1] == 'full' || $m[1] == 'horiz' || $m[1] == 'vert' || $m[1] == 'hstat' || $m[1] == 'vstat')
                    $flpmen = true;

                $flag_nodat = false;

                if ($m[1] == 'ntree')
                    return fpagemenu(-1, array(), $drivemenu);
                elseif ($m[1] == 'full')
                    return fpagemenu(0, array(), $drivemenu);
                elseif ($m[1] == 'horiz')
                    return fpagemenu(3, array(), $drivemenu); //2
                elseif ($m[1] == 'vert')
                    return fpagemenu(4, array(), $drivemenu); //1
                elseif ($m[1] == 'hstat')
                    return fpagemenu(1, array(), $drivemenu); //4
                elseif ($m[1] == 'vstat')
                    return fpagemenu(2, array(), $drivemenu); //3
                else
                    return fpagemenu(0, array(), $drivemenu);

                break;
            }
        }
    }

    if ($flag_nodat)
        return fpagemenu(-1);
}

function replace_values($stext)
{
    $se = seData::getInstance();
    while (preg_match("/\[%(site[\w\d]{1,})%\]/i", $stext, $mm)) {
        $val = (!empty($se->prj->vars->{$mm[1]})) ? strval($se->prj->vars->{$mm[1]}) : '';
        $stext = str_replace($mm[0], $val, $stext);
    }
    while (preg_match("/\[menu.item-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[menu.item-" . $mm[1] . "]", ItemsMenu($mm[1]), $stext);
    $res = '';
    while (preg_match("/\[(site|page|menu|img|link)\.(.+?)\]/i", $stext, $mm)) {
        $mm[1] = strtolower($mm[1]);
        if (($mm[1] == 'site') or ($mm[1] == 'page')) {
            $dataval = @$mm[2];
            if ($mm[1] == 'site') {
                if ($mm[2] == 'authorizeform')
                    $res = seAuthorize($se->prj->vars->authorizeform);
                elseif ($mm[2] == 'sitelogotype')
                    $res = seLogotype($se->prj->vars->sitelogotype);
                elseif ($mm[2] == 'newsform')
                    $res = skin_news($se->prj->vars->newsform);
                else
                    $res = $se->prj->vars->$dataval;
            } else {
                if (!empty($se->page->vars->$dataval))
                    $res = $se->page->vars->$dataval;
                elseif (!empty($se->page->$dataval))
                    $res = $se->page->$dataval;
            }
        } else {
            $res = '';

            if ($mm[1] == 'img')
                $res = sePicture($mm[2]);

            if ($mm[1] == 'menu') {
                if ($mm[2] == 'mainmenu')
                    $res = fmainmenu(0);

                if ($mm[2] == 'mainhoriz')
                    $res = fmainmenu(1);

                if ($mm[2] == 'mainvert')
                    $res = fmainmenu(2);

                if ($mm[2] == 'pagemenu')
                    $res = pageMenu();
            }
        }

        if ($mm[1] == 'link' && $dataval == 'breadcrumb') {
            $res = $se->getPathLinks();
        }
        $stext = str_replace("[" . $mm[1] . "." . $mm[2] . "]", $res, $stext);
    }

    while (preg_match("/\[content-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[content-" . $mm[1] . "]", se_getContainer($mm[1]), $stext);

    while (preg_match("/\[section\=([\w]+)\(([\d\.\,]+)\)\]/i", $stext, $mm)) {
        $stext = str_replace($mm[0], se_getSection($mm[2]), $stext);
    }


    while (preg_match("/\[global-(\d{1,})\]/i", $stext, $mm))
        $stext = str_replace("[global-" . $mm[1] . "]", se_getContainer($mm[1] + 100), $stext);

    $stext = str_replace("[NAMEUSER]", (string)seUserName(), $stext);
    $stext = str_replace('[this_url]', _HOST_ . "/", $stext);
    $stext = conditions($stext);

    return replace_link($stext);
}


function se_getSection($section_id)
{
    global $site_res, $mes_noauthor;
    $result = '';
    $se = seData::getInstance();
    if (seUserAccess($se->getPageName())) {
        $md = new seModule39($section_id);
        $result = $md->execute(0);
        unset($md);
        return replace_link($result);
    }
}


function se_getContainer($cont)
{
    global $site_res, $mes_noauthor;

    $result = '';
    $se = seData::getInstance();

    if (seUserAccess($se->getPageName())) {
        $content = new seContent($cont);
        $result = $content->execute();
        unset($content);
    } else {
        if ($cont == 0 && $se->page->groupslevel < 4) {
            $result = seAuthorizeForm();
        } else
            $result = '';
    }
    if (utf8_strpos($result, '[') !== false) {
        $result = replace_values($result);
    }
    return replace_link($result);
}

function se_getVars($type, $name)
{

    $result = '';
    if ($name == 'enteringtext' || $name == 'closingtext' || $name == 'title') {
        $result = seData::getInstance()->page->$name;
    } else {
        $result = seData::getInstance()->$type->vars->$name;
        if ($name == 'newsform' || $name == 'reklamform') {
            $result = skin_news($result);
        }
    }

    if (utf8_strpos($result, '[') !== false) {
        $result = replace_values($result);
    }
    return replace_link($result);
}

function se_checkVars($type, $name)
{

    if ($name == 'enteringtext' || $name == 'closingtext') {
        return (strval(seData::getInstance()->page->$name) != '');
    } else {
        return (strval(seData::getInstance()->$type->vars->$name) != '');
    }
}
