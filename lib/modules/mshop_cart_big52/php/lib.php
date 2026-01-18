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
function getTimeWord($count, $words = array('день', 'дня', 'дней'), $define = array('сегодня', 'завтра', 'послезавтра')){
    if (!empty($define[$count])) return $define[$count];
    $keys = array(2, 0, 1, 1, 1, 2);
    $counts = explode('-', $count);
    $mod = array_pop($counts) % 100;
    $key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
    return $count . '&nbsp;' . $words[$key];
}
}

function getRegionList($type = 'country', $selected_id = 0){
    if ($type == 'region'){
        $table = 'region';
        $parent = 'id_country';
    }
    elseif ($type == 'city'){
        $table = 'town';
        $parent = 'id_region';
    }
    elseif ($type == 'country'){
         $table = 'country';
    }
    else
        return;
    
    $tlb_region = new seTable($table);
    $tlb_region->select('id, name');
    if (!empty($selected_id))
        $tlb_region->where($parent.'=?', $selected_id);   
    $tlb_region->orderBy('name', 0);
    $list = $tlb_region->getList();
    unset($tlb_region);
    if (!empty($list)){
        $selected = $_SESSION['userregion'][$type];
        $option_list = '';
        foreach($list as $val){
            $option_list .= '<option value="'.$val['id'].'"';
            if ($val['id'] == $selected){
                $option_list .= ' selected';
            }
            $option_list .= '>'.$val['name'].'</option>';
        }
        return $option_list;
    }
    return;
}

function getRegionName(){
    $country_id = (int)$_SESSION['userregion']['country'];
    $region_id = (int)$_SESSION['userregion']['region'];
    $city_id = (int)$_SESSION['userregion']['city'];

    $region_city = '';
    $tlb_region = new seTable('region', 'r');
    $tlb_region->select("r.name as region, (SELECT t.name FROM town AS t WHERE t.id_region=r.id AND t.id=$city_id LIMIT 1) as city");
    $tlb_region->where("r.id = ?", $region_id);
    $tlb_region->fetchOne();
    if ($tlb_region->isFind()){
        $region_city = $tlb_region->city; 
        if (empty($region_city)){
            $region_city = $tlb_region->region;
            $_SESSION['userregion']['city'] = 0;
        }       
    }
    return $region_city;
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
function cartRegUser(){      
    
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

?>