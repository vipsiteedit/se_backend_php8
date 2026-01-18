<?php

/**
 * @copyright EDGESTILE
 */
class plugin_shopdelivery
{
    private static $instance = null;
    private $basecurr;
    private $total;
    private $ems_locations = null;
    private $ems_params = array();
    public $delivery_type_id = 0;
    private $not_delivery;
    private $geo_data = array();
    private $param = array();
    private $ems_param;

    private $services = array(
        'ems' => array(
            'code' => 'ems',
            'name' => 'EMS калькулятор',
            'description' => '',
        ),
        'post' => array(
            'code' => 'post',
            'name' => 'Почта России',
            'description' => '',
        ),
        'sdek' => array(
            'code' => 'sdek',
            'name' => 'СДЭК',
            'description' => '',
        ),
    );

    private $service_settings = array(
        'sdek' => array(
            'from_city' => array(
                'name' => 'Город отправителя',
                'description' => '',
                'code' => 'from_city',
                'type' => 'city',
                'value' => null,
            ),
            'type_delivery' => array(
                'name' => 'Тип доставки',
                'description' => 'Укажите необходимый тип отправления',
                'code' => 'type_delivery',
                'type' => 'list',
                'values' => array(
                    '1' => 'Склад-Склад',
                    '2' => 'Склад-Дверь',
                    '3' => 'Дверь-Склад',
                    '4' => 'Дверь-Дверь',
                ),
                'value' => '1',
            ),
            'login' => array(
                'name' => 'Логин',
                'description' => '',
                'code' => 'login',
                'type' => 'text',
                'value' => null,
            ),
            'password' => array(
                'name' => 'Пароль',
                'description' => '',
                'code' => 'password',
                'type' => 'password',
                'value' => null,
            ),
        ),
        'post' => array(
            'from_index' => array(
                'name' => 'Почтовый индекс отправителя',
                'description' => '',
                'code' => 'from_index',
                'type' => 'text',
                'value' => '101000',
            ),
        ),
        'ems' => array(
            'from_city' => array(
                'name' => 'Город отправителя',
                'description' => '',
                'code' => 'from_city',
                'type' => 'city',
                'value' => null,
            ),
            'declared_value' => array(
                'name' => 'Объявленная ценность послыки',
                'description' => '',
                'code' => 'declared_value',
                'type' => 'bool',
                'value' => 0,
            ),
        ),
    );


    public function __construct($not_delivery = array())
    {
        define('DATA_DIR', SE_ROOT . 'data/');
        $this->not_delivery = $not_delivery;
        $plugin_cart = new plugin_shopcart(array('curr' => se_baseCurrency()));
        $this->total = $plugin_cart->getTotalCart();

        $this->basecurr = se_getMoney();

        if (isRequest('delivery_type_id')) {
            $id = getRequest('delivery_type_id');
            if (strpos($id, 'sub_') !== false) {
                $id_sub = str_replace('sub_', '', $id);
                $id = $this->getParentId($id_sub);
                if ($id_sub && $id)
                    $_SESSION['delivery_sub'][$id] = $id_sub;
            }

            $this->delivery_type_id = $_SESSION['delivery_type_id'] = (int)$id;
        } elseif (!empty($_SESSION['delivery_type_id']))
            $this->delivery_type_id = $_SESSION['delivery_type_id'];

        if (isRequest('delivery_sub_' . $this->delivery_type_id))
            $_SESSION['delivery_sub'][$this->delivery_type_id] = getRequest('delivery_sub_' . $this->delivery_type_id);

        $this->checkUserRegion();

        $this->updateDB();
    }

    private function updateDB()
    {
        if (!file_exists(SE_ROOT . '/system/logs/shop_delivery_settings.upd')) {
            $sql = "CREATE TABLE IF NOT EXISTS shop_delivery_settings (
			  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  id_delivery int(10) UNSIGNED NOT NULL,
			  code varchar(255) NOT NULL,
			  name varchar(255) DEFAULT NULL,
			  value text DEFAULT NULL,
			  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (id),
			  UNIQUE INDEX UK_shop_delivery_settings (id_delivery, code),
			  CONSTRAINT FK_shop_delivery_settings_shop_deliverytype_id FOREIGN KEY (id_delivery)
			  REFERENCES shop_deliverytype (id) ON DELETE CASCADE ON UPDATE CASCADE
			)
			ENGINE = INNODB
			AUTO_INCREMENT = 2
			AVG_ROW_LENGTH = 1365
			CHARACTER SET utf8
			COLLATE utf8_general_ci;";

            se_db_query($sql);
            echo se_db_error();
            file_put_contents(SE_ROOT . '/system/logs/shop_delivery_settings.upd', date('Y-m-d H:i:s'));
        }
    }

    public function getParentId($id_sub = 0)
    {
        $id = null;
        if ($id_sub) {
            $sdt = new seTable('shop_deliverytype', 'sdt');
            $sdt->select('sdt.id_parent');
            $sdt->find($id_sub);
            $id = $sdt->id_parent;
        }
        return $id;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function checkUserRegion()
    {
        $plugin_shopgeo = new plugin_shopgeo();
        $this->geo_data = $plugin_shopgeo->getSelected();
    }

    public function getDeliveryList($id_price = null)
    {
        $this->checkUserRegion();

        if (!$id_price) {
            $id_price = $this->total['goods_id'];
        }

        if (is_array($id_price)) {
            $id_price = join(',', $id_price);
        }

        $rdeliv = new seTable('shop_deliverytype', 'sdt');
        $rdeliv->select('DISTINCT sdt.id, sdt.name, sdt.code, sdt.time, sdt.price, sdt.curr, sdt.forone, sdt.note, sdt.week, sdt.need_address, sdt.sort');
        $rdeliv->innerjoin('shop_deliverygroup sdg', 'sdg.id_type=sdt.id');
        $rdeliv->innerjoin('shop_price_group spg', 'sdg.id_group = spg.id_group');
        $rdeliv->where('spg.id_price IN (?)', $id_price);
        $rdeliv->andWhere("sdt.status ='?'", 'Y');
        $rdeliv->andWhere("(sdt.max_weight >= '?' OR sdt.max_weight IS NULL OR sdt.max_weight = 0)", floatval($this->total['weight']));
        $rdeliv->andWhere("(sdt.max_volume >= '?' OR sdt.max_volume IS NULL OR sdt.max_volume = 0)", floatval($this->total['volume']));

        if ($rdeliv->isFindField('sort'))
            $rdeliv->orderBy('sort');

        $deliveries = $rdeliv->getList();

        $find_delivery = false;

        if (!empty($deliveries)) {
            $day_week = (date('w') + 6) % 7;
            foreach ($deliveries as $key => $delivery) {
                $delivery['code'] = strtolower($delivery['code']);

                if ($delivery['code'] == 'region' || $delivery['code'] == 'subregion') {
                    if ($this->checkRegion($delivery)) {
                        if ($delivery['code'] == 'region')
                            $delivery = $this->getDeliveryPriceParam($delivery);
                        else {
                            $sub = $this->getSubDeliveries($delivery);
                            if (!empty($sub)) {
                                $delivery['sub'] = $sub;
                            }
                        }
                    } else {
                        unset($deliveries[$key]);
                        continue;
                    }
                } elseif ($delivery['code'] == 'ems') {
                    $delivery_ems = $this->getDeliveryPriceEms($delivery['curr']);
                    if (empty($delivery_ems)) {
                        unset($deliveries[$key]);
                        continue;
                    } else {
                        $delivery['price'] += se_Money_Convert($delivery_ems['price'], 'RUR', $delivery['curr']);
                        $delivery['time'] = $delivery_ems['time'];
                    }
                } elseif ($delivery['code'] == 'post') {
                    $delivery_post = $this->getDeliveryPricePost();
                    if (empty($delivery_post)) {
                        unset($deliveries[$key]);
                        continue;
                    } else {
                        $delivery['price'] += se_Money_Convert($delivery_post['price'], 'RUR', $delivery['curr']);
                        $delivery['time'] = $delivery_post['time'];
                    }
                } elseif ($delivery['code'] == 'cdek' || $delivery['code'] == 'sdek') {
                    $delivery_cdek = $this->getDeliveryPriceCdek($delivery['id']);
                    if (empty($delivery_cdek)) {
                        unset($deliveries[$key]);
                        continue;
                    } else {
                        $delivery['price'] += se_Money_Convert($delivery_cdek['price'], 'RUR', $delivery['curr']);
                        $delivery['time'] = $delivery_cdek['time'];
                        $delivery['sub'] = $delivery_cdek['sub'];
                    }
                } elseif ($delivery['code'] == 'pek') {
                    $delivery_pek = $this->getDeliveryPricePek();
                    if (empty($delivery_pek)) {
                        unset($deliveries[$key]);
                        continue;
                    } else {
                        $delivery['price'] += se_Money_Convert($delivery_pek['price'], 'RUR', $delivery['curr']);
                        $delivery['time'] = $delivery_pek['time'];
                    }
                }
                if ($delivery['price'] > 0)
                    $delivery['price'] = se_Money_Convert($delivery['price'] * ($delivery['forone'] == 'Y' ? $this->total['count'] : 1), $delivery['curr'], $this->basecurr);
                $delivery['curr'] = $this->basecurr;
                $delivery['week'] = $delivery['week'][$day_week];
                $delivery['addr'] = ($delivery['need_address'] == 'Y');
                $deliveries[$key] = $delivery;
                if (!$find_delivery)
                    $find_delivery = ($delivery['id'] == $this->delivery_type_id);
            }
        }
        if (!empty($this->not_delivery))
            array_unshift($deliveries, $this->not_delivery);

        if (!$find_delivery) {
            $first = current($deliveries);
            $this->delivery_type_id = $_SESSION['delivery_type_id'] = $first['id'];
        }

        return $deliveries;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function getSettings($id_delivery = 0, $type = '')
    {
        $settings = array();

        if ($id_delivery) {
            $sdt = new seTable('shop_deliverytype');

            if ($sdt->find($id_delivery)) {
                if (empty($type))
                    $type = $sdt->code;

                $sds = new seTable('shop_delivery_settings', 'sds');
                $sds->select('sds.code, sds.value');
                $sds->where('id_delivery=?', $id_delivery);
                $list = $sds->getList();
            }
        }

        if (!empty($type) && !empty($this->service_settings[$type])) {
            $settings = $this->service_settings[$type];

            if (!empty($list)) {
                foreach ($list as $val) {
                    $code = $val['code'];
                    $value = $val['value'];
                    if (isset($settings[$code])) {
                        $settings[$code]['value'] = $value;
                    }
                }
            }
        }

        return $settings;
    }

    public function saveSettings($id_delivery = 0, $settings = array())
    {
        if ($id_delivery && $settings) {
            foreach ($settings as $val) {
                $sds = new seTable('shop_delivery_settings');
                $sds->where('id_delivery=?', $id_delivery);
                $sds->andWhere('code="?"', $val['code']);
                $sds->fetchOne();
                $sds->id_delivery = $id_delivery;
                $sds->code = $val['code'];
                $sds->value = $val['value'];
                $sds->save();
            }
        }
    }

    private function getDeliveryPricePost()
    {

        $delivery = array();

        //101000 - МОСКВА,  ВЛАДИМИР - 600000
        $from_index = plugin_shopsettings::getInstance()->getValue('from_index');
        $to_index = !empty($_SESSION['cartcontact']['post_index']) ? $_SESSION['cartcontact']['post_index'] : getRequest('contact_post_index');

        $request = array(
            'apikey' => 'f99816035f28166b9fa8b57fce4dce4c',
            'method' => 'calc',
            'from_index' => $from_index,
            'to_index' => $to_index,
            'weight' => $this->total['weight'],
            'ob_cennost_rub' => $this->total['sum_total']
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://russianpostcalc.ru/api_v1.php');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        curl_close($curl);

        if ($response === false) {
            $error = '10000 server error';
        } else {
            $data = json_decode($response, true);
            if (isset($data['msg']['type']) && $data['msg']['type'] == 'done' && !empty($data['calc'])) {
                foreach ($data['calc'] as $val) {
                    //rp_1class - Почта России 1 Класс, rp_main - Ценная Посылка, обычное отправление Почтой России
                    $delivery['price'] = $val['cost'];
                    $delivery['time'] = $val['days'];
                    if ($val['rp_main']) {
                        break;
                    }
                }
            } else {
                $error = $data['msg']['text'];
            }
        }

        return $delivery;
    }

    private function getDeliveryPriceCdek($id)
    {
        $delivery = array();

        $city_to = $this->geo_data['city'];

        if (empty($this->ems_params['city_from'])) {
            $tlb_main = new seTable('main');
            $tlb_main->select('city_from_delivery as city');
            $tlb_main->fetchOne();
            $this->ems_params['city_from'] = $tlb_main->city;
            unset($tlb_main);
        }

        $city_from = $this->ems_params['city_from'];

        $settings = $this->getSettings($id);

        if (!empty($settings['from_city']['value'])) {
            $plugin_shopgeo = new plugin_shopgeo();
            $geo_data = $plugin_shopgeo->getCity($settings['from_city']['value']);
            if (!empty($geo_data['city_name'])) {
                $city_from = $geo_data['city_name'];
            }
        }

        if ($city_to && $city_from) {

            $filename = __DIR__ . '/delivery/cdek_new/rus.json';;

            if (file_exists($filename))
                $cities = json_decode(file_get_contents($filename), 1);

            if (($id_city_from = $cities[$city_from]) && ($id_city_to = $cities[$city_to])) {

                $date = date('Y-m-d');

                $request = array(
                    'version' => '1.0',
                    'dateExecute' => $date,
                    'senderCityId' => $id_city_from,
                    'receiverCityId' => $id_city_to,
                    'tariffList' => array(
                        array('priority' => 1, 'id' => 5), //Экономичный экспресс склад-склад
                        array('priority' => 2, 'id' => 10), //Экспресс лайт склад-склад
                        array('priority' => 3, 'id' => 11), //Экспресс лайт склад-дверь
                        array('priority' => 4, 'id' => 12), //Экспресс лайт дверь-склад
                        array('priority' => 5, 'id' => 1), //Экспресс лайт дверь-дверь
                        array('priority' => 6, 'id' => 3), //Супер-экспресс до 18
                    ),
                    //'modeId' => '1', //1 - дверь-дверь 2 - дверь-склад 3 - склад-дверь 4 - склад-склад
                    'goods' => array()
                );

                if (!empty($settings['type_delivery']['value'])) {
                    switch ($settings['type_delivery']['value']) {
                        case '1': //Склад-Склад
                            $request['tariffList'] = array(
                                array('priority' => 1, 'id' => 136),
                                array('priority' => 2, 'id' => 234),
                                array('priority' => 3, 'id' => 5),
                                array('priority' => 4, 'id' => 10),
                            );
                            $get_points = true;
                            break;
                        case '2': //Склад-Дверь
                            $request['tariffList'] = array(
                                array('priority' => 1, 'id' => 137),
                                array('priority' => 2, 'id' => 233),
                                array('priority' => 3, 'id' => 11),
                            );
                            break;
                        case '3': //Дверь-Склад
                            $request['tariffList'] = array(
                                array('priority' => 1, 'id' => 138),
                                array('priority' => 2, 'id' => 301),
                                array('priority' => 3, 'id' => 12),
                            );
                            $get_points = true;
                            break;
                        case '4': //Дверь-Дверь
                            $request['tariffList'] = array(
                                array('priority' => 1, 'id' => 139),
                                array('priority' => 2, 'id' => 1),
                            );
                            break;
                    }
                }

                if (!empty($settings['login']['value']) && !empty($settings['password']['value'])) {
                    $request['authLogin'] = $settings['login']['value'];
                    $request['secure'] = md5($date . '&' . $settings['password']['value']);
                    /*
					$request['tariffList'] = array(
						array('priority' => 1, 'id' => 136),
						array('priority' => 2, 'id' => 137),
						array('priority' => 3, 'id' => 138),
						array('priority' => 4, 'id' => 139),
						array('priority' => 5, 'id' => 233),
						array('priority' => 6, 'id' => 234),	
						array('priority' => 7, 'id' => 301),
						array('priority' => 8, 'id' => 302),
						array('priority' => 9, 'id' => 291),
						array('priority' => 10, 'id' => 293),
						array('priority' => 11, 'id' => 294),
						array('priority' => 12, 'id' => 295),
						array('priority' => 20, 'id' => 5),
						array('priority' => 21, 'id' => 10),
					);
					*/
                }

                $plugin_cart = new plugin_shopcart(array('curr' => se_baseCurrency()));
                $goods = $plugin_cart->getGoodsCart();

                foreach ($goods as $val) {
                    $volume = (($val['volume'] > 0) ? ($val['volume'] * $val['count']) : 1) / 1000000;
                    $weight = (($val['weight'] > 0) ? ($val['weight'] * $val['count']) : 1) / 1000;
                    $request['goods'][] = array(
                        'volume' => $volume,
                        'weight' => $weight
                    );
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://api.cdek.ru/calculator/calculate_price_by_json.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

                $response = curl_exec($ch);
                curl_close($ch);

                if ($response === false) {
                    $error = '10000 server error';
                } else {
                    $data = json_decode($response, true);
                    if (!empty($data['result'])) {
                        $delivery['price'] = $data['result']['price'];
                        $delivery['time'] = $data['result']['deliveryPeriodMin'];
                        if ($data['result']['deliveryPeriodMin'] != $data['result']['deliveryPeriodMax'])
                            $delivery['time'] .= '-' . $data['result']['deliveryPeriodMax'];

                        if (!empty($get_points)) {
                            $points = $this->getCdekPoints($id_city_to, $id);
                            $delivery['sub'] = $points;
                        }
                    } else {
                        $error = $data;
                    }
                }
            }
        }

        return $delivery;
    }

    public function getCdekPoints($id_city = 0, $id_delivery = 0)
    {
        $points = array();

        if ($id_city) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://int.cdek.ru/pvzlist.php?cityid=' . $id_city);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($status == 200 && $response !== false) {
                $xml = simplexml_load_string($response);

                if ($xml->Pvz) {
                    foreach ($xml->Pvz as $pvz) {
                        $id_sub = (string)$pvz['Code'];
                        $description = '<p><a href="' . (string)$pvz['Site'] . '" title="' . (string)$pvz['FullAddress'] . '" target="_blank">' . (string)$pvz['Address'] . '</a>, Тел.:' . (string)$pvz['Phone'] . '</p>';

                        $description .= '<p>Время работы: ' . (string)$pvz['WorkTime'] . '</p>';

                        $points[] = array(
                            'id' => $id_sub,
                            'name' => (string)$pvz['Name'],
                            'note' => $description,
                            'sel' => ($_SESSION['delivery_sub'][$id_delivery] && $_SESSION['delivery_sub'][$id_delivery] == $id_sub),
                        );
                    }
                }
            }
        }

        return $points;
    }

    private function getDeliveryPricePek()
    {
        $delivery = array();

        return $delivery;
    }

    private function getDeliveryPriceEms()
    {
        $delivery = array();

        if (!file_exists(DATA_DIR))
            mkdir(DATA_DIR);
        if (empty($this->ems_params['max_weight'])) {
            $this->ems_params['max_weight'] = 0;
            if (!file_exists(DATA_DIR . 'ems_max_weight.dat') || filemtime(DATA_DIR . 'ems_max_weight.dat') < (time() - (3600 * 24))) {
                $ems_response = $this->getContentsEms('?method=ems.get.max.weight');
                if ($ems_response) {
                    $ems_options = json_decode($ems_response, true);
                    if (isset($ems_options['rsp']['stat']) && $ems_options['rsp']['stat'] == 'ok') {
                        $max_weight = (float)$ems_options['rsp']['max_weight'];
                        $this->ems_params['max_weight'] = $max_weight;
                        $f = fopen(DATA_DIR . 'ems_max_weight.dat', 'w+');
                        fwrite($f, $max_weight);
                        fclose($f);
                    }
                }
            } else {
                $this->ems_params['max_weight'] = file_get_contents(DATA_DIR . 'ems_max_weight.dat');
            }
        }

        if ($this->ems_params['max_weight'] >= $this->total['weight']) {
            if (empty($this->ems_params['city_to'])) {
                $this->ems_params['city_to'] = $this->geo_data['city'];
                $this->ems_params['region_to'] = $this->geo_data['region'];
            }
            if (empty($this->ems_params['city_from'])) {
                $tlb_main = new seTable('main');
                $tlb_main->select('city_from_delivery as city');
                $tlb_main->where("lang = '?'", se_getLang());
                $tlb_main->fetchOne();
                $this->ems_params['city_from'] = $tlb_main->city;
                unset($tlb_main);
            }

            if (!empty($this->ems_params['city_from']) && (!empty($this->ems_params['city_to']) || !empty($this->ems_params['region_to'])) && (empty($this->ems_params['ems_from']) || empty($this->ems_params['ems_to']))) {
                $ems_region_to = '';
                $this->ems_params['ems_from'] = $this->ems_params['ems_to'] = '';
                if (empty($this->ems_locations)) {
                    if (!file_exists(DATA_DIR . 'ems_locations.dat') || filemtime(DATA_DIR . 'ems_locations.dat') < (time() - (3600 * 24))) {
                        $ems_response = $this->getContentsEms('?method=ems.get.locations&type=russia&plain=true');
                        if ($ems_response) {
                            $ems_options = json_decode($ems_response, true);
                            $this->ems_locations = $ems_options['rsp']['locations'];
                            $f = fopen(DATA_DIR . 'ems_locations.dat', 'w+');
                            fwrite($f, serialize($this->ems_locations));
                            fclose($f);
                        }
                    } else {
                        $locations = file_get_contents(DATA_DIR . 'ems_locations.dat');
                        $this->ems_locations = unserialize($locations);
                    }
                }
                if (!empty($this->ems_locations)) {
                    $find = 0;
                    foreach ($this->ems_locations as $val) {
                        if (3 == $find) {
                            break;
                        }
                        if (empty($this->ems_params['ems_to']) && utf8_strtoupper($val['name']) == utf8_strtoupper($this->ems_params['city_to'])) {
                            $this->ems_params['ems_to'] = $val['value'];
                            $find += 2;
                        }
                        if (empty($ems_region_to) && utf8_strtoupper($val['name']) == utf8_strtoupper($this->ems_params['region_to'])) {
                            $ems_region_to = $val['value'];
                            $find++;
                        }
                        if (empty($this->ems_params['ems_from']) && utf8_strtoupper($val['name']) == utf8_strtoupper($this->ems_params['city_from'])) {
                            $this->ems_params['ems_from'] = $val['value'];
                            $find++;
                        }
                    }
                }
                if (empty($this->ems_params['ems_to']) && !empty($ems_region_to)) {
                    $this->ems_params['ems_to'] = $ems_region_to;
                }
            }

            if (empty($this->ems_param['price']) || empty($this->ems_param['time'])) {
                if (!empty($this->ems_params['ems_from']) && !empty($this->ems_params['ems_to'])) {
                    $url = '?method=ems.calculate&from=' . $this->ems_params['ems_from'] . '&to=' . $this->ems_params['ems_to'] . '&weight=' . $this->total['weight'];
                    $ems_response = $this->getContentsEms($url);
                    if ($ems_response) {
                        $ems_options = json_decode($ems_response, true);
                        if (isset($ems_options['rsp']['stat']) && $ems_options['rsp']['stat'] == 'ok') {
                            $delivery['price'] = $this->ems_param['price'] = $ems_options['rsp']['price'];
                            $delivery['time'] = $this->ems_param['time'] = $ems_options['rsp']['term']['min'] . '-' . $ems_options['rsp']['term']['max'];
                        }
                    }
                }
            } else {
                $delivery['price'] = $this->ems_param['price'];
                $delivery['time'] = $this->ems_param['time'];
            }
        }
        return $delivery;
    }

    private function getContentsEms($query)
    {
        $url = 'http://emspost.ru/api/rest/' . $query;
        if (extension_loaded('curl')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            $contents = curl_exec($ch);
            curl_close($ch);
        } else {
            $contents = file_get_contents($url);
        }
        return $contents;
    }

    private function checkRegion($delivery)
    {
        $id_delivery = $delivery['id'];
        $country = $this->geo_data['id_country'];
        $region = $this->geo_data['id_region'];
        $city = $this->geo_data['id_city'];

        $delivery_region = new seTable('shop_delivery_region');
        $delivery_region->select('id');
        $delivery_region->where('id_delivery=?', $id_delivery);
        $delivery_region->andWhere("(id_country=$country OR id_region=$region OR id_city=$city)");
        $delivery_region->fetchOne();
        return $delivery_region->isFind();
    }

    private function getDeliveryPriceParam($delivery)
    {
        $deliv = array();
        if (!empty($delivery)) {
            $id_delivery = $delivery['id'];
            $country = $this->geo_data['id_country'];
            $region = $this->geo_data['id_region'];
            $city = $this->geo_data['id_city'];

            $delivery_region = new seTable('shop_delivery_region');
            $delivery_region->select('id');
            $delivery_region->where('id_delivery=?', $id_delivery);
            $delivery_region->andWhere("(id_country=$country OR id_region=$region OR id_city=$city)");
            $delivery_region->fetchOne();
            if ($delivery_region->isFind()) {

                $sdt = new seTable('shop_deliverytype', 'sdt');
                $sdt->select('DISTINCT sdt.id, sdt.name, sdt.code, sdt.time, sdt.price, sdt.curr, sdt.forone, sdt.note, sdt.week, sdt.need_address');
                $sdt->innerJoin('shop_delivery_region sdr', 'sdt.id=sdr.id_delivery');
                $sdt->where('sdt.id_parent=?', $id_delivery);
                $sdt->andWhere("(sdr.id_country=$country OR sdr.id_region=$region OR sdr.id_city=$city)");
                $sdt->andWhere("sdt.status='Y'");
                //$sdt->andWhere("(sdt.max_weight >= '?' OR sdt.max_weight IS NULL OR sdt.max_weight = 0)", floatval($this->total['weight']));
                //$sdt->andWhere("(sdt.max_volume >= '?' OR sdt.max_volume IS NULL OR sdt.max_volume = 0)", floatval($this->total['volume']));
                $list = $sdt->getList();
                if ($list) {
                    foreach ($list as $val) {
                        if (!empty($val['price']))
                            $delivery['price'] = $val['price'];
                        if (!empty($val['time']))
                            $delivery['time'] = $val['time'];
                        if (!empty($val['note']))
                            $delivery['note'] = $val['note'];
                    }
                }
                $delivery['price'] = $this->getPriceParam($id_delivery, $delivery['price']);

                $deliv = $delivery;
            }
        }
        return $deliv;
    }

    private function getPriceParam($id_delivery, $price)
    {
        if (!isset($this->param[$id_delivery])) {
            $this->param[$id_delivery] = null;

            $current_priority = 0;
            $current_key = -1;
            $delivery_param = new seTable('shop_delivery_param');
            $delivery_param->select('type_param, price, min_value, max_value, priority, type_price, operation');
            $delivery_param->where("id_delivery = ?", $id_delivery);
            $param_list = $delivery_param->getList();
            if (!empty($param_list)) {
                foreach ($param_list as $key => $param) {
                    $param_defined = false;
                    switch ($param['type_param']) {
                        case 'sum':
                            if ($param['min_value'] <= $this->total['sum_total'] && ($param['max_value'] > $this->total['sum_total'] || $param['max_value'] == 0)) {
                                $param_defined = true;
                            }
                            break;
                        case 'weight':
                            if ($param['min_value'] <= $this->total['weight'] && ($param['max_value'] > $this->total['weight'] || $param['weight'] == 0)) {
                                $param_defined = true;
                            }
                            break;
                        case 'volume':
                            if ($param['min_value'] <= $this->total['volume'] && ($param['max_value'] > $this->total['volume'] || $param['volume'] == 0)) {
                                $param_defined = true;
                            }
                            break;
                        default:
                            break;
                    }
                    if ($param_defined && $param['priority'] >= $current_priority) {
                        $current_key = $key;
                        $current_priority = $param['priority'];
                    }
                }
                if ($current_key >= 0) {
                    $this->param[$id_delivery] = $param_list[$current_key];
                }
            }
        }

        if (!empty($this->param[$id_delivery])) {
            $param = $this->param[$id_delivery];
            $add_price = 0;
            if ($param['type_price'] == 'a')
                $add_price = $param['price'];
            else {
                $percent = $param['price'] <= 100 ? $param['price'] / 100 : 1;
                $add_price = $percent * ($param['type_price'] == 's' ? $this->total['sum_total'] : $price);
            }
            $price = max(($param['operation'] == '+') ? $price + $add_price : ($param['operation'] == '-' ? $price - $add_price : $add_price), 0);
        }

        return $price;
    }

    private function getSubDeliveries(&$delivery)
    {
        $deliveries = array();

        $id_delivery = $delivery['id'];
        $country = $this->geo_data['id_country'];
        $region = $this->geo_data['id_region'];
        $city = $this->geo_data['id_city'];

        $sdt = new seTable('shop_deliverytype', 'sdt');
        $sdt->select('DISTINCT sdt.id, sdt.name, sdt.time, sdt.price, sdt.note');
        $sdt->innerJoin('shop_delivery_region sdr', 'sdt.id=sdr.id_delivery');
        $sdt->where('sdt.id_parent=?', $id_delivery);
        $sdt->andWhere("(sdr.id_country=$country OR sdr.id_region=$region OR sdr.id_city=$city)");
        $sdt->andWhere("sdt.status='Y'");

        $list = $sdt->getList();

        if (!empty($list)) {
            $selected = 0;
            foreach ($list as $key => $val) {
                if (empty($val['name']))
                    $val['name'] = $delivery['name'];
                if ($val['price'] > 0)
                    $val['price'] = se_Money_Convert($val['price'], $delivery['curr'], $this->basecurr);

                $val['price'] = $this->getPriceParam($id_delivery, $val['price']);

                $deliveries[] = $val;
                if (!empty($_SESSION['delivery_sub'][$id_delivery]) && $_SESSION['delivery_sub'][$id_delivery] == $val['id']) {
                    $selected = $key;
                }
            }
            $deliveries[$selected]['sel'] = true;
            $delivery['price'] = $deliveries[$selected]['price'];
            $delivery['time'] = $deliveries[$selected]['time'];
        }

        return $deliveries;
    }

    public function getDelivery($id_delivery = null, $base_curr = false)
    {
        $id = (!empty($id_delivery)) ? $id_delivery : $this->delivery_type_id;
        $delivery = array();
        if ($id) {
            $delivery_type = new seTable('shop_deliverytype', 'sdt');
            $delivery_type->select('sdt.id, sdt.name, sdt.code, sdt.time, sdt.price, sdt.curr, sdt.forone, sdt.note, sdt.need_address');
            $delivery_type->where("sdt.id = ?", $id);
            $delivery_type->andWhere("sdt.status ='?'", 'Y');
            $delivery = $delivery_type->fetchOne();
            if ($delivery_type->isFind()) {
                $delivery['code'] = strtolower($delivery['code']);
                if ($delivery['code'] == 'region') {
                    $delivery = $this->getDeliveryPriceParam($delivery);
                } elseif ($delivery['code'] == 'subregion') {
                    $this->getSubDeliveries($delivery);
                } elseif ($delivery['code'] == 'ems' && $delivery_ems = $this->getDeliveryPriceEms()) {
                    $delivery['price'] += $delivery_ems['price'];
                    $delivery['time'] = $delivery_ems['time'];
                } elseif ($delivery['code'] == 'post' && $delivery_post = $this->getDeliveryPricePost()) {
                    $delivery['price'] += $delivery_post['price'];
                    $delivery['time'] = $delivery_post['time'];
                } elseif (($delivery['code'] == 'cdek' || $delivery['code'] == 'sdek') && $delivery_cdek = $this->getDeliveryPriceCdek($id)) {
                    $delivery['price'] += $delivery_cdek['price'];
                    $delivery['time'] = $delivery_cdek['time'];
                } elseif ($delivery['code'] == 'pek' && $delivery_pek = $this->getDeliveryPricePek()) {
                    $delivery['price'] += $delivery_pek['price'];
                    $delivery['time'] = $delivery_pek['time'];
                }
                $delivery['price'] = se_Money_Convert($delivery['price'] * ($delivery['forone'] == 'Y' ? $this->total['count'] : 1), $delivery['curr'], $this->basecurr);

                $delivery['addr'] = ($delivery['need_address'] == 'Y');
            }
        } elseif ($id === 0 && !empty($this->not_delivery)) $delivery = $this->not_delivery;
        return $delivery;
    }
}
