<?php
error_reporting(0);
$exchange_time_start = microtime(true);
session_start();
chdir('../');
date_default_timezone_set("Europe/Moscow");
define('SE_INDEX_INCLUDED', true);
require_once "system/main/init.php";
require_once "upload/lib_exchange.php";

function logExchange($text, $log_file = 'exchange.log', $mode = 'ab')
{
    $text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";
    $file = fopen($log_file, $mode);
    fwrite($file, $text);
    fclose($file);
}

function updImages52()
{
    se_db_query('INSERT LOW_PRIORITY IGNORE INTO `shop_img` (`id_price`,`picture`,`picture_alt`, `sort`, `default`) SELECT `sp`.`id`, `sp`.`img`, `sp`.`img_alt`, 0, 1 FROM `shop_price` AS `sp` WHERE `sp`.`img` IS NOT NULL AND TRIM(`sp`.`img`) <> "" ON DUPLICATE KEY UPDATE `default` = 1');
}

function getExchangeContent($query = '')
{
    $url = 'http://api.siteedit.ru/exchangesettings.php?hash=' . $query;
    if (extension_loaded('curl')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $content = curl_exec($ch);
        curl_close($ch);
    } else {
        $content = file_get_contents($url);
    }
    return $content;
}

$serial = $CONFIG['DBSerial'];
$user = urlencode($_SERVER['PHP_AUTH_USER']);
$password = urlencode($_SERVER['PHP_AUTH_PW']);


if ($user == 'admin' && $password == 'exchange_' . $serial) {
    logExchange('login server: ' . $_SERVER['QUERY_STRING']);
} else {
    logExchange('not exchange');
    exit('not');
}

function addFeatureModification($name, $value, $id_product, $id_modification)
{
    $id_feature = $id_value = 0;

    if (empty($_SESSION['features_modifications'][$name]['id'])) {
        $shop_feature = new seTable('shop_feature');
        $shop_feature->select('id, type');
        $shop_feature->where('name="?"', $name);
        $shop_feature->fetchOne();
        if ($shop_feature->isFind()) {
            if ($shop_feature->type == 'list' || $shop_feature->type == 'colorlist')
                $id_feature = $shop_feature->id;
            else
                $name .= '_1s';
        }
        if (empty($id_feature)) {
            $shop_feature->insert();
            $shop_feature->name = $name;
            $id_feature = $shop_feature->save();
        }
        $_SESSION['features_modifications'][$name]['id'] = $id_feature;
    } else {
        $id_feature = $_SESSION['features_modifications'][$name]['id'];
    }

    if (!empty($id_feature)) {
        if (empty($_SESSION['features_modifications'][$name]['values'][$value])) {
            $shop_feature_value = new seTable('shop_feature_value_list');
            $shop_feature_value->select('id');
            $shop_feature_value->where('id_feature=?', $id_feature);
            $shop_feature_value->andWhere('value="?"', $value);
            $shop_feature_value->fetchOne();
            if ($shop_feature_value->isFind()) {
                $id_value = $shop_feature_value->id;
            } else {
                $shop_feature_value->insert();
                $shop_feature_value->id_feature = $id_feature;
                $shop_feature_value->value = $value;
                $id_value = $shop_feature_value->save();
            }
            $_SESSION['features_modifications'][$name]['values'][$value] = $id_value;
        } else {
            $id_value = $_SESSION['features_modifications'][$name]['values'][$value];
        }
    }
    if (!empty($id_feature) && !empty($id_value)) {
        $shop_modifications_feature = new seTable('shop_modifications_feature');
        $shop_modifications_feature->insert();
        $shop_modifications_feature->id_price = $id_product;
        $shop_modifications_feature->id_modification = $id_modification;
        $shop_modifications_feature->id_feature = $id_feature;
        $shop_modifications_feature->id_value = $id_value;
        $shop_modifications_feature->save();
    }
}

function addModificationsGroup($group_name = '1s')
{
    $id_group = $_SESSION['modifications_group'];
    if (empty($id_group)) {
        $mod_group = new seTable('shop_modifications_group');
        $mod_group->select('id');
        $mod_group->where('name = "?"', $group_name);
        $mod_group->fetchOne();
        if ($mod_group->isFind())
            $id_group = $mod_group->id;
        else {
            $mod_group->insert();
            $mod_group->name = $group_name;
            $mod_group->vtype = 2;
            $id_group = $mod_group->save();
        }
        $_SESSION['modifications_group'] = $id_group;
    }
    return $id_group;
}

function import_offers_51($xml, $default_param_name, $update_data)
{
    $guid_product = $guid_offer = $price_opt = $price_opt_corp = $price = 0;
    @list($guid_product, $guid_offer) = explode('#', $xml->Ид);
    if ($guid_offer)
        $guid_offer = trim($xml->Ид);
    if (($xml->Статус == 'Удален' || $xml['Статус'] == 'Удален') && $guid_offer && $update_data['delete']) {
        $shop_modifications = new seTable('shop_modifications');
        $shop_modifications->where("id_exchange = '?'", $guid_offer)->deletelist();
        return;
    }

    if ($guid_product) {
        $goods = new seTable('shop_price');
        $goods->select('id, name, price, price_opt, price_opt_corp');
        $goods->where("article = '?'", $_SESSION['import_ex'][$guid_product]);
        $goods->fetchOne();
        logExchange('+++' . $_SESSION['import_ex'][$guid_product]);
        if ($goods->isFind()) {
            logExchange('+++++++');
            $guid_offer = null;
            if (isset($xml->Цены->Цена)) {
                $price = null;
                foreach ($xml->Цены->Цена as $type_price) {
                    if (empty($price))
                        $price = $type_price->ЦенаЗаЕдиницу;

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
                    }
                }
            }
            $id_good = $goods->id;

            //$count = ((float)$xml->Количество > 0) ? (float)$xml->Количество : 0;

            $count = 0;
            if (!empty($xml->Количество)) {
                $count = (float)$xml->Количество;
            } elseif (!empty($xml->Склад['КоличествоНаСкладе'])) {
                $count = (float)$xml->Склад['КоличествоНаСкладе'];
            }
            $count = max(0, $count);
            if ($guid_offer) {
                return;
                $shop_modifications = new seTable('shop_modifications');
                $shop_modifications->select('id');
                $shop_modifications->where("id_exchange = '?'", $guid_offer);
                $shop_modifications->fetchOne();
                if ($shop_modifications->isFind()) {
                    $id_modification = $shop_modifications->id;
                    if ($update_data['price']) {
                        $shop_modifications->value = $price;
                        $shop_modifications->value_opt = $price_opt;
                        $shop_modifications->value_opt_corp = $price_opt_corp;
                    }
                    if ($update_data['count'])
                        $shop_modifications->count = $count;
                    $shop_modifications->save();
                } else {
                    $shop_modifications->insert();
                    $shop_modifications->id_mod_group = addModificationsGroup();
                    $shop_modifications->id_price = $id_good;
                    $shop_modifications->value = $price;
                    $shop_modifications->value_opt = $price_opt;
                    $shop_modifications->value_opt_corp = $price_opt_corp;
                    $shop_modifications->count = $count;
                    $shop_modifications->id_exchange = $guid_offer;
                    $shop_modifications->description = $xml->Наименование;
                    $id_modification = $shop_modifications->save();

                    if ($xml->ХарактеристикиТовара->ХарактеристикаТовара) {
                        foreach ($xml->ХарактеристикиТовара->ХарактеристикаТовара as $param) {
                            $param_name = trim($param->Наименование);
                            $param_value = trim($param->Значение);

                            addFeatureModification($param_name, $param_value, $id_good, $id_modification);
                        }
                    } else {
                        $param_value = trim(str_replace(array($goods->name, '(', ')'), '', $xml->Наименование));
                        $param_name = $default_param_name;

                        addFeatureModification($param_name, $param_value, $id_good, $id_modification);
                    }
                }
            } else {

                $goods->price = $price;
                if ($price_opt)
                    $goods->price_opt = $price_opt;
                if ($price_opt_corp)
                    $goods->price_opt_corp = $price_opt_corp;
                $goods->save();
            }
        }

        if (isset($_SESSION['exchange_products'][$guid_product])) {
            unset($_SESSION['exchange_products'][$guid_product]);
        }
    }
}

$exchange_dir = SE_ROOT . 'exchange_dir/';              //директория для записи файлов импорта
if (!is_dir($exchange_dir))
    mkdir($exchange_dir);

$orders_dir = $exchange_dir . 'orders/';
if (!is_dir($orders_dir))
    mkdir($orders_dir);

$last_dir = $exchange_dir . 'last_exchange/';
if (!is_dir($last_dir))
    mkdir($last_dir);

$lang_exchange = $settings['lang'];;                          //язык для импорта
$zip_files = 'yes';                          //использовать zip сжатие  [yes, no]
$limit_filesize = 1000000;          //максимальный размер пакета для передачи
$type_price_main = '00 Розничная цена';             //наименование типов цен (типовое соглашение) для импорта ценовых предложений
$type_price_opt = '25';
$type_price_opt_corp = 'Минимально-оптовая цена';
$type_price_bonus = 'test';
$manufacturer = $settings['manufacturer'];              //наименование доп. реквизита (по 1с) используемого как производитель
$default_param_name = $settings['new_param'];          //название параметра товара для неизвестных характеристик ценового предложения
$type_code_goods = $settings['code_product'];           //тип записи кода товаров [translit, article, id, barcode] 
$type_code_groups = $settings['code_group'];            //тип записи кода групп [translit, id] транслировать, либо GUID по 1с
$max_execution_time = 30;  //максимальное время обработки одного пакета
$parent_group = $settings['parent_group'];              //код родительской группы для всех импортируемых групп
$status_accept = $settings['change_status'];           //изменить статус если заказ проведен в 1с ['Y', 'N', 'K', 'P', false]
$new_date_order = ($settings['new_date_order'] == 'Y'); //записывать дату заказа на сайте датой по 1с [true, false]
$new_date_payee = ($settings['new_date_payee'] == 'Y'); //записывать дату оплаты на сайте датой по 1с [true, false]
$main_import_image = $settings['main_image'];           //основная картинка товара (первая, либо последняя в 1с) [first, last]

$ex_group_name = ($settings['ex_group_name'] == 'Y');   //дополнительная синхронизация групп товаров по наименованию
$ex_catalog_name = array(                               //дополнительная синхронизация товаров
    'goods' => $settings['ex_catalog_product'],
    'tree_group' => ($settings['ex_catalog_group'] == 'Y')
);
$date_export_orders = $settings['date_export_orders'];  //дата (дата добавления) с которой необходимо начать экспорт закзов


$update_groups_exchange = array(                        //данные которые необходимо обновлять при импорте групп товаров
    'name' => ($settings['upd_name_group'] == 'Y'),
    'code' => ($settings['upd_code_group'] == 'Y'),
    'upid' => ($settings['upd_upid_group'] == 'Y'),
);

$update_offers_exchange = array(                        //данные которые необходимо обновлять при импорте ценовых предложений
    'price' => false,
    'count' => true,
    'delete' => false
);

$update_goods_exchange = array(                         //данные которые необходимо обновлять при импорте товаров
    'name' => ($settings['upd_name_product'] == 'Y'),
    'group' => ($settings['upd_group_product'] == 'Y'),
    'article' => ($settings['upd_article_product'] == 'Y'),
    'manufacturer' => ($settings['upd_manufacturer_product'] == 'Y'),
    'note' => ($settings['upd_note_product'] == 'Y'),
    'text' => ($settings['upd_text_product'] == 'Y'),
    'main_image' => ($settings['upd_main_image_product'] == 'Y'),
    'more_image' => ($settings['upd_more_image_product'] == 'Y'),
    'code' => ($settings['upd_code_product'] == 'Y'),
    'measure' => ($settings['upd_measure_product'] == 'Y'),
    'weight' => ($settings['upd_weight_product'] == 'Y'),
    'delete' => ($settings['remove_product'] == 'Y'),
    'features' => ($settings['import_features'] == 'Y')
);

$upd_orders = ($settings['export_only_update'] == 'Y');

if (isRequest('type') && getRequest('type') == 'sale') {

    if (getRequest('type') == 'sale' && getRequest('mode') == 'checkauth') {
        echo "success\n";
        echo session_name() . "\n";
        echo session_id() . "\n";
    }

    if (getRequest('type') == 'sale' && getRequest('mode') == 'init') {
        $mask = glob($orders_dir . "*.*");
        if (!empty($mask)) {
            foreach ($mask as $filename) {
                //$del_img = getcwd().'/'.$filename;
                @unlink($filename);
            }
        }
        echo "zip=$zip_files\n";
        echo "file_limit=$limit_filesize\n";
    }

    if (getRequest('type') == 'sale' && getRequest('mode') == 'query') {

        if (!se_db_is_field('shop_order', 'id_exchange')) {
            se_db_add_field('shop_order', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
        }
        if (!se_db_is_field('shop_order', 'number_1c')) {
            se_db_add_field('shop_order', 'number_1c', 'VARCHAR(20) DEFAULT NULL');
        }
        if (!se_db_is_field('shop_order', 'date_exchange')) {
            se_db_add_field('shop_order', 'date_exchange', "timestamp NOT NULL default '0000-00-00 00:00:00'");
        }
        if (!se_db_is_field('shop_price', 'id_exchange')) {
            se_db_add_field('shop_price', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
        }
        header("Content-type: application/xml; charset=utf-8");
        echo "\xEF\xBB\xBF";

        $list_currencies = array();
        if (!empty($settings['conform_currency'])) {
            foreach (explode(',', $settings['conform_currency']) as $currency) {
                @list($curr_shop, $curr_1c) = explode('-', trim($currency));
                if ($curr_shop && $curr_1c)
                    $list_currencies[trim($curr_shop)] = trim($curr_1c);
            }
        }

        echo get_orders_xml($date_export_orders, $list_currencies, $upd_orders);
    }

    if (getRequest('type') == 'sale' && getRequest('mode') == 'file') {

        $filename = basename(getRequest('filename', 3));
        if (!$file = fopen($orders_dir . $filename, 'ab')) {
            echo "failure\n";
            echo "Не удается записать файл: $filename\n";
            exit;
        }
        $data_file = file_get_contents('php://input');
        fwrite($file, $data_file);
        fclose($file);

        if (substr($filename, -4) == '.zip') {
            $new_filename = unzip($filename, $orders_dir);
            unlink($orders_dir . $filename);
            $filename = $new_filename;
        }

        $xml = simplexml_load_file($orders_dir . $filename);

        foreach ($xml->Документ as $order) {
            update_order($order, $status_accept, $new_date_order, $new_date_payee);
        }
        echo "success\n";
    }

    if (getRequest('type') == 'sale' && getRequest('mode') == 'success') {

        echo "success";
    }
    exit;
}

if (isRequest('type') && getRequest('type') == 'catalog') {

    if (getRequest('type') == 'catalog' && getRequest('mode') == 'checkauth') {
        echo "success\n";
        echo session_name() . "\n";
        echo session_id() . "\n";
    }

    if (getRequest('type') == 'catalog' && getRequest('mode') == 'init') {
        $mask = glob($exchange_dir . "*.*");
        if (!empty($mask)) {
            foreach ($mask as $filename) {
                //$del_img = getcwd().'/'.$filename;
                @unlink($filename);
            }
        }
        echo "zip=$zip_files\n";
        echo "file_limit=$limit_filesize\n";
    }

    if (getRequest('type') == 'catalog' && getRequest('mode') == 'file') {
        $filename = basename(getRequest('filename', 3));
        if (!$file = fopen($exchange_dir . $filename, 'ab')) {
            echo "failure\n";
            echo "Не удается записать файл: $filename\n";
        }
        $data_file = file_get_contents('php://input');
        fwrite($file, $data_file);
        fclose($file);

        if (!isset($_SESSION['unzip_file_exchange']) && $zip_files == 'yes') {
            if (substr($filename, -4) == '.zip')
                $_SESSION['unzip_file_exchange'] = $filename;
            else
                $_SESSION['unzip_file_exchange'] = 'no';
        }

        unset($_SESSION['exchange_import_groups'], $_SESSION['exchange_manufacturer']);
        $_SESSION['count_import_product'] = $_SESSION['count_import_offers'] = array();
        $_SESSION['exchange_products'] = array();

        echo "success\n";
    }

    if (getRequest('type') == 'catalog' && getRequest('mode') == 'import') {

        if (isset($_SESSION['unzip_file_exchange']) && $_SESSION['unzip_file_exchange'] != 'no') {
            $filename = $_SESSION['unzip_file_exchange'];
            logExchange('start unzip filename ' . $filename);
            unzip($filename, $exchange_dir);
            unlink($exchange_dir . $filename);
            $_SESSION['unzip_file_exchange'] = 'no';
            logExchange('complete unzip filename ' . $filename);
            echo "progress\n";
            exit;
        }
        $filename = basename(getRequest('filename', 3));
        if (preg_match('/^import(.*?)\.xml$/', $filename)) {
            /*
            if (file_exists($exchange_dir . $filename) && !isset($_SESSION['exchange_import_groups'][$filename])) {
                logExchange('start import groups catalog ' . $filename);

                if (!se_db_is_field('shop_group', 'id_exchange')) {
                    se_db_add_field('shop_group', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
                }

                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);

                while ($read_xml->read() && $read_xml->name !== 'Классификатор') ;

                $xml = new SimpleXMLElement($read_xml->readOuterXML());
                $read_xml->close();

                $id_group = !empty($parent_group) ? $parent_group : 0;

                import_groups($xml, $id_group, $type_code_groups, $lang_exchange, $ex_group_name, $update_groups_exchange);
                if ($settings['import_features'] == 'Y') {
                    if (!se_db_is_field('shop_feature', 'id_exchange')) {
                        se_db_add_field('shop_feature', 'id_exchange', 'VARCHAR(40) DEFAULT NULL');
                        se_db_query('ALTER TABLE shop_feature ADD UNIQUE INDEX UK_shop_feature_id_ex (id_exchange)');
                    }
                    if (!se_db_is_field('shop_feature_value_list', 'id_exchange')) {
                        se_db_add_field('shop_feature_value_list', 'id_exchange', 'VARCHAR(40) DEFAULT NULL');
                        se_db_query('ALTER TABLE shop_feature_value_list ADD UNIQUE INDEX UK_shop_feature_value_list_id_ex (id_exchange)');
                    }
                    import_properties($xml, $manufacturer);
                }
                else {
                    check_brand_properties($xml, $manufacturer);
                }
                $_SESSION['exchange_import_groups'][$filename] = 'complete';
                logExchange('complete import groups catalog ' . $filename);
            }
            */

            if (file_exists($exchange_dir . $filename)) {
                logExchange('start import products');

                if (copy($exchange_dir . $filename, $last_dir . $filename)) {
                    logExchange('copy last file products ' . $filename);
                }

                if (!se_db_is_field('shop_price', 'id_exchange')) {
                    se_db_add_field('shop_price', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
                }

                $image_dir = getcwd() . '/images/';
                if (!is_dir($image_dir))
                    mkdir($image_dir);

                $image_dir_lang = $image_dir . $lang_exchange . '/';
                if (!is_dir($image_dir_lang))
                    mkdir($image_dir_lang);

                if (!is_dir($image_dir_lang . 'shopprice/'))
                    mkdir($image_dir_lang . 'shopprice/');

                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);

                while ($read_xml->read() && $read_xml->name !== 'Товар');

                if (isset($_SESSION['count_import_product'][$filename]) && $_SESSION['count_import_product'][$filename] > 0) {
                    for ($i = 0; $i < $_SESSION['count_import_product'][$filename]; $i++) {
                        $read_xml->next('Товар');
                    }
                } else {
                    $_SESSION['count_import_product'][$filename] = 0;
                }

                while ($read_xml->name == 'Товар') {
                    //echo 'test'.$lang_exchange;
                    $xml = new SimpleXMLElement($read_xml->readOuterXML());
                    //import_catalog($xml, $exchange_dir, $main_import_image, $type_code_goods, $lang_exchange, $ex_catalog_name, $update_goods_exchange, '5.3');

                    $_SESSION['import_ex'][(string)$xml->Ид] = (string)$xml->Артикул;

                    $exchange_time_current = microtime(true);
                    if (ceil($exchange_time_current - $exchange_time_start) >= $max_execution_time) {
                        $read_xml->close();
                        unset($read_xml, $xml);
                        logExchange('progress import products, already imported ' . $_SESSION['count_import_product'][$filename]);
                        echo "\xEF\xBB\xBF";
                        echo "progress\n";
                        echo "Количество импортированных товаров: " . $_SESSION['count_import_product'][$filename];
                        exit;
                    } else {
                        $read_xml->next('Товар');
                    }
                }
                $read_xml->close();
                logExchange('complete import products, all imported ' . $_SESSION['count_import_product'][$filename]);

                $file = fopen($last_dir . 'exchange_products.json', 'w+');
                fwrite($file, json_encode($_SESSION['exchange_products']));
                fclose($file);
            } else {
                echo "\xEF\xBB\xBF";
                echo 'файл import.xml не существует';
                exit;
            }

            /*
            updImages52();
			se_db_query('TRUNCATE shop_group_tree;');
            unlink(SE_ROOT . 'system/logs/shop_group_tree.upd');
			
			plugin_shopgroups::getInstance();
            */

            echo "success\n";
        } elseif (preg_match('/^offers(.*?)\.xml$/', $filename)) {
            if (file_exists($exchange_dir . $filename) && !isset($_SESSION['exchange_type_price'])) {
                logExchange('start record prices');
                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);
                while ($read_xml->read() && $read_xml->name !== 'ТипЦены');

                while ($read_xml->name == 'ТипЦены') {
                    $xml = new SimpleXMLElement($read_xml->readOuterXML());

                    if (!isset($_SESSION['exchange_type_price']['main']) && isset($xml->Ид)) {
                        $_SESSION['exchange_type_price']['main'] = (string)$xml->Ид;
                    }

                    switch (trim($xml->Наименование)) {
                        case trim($type_price_main):
                            $_SESSION['exchange_type_price']['main'] = (string)$xml->Ид;
                            break;
                        case trim($type_price_opt):
                            $_SESSION['exchange_type_price']['opt'] = (string)$xml->Ид;
                            break;
                        case trim($type_price_opt_corp):
                            $_SESSION['exchange_type_price']['opt_corp'] = (string)$xml->Ид;
                            break;
                        case trim($type_price_bonus):
                            $_SESSION['exchange_type_price']['bonus'] = (string)$xml->Ид;
                            break;
                    }

                    $read_xml->next('ТипЦены');
                }
                $read_xml->close();
                unset($read_xml, $xml);
                logExchange('comlete record prices');
            }
            if (file_exists($exchange_dir . $filename)) {
                logExchange('start import offers');
                if (copy($exchange_dir . $filename, $last_dir . $filename)) {
                    logExchange('copy last file offers ' . $filename);
                }

                $feature = new seTable('shop_modifications');
                $feature->select('COUNT(*)');
                if ($feature->fetchOne()) {
                    $import51 = true;
                    if (!se_db_is_field('shop_modifications', 'id_exchange')) {
                        se_db_add_field('shop_modifications', 'id_exchange', 'VARCHAR(80) DEFAULT NULL');
                        se_db_query('ALTER TABLE shop_modifications ADD UNIQUE INDEX UK_shop_modifications_id_ex (id_exchange)');
                    }
                } else {
                    if (!se_db_is_field('shop_price_param', 'id_exchange')) {
                        se_db_add_field('shop_price_param', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
                    }
                }

                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);

                while ($read_xml->read() && $read_xml->name !== 'КоммерческаяИнформация');

                $schema_version = $read_xml->getAttribute('ВерсияСхемы');

                while ($read_xml->read() && $read_xml->name !== 'Предложение');

                if (isset($_SESSION['count_import_offers'][$filename])) {
                    for ($i = 0; $i < $_SESSION['count_import_offers'][$filename]; $i++) {
                        $read_xml->next('Предложение');
                    }
                } else {
                    $_SESSION['count_import_offers'][$filename] = 0;
                    /*
                    if ($schema_version == '2.07' && !empty($_SESSION['exchange_products'])) {
                        $sql = 'UPDATE shop_price sp SET sp.enabled = "Y" WHERE sp.id IN (' . join(',', $_SESSION['exchange_products']) . ')';
                        se_db_query($sql);
                        logExchange('schema version 2.07, enabled imported products, count ' . count($_SESSION['exchange_products']));
                    }
                    */
                }

                while ($read_xml->name == 'Предложение') {
                    $xml = new SimpleXMLElement($read_xml->readOuterXML());

                    import_offers_51($xml, $default_param_name, $update_offers_exchange);

                    $_SESSION['count_import_offers'][$filename]++;

                    $exchange_time_current = microtime(true);

                    if (ceil($exchange_time_current - $exchange_time_start) >= $max_execution_time) {
                        $read_xml->close();
                        unset($read_xml, $xml);
                        logExchange('progress import offers, already imported ' . $_SESSION['count_import_offers'][$filename]);
                        echo "\xEF\xBB\xBF";
                        echo "progress\n";
                        echo "Количество импортированных предложений: " . $_SESSION['count_import_offers'][$filename];
                        exit;
                    }

                    $read_xml->next('Предложение');
                }
                $read_xml->close();
                unset($read_xml, $xml);
                logExchange('complete import offers, all imported ' . $_SESSION['count_import_offers'][$filename]);
                /*
                if ($schema_version == '2.07' && !empty($_SESSION['exchange_products'])) {
                    $sql = 'UPDATE shop_price sp SET sp.enabled = "N" WHERE sp.id IN (' . join(',', $_SESSION['exchange_products']) . ')';
                    se_db_query($sql);
                    logExchange('schema version 2.07, disabled products (not offers), count ' . count($_SESSION['exchange_products']));
                }
                */
            } else {
                echo "\xEF\xBB\xBF";
                echo 'файл offers.xml не существует';
                exit;
            }
            echo "success\n";
        }
    }
    exit;
}
