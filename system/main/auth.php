<?php

function auth_activate()
{
    if (isRequest('activate_code') && isRequest('login')) {
        if (seUserId()) return;
        if (!SE_DB_ENABLE) return;
        $activate_code = getRequest('activate_code');
        $activate_login = getRequest('login');
        if (substr(md5($activate_login . "USymlQpSK"), 0, 16) != $activate_code) return;

        $user = new seUser();
        $user->Where("LOWER(`username`) = '?'", $activate_login);
        $user->andWhere("is_active = 'N'");
        $user->fetchOne();
        if ($user->id) {
            $user->is_active = 'Y';
            $user->last_login = date("Y-m-d H:i:s");
            $user->save();

            check_session(true);
            $usergroup = $user->getAccess();
            foreach ($usergroup as $access) break;
            $person = $user->getPerson();
            $SESSION_VARS['IDUSER'] = $user->id;
            $SESSION_VARS['GROUPUSER'] = $access['level'];
            $SESSION_VARS['USER'] = trim($person->last_name . ' ' . $person->first_name);
            $SESSION_VARS['ADMINUSER'] = $access['name'];
            check_session(false, $SESSION_VARS);
        }
    }
}

function get_admin($admin, $a_admin, $login)
{
    $login = trim(utf8_strtolower($login));
    $a_admin = trim(utf8_strtolower($a_admin));
    $adm = explode(';', utf8_strtolower($admin));
    foreach ($adm as $l) {
        $l = trim($l);
        if (empty($l)) {
            continue;
        }

        if ($l == '*' . $login || ($a_admin != "" && $l == $a_admin)) {
            return true;
        }
    }
    return false;
}

function getLoginAccess($group, $namegroup, $userGroup, $userGroupName, $userLogin)
{
    return (
        ($userGroup == $group && (get_admin($namegroup, $userGroupName, $userLogin) || trim($namegroup) == ''))
        || ($group == 4 && !get_admin($namegroup, $userGroupName, $userLogin))
        || ($group == 5 && $userGroup == 0)
        || ($group <= 0)
        || ($userGroup > $group && $userGroup < 4));
}


function seAuthAdmin()
{
    $auth['IDUSER'] = 0;
    $auth['GROUPUSER'] = 3;
    $auth['ADMINUSER'] = '';
    $auth['USER'] = 'Administrator';
    check_session(false, $auth);
}

function GetLogin($ch, $login, $password, $ladmin, $padmin, $admin, $group)
{
    global $SESSION_VARS;

    $login = utf8_strtolower(htmlspecialchars($login, ENT_QUOTES));
    $password = htmlspecialchars($password, ENT_QUOTES);
    $ladmin = htmlspecialchars(trim($ladmin), ENT_QUOTES);
    $padmin = htmlspecialchars($padmin, ENT_QUOTES);
    $admin = htmlspecialchars(trim($admin), ENT_QUOTES);
    $group = htmlspecialchars($group, ENT_QUOTES);

    $SESSION_VARS['AUTH_USER'] = $login;
    $SESSION_VARS['AUTH_PW'] = $password;

    if ($ch == 0) {
        return true;
    }

    if (($login == utf8_strtolower($ladmin)) && (utf8_strtolower($password) == utf8_strtolower($padmin))) {
        if ((empty($login) && empty($password)) or empty($ladmin)) {
            return false;
        }
        seAuthAdmin();
        return true;
    }

    if (!SE_DB_ENABLE) return false;

    $user = new seUser();
    $user->where("(`password` = '$password' OR `tmppassw` = '$password')");
    $user->andWhere("LOWER(`username`) = '?'", $login);
    $user->andWhere("is_active = 'Y'");
    $user->fetchOne();
    if ($user->id) {
        $usergroup = $user->getAccess();
        foreach ($usergroup as $access) break;

        $person = $user->getPerson();
        if (getLoginAccess($group, $admin, $access['level'], $access['name'], $login)) {
            $SESSION_VARS['IDUSER'] = $user->id;
            $SESSION_VARS['EMAIL'] = $person->email;
            $SESSION_VARS['GROUPUSER'] = $access['level'];
            $SESSION_VARS['USER'] = trim($person->last_name . ' ' . $person->first_name);
            $SESSION_VARS['ADMINUSER'] = $access['name'];
            check_session(false, $SESSION_VARS);
            $user->last_login = date("Y-m-d H:i:s");
            $user->save();
            return true;
        }
    }
    unset($SESSION_VARS);
    return false;
}

function check_session($logout, $arr_auth = array())
{
    global $SESSION_VARS, $SESSIONBLOCK, $ID_AUTHOR, $GR_AUTHOR, $ADM_AUTHOR;

    if (!empty($arr_auth)) {
        $SESSION_VARS = $arr_auth;
    }
    if (SE_DB_ENABLE) {
        if (!file_exists(SE_ROOT . 'system/logs/session.upd')) {
            se_db_query("
		CREATE TABLE IF NOT EXISTS `session` (
		`SID` varchar(32) NOT NULL DEFAULT '',
		`TIMES` int(11) DEFAULT NULL,
		`IDUSER` bigint(20) NOT NULL DEFAULT '0',
		`GROUPUSER` int(11) NOT NULL DEFAULT '0',
		`ADMINUSER` varchar(10) DEFAULT '',
		`USER` varchar(40) DEFAULT '',
		`LOGIN` varchar(30) DEFAULT '',
		`PASSW` varchar(32) DEFAULT '',
		`PAGES` varchar(30) DEFAULT '',
		`BLOCK` char(1) DEFAULT 'Y',
		`IP` varchar(15) DEFAULT '',
		PRIMARY KEY (`SID`),
		KEY `TIMES` (`TIMES`),
		KEY `GROUPUSER` (`GROUPUSER`),
		KEY `IDUSER` (`IDUSER`),
		KEY `IP` (`IP`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            if (!is_dir(SE_ROOT . 'system/logs')) mkdir(SE_ROOT . 'system/logs');
            $fp = fopen(SE_ROOT . 'system/logs/session.upd', "w+");
            fclose($fp);
        }

        $times = time();
        $newtimes = $times - 3600;
        $daytimes = $times - 3600 * 24;

        $ip = $_SERVER['REMOTE_ADDR'];
        $it_session = session_id();
        if ($logout) {
            se_db_query("DELETE from `session` where `SID`='$it_session' and `IP` = '$ip'");
            unset($SESSION_VARS);
        } else {
            se_db_query("DELETE from `session` where (`TIMES`<'$newtimes') OR (`TIMES`<'$daytimes')");
            if (se_db_error()) {
                unlink(SE_ROOT . 'system/logs/session.upd');
            }


            $mysql = se_db_query("select * from `session` where `SID` = '$it_session' and `IP` = '$ip'");
            if (se_db_num_rows($mysql) > 0) {


                $PAGES = "";
                $sess = se_db_fetch_array($mysql);

                if ($sess['PAGES'] == getRequest('page')) {
                    $BLOCK = $sess['BLOCK'];
                } else {
                    if ($SESSIONBLOCK != "") {
                        $BLOCK = "Y";
                        $PAGES = getRequest('page');
                    } else
                        $BLOCK = "N";
                }

                $SESSION_VARS['SID'] = $sess['SID'];
                $SESSION_VARS['TIMES'] = $sess['TIMES'];
                $ID_AUTHOR = $SESSION_VARS['IDUSER'] = $sess['IDUSER'];
                $GR_AUTHOR = $SESSION_VARS['GROUPUSER'] = $sess['GROUPUSER'];
                $ADM_AUTHOR = $SESSION_VARS['ADMINUSER'] = $sess['ADMINUSER'];
                $SESSION_VARS['USER'] = $sess['USER'];
                $SESSION_VARS['AUTH_USER'] = $sess['LOGIN'];
                $SESSION_VARS['AUTH_PW'] = $sess['PASSW'];
                $SESSION_VARS['IP'] = $sess['IP'];
                se_db_query("UPDATE `session` SET `TIMES` = '$times',`PAGES` = '$PAGES',`BLOCK`='$BLOCK' WHERE `SID`='$it_session'");

            } else {
                if (
                    !empty($SESSION_VARS)
                    && ((isset($SESSION_VARS['GROUPUSER']) && $SESSION_VARS['GROUPUSER'] > 0) || !empty($SESSION_VARS['IDUSER']))
                ) {

                    $ID_AUTHOR = $s1 = $SESSION_VARS['IDUSER'];
                    $GR_AUTHOR = $s2 = $SESSION_VARS['GROUPUSER'];
                    $ADM_AUTHOR = $s3 = $SESSION_VARS['ADMINUSER'];
                    $s4 = $SESSION_VARS['USER'];
                    $s5 = $SESSION_VARS['AUTH_USER'];
                    $s6 = $SESSION_VARS['AUTH_PW'];

                    se_db_query("INSERT INTO `session`
								(`SID`,`TIMES`,`IDUSER`,`GROUPUSER`,`ADMINUSER`,`USER`,`LOGIN`,`PASSW`,`IP`)
								 values('$it_session','$times','$s1','$s2','$s3','$s4','$s5','$s6','$ip')");
                } else
                    unset($SESSION_VARS);
            }
        }
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
        $it_session = session_id();
        if ($logout) {
            unset($SESSION_VARS, $_SESSION['__SE_AUTH']);
        } else {
            if (isset($_SESSION['__SE_AUTH']) && !isset($SESSION_VARS)) {
                $SESSION_VARS = $_SESSION['__SE_AUTH'];
            } else {
                if (isset($SESSION_VARS))
                    $_SESSION['__SE_AUTH'] = $SESSION_VARS;
            }
        }
    }
}

// Получить уровень доступа пользователф
function seUserGroup()
{
    global $SESSION_VARS;
    if (isset($SESSION_VARS['GROUPUSER'])) {
        return intval($SESSION_VARS['GROUPUSER']);
    }
    return false;
}

//Получить ID учетной записи пользователя
function seUserId()
{
    if (isset($SESSION_VARS['IDUSER'])) {
        return intval($SESSION_VARS['IDUSER']);
    }
    return false;
}

//Полцчить наименование группы
function seUserGroupName()
{
    if (isset($SESSION_VARS['ADMINUSER'])) {
        return strval($SESSION_VARS['ADMINUSER']);
    }
    return '';
}

//Получить имя пользователя
function seUserName()
{
    global $SESSION_VARS;
    $user_id = seUserId();
    if ($user_id && SE_DB_ENABLE) {
        $person = new sePerson();
        $person->find($user_id);
        return trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
    } else
        if (seUserGroup()) {
            return 'Administrator';
        }
}

// Получить логин пользователя
function seUserLogin()
{
    global $SESSION_VARS;
    return isset($SESSION_VARS['AUTH_USER']) ? $SESSION_VARS['AUTH_USER'] : '';
}

function seUserEmail()
{
    global $SESSION_VARS;
    return isset($SESSION_VARS['EMAIL']) ? $SESSION_VARS['EMAIL'] : '';
}

function seUserAccess($namepage)
{
    $group = 0;
    $namegroup = '';
    $pages = seData::getInstance()->getPages();
    if (!empty($pages))
        foreach ($pages as $page) {
            if (trim($page['name'][0]) == $namepage) {
                $group = ($page->groupslevel < 6) ? $page->groupslevel : 0;
                $namegroup = $page->groupsname;
                break;
            }
        }

    return getLoginAccess($group, $namegroup, seUserGroup(), seUserGroupName(), seUserLogin());
}

// Проверить роль пользователя
function seUserRole($namerole, $user_id = 0)
{
    if (!SE_DB_ENABLE) return false;
    if (!$user_id) $user_id = seUserId();
    $user = new seTable('se_user_group', 'sug');
    $user->innerjoin('se_group sg', 'sug.group_id=sg.id');
    $user->where("sug.user_id=?", $user_id);
    $user->andwhere("sg.name='?'", $namerole);
    $user->fetchOne();
    return $user->isFind();
}
