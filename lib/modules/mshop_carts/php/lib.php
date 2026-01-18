<?php

if (!function_exists('generate_password')) {
  // Параметр $number - сообщает число 
  // символов в пароле
function generate_password($number) 
{
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0',
                 '!','?',
                 '&','^','%','@','*','$');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
}
}


if (!function_exists('validate_postindex')) {
// Проверка почтового индекса
function validate_postindex($postindex) {
    return preg_match('/^[[:digit:]]{6}$/', $postindex) || 
           preg_match('/^[[:digit:]]{5}(-[[:digit:]]{4})?$/', $postindex);
}
}    


if (!function_exists('shcart_registration')) {
  // Параметр $number - сообщает число 
  // символов в пароле
function shcart_registration($username, $passwd, $email, $phone, $last_name, $first_name, $sec_name) 
{
    $user = new seUser();
    $user->where("username = '?'", $username);
    if($user->fetchOne()) 
    {  
        return $username." - ".$section->parametrs->param22;
    } 
    else 
    {
        $req['username'] = $username;
        $req['passw'] = $passwd;
        $req['confirm'] = $passwd;
        $req['last_name'] = $last_name;
        $req['first_name'] = $first_name;
        $req['sec_name'] = $sec_name;
        $req['email'] = $email;
        $req['phone'] = $phone;

        if ($id_num = $user->registration($req, 0, 1, '')){
            // Открываем доступ
            check_session(true); // Удаляем старую сессию
            $auth['IDUSER'] = $id_num;
            $auth['GROUPUSER'] = 1;
            $auth['USER'] = $last_name." ".$first_name;
            $auth['AUTH_USER'] = $username;
            $auth['AUTH_PW'] = $password;
            $auth['ADMINUSER'] = $id_up;
            check_session(false, $auth); // Создаем новое вхождение авторизации

            $nameuser = trim($last_name." ".$first_name);
            $tbl = new seTable("main");

            $tbl->select("esupport");
            $tbl->where("lang = '?'", se_getLang());
            $tbl->fetchOne();
            $email_from = $tbl->esupport;
            unset($tbl);          
            $from = $section->parametrs->param58 . " " . $_SERVER['HTTP_HOST'];
            $array_change = array(
                                'USERNAME' => $nameuser,
                                'THISNAMESITE' => $_SERVER['HTTP_HOST'],
                                'SHOP_USERLOGIN' => $username,
                                'SHOP_USERPASSW' => $passwd
                            );
            
            $mails = new plugin_shopmail();
            $mails->sendmail('reguser', $email, $array_change);
            $mails->sendmail('regadm', '', $array_change);
            unset($_SESSION['shopcartunreg']);

            header("Location: ?");
            exit();
        } 
        else 
        { 
            return $section->parametrs->param67;
        }
    }    
}
}

if (!function_exists('sc_getGroupsTree')){
function sc_getGroupsTree($group_id){
    $id = $group_id;
    $groups = new seTable('shop_group', 'sg');
    $groups->select('sg.upid, sdg.id_type');
    $groups->leftjoin('shop_deliverygroup sdg', 'sg.id=sdg.id_group');
    $groups->where('sg.id=?', $group_id);
    $groups->fetchOne();
    if ($groups->upid && $groups->id_type < 1){
        $id = sc_getGroupsTree($groups->upid);
    }
    return $id;
}
}

// Доставка - выдает список способов доставки для списка указанных товаров
/**
 * @param string incart_ids - список id товаров через запятую
 */
if (!function_exists('sc_getDeliverySelect')){
function sc_getDeliverySelect($incart_ids){
    $incart_arr = explode(',', $incart_ids);
    
    $groups = new seTable('shop_price');
    $groups->select('id_group');
    $groups->where('id IN (?)', $incart_ids);
    $groups->andwhere("enabled='Y'");
    $glist = $groups->getlist();
    $incart_arr = array();

    foreach($glist as $line) {
       $incart_arr[] = sc_getGroupsTree($line['id_group']);
    }
    $rdeliv = new seTable('shop_deliverytype', 'sdt');
    $rdeliv->select('DISTINCT sdt.id, sdt.name, sdt.code, sdt.time, sdt.price, sdt.curr, sdt.forone, sdt.note');
    $rdeliv->innerjoin('shop_deliverygroup sdg', 'sdg.id_type=sdt.id');
    $rdeliv->where("sdg.id_group IN (?)",join(',', $incart_arr));
    // SELECT id_group FROM shop_price WHERE id IN (".$id_incart.") AND `enabled`='Y');");
    return $rdeliv->getlist();             
}}
                                          
                                          
if (!function_exists('decreaseActualCount')) {  // переместить в модуль обработки заказа
    function decreaseActualCount($price_id, $spp_id, $n){
// Уменьшает актуальное количество товара в наличии на величину n
        $tbl = new seTable("shop_price");
        $tbl->find($id_cart);
        $value['price'] = $tbl->price;   // надо? (не было)
        $value['price_opt'] = $tbl->price_opt;
        $value['price_opt_corp'] = $tbl->price_opt_corp;
        
        $maxvalue = $tbl->presence_count;
        if (($maxvalue < $value['count']) && ($maxvalue != '') && ($maxvalue != -1)) { 
            $value['count'] = $maxvalue;
        }
    }
}

if (!function_exists('myFormatMoney')) {
    function myFormatMoney($money, $name, $rounded) {
        $tbl = new seTable('money_title', 'mt');
        $tbl->where("mt.name = '$name'");
        $tbl->fetchOne();
        if ($rounded)
            $money = round($money);
        
        $submoney = $money - intval($money);
        if ($submoney > 0) {
            return $tbl->name_front . ' ' . number_format($money, 2, '.', ' ') . ' ' . $tbl->name_flang;
        } else {
            return $tbl->name_front . ' ' . number_format($money, 0, '', ' ') . ' ' . $tbl->name_flang;
        }
    }
}
?>