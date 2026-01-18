<?php
require_once(dirname(__FILE__) . '/classes/seMenu.class.php');
require_once(dirname(__FILE__) . '/classes/seMenuTree.class.php');


function DelExtendFile($name)
{
    list($name) = explode('.', $name);
    return $name;
}

function getItemMenu($itmenu, $mcount = -1, $mtitle = '...')
{
    $se = seData::getInstance();
    $skin = '/' . $se->getSkinService();

    if ($itmenu == 'pmenu' || $itmenu == 'mmenu') {
        if ($itmenu == 'pmenu') {
            $menulist = simplexml_load_file(SE_ROOT . '/projects/' . SE_DIR . 'pagemenu.xml');
        } else {
            $menulist = simplexml_load_file(SE_ROOT . '/projects/' . SE_DIR . 'mainmenu.xml');
        }

        return array($menulist, 0, 1);
    }

    $is_utf = false;
    if (file_exists('./' . $skin . '/item' . $itmenu . '.mmit')) {
        $list = file('./' . $skin . '/item' . $itmenu . '.mmit');
        $is_utf = true;
    } elseif (file_exists('./' . $skin . '/item' . $itmenu . '.mlst')) {
        $list = file('./' . $skin . '/item' . $itmenu . '.mlst');
    }
    if (count($list) > 0) {
        if ($is_utf) {
            list(, $tp, $drive) = explode("\t", $list[0]);
        } else {
            list(, $tp, $drive) = explode("\t", iconv('CP1251', 'UTF-8', $list[0]));
        }
        $drivemenu = (trim($drive) == '1');
        $list = array_splice($list, 1, count($list) - 1);
        $newlist = array();
        $levelcnt = 0;
        foreach ($list as $line) {
            if ($line[0] == 0) $levelcnt++;
        }

        $lcnt = 0;
        foreach ($list as $line) {
            if ($is_utf) {
                $line = explode("\t", $line);
            } else {
                $line = explode("\t", iconv('CP1251', 'UTF-8', $line));
            }
            $title = '';
            $stitle = '';
            if (isset($line[2])) {
                $title_parts = explode('|', $line[2], 2);
                $title = $title_parts[0];
                $stitle = isset($title_parts[1]) ? $title_parts[1] : '';
            }
            if ($mcount > 0 && $levelcnt > $mcount) {
                if ($line[0] == 0) $lcnt++;
                if ($lcnt == $mcount) {
                    $newlist[] = '1|nexts|#|' . $mtitle . '|||1||||';
                }
                if ($lcnt >= $mcount) {
                    $line[0] += 1;
                }
            }

            $newlist[] = ($line[0] + 1) . '|' . $line[1] . '|' . $line[1] . '|' . $title . '|||1|' . $line[3] . chr(8) . $line[4] . '||' . $line[5] . '|' . $stitle;
        }
        $menutree = new seMenuTree();
        $menu = $menutree->execute($newlist);
        return array($menu, $tp, $drivemenu);
    }
}


function ItemsMenu($itmenu)
{
    $tpconv = array(0 => 0, 1 => 4, 2 => 3, 3 => 2, 4 => 1, 5 => -1);
    $se = seData::getInstance();
    $skin = '/' . $se->getSkinService();

    list($menu, $tp, $drivemenu) = getItemMenu($itmenu);
    $result = '<div id="pageMenu">' . fpagemenu($tpconv[intval($tp)], $menu, $drivemenu, false) . '</div>';
    if ($se->editorAccess() && empty($_SESSION['siteediteditor'])) {
        $result = '<div data-menu="item-' . $itmenu . '">' . $result . '</div>';
    }
    return $result;
}

function getWorkFolder($namefile)
{
    return (file_exists(SE_ROOT . 'projects/' . SE_DIR . 'edit/' . $namefile)
        && filemtime(SE_ROOT . 'projects/' . SE_DIR . 'edit/' . $namefile) > filemtime(SE_ROOT . 'projects/' . SE_DIR . $namefile)
    ) ? 'edit/' : '';
}


function fpagemenu($typmenu = 0, $menulist = array(), $drivemenu = false, $multi = true)
{
    if (empty($menulist)) {
        $folder = getWorkFolder('pagemenu.xml');
        $menulist = simplexml_load_file(SE_ROOT . '/projects/' . SE_DIR . $folder . 'pagemenu.xml');
    }
    $menu = new seMenu(seData::getInstance()->getPageName(), $menulist, true, $typmenu, $multi);
    $result = $menu->execute($drivemenu);
    if (seData::getInstance()->editorAccess() && empty($_SESSION['siteediteditor'])) {
        $result = '<div data-menu="pagemenu">' . $result . '</div>';
    }
    return $result;
}
