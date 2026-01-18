<?php

// ### Выводим список товаров в корзине
$disabled_button_order = 'disabled="disabled"';
$usergroup = $controller->order->user->userGroup;

//$display_pageNavigator = $controller->view->display_pageNavigator;
$display_author = $controller->view->display_author;
$display_resume = $controller->view->display_resume; 
$display_unregBtn = $controller->view->display_unregBtn;

// Итого
$summa_order =    $controller->view->sum_subtotal; 

// Резюме
$show_sum_order = $controller->view->sum_subtotal;
$show_sum_discount = $controller->view->sum_discount;
$show_sum_delivery = $controller->view->sum_delivery;
$show_sum_all = $controller->view->sum_all;

$backpage = $controller->backpage;

// Сообщения об ошибках
$display_error =  $controller->view->display_error;     
$error_message = $controller->view->error_message;

$display_ex_error = $controller->view->display_ex_error;
$_ex_error = $controller->view->ex_error; 

// Данные о клиенте
$client_name = $controller->order->user->client_name;
$client_last_name = $controller->order->user->client_last_name;
$client_first_name = $controller->order->user->client_first_name;
$client_sec_name = $controller->order->user->client_sec_name;

// Данные регистрации
$reg_username = $controller->registration->username; 
$reg_first_name = $controller->registration->first_name;
$reg_last_name = $controller->registration->last_name;
$reg_sec_name = $controller->registration->sec_name;
$reg_email = $controller->registration->email; 
$reg_phone = $controller->registration->phone;
$reg_passw = $controller->registration->passw;
$reg_passw1 = $controller->registration->passw1;

// Данные доставки
$delivery_type_id = $controller->delivery->delivery_type_id;
$_phone = $controller->delivery->_phone;
$_email = $controller->delivery->_email;
$_post_index = $controller->delivery->_post_index;
$_addr = $controller->delivery->_addr;
$_addr = htmlspecialchars_decode($_addr);
$_addr = htmlspecialchars($_addr);
$display_addr_select = ($controller->delivery->SELECT_ADDRESSES != ''); 

$_calltime = $controller->delivery->_calltime;
$_ordercomment = $controller->delivery->_ordercomment;
                     
// Блоки HTML
$DELIVERY_TYPES = $controller->delivery->DELIVERY_TYPES;
$DELIVERY_NOTE =  $controller->delivery->DELIVERY_NOTE;
$SELECT_ADDRESSES = $controller->delivery->SELECT_ADDRESSES;       

$display_delivery = (!empty($delivery_type_id));
$show_delivery_weight = $controller->delivery->weight;  

$display_cities = $controller->delivery->display_cities;
if ($display_cities)
    $CITIES = $controller->delivery->CITIES;
$display_EMS_delivery_system = !empty($controller->delivery->delivery_code);

if ($display_EMS_delivery_system) 
{                  
    $show_delivery_system_name = $controller->delivery->delivery_code;
    $show_delivery_term = $controller->delivery->show_delivery_term;  
}                
?>