<?php

$error_message = '';
$fl_err = false;

$antispamon = true;

if ($antispamon) {
    $capcha = new plugin_capcha();
}

if (isRequest('GoToAuthor')) {
    $_username = getRequest('username');
    $_last_name = getRequest('last_name');
    $_first_name = getRequest('first_name');
    $_email = getRequest('email');
    $phone = getRequest('phone');
    
    $passw = getRequest('passw');
    $confpassw = getRequest('confpassw');
    
    if (empty($_username) || !Mail_CheckMail($_email) || empty($_first_name) || empty($_last_name)) {
        $error_message = $section->language->lang007;
        $fl_err = true;
    } 
    else if (empty($passw)) { // проверка пароля 
        $error_message = $section->language->lang006;
        $fl_err = true;
    } 
    else if ($passw != $confpassw) { // проверка совпадают ли пароли
        $error_message = $section->language->lang008;
        $fl_err = true;
    } 
    else {
        $ch_license = $section->parametrs->param46;
        if (($ch_license == 'Y') && !isRequest('license')) {
            $error_message = $section->language->lang026;
            $fl_err = true;
        }
    }
    
    if ($antispamon) {
        $check = $capcha->check();
        if ($check === -10){
            $error_message = $section->language->lang023;
            $fl_err = true;
        } elseif (!$check) {
            $error_message = $section->language->lang023;
            $fl_err = true;
        }
    }
    
    if ($section->parametrs->param49 == 'Y' || $section->parametrs->param51 == 'Y') {
        if ($section->parametrs->param49 == 'Y' && !isRequest('personal_accepted')) {
            $errorlicense = $section->language->lang026; 
            $err = true;   
        }
        
        if ($section->parametrs->param51 == 'Y' && !isRequest('additional_accepted')) {
            $errorlicense = $section->language->lang026; 
            $err = true;   
        }
    }
    
    if (!$fl_err) {
        if ($frommail == 'Y') {
            $enable = 'N'; 
        } else {
            $enable='Y';
        }
        $user = new seUser();
        $user->where("username='?'", $_username)->fetchOne();
        if (!$user->id) {
            $user->username = $_username;
            $user->password = md5($passw);
            $user->is_active = $enable;
            if ($user_id = $user->save()){
                $user->setAccess(array(array(intval($section->parametrs->param2), $section->parametrs->param3)));
                $person = $user->getPerson();
                $person->id = $user_id;
                if (intval($_SESSION['REFER'])) {
                    $ref = new seUser();
                    $ref->select('id');
                    $ref->find(intval($_SESSION['REFER']));
                    if ($ref->isFind()) {
                        $person->id_up = $ref->id;
                    }
                    unset($ref);
                }
                $person->last_name = $_last_name;
                $person->first_name = $_first_name;
                $person->email = $_email;
                $person->phone = $phone;
                $person->reg_date = date('Y-m-d');
                $person->save();
                Mail_UserRegistration($section, $__request);
                if ($frommail=='N') {
                    check_session(true); // Удаляем старую сессию
                    $SESSION_VARS['AUTH_PW']    = md5($passw);
                    $SESSION_VARS['AUTH_USER']  = $_username;
                    $SESSION_VARS['IDUSER']     = $user_id;
                    $SESSION_VARS['USER']       = $_first_name;
                    $SESSION_VARS['GROUPUSER']  = $section->parametrs->param2;
                    $SESSION_VARS['ADMINUSER']  = $section->parametrs->param3;
//                    $SESSION_VARS['ADMINUSER']  = $section->parametrs->param3;
                    check_session(false, $SESSION_VARS);
                }
                header("Location: /".$_page."/".$razdel."/sub1/");
            }
        } else {
            $error_message = $section->language->lang019;
        }
    }
}

if ($antispamon) {
    $anti_spam = $capcha->getCapcha($section->language->lang022, $section->language->lang023);
}
?>