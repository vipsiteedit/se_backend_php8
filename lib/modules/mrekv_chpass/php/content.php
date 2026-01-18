<?php

se_db_connect();
$_oldpass = getRequest("oldpass", 3);
$_newpass = getRequest("newpass", 3);
$_confirmpass = getRequest("confirmpass", 3);
if ((isset($_SESSION['ID_AUTH'])) && ($_SESSION['ID_AUTH'] > 0)) {
    $user = $_SESSION['ID_AUTH'];
} else {
    $user = seUserId();
}
$flag = false;
$users = new seUser(); 
$users->find($user);
$login = seUserName();
if (isRequest("gochpass") &&((seUserGroup()) > 0)) {
    if (!$user) {
        $error_message = $section->language->lang010;
        $flag = true;
    }
    if ((($_newpass == "") || ($_confirmpass == "")) && !$flag) {
        $error_message = $section->language->lang007;
        $flag = true;
    }
    if (($section->parametrs->param9 != 'N') && !$flag) {
        $temp = md5($_oldpass);
        $temp1 = $users->password;
        $temp2 = $users->tmppassw;
        if (($temp != $temp1) && ($temp != $temp2)) {
            $error_message = $section->language->lang008;
            $flag = true;
        } else {
            if ($_newpass != $_confirmpass) {
                $error_message = $section->language->lang009;  
                $flag = true;
            } else {
                $users->password = md5($_newpass); 
            }
        }
    } else {
        if ($_newpass != $_confirmpass) {
            $error_message = $section->language->lang009;  
            $flag = true;
        } else {
            $users->password = md5($_newpass); 
        }
    }
    if (!$flag) {
        $users->save();
// автризация с новым паролем
        $TMP_SESSION=$SESSION_VARS;
        check_session(false);
        $SESSION_VARS = $TMP_SESSION;
        $SESSION_VARS['AUTH_PW'] = $temp;
        check_session(true);
        $error_message = $section->language->lang002;
    }
} 
?>