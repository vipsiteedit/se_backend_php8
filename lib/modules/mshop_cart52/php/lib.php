<?php

if (!function_exists('genPassword')) {
function genPassword($count = 10){
    $char = 'abcdefghijklnopqrstuvwxyzABCDEFGHIJKLNOPQRSTUVWXYZ1234567890';
    $pass = '';
    $char_length = strlen($char)-1;
    for($i=0; $i < $count; $i++)
        $pass .= $char[rand(0, $char_length)];
    return $pass;
}
}

if (!function_exists('getTimeWord')){
function getTimeWord($section, $count, $words = null, $define = null){
    if (empty($words))
        $words = array($section->language->lang073, $section->language->lang074, $section->language->lang075);
    if (empty($define))
        $define = array($section->language->lang076, $section->language->lang077, $section->language->lang078);    
    if (!empty($define[$count])) return $define[$count];
    $keys = array(2, 0, 1, 1, 1, 2);
    $counts = explode('-', $count);
    $mod = array_pop($counts) % 100;
    $key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
    return $count . '&nbsp;' . $words[$key];
}
}


if (!function_exists('validPostindex')){
function validPostindex($postindex) {
    return preg_match('/^(\d{5}(-\d{4})?|\d{6})$/', $postindex); 
}
}

function getPaymentList($id_delivery, $all = false){
    $payment_list = array();
    $lang = se_getLang();
    $selected = false;
    $key_select = false;
    $selected_payment = !empty($_SESSION['payment_type_id']) ? $_SESSION['payment_type_id'] : null;
    $_SESSION['payment_type_id'] = null;
    $first_delivery = 0;
    $shop_payment = new seTable('shop_payment', 'sp');
    $select = '';
    if ($all && $id_delivery)
        $select = ", (SELECT id_payment FROM shop_delivery_payment WHERE id_payment = sp.id AND id_delivery = $id_delivery) as delivery";
    $shop_payment->select('sp.id, sp.name_payment, sp.logoimg, sp.way_payment, sp.startform'.$select);
    $shop_payment->where("sp.lang = '?'", $lang);
    $shop_payment->andWhere("sp.active = 'Y'");
    
    if ($id_delivery && !$all) {
        $shop_payment->innerjoin('shop_delivery_payment sdp', 'sdp.id_payment=sp.id');
        $shop_payment->andWhere("sdp.id_delivery=?", $id_delivery);
    }
     
    $shop_payment->orderby("sort", 0);
    $payment_list = $shop_payment->getlist();
    
    if (!empty($payment_list)){
        foreach($payment_list as $key => $val){
            if ($val['logoimg'])
                $payment_list[$key]['logoimg'] = '/images/'.$lang.'/shoppayment/'.$val['logoimg'];
            
            $payment_list[$key]['selected'] = '';
            if (!$selected && $val['id'] == $selected_payment){
                $selected = $val['id'];
                if (!$all || empty($id_delivery))
                    $payment_list[$key]['selected'] = $_SESSION['payment_type_id'] = $val['id'];
                elseif(!empty($val['delivery']))
                    $payment_list[$key]['selected'] = $_SESSION['payment_type_id'] = $val['id'];   
            }
            
            $payment_list[$key]['delivery'] = false;
            if ($all && $id_delivery){
                $payment_list[$key]['delivery'] = empty($val['delivery']);
                if(empty($first_delivery) && $val['delivery'])
                    $first_delivery = $key;
            }
            
        }
        if (empty($_SESSION['payment_type_id'])){
            if (!$all)
                $payment_list[0]['selected'] = $_SESSION['payment_type_id'] = $payment_list[0]['id'];
            else
                $payment_list[$first_delivery]['selected'] = $_SESSION['payment_type_id'] = $payment_list[$first_delivery]['id'];
        }
    }
    return $payment_list;
}

function getPaymentType($id = 0){
    $payment_id = ($id) ? $id : $_SESSION['payment_type_id'];
    if (!$payment_id) return;
    
    $payment_type = new seTable("shop_payment", 'sp'); 
    $payment_type->select("sp.name_payment as name, sp.way_payment as way, sp.type");
    $payment_type->where("sp.id = ?", $payment_id);
    return $payment_type->fetchOne();
}

if (!function_exists('cartRegUser')){
function cartRegUser($save_requisite = false){      
    
    $user_login = $_SESSION['cartcontact']['email'];
    $user_name = $_SESSION['cartcontact']['name'];
    $user_pass = genPassword();
    $user_email = $_SESSION['cartcontact']['email'];
    $user_phone = $_SESSION['cartcontact']['phone'];
    
    $user = new seUser();
    $user->select('u.id, u.username');
    $user->where("u.username = '?'", $user_login);
    
    if ($user->fetchOne()){
        $user_id = $user->id;
        check_session(true);
        $auth['IDUSER'] = $user_id;
        $auth['GROUPUSER'] = 0;
        $auth['ADMINUSER'] = 0;
        $auth['AUTH_USER'] = $user_login;
        $auth['USER'] = $user_name;
        //$auth['AUTH_PW'] = $user_pass;
        check_session(false, $auth);    
    }
    else{
        $user_pass = genPassword();
        
        $reg['username'] = $user_login;
        $reg['passw'] = $reg['confirm'] = $user_pass;
        $reg['last_name'] = null;
        $reg['first_name'] = $user_name;
        $reg['sec_name'] = null;
        $reg['email'] = $user_email;
        $reg['phone'] = $user_phone;
        $user = new seUser();
        if ($user_id = $user->registration($reg, 0, 1, '')) {
            $person = new sePerson();
            $person->where('id = ?', $user_id);
            $person->fetchOne();
            $person->update('country_id', "{$_SESSION['userregion']['country']}");
            $person->addupdate('state_id', "{$_SESSION['userregion']['region']}");
            $person->addupdate('town_id', "{$_SESSION['userregion']['city']}");   
            $person->save();
            
            if ($save_requisite && !empty($_SESSION['user_type']) && $_SESSION['user_type'] == 'u') {
                saveUserRequisite($user_id);
            }
            
            check_session(true);
            $auth['IDUSER'] = $user_id;
            $auth['GROUPUSER'] = 1;
            $auth['AUTH_USER'] = $user_login;
            $auth['USER'] = $user_name;
            $auth['AUTH_PW'] = $user_pass;
            check_session(false, $auth);
            
            $array_change = array(
                'USERNAME' => $user_name,
                'THISNAMESITE' => $_SERVER['HTTP_HOST'],
                'SHOP_USERLOGIN' => $user_login,
                'SHOP_USERPASSW' => $user_pass
            );
    
            $plugin_mail = new plugin_shopmail();
            $plugin_mail->sendmail('reguser', $user_email, $array_change);
            $plugin_mail->sendmail('regadm', '', $array_change);
        }
        
    }
 
    return $user_id;
}
}

if (!function_exists('saveUserRequisite')){
function saveUserRequisite($id_user) {
    if (empty($id_user)) return;
    
    $user_urid = new seTable('user_urid');
    $user_urid->insert();
    $user_urid->id = $id_user;
    if (!empty($_SESSION['requisite']['company_name']))
        $user_urid->company = $_SESSION['requisite']['company_name'];
    if (!empty($_SESSION['requisite']['company_director']))
        $user_urid->director = $_SESSION['requisite']['company_director'];
    if (!empty($_SESSION['requisite']['company_addr']))
        $user_urid->uradres = $_SESSION['requisite']['company_addr'];
    if (!empty($_SESSION['requisite']['company_phone']))
        $user_urid->tel = $_SESSION['requisite']['company_phone'];
    if (!empty($_SESSION['requisite']['company_fax']))
        $user_urid->fax = $_SESSION['requisite']['company_fax'];
    $user_urid->save();
    
    $requisite = getRequisite();
    if (!empty($requisite)) {
        $user_rekv = new seTable('user_rekv');
        foreach($requisite as $val) {
            if (!isset($_SESSION['requisite'][$val['code']]))
                continue;
            $user_rekv->insert();
            $user_rekv->id_author = $id_user;
            $user_rekv->rekv_code = $val['code'];
            $user_rekv->value = $_SESSION['requisite'][$val['code']];
            $user_rekv->save();        
        }
    }
    unset($_SESSION['requisite'], $_SESSION['user_type']);
}
}

if (!function_exists('getRequisite')){
function getRequisite() {
    $user_rekv_type = new seTable('user_rekv_type');
    $user_rekv_type->select('id, title, size, code');
    $user_rekv_type->where('lang="?"', se_getLang());
    return $user_rekv_type->getList();
}
}
?>