<?php

require_once SE_CORE . 'convertor/skin_construction_cache.php';
require_once SE_CORE . 'sitepagemenu.php';
require SE_CORE . 'classes/seContent.class.php';

require_once SE_CORE . 'sitemainmenu.php';
require_once SE_CORE . 'siteauthorize.php';
//include SE_CORE . 'service.php';


// INDEX -------------------------------------------------------------------------------------------------------------


// Логаут пользователя
if (isRequest('logout') || (isRequest('login') && 1 == getRequest('login'))) {
    // уничтожение сессии
    check_session(true);
    $site_res = false;

    setcookie("authorlogin", "", time() - 3600 * 24 * 30, "/");
    setcookie("authorpassword", "", time() - 3600 * 24 * 30, "/");
    header("Location: ?" . time());
    exit;
}

if (isset($_COOKIE['authorlogin'], $_COOKIE['authorpassword']) && !seUserGroup()) {
    check_session(true);
    $clogin = $_COOKIE['authorlogin'];
    $cpassword = $_COOKIE['authorpassword'];
    $site_res = GetLogin(true, $clogin, $cpassword, $se->prj->adminlogin, $se->prj->adminpassw, $se->page->groupsname, -1);
    if ($site_res) {
        $SESSION_VARS['AUTH_USER'] = $clogin;
        $SESSION_VARS['AUTH_PW'] = $cpassword;
        header("Location: ?" . time());
    } else {
        setcookie("authorlogin", "", 0, "/");
        setcookie("authorpassword", "", 0, "/");
    }
}

// Activate new user
auth_activate();

if (isRequest('authorize') && isRequest('authorlogin')) {
    check_session(true);
    $_authorpassword = trim(getRequest('authorpassword', 3));
    $_authorlogin = trim(getRequest('authorlogin', 3));

    //  cookie
    if (isset($_POST['authorSaveCheck'])) {
        setcookie("authorlogin", $_authorlogin, time() + 3600 * 24 * 30, "/");
        setcookie("authorpassword", md5($_authorpassword), time() + 3600 * 24 * 30, "/");
    }

    $site_res = GetLogin(true, $_authorlogin, md5($_authorpassword),
        $se->prj->adminlogin, $se->prj->adminpassw, $se->page->groupsname, -1);

    if ($site_res) {
        $SESSION_VARS['AUTH_USER'] = $_authorlogin;
        $SESSION_VARS['AUTH_PW'] = md5($_authorpassword);
        $linkstr = str_replace('login', '', $_SERVER["QUERY_STRING"]);
        $linkstr = UrlToLine($linkstr);
        header("Location: " . $linksstr . '?' . time());
    } else {
        $fl_messerr = true;
    }
}

// !!!!!!!!!!!!!!!!!!!!!!!!!!!if ($_object != "") && ($view)

//# Constructor ############################
$skinfolder = $se->getSkinService();
$skinmap = $se->page->css . '.map';
if (isRequest('sub') && isset($se->sections[getRequest('razdel', 1)]->subpage[getRequest('sub')])) {
    $subpage = $se->sections[getRequest('razdel', 1)]->subpage[getRequest('sub')]->form;
} else $subpage = '';
if (isRequest('sub') && (utf8_strpos($subpage, '{NOBODY}') !== false)) {
    $subpage = $se->sections[getRequest('razdel', 1)]->subpage[getRequest('sub')]->form;
    $subname = getRequest('sub', 1);
    $se->sections[getRequest('razdel', 1)]->subpage->$subname->form =
        str_replace('{NOBODY}', '', $se->sections[getRequest('razdel', 1)]->subpage->$subname->form);

    $result = replace_values('[content-0]');
    if (!preg_match("/<BODY/m", $result, $m))
        $result = '<body>' . $result;
    $result .= $se->footer;
    if (!preg_match("/<\/BODY/m", $result, $m))
        $result .= '</body>';
    echo $result;
} else {
    if (file_exists('projects/' . SE_DIR . 'cache/folder')) {
        $checkskin = join(file('projects/' . SE_DIR . 'cache/folder'));
    } else $checkskin = '';
    if (!file_exists('projects/' . SE_DIR . 'cache/map_' . basename($skinmap, '.map') . '.php')
        || (filemtime('projects/' . SE_DIR . 'cache/map_' . basename($skinmap, '.map') . '.php') < filemtime(SE_ROOT . $skinfolder . '/' . $skinmap))
        || (filemtime('projects/' . SE_DIR . 'cache/map_' . basename($skinmap, '.map') . '.php') < filemtime(SE_CORE . 'convertor/skin_construction_cache.php'))
        || ($checkskin != $skinfolder)
    ) {
        bildingSkinCache(SE_ROOT . $skinfolder . "/" . $skinmap);
        $fp = fopen('projects/' . SE_DIR . 'cache/folder', "w+");
        fwrite($fp, $skinfolder);
        fclose($fp);

        @unlink(SE_DIR . 'skin/ver39.ini');
        $d = opendir('./' . SE_DIR);
        while (($f = readdir($d)) !== false) {
            if (!utf8_strpos($f, '.phtml')) continue;
            unlink(SE_DIR . $f);
        }
        closedir($d);
    }
    if (isRequest('login')) {
        include dirname(__FILe__) . '/loginform.php';
    }
    include "system/main/sitehead.php";
    include 'projects/' . SE_DIR . 'cache/map_' . basename($skinmap, '.map') . '.php';
    if (isset($__logincontent)) {
        echo $__logincontent;
    }
    include "system/main/sitefooter.php";
}
