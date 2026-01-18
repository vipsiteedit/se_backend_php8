<?php

//function getWorkFolder($namefile)
//{
//	return (file_exists(SE_ROOT.'projects/' . SE_DIR . 'edit/' . $namefile)
//		&& filemtime(SE_ROOT.'projects/' . SE_DIR . 'edit/' . $namefile) > filemtime(SE_ROOT.'projects/' . SE_DIR . $namefile)
//	) ? 'edit/' : '';
//}


function fmainmenu($typmenu = 0)
{
    //$se = seData::getInstance();
    $folder = getWorkFolder('mainmenu.xml');
    $menulist = simplexml_load_file(SE_ROOT . '/projects/' . SE_DIR . $folder . 'mainmenu.xml');
    //$menulist = $se->menu[0];
    $menu = new seMenu(seData::getInstance()->getPageName(), $menulist, false, $typmenu, true);
    $result = $menu->execute();
    if (seData::getInstance()->editorAccess() && !$_SESSION['siteediteditor']) {
        $result = '<div data-menu="mainmenu">' . $result . '</div>';
    }
    return $result;
}

