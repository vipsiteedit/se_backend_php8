<?php

if (isRequest('user_type'))
    $user_type = getRequest('user_type');
elseif (isset($_SESSION['user_type']))
    $user_type = $_SESSION['user_type'];

$is_urid = !empty($user_type) && $user_type == 'u';

if (isRequest('company_name'))
    $company_name = trim(getRequest('company_name'));
elseif (isset($_SESSION['requisite']['company_name']))
    $company_name = $_SESSION['requisite']['company_name'];

if (isRequest('company_director'))
    $company_director = trim(getRequest('company_director'));
elseif (isset($_SESSION['requisite']['company_director']))
    $company_director = $_SESSION['requisite']['company_director'];
    
if (isRequest('company_phone'))
    $company_phone = trim(getRequest('company_phone')); 
elseif (isset($_SESSION['requisite']['company_phone']))
    $company_phone = $_SESSION['requisite']['company_phone'];
        
if (isRequest('company_fax'))
    $company_fax = trim(getRequest('company_fax'));
elseif (isset($_SESSION['requisite']['company_fax']))
    $company_fax = $_SESSION['requisite']['company_fax'];
    
if (isRequest('company_addr'))
    $company_addr = trim(getRequest('company_addr')); 
elseif (isset($_SESSION['requisite']['company_addr']))
    $company_addr = $_SESSION['requisite']['company_addr'];  

$requisite = getRequisite();
if (!empty($requisite)) {
    foreach($requisite as $val) {
        if (isRequest('bank_' . $val['code']))
            $val['value'] = trim(getRequest('bank_' . $val['code']));
        elseif (isset($_SESSION['requisite'][$val['code']]))
            $val['value'] = $_SESSION['requisite'][$val['code']]; 
        $__data->setItemList($section, 'requisite', $val);
    }
}

?>