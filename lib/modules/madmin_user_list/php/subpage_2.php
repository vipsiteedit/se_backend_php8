<?php

$REG_STATUS1 = "";
$REG_STATUS2 = "";
if ((seUserGroup() == 3) && isRequest('GoToRekvEdit')) {
    $ugrp = $_POST['usergroup'];
    $_last_name = getRequest('last_name', 3);
    $_first_name = getRequest('first_name', 3);
    $_sec_name = getRequest('sec_name', 3);
    $_a_login = getRequest('a_login', 3);
    $_doc_ser = getRequest('doc_ser');
    $_doc_num = getRequest('doc_num');
    $_post_index = getRequest('post_index');
    $_country = getRequest('country', 1);
    $_state = getRequest('state', 1);
    $_city = getRequest('city', 1);
    $_addr = getRequest('addr', 3);
    $_phone = getRequest('phone');
    $_email = getRequest('email');
    $_icq = getRequest('icq');
    $is_active = getRequest('reg_status', 3);
    if ($is_active == 'Y') {
        $REG_STATUS1 = "CHECKED";
    } else {
        $REG_STATUS2 = "CHECKED";
    }
    $_newpassword = getRequest('newpassword', 3);
    $_confirmpassword = getRequest('confirmpassword', 3);
    if ($_user) {
//        $_idup = getRequest('a_idup'); 
//        $id_up = $person->id;
        $user->find($_user);
        $enabled = $user->is_active;
    } else if (empty($_a_login)) {
        $errorRes = $section->language->lang074;//'Поле с логином пустое'; 
    } else {
        $user = new seUser(); 
        $user->where("username = '$_a_login'");
        $user->fetchOne();
        if ($user->id) {
            $errorRes = $section->language->lang075;//'Пользователь с таким логином уже есть';
        } else {
            $user->username = $_a_login;
        }
    }
    if ($errorRes == '') {
        if (empty($ugrp)) {
            $errorRes = $section->language->lang076;//'Пользователь должен входить минимум в одну группу';
        } else if (empty($_last_name) || empty($_first_name)) {
            $errorRes = $section->language->lang077;//'Не все важные поля заполнены';
        } else if (!se_CheckMail($_email)) {
            $errorRes = $section->language->lang078;//'Email записан неверно';
        } else if (empty($_newpassword)) {
            if (!$_user) {
                $errorRes = $section->language->lang079;//'Поле с паролем пустое';
            }
        } else if ($_newpassword != $_confirmpassword) {
            $errorRes = $section->language->lang080;//'Значение поля с подтверждением пароля не совпадает с указанным паролем';
        } else {
            $mess_reppassword = $section->language->lang041;
            $user->password = md5($_newpassword);        
        }
        if ($errorRes == '') {
            $user->is_active = $is_active;
            $usrId = $user->save();
            if ($_user) {
                $usrId = $_user;
                $tbl = new seTable("se_user_group");
                $tbl->where("user_id = '$usrId'");
                $tbl->andWhere("NOT group_id IN ('" . join("','", $ugrp) . "')");
                $tbl->deletelist();
                unset($tbl);
                foreach ($ugrp as $v) {
                    $tbl = new seTable("se_user_group", "ug");
                    $tbl->where("ug.user_id = '$usrId'");
                    $tbl->andWhere("ug.group_id = '$v'");
                    $inG = $tbl->fetchOne();
                    if (empty($inG)) {
                        $tbl->user_id = $usrId;
                        $tbl->group_id = $v;
                        $tbl->save();
                    }
                    unset($tbl);
                }
            } else {
                se_db_query("INSERT INTO se_user_group(user_id, group_id)
                        VALUES('$usrId', '" . join("'), VALUES('$usrId', '", $ugrp) . "')");
            }
            $ugrp = array($ugrp[0]);
            $person = $user->getPerson();
            if (!$_user) {
                $person->id = $usrId;
                $person->reg_date = date('Y-m-d');
            }
            $person->last_name = $_last_name;//getRequest('last_name', VAR_STRING);
            $person->first_name = $_first_name;//getRequest('first_name', VAR_STRING); 
            $person->sec_name = $_sec_name;//getRequest('sec_name', VAR_STRING);
            $person->email = $_email;//getRequest('email');
            $person->doc_ser = $_doc_ser;//getRequest('doc_ser');
            $person->doc_num = $_doc_num;//getRequest('doc_num');
            $person->post_index = $_post_index;//getRequest('post_index');
            $person->country_id = $_country;//getRequest('country', VAR_INT);
            $person->state_id = $_state;//getRequest('state', VAR_INT);
            $person->town_id = $_city;//getRequest('city', VAR_INT);
            $person->addr = $_addr;//getRequest('addr', VAR_STRING);
            $person->phone = $_phone;//getRequest('phone');
            $person->icq = $_icq;//getRequest('icq');
            $person->save();
/*
            $main = new seTable('main');
            $main->where("lang = '?'", se_getlang());
            $main->fetchOne();
//*/
            $adminemail = $section->parametrs->param131;//trim($main->esupport);
            if ($_user && ($enabled != $is_active) && ($is_active == 'Y') && ($adminemail != '')) {
//                $_email = getRequest('email');
                $message = str_replace("%user%", "$_first_name $_sec_name", $section->parametrs->param4);
                $message = str_replace("%site%", $_SERVER['HTTP_HOST'], $message);
                $message = str_replace('\r\n', "<br>", $message);
                $mailtitl = str_replace("%user%", "$_first_name $_sec_name", $section->parametrs->param5);
                $mailtitl = str_replace("%site%", $_SERVER['HTTP_HOST'], $mailtitl);
                $from = "=?utf-8?b?" . base64_encode($section->language->lang042) . "?= " . $_SERVER['HTTP_HOST'] . " <$adminemail>";
                $mailsend = new plugin_mail($mailtitl, $_email, $from, $message, 'text/html');
                $mailsend->sendfile();
            }
            header("Location: /$_page/");
            exit();
        }
    }  
} else if ($_user) {
    $person->find($_user);
    $user->find($_user);
    if ($user->is_active == 'Y') {
            $REG_STATUS1 = "CHECKED";
    } else {
            $REG_STATUS2 = "CHECKED";
    }
    $_last_name = $person->last_name;
    $_first_name = $person->first_name;
    $_sec_name = $person->sec_name;
//        $_a_idup = $person->id_up;
    $_a_login = se_db_output($user->username);
    $_doc_ser = $person->doc_ser;
    $_doc_num = $person->doc_num;
    $_post_index = se_db_output($person->post_index);
    $_country = $person->country_id;
    $_state = $person->state_id;
    $_city = $person->town_id;
//        $_overcity = $person->overcity;
    $_addr = str_replace('"', "'", htmlspecialchars_decode($person->addr));
    $_phone = $person->phone;
    $_email = $person->email;
    $_icq = $person->icq;
    $usrg = array();
    $tbl = new seTable("se_user_group", "ug");
    $tbl->select("ug.group_id AS id");
    $tbl->where("ug.user_id = '$_user'");
    foreach ($tbl->getList() as $acc) {
        $usrg[] = $acc["id"];
    } 
    unset($tbl);
    $ugrp = &$usrg;
/*
    $person->find($_a_idup);
    $user->find($_a_idup);
    $login = $user->username;
    $_top_idname = trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
    if (!empty($user->username)) {
        $_top_idname = "Company";
    }
//*/
    $delLinks3 = '/' . $_page . '/' . $razdel . '/sub1/user/' . $_user . '/';
} else {
    $_last_name = $_first_name = $_sec_name = $_a_login = $_doc_ser = 
    $_doc_num = $_post_index = $_addr = $_phone = $_email = $_icq = '';  
    $_country = $_state = $_city = 0;
    $REG_STATUS1 = "CHECKED";
}
//************************************************
// генерируем html код списка стран
$country = new seTable('country');
$countrylist = $country->getList();
foreach ($countrylist as $row) {
    $row['sel'] = intval($_country == $row['id']);
    $__data->setItemList($section, 'countrylist', $row);
} 
//************************************************
//Генерируем html код списка регионов 
$region = new seTable('region');
$region->where('id_country=?', $_country);
$regionlist = $region->getList();
foreach ($regionlist as $row) {
    $row['sel'] = intval($_state == $row['id']);
    $__data->setItemList($section, 'statelist', $row);
} 
//************************************************
//Генерируем html код списка городов 
$town = new seTable('town');
$town->where('id_region=?', $_state);
$townlist = $town->getList();
foreach ($townlist as $row) {
    $row['sel'] = intval($_city == $row['id']);
    $__data->setItemList($section, 'townlist', $row);
} 
//************************************************ 
 

?>