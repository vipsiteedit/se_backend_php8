<?php

function getExternalCode($prefix = 'product')
{
    $pass = md5($_SERVER['HTTP_HOST'] . uniqid($prefix, true));
    return substr($pass,  0, 8) . '-' . substr($pass, 8, 4) . '-' . substr($pass, 12,  4) . '-' . substr($pass, 16, 4) . '-' . substr($pass, 20, 12);
}

function create_guid($namespace = "")
{
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = hash('ripemd128', $uid . $guid . md5($data));
    $guid = substr($hash,  0,  8) .
        '-' . substr($hash,  8,  4) .
        '-' . substr($hash, 12,  4) .
        '-' . substr($hash, 16,  4) .
        '-' . substr($hash, 20, 12);
    return $guid;
}

function get_code_goods($xml, $type = 'translit', $id = 0, $length = 255)
{

    $code = substr(trim($xml->Ид), 0, $length);

    if ($type == 'translit' && $xml->Наименование) {
        $find_code = substr(translit($xml->Наименование), 0, $length);
    } elseif ($type == 'article' && $xml->Артикул) {
        $find_code = substr(trim($xml->Артикул), 0, $length);
    } elseif ($type == 'barcode' && (trim($xml->ШтрихКод) || trim($xml->Штрихкод))) {
        $find_code = (trim($xml->ШтрихКод)) ? substr(trim($xml->ШтрихКод), 0, $length) : substr(trim($xml->Штрихкод), 0, $length);
    }

    if ($find_code) {
        $find_goods = new seTable('shop_price');
        $find_goods->select('id');
        $find_goods->where("code = '?'", $find_code);
        if ($id)
            $find_goods->andwhere("id <> ?", $id);
        $find_goods->fetchOne();
        if (!$find_goods->isFind()) {
            $code = $find_code;
        }
    }

    return $code;
}

function getIdBrand($value = '')
{
    $id = 0;
    if ($value) {
        $sb = new seTable('shop_brand');
        $sb->select('id');
        $sb->where('name = "?"', $value);
        $sb->fetchOne();
        if ($sb->isFind())
            $id = $sb->id;
        else {
            $sb->insert();
            $sb->name = $value;
            $sb->code = translit($value);
            $id = $sb->save();
        }
    }
    return $id;
}

function getMeasure($xml)
{
    $measure = '';

    if (!empty($xml)) {
        $children = $xml->children();
        if (count($children) > 0) {
            if (!empty($xml['НаименованиеПолное']))
                $measure = trim($xml['НаименованиеПолное']);
        } else
            $measure = trim($xml);
    }
    return $measure;
}

function getUserRequisites($id_user)
{
    $requisites = array();

    $user_urid = new seTable('user_urid');
    $user_urid->find($id_user);
    if ($user_urid->isFind()) {
        $requisites[] = array(
            'name' => 'ИмяКомпании',
            'value' => $user_urid->company
        );
        $requisites[] = array(
            'name' => 'Директор',
            'value' => $user_urid->director
        );
        $requisites[] = array(
            'name' => 'Телефон',
            'value' => $user_urid->tel
        );
        $requisites[] = array(
            'name' => 'Факс',
            'value' => $user_urid->fax
        );
        $requisites[] = array(
            'name' => 'ФизАдрес',
            'value' => $user_urid->fizadres
        );
        $requisites[] = array(
            'name' => 'ЮрАдрес',
            'value' => $user_urid->uradres
        );
    }

    $user_rekv = new seTable('user_rekv', 'ur');
    $user_rekv->select('ur.value, urt.title, urt.code');
    $user_rekv->innerJoin('user_rekv_type urt', 'urt.code=ur.rekv_code');
    $user_rekv->where('ur.id_author=?', $id_user);
    $list = $user_rekv->getList();

    if (!empty($list)) {
        foreach ($list as $val) {
            $requisites[] = array(
                'name' => $val['title'],
                'value' => $val['value']
            );
        }
    }

    return $requisites;
}

function get_orders_xml($date_export_orders, $currencies, $upd_orders)
{
    $countries = array();
    $cities = array();
    $regions = array();
    $users = array();
    $goods = array();
    $payment_type = array();
    $xml = '<?xml version="1.0" encoding="utf-8"?>
        <КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d') . '"></КоммерческаяИнформация>';
    $xml = new SimpleXMLElement($xml);

    $orders = new seTable('shop_order');
    $orders->select('id, id_exchange, date_order, curr, payment_type, id_author, discount, status, commentary, delivery_payee, delivery_type, delivery_status, is_delete, transact_amount, transact_curr, updated_at, created_at, (SELECT sdt.name FROM shop_deliverytype sdt WHERE sdt.id=delivery_type LIMIT 1) as delivery_name');
    $orders->where("created_at >= '?'", $date_export_orders);
    if ($upd_orders)
        $orders->andwhere('(updated_at > date_exchange OR created_at > date_exchange)');
    $order_list = $orders->getList();

    $count_orders = count($order_list);
    $i = 0;

    $orders = new seTable('shop_order');
    $orders->select('id, date_exchange');

    foreach ($order_list as $val) {
        if (empty($val['id_author']))
            continue;

        $orders->find($val['id']);
        $orders->date_exchange = date('Y-m-d H:i:s');
        $orders->save();

        $time = strtotime($val['created_at']);
        $summ_order = $val['delivery_payee'];

        $document = $xml->addChild('Документ');
        if (!empty($val['id_exchange']))
            $document->addChild('Ид', $val['id_exchange']);
        else {
            $document->addChild('Ид', $_SERVER['HTTP_HOST'] . '_order_' . $val['id']);
        }

        $document->addChild('Номер', $val['id']);
        $document->addChild('Дата', date('Y-m-d', $time));
        $document->addChild('Время',  date('H:i:s', $time));
        $document->addChild('ХозОперация', 'Заказ товара');
        $document->addChild('Роль', 'Продавец');
        if (isset($currencies[trim($val['curr'])])) {
            $val['curr'] = $currencies[trim($val['curr'])];
        }
        $document->addChild('Валюта', $val['curr']);
        $document->addChild('Курс', '1');
        if (!empty($val['commentary']))
            $document->addChild('Комментарий', $val['commentary']);

        if (!empty($val['id_author'])) {
            $kontragents = $document->addChild('Контрагенты');
            $kontragent = $kontragents->addChild('Контрагент');
            $id_user = $val['id_author'];

            if (empty($users[$id_user])) {
                $person = new seTable('person', 'p');
                $person->select('(SELECT username FROM se_user as u WHERE u.id=p.id) as username, p.last_name, p.first_name, p.sec_name, p.sex, p.birth_date, p.email, p.post_index, p.country_id, p.state_id, p.town_id, p.addr, p.phone, p.note');
                $person->where('id = ?', $id_user);
                $person->fetchOne();
                $users[$id_user]['username'] = $person->username;
                $users[$id_user]['last_name'] = $person->last_name;
                $users[$id_user]['first_name'] = $person->first_name;
                $users[$id_user]['sec_name'] = $person->sec_name;
                if ($person->sex == 'M')
                    $users[$id_user]['sex'] = 'М';
                elseif ($person->sex == 'F')
                    $users[$id_user]['sex'] = 'Ж';
                $users[$id_user]['birth_date'] = $person->birth_date;
                $users[$id_user]['email'] = $person->email;
                $users[$id_user]['post_index'] = $person->post_index;
                $users[$id_user]['addr'] = $person->addr;
                $users[$id_user]['phone'] = $person->phone;
                $users[$id_user]['country_id'] = $person->country_id;
                $users[$id_user]['state_id'] = $person->state_id;
                $users[$id_user]['town_id'] = $person->town_id;
                $users[$id_user]['comment'] = $person->note;

                $users[$id_user]['requisites'] = getUserRequisites($id_user);

                unset($person);
            }
            $kontragent->addChild('Идентификатор', $id_user);
            $kontragent->addChild('Ид', $_SERVER['HTTP_HOST'] . '_user_' . $id_user);
            $kontragent->addChild('Наименование', $users[$id_user]['username']);
            $kontragent->addChild('Роль', 'Покупатель');
            $kontragent->addChild('ПолноеНаименование', $users[$id_user]['last_name'] . ' ' . $users[$id_user]['first_name'] . ' ' . $users[$id_user]['sec_name']);
            $kontragent->addChild('Фамилия', $users[$id_user]['last_name']);
            $kontragent->addChild('Имя', $users[$id_user]['first_name']);
            $kontragent->addChild('Отчество', $users[$id_user]['sec_name']);
            if (!empty($users[$id_user]['birth_date']))
                $kontragent->addChild('ДатаРождения', $users[$id_user]['birth_date']);
            if (!empty($users[$id_user]['sex']))
                $kontragent->addChild('Пол', $users[$id_user]['sex']);
            if (!empty($users[$id_user]['comment']))
                $kontragent->addChild('Комментарий', $users[$id_user]['comment']);

            if (!empty($users[$id_user]['requisites'])) {
                foreach ($users[$id_user]['requisites'] as $req) {
                    $kontragent->addChild($req['name'], $req['value']);
                }
            }

            $contacts = $kontragent->addChild('Контакты');
            $contact = $contacts->addChild('Контакт');
            $contact->addChild('Тип', 'Почта');
            $contact->addChild('Значение', $users[$id_user]['email']);
            if (!empty($users[$id_user]['phone'])) {
                $contact = $contacts->addChild('Контакт');
                $contact->addChild('Тип', 'ТелефонРабочий');
                $contact->addChild('Значение', $users[$id_user]['phone']);
            }
            $full_addres = '';

            $addres = $kontragent->addChild('АдресРегистрации');

            if (!empty($users[$id_user]['post_index'])) {
                $full_addres .= $users[$id_user]['post_index'];
                $addr_field = $addres->addChild('АдресноеПоле');
                $addr_field->addChild('Тип', 'Почтовый индекс');
                $addr_field->addChild('Значение', $users[$id_user]['post_index']);
            }
            if (!empty($users[$id_user]['country_id'])) {
                $country_id = $users[$id_user]['country_id'];
                if (empty($countries[$country_id])) {
                    $country = new seTable('country');
                    $country->select('name');
                    $country->where('id = ?', $country_id);
                    $country->fetchOne();
                    $countries[$country_id] = $country->name;
                    unset($country);
                }
                if (!empty($countries[$country_id])) {
                    $addr_field = $addres->addChild('АдресноеПоле');
                    $addr_field->addChild('Тип', 'Страна');
                    $addr_field->addChild('Значение', $countries[$country_id]);
                }
            }

            if (!empty($users[$id_user]['state_id'])) {
                $state_id = $users[$id_user]['state_id'];
                if (empty($regions[$state_id])) {
                    $region = new seTable('region');
                    $region->select('name');
                    $region->where('id = ?', $state_id);
                    $region->fetchOne();
                    $regions[$state_id] = $region->name;
                    unset($region);
                }
                if (!empty($regions[$state_id])) {
                    if (!empty($full_addres))
                        $full_addres .= ', ';
                    $full_addres .= $regions[$state_id];
                    $addr_field = $addres->addChild('АдресноеПоле');
                    $addr_field->addChild('Тип', 'Регион');
                    $addr_field->addChild('Значение', $regions[$state_id]);
                }
            }

            if (!empty($users[$id_user]['town_id'])) {
                $city_id = $users[$id_user]['town_id'];
                if (empty($cities[$city_id])) {
                    $town = new seTable('town');
                    $town->select('name');
                    $town->where('id = ?', $city_id);
                    $town->fetchOne();
                    $cities[$city_id] = $town->name;
                    unset($town);
                }
                if (!empty($cities[$city_id])) {
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

        $order_goods = new seTable('shop_tovarorder', 'st');
        $order_goods->select('st.id_price, st.article, st.nameitem, st.price, st.discount, st.count, (SELECT sm.id_exchange FROM shop_modifications sm WHERE sm.id=st.modifications) as mod_ex');
        $order_goods->where('st.id_order = ?', $val['id']);
        $order_goods_list = $order_goods->getList();

        $products = $document->addChild('Товары');

        foreach ($order_goods_list as $val_goods) {
            $product = $products->addChild('Товар');

            if (!empty($val_goods['id_price'])) {
                $id_price = $val_goods['id_price'];
                if (empty($goods[$id_price])) {
                    $price = new seTable('shop_price');
                    $price->select('id, article, name, note, nds, id_exchange, measure');
                    $price->where('id = ?', $id_price);
                    $price->fetchOne();
                    if ($price->isFind()) {
                        $goods[$id_price]['article'] = $price->article;
                        $goods[$id_price]['name'] = $price->name;
                        $goods[$id_price]['note'] = $price->note;
                        $goods[$id_price]['nds'] = $price->nds;
                        $goods[$id_price]['measure'] = $price->measure;
                        $goods[$id_price]['id_exchange'] = $price->id_exchange;
                        if (empty($goods[$id_price]['id_exchange'])) {
                            $price->id_exchange = $goods[$id_price]['id_exchange'] = getExternalCode();
                            $price->save();
                        }
                    } else {
                        $goods[$id_price]['article'] = $val_goods['article'];
                        $goods[$id_price]['name'] = $val_goods['nameitem'];
                        $goods[$id_price]['note'] = '';
                        $goods[$id_price]['nds'] = '';
                        $goods[$id_price]['id_exchange'] = $id_price;
                    }
                    unset($price);
                }
                if (!empty($val_goods['mod_ex'])) {
                    $product->addChild('Ид', $val_goods['mod_ex']);
                } else {
                    $product->addChild('Ид', $goods[$id_price]['id_exchange']);
                }
                $product->addChild('Артикул', $goods[$id_price]['article']);
                $product->addChild('Наименование', $goods[$id_price]['name']);
                if (!empty($goods[$id_price]['note']))
                    $product->addChild('Описание', $goods[$id_price]['note']);
                if (!empty($goods[$id_price]['measure']))
                    $product->addChild('БазоваяЕдиница', $goods[$id_price]['measure'])->addAttribute('НаименованиеПолное', $goods[$id_price]['measure']);

                $product->addChild('ЦенаЗаЕдиницу', $val_goods['price']);
                $product->addChild('Количество', $val_goods['count']);
                $product->addChild('Сумма', $val_goods['price'] * $val_goods['count']);

                if (!empty($goods[$id_price]['nds']) && $goods[$id_price]['nds'] > 0) {
                    $taxes = $product->addChild('Налоги');
                    $tax = $taxes->addChild('Налог');
                    $tax->addChild('Наименование', 'НДС');
                    $tax->addChild('УчтеноВСумме', 'true');
                    $rate_taxes = $product->addChild('СтавкиНалогов');
                    $rate_tax = $rate_taxes->addChild('СтавкаНалога');
                    $rate_tax->addChild('Наименование', 'НДС');
                    $rate_tax->addChild('Ставка', '18');
                }

                if (!empty($val_goods['discount']) && $val_goods['discount'] > 0) {
                    $discounts = $product->addChild('Скидки');
                    $discount = $discounts->addChild('Скидка');
                    $discount->addChild('Наименование', 'скидка товара');
                    $discount->addChild('Сумма', $val_goods['discount'] * $val_goods['count']);
                    $discount->addChild('УчтеноВСумме', 'false');
                }
                $summ_order += ($val_goods['count'] * ($val_goods['price'] - $val_goods['discount']));

                $properties = $product->addChild('ЗначенияРеквизитов');
                $property = $properties->addChild('ЗначениеРеквизита');
                $property->addChild('Наименование', 'ВидНоменклатуры');
                $property->addChild('Значение', "Товар");
                $property = $properties->addChild('ЗначениеРеквизита');
                $property->addChild('Наименование', 'ТипНоменклатуры');
                $property->addChild('Значение', 'Товар');
            } else {
                $product->addChild('Ид', 'ORDER_SERVICE');
                $product->addChild('Наименование', $val_goods['nameitem']);
                $product->addChild('ЦенаЗаЕдиницу', $val_goods['price']);
                $product->addChild('БазоваяЕдиница', 'шт')->addAttribute('НаименованиеПолное', 'Штука');
                $product->addChild('Количество', $val_goods['count']);
                $product->addChild('Сумма', $val_goods['price'] * $val_goods['count']);

                if (!empty($val_goods['discount']) && $val_goods['discount'] > 0) {
                    $discounts = $product->addChild('Скидки');
                    $discount = $discounts->addChild('Скидка');
                    $discount->addChild('Наименование', 'скидка услуги');
                    $discount->addChild('Сумма', $val_goods['discount'] * $val_goods['count']);
                    $discount->addChild('УчтеноВСумме', 'false');
                }

                $properties = $product->addChild('ЗначенияРеквизитов');
                $property = $properties->addChild('ЗначениеРеквизита');
                $property->addChild('Наименование', 'ВидНоменклатуры');
                $property->addChild('Значение', 'Услуга');
                $property = $properties->addChild('ЗначениеРеквизита');
                $property->addChild('Наименование', 'ТипНоменклатуры');
                $property->addChild('Значение', 'Услуга');
            }
        }

        $delivery_status = '';
        if (!empty($val['delivery_type']) && $val['delivery_payee'] > 0) {
            $product = $products->addChild('Товар');
            $product->addChild('Ид', 'ORDER_DELIVERY');
            $product->addChild('Наименование', 'Доставка заказа');
            $product->addChild('ЦенаЗаЕдиницу', $val['delivery_payee']);
            $product->addChild('БазоваяЕдиница', 'шт')->addAttribute('НаименованиеПолное', 'Штука');
            $product->addChild('Количество', 1);
            $product->addChild('Сумма', $val['delivery_payee']);

            $product->addChild('Комментарий', $val['delivery_name']);

            $taxes = $product->addChild('Налоги');
            $tax = $taxes->addChild('Налог');
            $tax->addChild('Наименование', 'НДС');
            $tax->addChild('УчтеноВСумме', 'true');

            $rate_taxes = $product->addChild('СтавкиНалогов');
            $rate_tax = $rate_taxes->addChild('СтавкаНалога');
            $rate_tax->addChild('Наименование', 'НДС');
            $rate_tax->addChild('Ставка', '18');

            $properties = $product->addChild('ЗначенияРеквизитов');
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'ВидНоменклатуры');
            $property->addChild('Значение', 'Услуга');
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'ТипНоменклатуры');
            $property->addChild('Значение', 'Услуга');

            switch ($val['delivery_status']) {
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
        if ($val['discount'] > 0) {
            $discounts = $document->addChild('Скидки');
            $discount = $discounts->addChild('Скидка');
            $discount->addChild('Наименование', 'скидка заказа');
            $discount->addChild('Сумма', $val['discount']);
            $discount->addChild('УчтеноВСумме', 'false');
        }

        switch ($val['status']) {
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
        $property->addChild('Наименование', 'Отменен');
        if ($val['is_delete'] == 'Y') {
            $property->addChild('Значение', 'true');
            $status = 'Отменен';
        } else {
            $property->addChild('Значение', 'false');
        }

        $property = $properties->addChild('ЗначениеРеквизита');
        $property->addChild('Наименование', 'Статус заказа');
        $property->addChild('Значение', $status);

        if (!empty($delivery_status)) {
            $property = $properties->addChild('ЗначениеРеквизита');
            $property->addChild('Наименование', 'Статус доставки');
            $property->addChild('Значение', $delivery_status);
        }

        if (!empty($val['payment_type']) && $val['transact_amount'] > 0 && $val['status'] == 'Y') {
            $id_payment = $val['payment_type'];
            if (empty($payment_type[$id_payment])) {
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
        $i++;
    }

    logExchange('generate orders xml: get - ' . $count_orders . ', processed - ' . $i);

    return $xml->asXML();
}

function update_order($xml, $new_status, $date_order, $date_payee)
{

    $status_list = array('Y', 'N', 'K', 'P');

    $order = new seTable('shop_order');
    $order->find((int)$xml->Номер);

    $order->id_exchange = $xml->Ид;

    if (isset($xml->ЗначенияРеквизитов->ЗначениеРеквизита)) {
        foreach ($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property) {

            switch ($property->Наименование) {
                case 'ПометкаУдаления':
                    $order->is_delete = ($property->Значение == 'true') ? 'Y' : 'N';
                    break;
                case 'Проведен':
                    if (!empty($new_status) && in_array($new_status, $status_list) && ($property->Значение == 'true')) {
                        $order->status = $new_status;
                    }
                    break;
                case 'Номер по 1С':
                    $order->number_1c = $property->Значение;
                    break;
                case 'Дата по 1С':
                    if ($date_order) {
                        $time = strtotime($property->Значение);
                        $order->date_order = date('Y-m-d', $time);
                    }
                    break;
                case 'Номер оплаты по 1С':
                    break;
                case 'Дата оплаты по 1С':
                    if ($date_payee) {
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

function delete_product($id_ex)
{
    $del_price = new seTable('shop_price');
    $del_price->select('id, id_group');
    $del_price->where("id_exchange = '?'", $id_ex);
    $del_price->fetchOne();
    $id = $del_price->id;
    $id_group = $del_price->id_group;
    if (!empty($id)) {
        $del_price->delete($id);
        if (!empty($id_group)) {
            se_db_query('UPDATE `shop_group` SET `scount` = `scount` - 1 WHERE `id` = ' . $id_group . ' AND `scount` > 0');
        }
        $del_img = new seTable('shop_img');
        $del_img->where('id_price = ?', $id)->deletelist();
    }
}

function setPriceGroup53($id_price, $id_group)
{
    if ($id_price && $id_group) {
        $spg = new seTable('shop_price_group');
        $spg->insert();
        $spg->id_price = $id_price;
        $spg->id_group = $id_group;
        $spg->is_main = 1;
        $spg->save();
    }
}

function import_catalog($xml, $exchange_dir, $type_image, $code_type, $lang, $ex_catalog_name, $update_data, $version = '5.2')
{
    $imagelist = array();
    $add_image = $first_image = $last_image = $manufacturer = '';
    $images_dir = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/images/' . $lang . '/shopprice/');
    @list($id_product_ex, $id_offer_ex) = explode('#', $xml->Ид);

    if ($id_product_ex) {
        if (($xml->Статус == 'Удален' || $xml['Статус'] == 'Удален') && $update_data['delete']) {
            delete_product($id_product_ex);
            return;
        }

        if (($update_data['main_image'] || $update_data['more_image'])) {

            if (isset($xml->Картинка)) {

                foreach ($xml->Картинка as $image) {
                    $image_name = basename($image);

                    if (is_file($exchange_dir . $image_name) && !empty($image_name)) {
                        //exchange_log('import product images '.$exchange_dir.$image_name.'->'.$images_dir.$image_name);
                        rename($exchange_dir . $image_name, $images_dir . $image_name);
                    }
                    if (file_exists($images_dir . $image_name)) {
                        if (!$first_image) {
                            $first_image = $image_name;
                        }
                        $imagelist[] = $image_name;
                        $last_image = $image_name;
                    }
                }
            }

            if (!empty($imagelist)) {
                if ($type_image == 'first') {
                    $add_image = $first_image;
                    unset($imagelist[array_search($first_image, $imagelist)]);
                } else {
                    $add_image = $last_image;
                    unset($imagelist[array_search($last_image, $imagelist)]);
                }
            }
        }

        $id_brand = 0;

        if (isset($_SESSION['exchange_manufacturer']['id']) && isset($xml->ЗначенияСвойств->ЗначенияСвойства)) {
            foreach ($xml->ЗначенияСвойств->ЗначенияСвойства as $property) {
                $guid = (string)$property->Ид;
                $guid_value = (string)$property->Значение;
                if ($_SESSION['exchange_manufacturer']['id'] == $guid) {
                    if ($_SESSION['exchange_manufacturer']['type'] == 'list') {
                        $id_brand = (int)$_SESSION['exchange_manufacturer']['values'][$guid_value];
                    } else {
                        $id_brand = getIdBrand($guid_value);
                    }
                }
            }
        }

        $goods = new seTable('shop_price');
        $goods->select('id');
        $goods->where("id_exchange = '?'", $id_product_ex);
        $goods->fetchOne();
        $id_price = $id_group = '';

        if ($goods->isFind()) {
            $id_price = $goods->id;
        } elseif ($ex_catalog_name['goods'] != 1) {
            $goods->select('id');
            if ($ex_catalog_name['goods'] == 2)
                $goods->where("name = '?'", trim($xml->Наименование));
            elseif ($ex_catalog_name['goods'] == 3)
                $goods->where("article = '?'", trim($xml->Артикул));
            elseif ($ex_catalog_name['goods'] == 4) {
                $goods->where("article = '?'", trim($xml->Артикул));
                $goods->orwhere("name = '?'", trim($xml->Наименование));
            } else {
                $goods->where("name = '?'", trim($xml->Наименование));
                $goods->andwhere("article = '?'", trim($xml->Артикул));
            }
            if ($ex_catalog_name['tree_group']) {
                if (isset($xml->Группы->Ид)) {
                    $group = new seTable('shop_group');
                    $group->select('id, name');
                    $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                    $group->fetchOne();
                    $id_group = $group->id;
                    unset($group);
                }
                $goods->andwhere("id_group = '?'", $id_group);
            }
            $goods->fetchOne();
            $id_price = $goods->id;
        }
        if ($id_price) {
            if ($update_data['group']) {
                if (isset($xml->Группы->Ид)) {
                    $group = new seTable('shop_group');
                    $group->select('id');
                    $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                    $group->fetchOne();
                    $id_group = $group->id;
                }
                if ($id_group) {
                    $goods->id_group = $id_group;
                    if ($version == '5.3') {
                        setPriceGroup53($id_price, $id_group);
                    }
                }
            }

            if ($update_data['article'])
                $goods->article = trim($xml->Артикул);
            if ($update_data['name'])
                $goods->name = trim($xml->Наименование);
            if ($update_data['manufacturer'] && !empty($id_brand))
                $goods->id_brand = $id_brand;
            if (!empty($xml->Описание) && $update_data['note'])
                $goods->note = $xml->Описание;
            if (!empty($add_image) && $update_data['main_image'])
                $goods->img = $add_image;
            if ($update_data['code'])
                $goods->code = get_code_goods($xml, $code_type, $id_price);
            if ($update_data['measure'])
                $goods->measure = getMeasure($xml->БазоваяЕдиница);

            if ($update_data['weight']) {
                foreach ($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property) {
                    if ($property->Наименование == 'Вес') {
                        $goods->weight = (int)$property->Значение;
                        break;
                    }
                }
            }

            if ($update_data['text']) {
                foreach ($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property) {

                    if ($property->Наименование == 'ОписаниеВФорматеHTML') {
                        if (trim($property->Значение)) {
                            $text = trim($property->Значение);
                            if (($encoding = getEncoding($text)) && ($encoding != 'utf-8')) {
                                $text = iconv($encoding, 'utf-8', $text);
                            }
                            $goods->text = $text;
                        }
                        continue;
                    } elseif ($property->Наименование == 'Файл') {
                        @list($file_name, $description_file) = explode('#', trim($property->Значение));
                        $file_name = $exchange_dir . basename($file_name);
                        if (is_file($file_name)) {
                            $text = file_get_contents($file_name);
                            if (($encoding = getEncoding($text)) && ($encoding != 'utf-8')) {
                                $text = iconv($encoding, 'utf-8', $text);
                            }
                            $goods->text = nl2br(trim($text));
                            continue;
                        }
                    }
                }
            }
            $goods->id_exchange = trim($id_product_ex);
            $new_id_price = $goods->save();

            if ($update_data['more_image']) {
                add_images($imagelist, $id_price);
            }
        } else {
            $goods->insert();
            $goods->id_exchange = trim($id_product_ex);
            if ($lang)
                $goods->lang = $lang;
            else
                $goods->lang = 'rus';
            if ($xml->Группы->Ид) {
                $group = new seTable('shop_group');
                $group->select('id, name');
                $group->where("id_exchange = '?'", trim($xml->Группы->Ид));
                $group->fetchOne();
                $id_group = $group->id;
            }
            if ($id_group) {
                $goods->id_group = $id_group;
                se_db_query('UPDATE `shop_group` SET `scount` = `scount` + 1 WHERE `id` = ' . $id_group);
            }

            $goods->code = get_code_goods($xml, $code_type);

            if (trim($xml->Артикул))
                $goods->article = trim($xml->Артикул);
            if (trim($xml->Наименование))
                $goods->name = trim($xml->Наименование);
            if (trim($xml->Описание))
                $goods->note = trim($xml->Описание);

            if ($manufacturer)
                $goods->manufacturer = $manufacturer;

            if (!empty($id_brand))
                $goods->id_brand = $id_brand;

            if (isset($xml->ПолноеНаименование)) {
                $goods->title = trim($xml->ПолноеНаименование);
                $goods->keywords = trim($xml->ПолноеНаименование) . ', ' . $group->name;
            } else {
                $goods->title = trim($xml->Наименование);
                $goods->keywords = trim($xml->Наименование) . ', ' . $group->name;
            }

            if (isset($xml->СтавкиНалогов->СтавкаНалога)) {
                foreach ($xml->СтавкиНалогов->СтавкаНалога as $nalog) {
                    if ($nalog->Наименование == 'НДС') {
                        $nds = $nalog->Ставка;
                        break;
                    }
                }
            }

            if (!empty($nds))
                $goods->nds = $nds;
            if (isset($xml->БазоваяЕдиница))
                $goods->measure = getMeasure($xml->БазоваяЕдиница);

            if (!empty($add_image))
                $goods->img = $add_image;

            if (isset($xml->ЗначенияРеквизитов->ЗначениеРеквизита)) {
                foreach ($xml->ЗначенияРеквизитов->ЗначениеРеквизита as $property) {
                    if ($property->Наименование == 'Вес') {
                        $goods->weight = (int)$property->Значение;
                        continue;
                    }
                    /*
                    if ($property->Наименование == 'Полное наименование'){
                        $goods->note = (string)$property->Значение;
                        continue;
                    }
                    */

                    if ($property->Наименование == 'ОписаниеВФорматеHTML') {
                        if (trim($property->Значение)) {
                            $text = trim($property->Значение);
                            if (($encoding = getEncoding($text)) && ($encoding != 'utf-8')) {
                                $text = iconv($encoding, 'utf-8', $text);
                            }
                            $goods->text = $text;
                        }
                        continue;
                    }

                    if ($property->Наименование == 'Файл') {
                        @list($file_name, $description_file) = explode('#', trim($property->Значение));
                        $file_name = $exchange_dir . basename($file_name);
                        if (is_file($file_name)) {
                            $text = file_get_contents($file_name);
                            if (($encoding = getEncoding($text)) && ($encoding != 'utf-8')) {
                                $text = iconv($encoding, 'utf-8', $text);
                            }
                            $goods->text = nl2br(trim($text));
                            continue;
                        }
                    }
                }
            }
            $goods->presence_count = 0;
            $id_price = $goods->save();

            if ($version == '5.3' && $id_price && $id_group) {
                setPriceGroup53($id_price, $id_group);
            }

            if (!empty($imagelist)) {
                add_images($imagelist, $id_price);
            }
        }

        if ($id_price && isset($xml->ЗначенияСвойств->ЗначенияСвойства) && $update_data['features']) {
            foreach ($xml->ЗначенияСвойств->ЗначенияСвойства as $property) {
                $guid = (string)$property->Ид;
                $value = (string)$property->Значение;
                if (isset($_SESSION['properties'][$guid]) && $value) {
                    $id_feature = $_SESSION['properties'][$guid]['id'];
                    $type = $_SESSION['properties'][$guid]['type'];

                    $smf = new seTable('shop_modifications_feature');
                    $smf->select('id');
                    $smf->where('id_price=?', $id_price);
                    $smf->andWhere('id_feature=?', $id_feature);
                    if ($type == 'list')
                        $smf->andWhere('id_value=?', $_SESSION['properties'][$guid]['values'][$value]);
                    elseif ($type == 'number') {
                        $value = preg_replace("/[\s]+/ui", '', $value);
                        $value = str_replace(',', '.', $value);
                        $smf->andWhere('value_number=?', $value);
                    } elseif ($type == 'bool')
                        $smf->andWhere('value_bool=?', (bool)$value);
                    else
                        $smf->andWhere('value_string="?"', $value);
                    if (!$smf->fetchOne()) {
                        $smf->insert();
                        $smf->id_price = $id_price;
                        $smf->id_feature = $id_feature;
                        if ($type == 'list')
                            $smf->id_value = $_SESSION['properties'][$guid]['values'][$value];
                        elseif ($type == 'number')
                            $smf->value_number = $value;
                        elseif ($type == 'bool')
                            $smf->value_bool = (bool)$value;
                        else
                            $smf->value_string = $value;
                        $smf->save();
                    }
                }
            }
        }

        if (trim($id_offer_ex) && $id_price) {
            if ($xml->ХарактеристикиТовара->ХарактеристикаТовара) {
                $parent_id = '';
                foreach ($xml->ХарактеристикиТовара->ХарактеристикаТовара as $params) {
                    $parent_id = import_params($params->Наименование, $params->Значение, $id_price, $parent_id);
                }

                $price_params = new seTable('shop_price_param');
                $price_params->update('id_exchange', "'$id_offer_ex'");
                $price_params->where("id = ?", $parent_id);
                $price_params->save();
            }
        }
        $_SESSION['exchange_products'][$id_product_ex] = $id_price;
    }
}

function add_images($imagelist, $id_price)
{
    $shop_img = new seTable('shop_img');
    $shop_img->where("id_price = ?", $id_price)->deletelist();
    foreach ($imagelist as $image) {
        $shop_img->select('id');
        $shop_img->where("picture = '?'", $image);
        $shop_img->andwhere("id_price = ?", $id_price);
        $shop_img->fetchOne();
        if ($shop_img->isFind()) {
            continue;
        } else {
            $shop_img->insert();
            $shop_img->id_price = $id_price;
            $shop_img->picture = $image;
            $shop_img->save();
        }
    }
}

function import_offers($xml, $default_param_name, $update_data)
{
    $id_product_ex = $id_offer_ex = $price_opt = $price_opt_corp = $price_bonus = $price = 0;
    @list($id_product_ex, $id_offer_ex) = explode('#', $xml->Ид);
    if ($xml->Статус == 'Удален' && $id_offer_ex && $update_data['delete']) {
        $del_param = new seTable('shop_price_param');
        $del_param->where("id_exchange = '?'", $id_offer_ex)->deletelist();
        return;
    }

    if ($id_product_ex) {

        $goods = new seTable('shop_price');
        $goods->select('id, name');
        $goods->where("id_exchange = '?'", $id_product_ex);
        $goods->fetchOne();
        if ($goods->isFind()) {

            if (isset($xml->Цены->Цена)) {
                $price = null;
                foreach ($xml->Цены->Цена as $type_price) {
                    if (empty($price))
                        $price = $type_price->ЦенаЗаЕдиницу;

                    if (!empty($id_offers_ex)) {
                        if (trim($type_price->ИдТипаЦены) == trim($_SESSION['exchange_type_price']['main'])) {
                            $price = $type_price->ЦенаЗаЕдиницу;
                            break;
                        }
                    } else {
                        switch (trim($type_price->ИдТипаЦены)) {
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
            $id_good = $goods->id;

            if ($id_offer_ex) {
                if ($xml->ХарактеристикиТовара->ХарактеристикаТовара) {
                    $parent_id = '';
                    $params_name = array();
                    $params_val = array();
                    $i = 0;
                    foreach ($xml->ХарактеристикиТовара->ХарактеристикаТовара as $param) {
                        $params_name[$i] = (string)$param->Наименование;
                        $params_val[$i] = (string)$param->Значение;
                        $i++;
                    }

                    asort($params_name);
                    foreach ($params_name as $key => $value) {
                        $parent_id = import_params($params_name[$key], $params_val[$key], $id_good, $parent_id);
                    }

                    $price_params = new seTable('shop_price_param');
                    $price_params->find($parent_id);
                    if ($update_data['price'])
                        $price_params->price = $price;
                    if ($update_data['count'])
                        $price_params->count = (int)$xml->Количество;
                    $price_params->id_exchange = $id_offer_ex;
                    $price_params->save();
                } else {
                    $price_params = new seTable('shop_price_param');
                    $price_params->select('id');
                    $price_params->where("id_exchange = '?'", $id_offer_ex);
                    $price_params->fetchOne();
                    if ($price_params->isFind()) {
                        if ($update_data['price'])
                            $price_params->price = $price;
                        if ($update_data['count'])
                            $price_params->count = (int)$xml->Количество;
                        $price_params->save();
                    } else {
                        $value_param = trim(str_replace(array($goods->name, '(', ')'), '', $xml->Наименование));
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
                }
                if ($update_data['count'])
                    $goods->presence_count = -1;
                $goods->save();
            } else {
                if ($update_data['price']) {
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

function add_param($name)
{
    $params = new seTable('shop_param');
    $params->select('id');
    $params->where("nameparam = '?'", trim($name));
    $params->fetchOne();
    if ($params->isFind()) {
        return $params->id;
    } else {
        $params->insert();
        $params->nameparam = trim($name);
        return $params->save();
    }
}


function import_params($name, $value, $price_id, $up_id)
{

    $param_id = add_param($name);

    $price_params = new seTable('shop_price_param');
    $price_params->select('id');
    $price_params->where("price_id = ?", $price_id);
    $price_params->andWhere("param_id = ?", $param_id);
    $price_params->andWhere("value = '?'", trim($value));
    $price_params->andWhere("(parent_id IS NULL OR parent_id = '?')", $up_id);
    $price_params->fetchOne();

    if ($price_params->isFind()) {
        return $price_params->id;
    } else {
        $price_params->insert();
        $price_params->price_id = $price_id;
        $price_params->param_id = $param_id;
        if ($up_id) {
            $price_params->parent_id = $up_id;
        }
        $price_params->value = trim($value);
        return $price_params->save();
    }
}


function import_groups($xml, $up_id = '', $code_type, $lang, $ex_group_name, $update_data)
{
    $count_groups = 0;
    if (empty($up_id))
        $up_id = 'null';
    if (isset($xml->Группы->Группа)) {
        foreach ($xml->Группы->Группа as $xml_group) {
            $count_groups++;
            $id = 0;
            $groups = new seTable('shop_group');
            $groups->select('id');
            $groups->where("id_exchange = '?'", $xml_group->Ид);
            $groups->fetchOne();
            $id = $groups->id;
            if ($id) {
                if ($update_data['name'])
                    $groups->name = $xml_group->Наименование;
                if ($update_data['code']) {
                    if ($code_type == 'translit') {
                        $translit_name = substr(translit($xml_group->Наименование), 0, 40);
                        $find_group = new seTable('shop_group');
                        $find_group->select('id');
                        $find_group->where("code_gr = '?'", $translit_name);
                        $find_group->andWhere("id <> ?", $id);
                        $find_group->fetchOne();
                        if (!$find_group->isFind())
                            $groups->code_gr = $translit_name;
                        else
                            $groups->code_gr = trim($xml_group->Ид);
                    } else
                        $groups->code_gr = trim($xml_group->Ид);
                }
                if ($up_id > 0 && $update_data['upid'])
                    $groups->upid = $up_id;
                $groups->save();
            } else {
                $id = 0;
                if ($ex_group_name) {
                    $groups->select('id');
                    $groups->where("name = '?'", trim($xml_group->Наименование));
                    if ($up_id)
                        $groups->andwhere("upid = ?", $up_id);
                    else
                        $groups->andwhere("upid IS NULL OR upid=0");
                    $groups->fetchOne();
                    $id = $groups->id;
                }
                if ($id) {
                    $groups->id_exchange = $xml_group->Ид;
                    $groups->save();
                } else {
                    $groups->insert();
                    $groups->name = $xml_group->Наименование;
                    if ($lang)
                        $groups->lang = $lang;
                    $groups->title = $xml_group->Наименование;
                    $groups->keywords = $xml_group->Наименование;
                    if ($code_type == 'translit') {
                        $translit_name = substr(translit($xml_group->Наименование), 0, 40);
                        $find_group = new seTable('shop_group');
                        $find_group->select('id');
                        $find_group->where("code_gr = '?'", $translit_name);
                        $find_group->fetchOne();
                        if (!$find_group->isFind())
                            $groups->code_gr = $translit_name;
                        else
                            $groups->code_gr = trim($xml_group->Ид);
                    } else
                        $groups->code_gr = trim($xml_group->Ид);
                    $groups->id_exchange = trim($xml_group->Ид);
                    if ($up_id) {
                        $groups->upid = $up_id;
                    }
                    $id = $groups->save();
                }
            }

            $count_groups += import_groups($xml_group, $id, $code_type, $lang, $ex_group_name, $update_data);
        }
    }
    return $count_groups;
}

function check_brand_properties($xml, $manufacturer)
{
    $_SESSION['properties'] = array();
    if (isset($xml->Свойства->Свойство))
        $properties = $xml->Свойства->Свойство;
    elseif (isset($xml->Свойства->СвойствоНоменклатуры))
        $properties = $xml->Свойства->СвойствоНоменклатуры;
    else
        return;
    foreach ($properties as $property) {
        if (strtolower(trim($property->Наименование)) == strtolower(trim($manufacturer))) {
            $_SESSION['exchange_manufacturer']['id'] = (string)$property->Ид;
            if (isset($property->ВариантыЗначений)) {
                $list_val = $property->ВариантыЗначений;
                $_SESSION['exchange_manufacturer']['type'] = 'list';
                if (isset($list_val->Справочник)) {
                    foreach ($list_val->Справочник as $val) {
                        $_SESSION['exchange_manufacturer']['values'][(string)$val->ИдЗначения] = getIdBrand((string)$val->Значение);
                    }
                }
            } else
                $_SESSION['exchange_manufacturer']['type'] = 'value';

            break;
        }
    }
    logExchange('check brand, found - ' . !empty($_SESSION['exchange_manufacturer']));
}

function import_properties($xml, $manufacturer)
{
    $_SESSION['properties'] = array();
    if (isset($xml->Свойства->Свойство))
        $properties = $xml->Свойства->Свойство;
    elseif (isset($xml->Свойства->СвойствоНоменклатуры))
        $properties = $xml->Свойства->СвойствоНоменклатуры;
    else
        return;
    foreach ($properties as $property) {
        if (strtolower(trim($property->Наименование)) == strtolower(trim($manufacturer))) {
            $_SESSION['exchange_manufacturer']['id'] = (string)$property->Ид;
            if (isset($property->ВариантыЗначений)) {
                $list_val = $property->ВариантыЗначений;
                $_SESSION['exchange_manufacturer']['type'] = 'list';
                if (isset($list_val->Справочник)) {
                    foreach ($list_val->Справочник as $val) {
                        $_SESSION['exchange_manufacturer']['values'][(string)$val->ИдЗначения] = getIdBrand((string)$val->Значение);
                    }
                }
            } else
                $_SESSION['exchange_manufacturer']['type'] = 'value';
        } else {
            //continue;
            $guid_feature = (string)$property->Ид;
            $name_feature = (string)$property->Наименование;
            $type_feature = isset($property->ВариантыЗначений) && isset($property->ВариантыЗначений->Справочник) ? 'list' : 'string';
            $sf = new seTable('shop_feature', 'sf');
            $sf->select('sf.id, sf.type');
            $sf->where('sf.id_exchange = "?"', $guid_feature);
            $sf->fetchOne();
            if ($sf->isFind()) {
                $id_feature = $sf->id;
                $type = $sf->type;
            } else {
                $sf->select('sf.id, sf.type, sf.id_exchange');
                $sf->where('sf.name = "?"', $name_feature);
                if ($type_feature == 'list') {
                    $sf->andWhere('sf.type = "list"');
                }
                if ($sf->fetchOne()) {
                    $sf->id_exchange = $guid_feature;
                    $sf->save();
                    $id_feature = $sf->id;
                    $type = $sf->type;
                } else {
                    $type = $type_feature;

                    if (isset($property->ТипЗначений)) {
                        if ((string)$property->ТипЗначений == 'Число') {
                            $type = 'number';
                        } elseif ((string)$property->ТипЗначений == 'Логический') {
                            $type = 'bool';
                        }
                    }

                    $sf->insert();
                    $sf->type = $type;
                    $sf->name = $name_feature;
                    $sf->id_exchange = $guid_feature;
                    $id_feature = $sf->save();
                }
            }

            if (!empty($id_feature)) {
                $_SESSION['properties'][$guid_feature] = array(
                    'id' => $id_feature,
                    'type' => $type
                );
            }

            if ($type_feature == 'list') {
                $values = array();
                foreach ($property->ВариантыЗначений->Справочник as $val) {
                    $guid_value = (string)$val->ИдЗначения;
                    $value = (string)$val->Значение;
                    $sfvl = new seTable('shop_feature_value_list');
                    $sfvl->select('id, id_exchange');
                    $sfvl->where('id_feature = ?', $id_feature);
                    $sfvl->andWhere('(id_exchange = "?" OR value = "' . $value . '")', $guid_value);
                    $sfvl->fetchOne();
                    if ($sfvl->isFind()) {
                        if (!$sfvl->id_exchange) {
                            $sfvl->id_exchange = $guid_value;
                            $sfvl->save();
                        }
                        $values[$guid_value] = $sfvl->id;
                    } else {
                        $sfvl->insert();
                        $sfvl->id_feature = $id_feature;
                        $sfvl->value = $value;
                        $sfvl->id_exchange = $guid_value;
                        $values[$guid_value] = $sfvl->save();
                    }
                }
                $_SESSION['properties'][$guid_feature]['values'] = $values;
            }
        }
    }
    logExchange('import features, count - ' . count($_SESSION['properties']));
}

function unzip($file, $folder = '')
{
    $zip = zip_open($folder . $file);
    $files = 0;
    $folders = 0;

    if ($zip) {

        while ($zip_entry = zip_read($zip)) {
            $filename = basename(zip_entry_name($zip_entry));
            $name = $folder . $filename;
            /*
            $path_parts = pathinfo($name);
            
            if(!is_dir($path_parts['dirname'])){
                mkdir($path_parts['dirname'], 0755, true);
            }
            */
            if (zip_entry_open($zip, $zip_entry, "r")) {
                $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                $file = fopen($name, "wb");
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

function translit($str, $dir = 'en', $type = 'url')
{

    $ru = array(0 => 'А', 1 => 'а', 2 => 'Б', 3 => 'б', 4 => 'В', 5 => 'в', 6 => 'Г', 7 => 'г', 8 => 'Д', 9 => 'д', 10 => 'Е', 11 => 'е', 12 => 'Ё', 13 => 'ё', 14 => 'Ж', 15 => 'ж', 16 => 'З', 17 => 'з', 18 => 'И', 19 => 'и', 20 => 'Й', 21 => 'й', 22 => 'К', 23 => 'к', 24 => 'Л', 25 => 'л', 26 => 'М', 27 => 'м', 28 => 'Н', 29 => 'н', 30 => 'О', 31 => 'о', 32 => 'П', 33 => 'п', 34 => 'Р', 35 => 'р', 36 => 'С', 37 => 'с', 38 => 'Т', 39 => 'т', 40 => 'У', 41 => 'у', 42 => 'Ф', 43 => 'ф', 44 => 'Х', 45 => 'х', 46 => 'Ц', 47 => 'ц', 48 => 'Ч', 49 => 'ч', 50 => 'Ш', 51 => 'ш', 52 => 'Щ', 53 => 'щ', 54 => 'Ъ', 55 => 'ъ', 56 => 'Ы', 57 => 'ы', 58 => 'Ь', 59 => 'ь', 60 => 'Э', 61 => 'э', 62 => 'Ю', 63 => 'ю', 64 => 'Я', 65 => 'я');

    $en = array(0 => 'A', 1 => 'a', 2 => 'B', 3 => 'b', 4 => 'V', 5 => 'v', 6 => 'G', 7 => 'g', 8 => 'D', 9 => 'd', 10 => 'E', 11 => 'e', 12 => 'E', 13 => 'e', 14 => 'ZH', 15 => 'zh', 16 => 'Z', 17 => 'z', 18 => 'I', 19 => 'i', 20 => 'J', 21 => 'j', 22 => 'K', 23 => 'k', 24 => 'L', 25 => 'l', 26 => 'M', 27 => 'm', 28 => 'N', 29 => 'n', 30 => 'O', 31 => 'o', 32 => 'P', 33 => 'p', 34 => 'R', 35 => 'r', 36 => 'S', 37 => 's', 38 => 'T', 39 => 't', 40 => 'U', 41 => 'u', 42 => 'F', 43 => 'f', 44 => 'H', 45 => 'h', 46 => 'TS', 47 => 'ts', 48 => 'CH', 49 => 'ch', 50 => 'SH', 51 => 'sh', 52 => 'SCH', 53 => 'sch', 54 => '', 55 => '', 56 => 'Y', 57 => 'y', 58 => '', 59 => '', 60 => 'E', 61 => 'e', 62 => 'YU', 63 => 'yu', 64 => 'YA', 65 => 'ya');

    if ($dir == 'en')
        $str = str_replace($ru, $en, $str);
    else
        $str = str_replace($en, $ru, $str);

    if ($type == 'url') {
        $str = preg_replace("/[\s]+/ui", '-', trim($str));
        $str = preg_replace("/[^0-9a-zа-я\_\-]+/ui", '', $str);
        $str = strtolower($str);
    }

    return $str;
}

function getEncoding($string)
{

    $encoding_list = array(
        'utf-8', 'windows-1251', 'ASCII', 'windows-1252', 'windows-1254',
        'iso-8859-1', 'iso-8859-2', 'iso-8859-3', 'iso-8859-4', 'iso-8859-5',
        'iso-8859-6', 'iso-8859-7', 'iso-8859-8', 'iso-8859-9', 'iso-8859-10',
        'iso-8859-13', 'iso-8859-14', 'iso-8859-15', 'iso-8859-16'
    );

    foreach ($encoding_list as $val) {
        $sample = iconv($val, $val, $string);
        if ($sample == $string)
            return $val;
    }
    return null;
}

function exchange_log($text, $log_file = 'exchange_log.txt', $mode = 'ab')
{
    $text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";
    $file = fopen('../' . $log_file, $mode);
    fwrite($file, $text);
    fclose($file);
}

function updateCountGroup()
{
    $price = new seTable('shop_price');
    $price->select('COUNT(*) AS count, id_group');
    $price->groupBy('id_group');

    foreach ($price->getList() as $val) {
        echo $query = 'UPDATE shop_group SET scount = ' . $val['count'] . ' WHERE id = ' . $val['id_group'];
        echo '<br />';
        //mysql_query($query);
    }
}
