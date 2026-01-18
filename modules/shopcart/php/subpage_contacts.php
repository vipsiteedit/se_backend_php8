<?php

if (empty($select_delivery)){
    $select_delivery = $plugin_delivery->getDelivery();
}

if (!empty($user_id)){
    $person = new seTable('person', 'p');
    $person->select("concat_ws (' ', p.last_name, p.first_name, p.sec_name) as name, p.email, p.phone, p.post_index, 
        concat_ws(', ', p.post_index, p.overcity, p.addr) as address");      
    $person->where('p.id=?', $user_id); 
    $contact_person = $person->fetchOne();
    unset($person);
}

$contact_name = null;
if (isRequest('contact_name'))
    $contact_name = trim(getRequest('contact_name', 3));
elseif(!empty($_SESSION['cartcontact']['name']))
    $contact_name = $_SESSION['cartcontact']['name'];
elseif(isset($contact_person['name']))
    $contact_name = $contact_person['name'];

$contact_email = null;
if (isRequest('contact_email'))
    $contact_email = trim(getRequest('contact_email', 3));
elseif(!empty($_SESSION['cartcontact']['email']))
    $contact_email = $_SESSION['cartcontact']['email'];
elseif(isset($contact_person['email']))
    $contact_email = $contact_person['email'];

$contact_phone = null;
if (isRequest('contact_phone'))    
    $contact_phone = trim(getRequest('contact_phone', 3));
elseif(!empty($_SESSION['cartcontact']['phone']))
    $contact_phone = $_SESSION['cartcontact']['phone'];
elseif(isset($contact_person['phone']))
    $contact_phone = $contact_person['phone'];

$contact_post_index = null;    
if (isRequest('contact_post_index'))  
    $contact_post_index = trim(getRequest('contact_post_index', 3));
elseif(!empty($_SESSION['cartcontact']['post_index']))
    $contact_post_index = $_SESSION['cartcontact']['post_index'];
elseif(isset($contact_person['post_index']))
    $contact_post_index = $contact_person['post_index'];

$contact_address = null;        
if (isRequest('contact_address'))  
    $contact_address = trim(getRequest('contact_address', 3));
elseif(!empty($_SESSION['cartcontact']['address']))
    $contact_address = $_SESSION['cartcontact']['address'];
elseif(isset($contact_person['address']))
    $contact_address = $contact_person['address'];

$contact_comment = null; 
if (isRequest('contact_comment'))  
    $contact_comment = trim(getRequest('contact_comment', 3));
elseif(!empty($_SESSION['cartcontact']['comment']))
    $contact_comment = $_SESSION['cartcontact']['comment']; 

$error_name = $error_email = $error_phone = $error_index = $error_address = '';
    
if (!empty($contact_errors)){
    if (!empty($contact_errors['name']))
        $error_name = $contact_errors['name'];
    if (!empty($contact_errors['email']))
        $error_email = $contact_errors['email'];
    if (!empty($contact_errors['phone']))    
        $error_phone = $contact_errors['phone'];
    if (!empty($contact_errors['post_index']))    
        $error_index = $contact_errors['post_index'];
    if (!empty($contact_errors['address']))    
        $error_address = $contact_errors['address'];
}

//unset($_SESSION['cartcontact']);

?>