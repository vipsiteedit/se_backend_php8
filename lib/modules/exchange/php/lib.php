<?php

function get_code_goods($xml, $type = 'translit', $id = 0, $length = 40){    
    
    $code = substr(trim($xml->Ид), 0, $length);
          
    if ($type == 'translit' && $xml->Наименование){
        $find_code = substr(translit($xml->Наименование), 0, $length);
    }
    elseif($type == 'article' && $xml->Артикул){
        $find_code = substr(trim($xml->Артикул), 0, $length);
    }    
    elseif($type == 'barcode' && (trim($xml->ШтрихКод) || trim($xml->Штрихкод))){
        $find_code = (trim($xml->ШтрихКод)) ? substr(trim($xml->ШтрихКод), 0, $length) : substr(trim($xml->Штрихкод), 0, $length);
    }
    
    if ($find_code){
        $find_goods = new seTable('shop_price');
        $find_goods->select('id');
        $find_goods->where("code = '?'", $find_code);
        if ($id)
            $find_goods->andwhere("id <> ?", $id);
        $find_goods->fetchOne();
        if (!$find_goods->isFind()){                                             
            $code = $find_code;         
        }
    }
        
    return $code;  
}


function get_orders_xml($date_export_orders, $currencies, $upd_orders){
    $countries = array();
    $cities = array();
    $regions = array();
    $users = array();
    $goods = array();
    $payment_type = array(); 
    $xml = '<?xml version="1.0" encoding="utf-8"?>
        <КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="'.date('Y-m-d').'"></КоммерческаяИнформация>';
    $xml = new SimpleXMLElement($xml);
    
    $orders = new seTable('shop_order');
    $orders->select('id, id_exchange, date_order, curr, payment_type, id_author, discount, status, commentary, delivery_payee, delivery_type, delivery_status, is_delete, transact_amount, transact_curr, updated_at, created_at');
    $orders->where("created_at >= '?'", $date_export_orders);
    if ($upd_orders)
        $orders->andwhere("(updated_at > date_exchange OR created_at > date_exchange)");
    $order_list = $orders->getList();

    foreach($order_list as $val){       
        $orders->find($val['id']);
        $orders->date_exchange = date('Y-m-d H:i:s');
        $orders->save();
        
        $time = strtotime($val['created_at']);
        $summ_order = $val['delivery_payee'];
        
        $document = $xml->addChild("Документ");
            if (!empty($val['id_exchange']))
                $document->addChild("Ид", $val['id_exchange']);
            else
                $document->addChild("Ид", $val['id']);
            $document->addChild("Номер", $val['id']);
            $document->addChild("Дата", date('Y-m-d', $time));
            $document->addChild("Время",  date('H:i:s', $time));
            $document->addChild("ХозОперация", "Заказ товара");
            $document->addChild("Роль", "Продавец" );
            if (isset($currencies[trim($val['curr'])]))
                $val['curr'] = $currencies[trim($val['curr'])];    
            $document->addChild("Валюта", $val['curr']);
            $document->addChild("Курс", "1");     
            $document->addChild("Комментарий", $val['commentary']);

        if (!empty($val['id_author'])){
            $kontragents = $document->addChild('Контрагенты');
            $kontragent = $kontragents->addChild('Контрагент');
            $id_user = $val['id_author'];
            if (empty($users[$id_user])){
                $user = new seTable('se_user');
                $user->select('username');
                $user->where('id = ?', $id_user); 
                $user->fetchOne();
                $users[$id_user]['username'] = $user->username;
                unset($user);
            
                $person = new seTable('person');
                $person->select('last_name, first_name, sec_name, sex, birth_date, email, post_index, country_id, state_id, town_id, addr, phone, icq');
                $person->where('id = ?', $id_user);
                $person->fetchOne();
                $users[$id_user]['last_name'] = $person->last_name;
                $users[$id_user]['first_name'] = $person->first_name;
                $users[$id_user]['sec_name'] = $person->sec_name;
                if ($person->sex == 'M')
                    $users[$id_user]['sex'] = 'М';
                elseif($person->sex == 'F')
                    $users[$id_user]['sex'] = 'Ж';    
                $users[$id_user]['birth_date'] = $person->birth_date;
                $users[$id_user]['email'] = $person->email;
                $users[$id_user]['post_index'] = $person->post_index;
                $users[$id_user]['addr'] = $person->addr;
                $users[$id_user]['phone'] = $person->phone;
                $users[$id_user]['country_id'] = $person->country_id;
                $users[$id_user]['state_id'] = $person->state_id;
                $users[$id_user]['town_id'] = $person->town_id;
                unset($person);
            }
            $kontragent->addChild("Ид", $id_user);
            $kontragent->addChild("Наименование", $users[$id_user]['username']);
            $kontragent->addChild("Роль", "Покупатель");
            $kontragent->addChild("ПолноеНаименование", $users[$id_user]['last_name'].' '.$users[$id_user]['first_name'].' '.$users[$id_user]['sec_name']);
            $kontragent->addChild("Фамилия", $users[$id_user]['last_name']);
            $kontragent->addChild("Имя", $users[$id_user]['first_name']);
            $kontragent->addChild("Отчество", $users[$id_user]['sec_name']);
            $kontragent->addChild("ДатаРождения", $users[$id_user]['birth_date']);
            if (!empty($users[$id_user]['sex']))
                $kontragent->addChild("Пол", $users[$id_user]['sex']);
            
            $contacts = $kontragent->addChild('Контакты');
            $contact = $contacts->addChild('Контакт');
            $contact->addChild('Тип', 'ТелефонРабочий');
            $contact->addChild('Значение', $users[$id_user]['phone']);  
            $contact = $contacts->addChild('Контакт');
            $contact->addChild('Тип', 'Почта');
            $contact->addChild('Значение', $users[$id_user]['email']);
            
            $full_addres = '';
            
            $addres = $kontragent->addChild("АдресРегистрации");
            
            if (!empty($users[$id_user]['post_index'])){
                $full_addres .= $users[$id_user]['post_index'];
                $addr_field = $addres->addChild('АдресноеПоле');
                $addr_field->addChild('Тип', 'Почтовый индекс');
                $addr_field->addChild('Значение', $users[$id_user]['post_index']);
            }
            if (!empty($users[$id_user]['country_id'])){
                $country_id = $users[$id_user]['country_id'];
                if (empty($countries[$country_id])){
                    $country = new seTable('country');
                    $country->select('name');
                    $country->where('id = ?', $country_id);
                    $country->fetchOne();
                    $countries[$country_id] = $country->name;
                    unset($country);
                }
                if (!empty($countries[$country_id])){
                    $addr_field = $addres->addChild('АдресноеПоле');
                    $addr_field->addChild('Тип', 'Страна');
                    $addr_field->addChild('Значение', $countries[$country_id]);
                }
            }
            
            if (!empty($users[$id_user]['state_id'])){
                $state_id = $users[$id_user]['state_id'];
                if (empty($regions[$state_id])){
                    $region = new seTable('region');
                    $region->select('name');
                    $region->where('id = ?', $state_id);
                    $region->fetchOne();
                    $regions[$state_id] = $region->name;
                    unset($region);    
                }
                if (!empty($regions[$state_id])){
                    if (!empty($full_addres))
                        $full_addres .= ', ';
                    $full_addres .= $regions[$state_id];
                    $addr_field = $addres->addChild('АдресноеПоле');
                    $addr_field->addChild('Тип', 'Регион');
                    $addr_field->addChild('Значение', $regions[$state_id]);
                }     
            }
            
            if (!empty($users[$id_user]['town_id'])){
                $city_id = $users[$id_user]['town_id'];
                if (empty($cities[$city_id])){
                    $town = new seTable('town');
                    $town->select('name');
                    $town->where('id = ?', $city_id);
                    $town->fetchOne();
                    $cities[$city_id] = $town->name;
                    unset($town);       
                }
                if (!empty($cities[$city_id])){
                    if (!empty($full_addres))
                        $full_addres .= ', ';
                    $full_addres .= $cities[$city_id];
                    $addr_field = $addres->addChild('АдресноеПоле');
                    $addr_field->addChild('Тип', 'Город');
                    $addr_field->addChild('Значение', $cities[$city_id]);    
                }
            }
            if (!empty($full_addres))
                $full_addres .= ', ';
            $full_addres .= $users[$id_user]['addr'];
            $addres->addChild('Представление', $full_addres);    
        }        

        $order_goods = new seTable('shop_tovarorder');
        $order_goods->select('id_price, article, nameitem, price, discount, count');
        $order_goods->where('id_order = ?', $val['id']);
        $order_goods_list = $order_goods->getList();
        
        $products = $document->addChild('Товары');
        
        foreach($order_goods_list as $val_goods){
            $product = $products->addChild('Товар');

            if(!empty($val_goods['id_price'])){
                $id_price = $val_goods['id_price'];  
                if(empty($goods[$id_price])){
                    $price = new seTable('shop_price');
                    $price->select('id, article, name, note, nds, id_exchange');
                    $price->where('id = ?', $id_price);
                    $price->fetchOne();
                    if ($price->isFind()){
                        $goods[$id_price]['article'] = $price->article;
                        $goods[$id_price]['name'] = $price->name;
                        $goods[$id_price]['note'] = $price->note;
                        $goods[$id_price]['nds'] = $price->nds;
                        $id_price_exchange = $price->id_exchange;
                    }
                    else{
                        $goods[$id_price]['article'] = $val_goods['article'];
                        $goods[$id_price]['name'] = $val_goods['nameitem'];
                        $goods[$id_price]['note'] = '';
                        $goods[$id_price]['nds'] = '';
                        $id_price_exchange = 0;
                    }
                    if (!empty($id_price_exchange))
                        $goods[$id_price]['id_exchange'] = $id_price_exchange;
                    else
                        $goods[$id_price]['id_exchange'] = $id_price;
                    unset($price);
                }
                $product->addChild("Ид", $goods[$id_price]['id_exchange']);
                $product->addChild("Артикул", $goods[$id_price]['article']);
                $product->addChild("Наименование", $goods[$id_price]['name']);
                $product->addChild("Описание", $goods[$id_price]['note']);
                $product->addChild("ЦенаЗаЕдиницу", $val_goods['price']);
                $product->addChild("Количество", $val_goods['count']);
                $product->addChild("Сумма", $val_goods['price'] * $val_goods['count']);
                
                if (!empty($goods[$id_price]['nds']) && $goods[$id_price]['nds'] > 0){
                    $taxes = $product->addChild("Налоги");
                    $tax = $taxes->addChild("Налог");
                    $tax->addChild("Наименование", 'НДС');
                    $tax->addChild("УчтеноВСумме", 'true');
                    //$tax->addChild("Сумма", '0');
                    //$tax->addChild("Ставка", '18');                            
                    
                    $rate_taxes = $product->addChild("СтавкиНалогов");
                    $rate_tax = $rate_taxes->addChild("СтавкаНалога");
                    $rate_tax->addChild("Наименование", 'НДС');
                    $rate_tax->addChild("Ставка", '18');    
                }
                
                if (!empty($val_goods['discount']) && $val_goods['discount'] > 0){
                    $discounts = $product->addChild("Скидки");
                    $discount = $discounts->addChild("Скидка"); 
                    $discount->addChild("Наименование", 'скидка товара');
                    $discount->addChild("Сумма", $val_goods['discount'] * $val_goods['count']);
                    $discount->addChild("УчтеноВСумме", 'false');
                }
                $summ_order += ($val_goods['count'] * ($val_goods['price'] - $val_goods['discount']));
                
                $properties = $product->addChild("ЗначенияРеквизитов");
                $property = $properties->addChild("ЗначениеРеквизита");
                $property->addChild("Наименование", "ВидНоменклатуры");
                $property->addChild("Значение", "Товар");
                $property = $properties->addChild("ЗначениеРеквизита");
                $property->addChild("Наименование", "ТипНоменклатуры");
                $property->addChild("Значение", "Товар");
            }
            else{
                $product->addChild("Ид", 'ORDER_SERVICE');
                $product->addChild("Наименование", $val_goods['nameitem']);
                $product->addChild("ЦенаЗаЕдиницу", $val_goods['price']);
                $product->addChild("Количество", $val_goods['count']);
                $product->addChild("Сумма", $val_goods['price'] * $val_goods['count']);
                
                if (!empty($val_goods['discount']) && $val_goods['discount'] > 0){
                    $discounts = $product->addChild("Скидки");
                    $discount = $discounts->addChild("Скидка"); 
                    $discount->addChild("Наименование", 'скидка услуги');
                    $discount->addChild("Сумма", $val_goods['discount'] * $val_goods['count']);
                    $discount->addChild("УчтеноВСумме", 'false');
                }
                
                $properties = $product->addChild("ЗначенияРеквизитов");
                $property = $properties->addChild("ЗначениеРеквизита");
                $property->addChild("Наименование", "ВидНоменклатуры");
                $property->addChild("Значение", "Услуга");
                $property = $properties->addChild("ЗначениеРеквизита");
                $property->addChild("Наименование", "ТипНоменклатуры");
                $property->addChild("Значение", "Услуга");    
            }
        }
        
        $delivery_status = '';
        if (!empty($val['delivery_type']) && $val['delivery_payee'] > 0){
            $product = $products->addChild('Товар');
            $product->addChild("Ид", 'ORDER_DELIVERY');
            $product->addChild("Наименование", 'Доставка заказа');
            $product->addChild("ЦенаЗаЕдиницу", $val['delivery_payee']);
            $product->addChild("Количество", 1);
            $product->addChild("Сумма", $val['delivery_payee']);
            
            $properties = $product->addChild("ЗначенияРеквизитов");
            $property = $properties->addChild("ЗначениеРеквизита");
            $property->addChild("Наименование", "ВидНоменклатуры");
            $property->addChild("Значение", "Услуга");
            $property = $properties->addChild("ЗначениеРеквизита");
            $property->addChild("Наименование", "ТипНоменклатуры");
            $property->addChild("Значение", "Услуга");
            
            switch ($val['delivery_status']){
                case 'N':
                    $delivery_status = 'Нет';
                    break;
                case 'Y':
                    $delivery_status = 'Доставлено';
                    break;
                case 'M':
                    $delivery_status = 'В работе';
                    break;
                case 'P':
                    $delivery_status = 'Отправлено';
                    break;
            }
    
        }
        
        $document->addChild("Сумма", $summ_order);
        if ($val['discount'] > 0){
            $discounts = $document->addChild("Скидки");
            $discount = $discounts->addChild("Скидка"); 
            $discount->addChild("Наименование", 'скидка заказа');
            //$discount->addChild("Процент", '5.00');
            $discount->addChild("Сумма", $val['discount']);
            $discount->addChild("УчтеноВСумме", 'false');
        }
              
        switch ($val['status']){            
            case 'Y':
                $status = 'Оплачен';
                break;  
            case 'N':
                $status = 'Не оплачен';
                break;            
            case 'K':
                $status = 'Кредит';
                break;            
            case 'P':
                $status = 'Подарок';
                break;   
        }
        $properties = $document->addChild('ЗначенияРеквизитов');  
        
        $property = $properties->addChild('ЗначениеРеквизита');   
        $property->addChild('Наименование', 'Статус заказа');
        $property->addChild('Значение', $status);                
                
        $property = $properties->addChild('ЗначениеРеквизита');
        $property->addChild('Наименование', 'Отменен');
        if ($val['is_delete'] == 'Y')
            $property->addChild('Значение', 'true');
        else
            $property->addChild('Значение', 'false');
                
        if (!empty($delivery_status)){
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Статус доставки');
            $property->addChild('Значение', $delivery_status);
        }
            
        if (!empty($val['payment_type']) && $val['transact_amount'] > 0 && $val['status'] == 'Y'){
            $id_payment = $val['payment_type'];
            if (empty($payment_type[$id_payment])){
                $payment = new seTable('shop_payment');
                $payment->select('name_payment');
                $payment->where('id = ?', $id_payment);
                $payment->fetchOne();
                $payment_type[$id_payment] = $payment->name_payment;
                unset($payment); 
            }
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Оплачен');
            $property->addChild('Значение', 'true');
            
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Метод оплаты');
            $property->addChild('Значение', $payment_type[$id_payment]);
            
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Сумма оплаты');
            $property->addChild('Значение', $val['transact_amount']);
            
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Валюта оплаты');
            if (isset($currencies[trim($val['transact_curr'])]))
                $val['transact_curr'] = $currencies[trim($val['transact_curr'])];
            $property->addChild('Значение', $val['transact_curr']);    
        }
    }
           
    return $xml->asXML();                                
}

function update_order($xml, $new_status, $date_order, $date_payee){
    
    $status_list = array('Y', 'N', 'K', 'P');
    
    $order = new seTable('shop_order');
    $order->find((int)$xml->Номер);
    
    $order->id_exchange = $xml->Ид;
    
    if(isset($xml->ЗначенияРеквизитов->ЗначениеРеквизита)){
        foreach($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property){

            switch ($property->Наименование){
                case 'ПометкаУдаления':
                    $order->is_delete = ($property->Значение == 'true') ? 'Y' : 'N';
                    break;
                case 'Проведен':
                    if (!empty($new_status) && in_array($new_status, $status_list) && ($property->Значение == 'true')){
                        $order->status = $new_status;
                    }
                    break;
                case 'Номер по 1С': 
                    $order->number_1c = $property->Значение;
                    break;
                case 'Дата по 1С':
                    if ($date_order){ 
                        $time = strtotime($property->Значение);
                        $order->date_order = date('Y-m-d', $time);
                    }
                    break;
                case 'Номер оплаты по 1С': 
                    break;
                case 'Дата оплаты по 1С':
                    if ($date_payee){ 
                        $time = strtotime($property->Значение);
                        $order->date_payee = date('Y-m-d', $time);
                    } 
                    break;
            }                       
        }
    }
    $order->date_exchange = date('Y-m-d H:i:s');
    $order->save();    
}

function delete_product($id_ex){
    $del_price = new seTable('shop_price');
    $del_price->select('id');
    $del_price->where("id_exchange = '?'", $id_ex);
    $del_price->fetchOne();
    $id = $del_price->id;
    if (!empty($id)){
        $del_price->delete($id);
        $del_img = new seTable('shop_img');
        $del_img->where('id_price = ?', $id)->deletelist();
    }    
}

function import_catalog($xml, $exchange_dir, $type_image, $code_type, $lang, $ex_catalog_name, $update_data){
    $imagelist = array();
    $add_image = $first_image = $last_image = $manufacturer = '';
    $images_dir = getcwd().'/images/'.$lang.'/shopprice/';
    $images_dir_ext = getcwd().'/images/'.$lang.'/shopimg/';
    @list($id_product_ex, $id_offer_ex) = explode('#', $xml->Ид);

    if ($id_product_ex){
        if($xml->Статус == 'Удален' && $update_data['delete']){
            delete_product($id_product_ex);
            return;   
        }
        
        if (($update_data['main_image'] || $update_data['more_image'])){
        
            if(isset($xml->Картинка)){ 
  
                foreach($xml->Картинка as $image){
                    $image_name = basename($image);
                    
                    if (is_file($exchange_dir.$image_name) && !empty($image_name)){
                        //if (!$add_image)
                        //    se_rename($exchange_dir.$image_name, $images_dir.$image_name);
                        //else
                            se_rename($exchange_dir.$image_name, $images_dir.$image_name);
                    } 
                    if (file_exists($images_dir.$image_name)){
                        if (!$first_image){
                            //$goods->img = $image_name;
                            $first_image = $image_name;
                        }
                        $imagelist[] = $image_name;
                        $last_image = $image_name;   
                    }          
                }
            }
        
            if (!empty($imagelist)){
                if ($type_image == 'first'){
                    $add_image = $first_image;
                    unset($imagelist[array_search($first_image, $imagelist)]);
                }
                else{
                    $add_image = $last_image;
                    unset($imagelist[array_search($last_image, $imagelist)]);    
                }
            }
        }

        if (isset($xml->ЗначенияСвойств->ЗначенияСвойства) && isset($_SESSION['exchange_manufacturer']['id'])){
            foreach($xml->ЗначенияСвойств->ЗначенияСвойства as $property){
                if ($_SESSION['exchange_manufacturer']['id'] == $property->Ид){
                    if ($_SESSION['exchange_manufacturer']['type'] == 'list'){
                        $manufacturer = $_SESSION['exchange_manufacturer']['values'][(string)$property->Значение];
                    }
                    else{
                        $manufacturer = $property->Значение;
                    }
                }                             
            }
        }   
        
        $goods = new seTable('shop_price');
        $goods->select('id');
        $goods->where("id_exchange = '?'", $id_product_ex);
        $goods->fetchOne();
        $id_price = $id_group = '';

        if ($goods->isFind())
            $id_price = $goods->id;
        elseif($ex_catalog_name != 1){
            $goods->select('id');
            if ($ex_catalog_name == 2)
                $goods->where("name = '?'", trim($xml->Наименование));
            elseif($ex_catalog_name == 3)
                $goods->where("article = '?'", trim($xml->Артикул));
            elseif($ex_catalog_name == 4){
                $goods->where("article = '?'", trim($xml->Артикул));
                $goods->orwhere("name = '?'", trim($xml->Наименование));
            }
            else{
                $goods->where("name = '?'", trim($xml->Наименование));
                $goods->andwhere("article = '?'", trim($xml->Артикул));
            }
            if (isset($xml->Группы->Ид)){
                $group = new seTable('shop_group');
                $group->select('id, name');
                $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                $group->fetchOne();
                $id_group = $group->id;
                unset($group);
            }
            $goods->andwhere("id_group = '?'", $id_group);
            $goods->fetchOne();
            $id_price = $goods->id;    
        }
        if ($id_price){
            if ($update_data['group']){
                if (isset($xml->Группы->Ид)){
                    $group = new seTable('shop_group');
                    $group->select('id');
                    $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                    $group->fetchOne();
                    $id_group = $group->id;
                }            
                if ($id_group)
                    $goods->id_group = $id_group;
            }
                            
            if ($update_data['article'])
                $goods->article = trim($xml->Артикул);
            if ($update_data['name'])
                $goods->name = trim($xml->Наименование);
            if ($update_data['manufacturer'])
                $goods->manufacturer = $manufacturer;
            if (!empty($xml->Описание) && $update_data['note'])
                $goods->note = $xml->Описание;
            if (!empty($add_image) && $update_data['main_image'])
                $goods->img = $add_image;            
            if ($update_data['code'])
                $goods->code = get_code_goods($xml, $code_type, $id_price);               
            if ($update_data['measure'])
                $goods->measure = trim($xml->БазоваяЕдиница);
                           
            if ($update_data['weight']){
                foreach($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property){
                    if ($property->Наименование == 'Вес'){
                        $goods->weight = (int)$property->Значение;
                        break;
                    }
                }
            }
            
            if ($update_data['text']){          
                foreach($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property){
                
                    if ($property->Наименование == 'ОписаниеВФорматеHTML'){
                        if (trim($property->Значение))
                            $goods->text = iconv("utf-8", "windows-1251", trim($property->Значение));
                        break;
                    }
                    elseif ($property->Наименование == 'Файл'){                       
                        @list($file_name, $description_file) = explode('#', trim($property->Значение));
                        $file_name = $exchange_dir.basename($file_name);
                        if (is_file($file_name)){
                            $goods->text = trim(se_file_get_contents($file_name));                               
                            break;    
                        }
                    }   
                }    
            }
            $goods->id_exchange = trim($id_product_ex);
            $id_price = $goods->save();
            
            if (!empty($imagelist) && $update_data['more_image']){
                add_images($imagelist, $id_price);
            }             
        }
        else{
            $goods->insert();
            $goods->id_exchange = trim($id_product_ex);
            if ($lang) 
                $goods->lang = $lang;
            else
                $goods->lang = 'rus';
            if ($xml->Группы->Ид){
                $group = new seTable('shop_group');
                $group->select('id, name');
                $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                $group->fetchOne();
                $id_group = $group->id;
            }
            if ($id_group)
                $goods->id_group = $id_group;
            
            $goods->code = get_code_goods($xml, $code_type);
            
            if (trim($xml->Артикул))           
                $goods->article = trim($xml->Артикул);
            if (trim($xml->Наименование))
                $goods->name = trim($xml->Наименование);
            if (trim($xml->Описание))
            $goods->note = trim($xml->Описание);
            
            if ($manufacturer)
                $goods->manufacturer = $manufacturer;
            
            if (isset($xml->ПолноеНаименование)){
                $goods->title = trim($xml->ПолноеНаименование);
                $goods->keywords = trim($xml->ПолноеНаименование).', '.$group->name;
            }
            else{
                $goods->title = trim($xml->Наименование);
                $goods->keywords = trim($xml->Наименование).', '.$group->name;
            }
            
            if (isset($xml->СтавкиНалогов->СтавкаНалога)){
                foreach ($xml->СтавкиНалогов->СтавкаНалога as $nalog){
                    if ($nalog->Наименование == 'НДС'){
                        $nds = $nalog->Ставка;
                        break;    
                    }    
                }            
            }            
 
            if (!empty($nds))                     
                $goods->nds = $nds;
            if (trim($xml->БазоваяЕдиница))
                $goods->measure = trim($xml->БазоваяЕдиница);
            
            if (!empty($add_image))
                $goods->img = $add_image;
            
            if (isset($xml->ЗначенияРеквизитов->ЗначениеРеквизита)){
                foreach($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property){
                    if ($property->Наименование == 'Вес'){
                        $goods->weight = (int)$property->Значение;
                        continue;
                    }
                    
                    if ($property->Наименование == 'ОписаниеВФорматеHTML'){
                        if (trim($property->Значение))
                            $goods->text = iconv("utf-8", "windows-1251", trim($property->Значение));
                        continue;
                    }
                    
                    if ($property->Наименование == 'Файл'){                       
                        @list($file_name, $description_file) = explode('#', trim($property->Значение));
                        $file_name = $exchange_dir.basename($file_name);
                        if (is_file($file_name)){
                            $goods->text = trim(se_file_get_contents($file_name));                               
                            continue;    
                        }
                    }
                    
                }
            }
            $goods->presence_count = 0;
            $id_price = $goods->save();
            
            if (!empty($imagelist)){
                add_images($imagelist, $id_price);
            }
              
        }                                                 
    }
}

function add_images($imagelist, $id_price){
    $shop_img = new seTable('shop_img');
    $shop_img->where("id_price = ?", $id_price)->deletelist();
    foreach ($imagelist as $image){
        $shop_img->select('id');
        $shop_img->where("picture = '?'", $image);
        $shop_img->andwhere("id_price = ?", $id_price);
        $shop_img->fetchOne();
        if ($shop_img->isFind()){
            continue;
        }
        else{
            $shop_img->insert();
            $shop_img->id_price = $id_price;
            $shop_img->picture = $image;
            $shop_img->save();
        }        
    }    
}

function import_offers($xml, $default_param_name, $update_data){
    $id_product_ex = $id_offer_ex = $price_opt = $price_opt_corp = $price_bonus = $price = 0;
    @list($id_product_ex, $id_offer_ex) = explode('#', $xml->Ид);
    
    if($xml->Статус == 'Удален' && $id_offer_ex && $update_data['delete']){
        $del_param = new seTable('shop_price_param');
        $del_param->where("id_exchange = '?'", $id_offer_ex)->deletelist();
        return;   
    }
    
    if ($id_product_ex){
        
        $goods = new seTable('shop_price');
        $goods->select('id, name');
        $goods->where("id_exchange = '?'", $id_product_ex);
        $goods->fetchOne();  
        if ($goods->isFind()){
            if (isset($xml->Цены->Цена)){
                foreach ($xml->Цены->Цена as $type_price){
                    $price = $type_price->ЦенаЗаЕдиницу;
                    
                    if (!empty($id_offers_ex)){
                        if (trim($type_price->ИдТипаЦены) == trim($_SESSION['exchange_type_price']['main'])){
                            $price = $type_price->ЦенаЗаЕдиницу;
                            break;
                        }
                    }
                    else{
                        switch (trim($type_price->ИдТипаЦены)){
                            case trim($_SESSION['exchange_type_price']['main']):
                                $price = $type_price->ЦенаЗаЕдиницу;
                                break;
                            case trim($_SESSION['exchange_type_price']['opt']):
                                $price_opt = $type_price->ЦенаЗаЕдиницу;
                                break;
                            case trim($_SESSION['exchange_type_price']['opt_corp']):
                                $price_opt_corp = $type_price->ЦенаЗаЕдиницу;
                                break;
                            case trim($_SESSION['exchange_type_price']['bonus']):
                                $price_bonus = $type_price->ЦенаЗаЕдиницу;
                                break;
                        }
                    }                         
                }
            }
            if ($id_offer_ex){
                if ($xml->ХарактеристикиТовара->ХарактеристикаТовара){
                    $parent_id = '';
                    foreach($xml->ХарактеристикиТовара->ХарактеристикаТовара as $params){
                        $parent_id = import_params($params->Наименование, $params->Значение, $goods->id, $parent_id);           
                    }
                    $price_params = new seTable('shop_price_param');
                    $price_params->find($parent_id);
                    if ($update_data['price'])
                        $price_params->price = $price;
                    if ($update_data['count'])
                        $price_params->count = (int)$xml->Количество;
                    $price_params->id_exchange = $id_offer_ex;
                    $price_params->save();
                }
                else{
                    $value_param = trim(str_replace(array($goods->name,'(',')'), '', $xml->Наименование));
                    $parent_id = import_params($default_param_name, $value_param, $goods->id, '');
                    
                    $price_params = new seTable('shop_price_param');
                    $price_params->update('id_exchange', "'$id_offer_ex'");
                    if ($update_data['price'])
                        $price_params->addupdate('price', $price);
                    if ($update_data['count'])
                        $price_params->addupdate('count', (int)$xml->Количество);
                    $price_params->where("id = ?", $parent_id);
                    $price_params->save();
                                
                }
                if ($update_data['count'])
                    $goods->presence_count = -1;
                $goods->save();                             
            }
            else{
                if ($update_data['price']){
                    $goods->price = $price;
                    if ($price_opt)
                        $goods->price_opt = $price_opt;
                    if ($price_opt_corp)
                        $goods->price_opt_corp = $price_opt_corp;
                    if ($price_bonus)
                        $goods->bonus = $price_bonus;
                }                
                if ($update_data['count'])
                    $goods->presence_count = ((int)$xml->Количество > 0) ? (int)$xml->Количество : 0;
                    
                $goods->save();
            }
        
        }
    }
}

function add_param($name){
    $params = new seTable('shop_param');
    $params->select('id');
    $params->where("nameparam = '?'", $name);
    $params->fetchOne();
    if ($params->isFind()){
        return $params->id;    
    }
    else{
        $params->insert();
        $params->nameparam = $name;
        return $params->save();
    }    
}

function import_params($name, $value, $price_id, $up_id){

    $param_id = add_param($name);
    
    $price_params = new seTable('shop_price_param');
    $price_params->select('id');
    $price_params->where("price_id = '?'", $price_id);
    $price_params->andwhere("param_id = '?'", $param_id);
    $price_params->andwhere("value = '?'", $value);
    $price_params->andwhere("(parent_id = '?' OR parent_id IS NULL)", $up_id);
    $price_params->fetchOne();
    if ($price_params->isFind()){
        return $price_params->id;                   
    }
    else{
        $price_params->insert();
        $price_params->price_id = $price_id;
        $price_params->param_id = $param_id;       
        if ($up_id ){
            $price_params->parent_id = $up_id;
        }
        $price_params->value = $value;
        return $price_params->save();         
    }
}

function import_groups($xml, $up_id = '', $code_type, $lang, $ex_group_name, $update_data){
    if(isset($xml->Группы->Группа)){
        foreach ($xml->Группы->Группа as $xml_group){
            $id = 0;
            $groups = new seTable('shop_group');
            $groups->select('id');
            $groups->where("id_exchange = '?'", $xml_group->Ид);
            $groups->fetchOne();
            $id = $groups->id; 
            if ($id){
                if ($update_data['name'])
                    $groups->name = $xml_group->Наименование;
                $groups->save();
            }
            else{
                $id = 0;
                if ($ex_group_name){
                    $groups->select('id');
                    $groups->where("name = '?'", trim($xml_group->Наименование));
                    if ($up_id)
                        $groups->andwhere("upid = ?", $up_id);
                    else 
                        $groups->andwhere("upid IS NULL OR upid=0");
                    $groups->fetchOne();
                    $id = $groups->id;
                }
                if ($id){
                    $groups->id_exchange = $xml_group->Ид;
                    $groups->save();
                }
                else{
                    $groups->insert();
                    $groups->name = $xml_group->Наименование;
                    if ($lang)
                        $groups->lang = $lang;
                    $groups->title = $xml_group->Наименование;
                    $groups->keywords = $xml_group->Наименование;
                    if ($code_type == 'translit'){
                        $translit_name = substr(translit($xml_group->Наименование), 0, 40);
                        $find_group = new seTable('shop_group');
                        $find_group->select('id');
                        $find_group->where("code_gr = '?'", $translit_name);
                        $find_group->fetchOne();
                        if ($find_group->isFind())
                            $groups->code_gr = $translit_name;
                        else
                            $groups->code_gr = trim($xml_group->Ид);
                    }
                    else
                        $groups->code_gr = trim($xml_group->Ид);
                    $groups->id_exchange = trim($xml_group->Ид);
                    if ($up_id){
                        $groups->upid = $up_id;
                    }
                    $id = $groups->save();
                }
            }
            
            import_groups($xml_group, $id, $code_type, $lang, $ex_group_name, $update_data); 
        }
    }
}

function import_properties($xml, $manufacturer){
    if (isset($xml->Свойства->Свойство))
        $properties = $xml->Свойства->Свойство;
    elseif (isset($xml->Свойства->СвойствоНоменклатуры))
        $properties = $xml->Свойства->СвойствоНоменклатуры;
    else
        return;
    foreach ($properties as $property){
        if (strtolower(trim($property->Наименование)) == strtolower(trim($manufacturer))){
            $_SESSION['exchange_manufacturer']['id'] = (string)$property->Ид;
            if (isset($property->ВариантыЗначений)){
                $list_val = $property->ВариантыЗначений; 
                $_SESSION['exchange_manufacturer']['type'] = 'list';                              
                if (isset($list_val->Справочник)){
                    foreach($list_val->Справочник as $val){
                        $_SESSION['exchange_manufacturer']['values'][(string)$val->ИдЗначения] = (string)$val->Значение; 
                    }
                }    
            }
            else
                $_SESSION['exchange_manufacturer']['type'] = 'value';
        }
        else
            continue;
    }
}

function unzip($file, $folder=''){
    $zip = zip_open($folder.$file);
    $files = 0;
    $folders = 0;

    if ($zip){
        
        while ($zip_entry = zip_read($zip)){
            $filename = basename(zip_entry_name($zip_entry));
            $name = $folder.$filename;
            /*
            $path_parts = se_pathinfo($name);
            
            if(!is_dir($path_parts['dirname'])){
                mkdir($path_parts['dirname'], 0755, true);
            }
            */
            if (zip_entry_open($zip, $zip_entry, "r")){
                $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                $file = se_fopen($name, "wb");
                if ($file) {
                    fwrite($file, $buf);
                    fclose($file);
                    $files++;
                } 
                zip_entry_close($zip_entry);
            }
            
        }
        zip_close($zip);
    }
    return $filename; 
}

function translit($str, $dir = 'en', $type = 'url'){

    $ru = array(0 => 'А', 1 => 'а', 2 => 'Б', 3 => 'б', 4 => 'В', 5 => 'в', 6 => 'Г', 7 => 'г', 8 => 'Д', 9 => 'д', 10 => 'Е', 11 => 'е', 12 => 'Ё', 13 => 'ё', 14 => 'Ж', 15 => 'ж', 16 => 'З', 17 => 'з', 18 => 'И', 19 => 'и', 20 => 'Й', 21 => 'й', 22 => 'К', 23 => 'к', 24 => 'Л', 25 => 'л', 26 => 'М', 27 => 'м', 28 => 'Н', 29 => 'н', 30 => 'О', 31 => 'о', 32 => 'П', 33 => 'п', 34 => 'Р', 35 => 'р', 36 => 'С', 37 => 'с', 38 => 'Т', 39 => 'т', 40 => 'У', 41 => 'у', 42 => 'Ф', 43 => 'ф', 44 => 'Х', 45 => 'х', 46 => 'Ц', 47 => 'ц', 48 => 'Ч', 49 => 'ч', 50 => 'Ш', 51 => 'ш', 52 => 'Щ', 53 => 'щ', 54 => 'Ъ', 55 => 'ъ', 56 => 'Ы', 57 => 'ы', 58 => 'Ь', 59 => 'ь', 60 => 'Э', 61 => 'э', 62 => 'Ю', 63 => 'ю', 64 => 'Я', 65 => 'я');

    $en = array(0 => 'A', 1 => 'a', 2 => 'B', 3 => 'b', 4 => 'V', 5 => 'v', 6 => 'G', 7 => 'g', 8 => 'D', 9 => 'd', 10 => 'E', 11 => 'e', 12 => 'E', 13 => 'e', 14 => 'ZH', 15 => 'zh', 16 => 'Z', 17 => 'z', 18 => 'I', 19 => 'i', 20 => 'J', 21 => 'j', 22 => 'K', 23 => 'k', 24 => 'L', 25 => 'l', 26 => 'M', 27 => 'm', 28 => 'N', 29 => 'n', 30 => 'O', 31 => 'o', 32 => 'P', 33 => 'p', 34 => 'R', 35 => 'r', 36 => 'S', 37 => 's', 38 => 'T', 39 => 't', 40 => 'U', 41 => 'u', 42 => 'F', 43 => 'f', 44 => 'H', 45 => 'h', 46 => 'TS', 47 => 'ts', 48 => 'CH', 49 => 'ch', 50 => 'SH', 51 => 'sh', 52 => 'SCH', 53 => 'sch', 54 => '', 55 => '', 56 => 'Y', 57 => 'y', 58 => '', 59 => '', 60 => 'E', 61 => 'e', 62 => 'YU', 63 => 'yu', 64 => 'YA', 65 => 'ya');
 
    if ($dir == 'en')
        $str = str_replace($ru, $en, $str);
    else
        $str = str_replace($en, $ru, $str);
  
    if ($type == 'url'){
        $str = preg_replace("/[\s]+/ui", '_', $str);
        $str = preg_replace("/[^0-9a-zа-я\_\-]+/ui", '', $str);
        $str = strtolower($str);
    }
 
    return $str;
}

function exchange_log($text, $log_file = 'exchange_log.txt'){
    $text = date('[Y-m-d H:i:s]').' '.$text."\r\n";
    $file = se_fopen($log_file, 'ab');
    fwrite($file, $text);
    fclose($file);    
}
?>