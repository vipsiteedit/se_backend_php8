<?php

error_reporting(0);
$exchange_time_start = microtime(true);

session_start();

define('SE_INDEX_INCLUDED', true);
define('SE_ROOT', str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . '/'));
define('DB_CACHED', false);
chdir(SE_ROOT);
include SE_ROOT . 'system/config_db.php';
require_once SE_ROOT . 'lib/lib_database.php';
se_db_dsn();
se_db_connect($CONFIG);
require_once SE_ROOT . 'system/main/serequests.php';
require_once SE_ROOT . 'lib/lib.php';
require_once SE_ROOT . 'lib/lib_se_function.php';

require_once SE_ROOT . 'lib/lib_exchange.php';



function logExchange($text, $log_file = 'exchange.log', $mode = 'ab')
{
    $text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";
    $file = fopen(__DIR__ . '/' . $log_file, $mode);
    fwrite($file, $text);
    fclose($file);
}

function updImages52()
{
    se_db_query('INSERT LOW_PRIORITY IGNORE INTO `shop_img` (`id_price`,`picture`,`picture_alt`, `sort`, `default`) SELECT `sp`.`id`, `sp`.`img`, `sp`.`img_alt`, 0, 1 FROM `shop_price` AS `sp` WHERE `sp`.`img` IS NOT NULL AND TRIM(`sp`.`img`) <> "" ON DUPLICATE KEY UPDATE `default` = 1');
}


$user = urldecode($_SERVER['PHP_AUTH_USER']);
$password = urldecode($_SERVER['PHP_AUTH_PW']);

$datadir = SE_ROOT . 'data';

if (file_exists($datadir . '/config_1c.json')) {
    $settings = json_decode(file_get_contents($datadir . '/config_1c.json'), true);
    if ($settings['login'] != $user || $settings['password'] != $password) {
        $settings = array();
        //unset($_SESSION['exchange_type_price']);
        //$_SESSION['count_import_offers']['offers.xml'] = 0;
    }
}

if (!empty($settings)) {
    logExchange('login server: ' . $_SERVER['QUERY_STRING']);
} else {
    logExchange('not exchange');
    exit('not');
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

$lang_exchange = ($settings['lang']) ? $settings['lang'] : 'rus';                          //язык для импорта
$zip_files = $settings['zip'];                          //использовать zip сжатие  [yes, no]
$limit_filesize = $settings['limit_filesize'];          //максимальный размер пакета для передачи
$type_price_main = $settings['price_main'];             //наименование типов цен (типовое соглашение) для импорта ценовых предложений
$type_price_opt = $settings['price_opt'];
$type_price_opt_corp = $settings['price_opt_corp'];
$type_price_bonus = 'test';
$manufacturer = $settings['manufacturer'];              //наименование доп. реквизита (по 1с) используемого как производитель
$default_param_name = $settings['new_param'];          //название параметра товара для неизвестных характеристик ценового предложения
$type_code_goods = $settings['code_product'];           //тип записи кода товаров [translit, article, id, barcode] 
$type_code_groups = $settings['code_group'];            //тип записи кода групп [translit, id] транслировать, либо GUID по 1с
$max_execution_time = $settings['max_execution_time'];  //максимальное время обработки одного пакета
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
    'price' => ($settings['upd_price_product'] == 'Y'),
    'count' => ($settings['upd_count_product'] == 'Y'),
    'delete' => ($settings['remove_product'] == 'Y')
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
            if (file_exists($exchange_dir . $filename) && !isset($_SESSION['exchange_import_groups'][$filename])) {
                logExchange('start import groups catalog ' . $filename);

                if (!se_db_is_field('shop_group', 'id_exchange')) {
                    se_db_add_field('shop_group', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');
                }

                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);

                while ($read_xml->read() && $read_xml->name !== 'Классификатор');

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
                } else {
                    check_brand_properties($xml, $manufacturer);
                }
                $_SESSION['exchange_import_groups'][$filename] = 'complete';
                logExchange('complete import groups catalog ' . $filename);
            }
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
                    import_catalog($xml, $exchange_dir, $main_import_image, $type_code_goods, $lang_exchange, $ex_catalog_name, $update_goods_exchange, '5.3');
                    $_SESSION['count_import_product'][$filename]++;

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

            updImages52();
            se_db_query('TRUNCATE shop_group_tree;');
            unlink(SE_ROOT . 'system/logs/shop_group_tree.upd');

            plugin_shopgroups::getInstance();

            echo "success\n";
        } elseif (preg_match('/^offers(.*?)\.xml$/', $filename)) {
            if (file_exists($exchange_dir . $filename) && !isset($_SESSION['exchange_type_price'])) {
                logExchange('start record prices');
                $read_xml = new XMLReader;
                $read_xml->open($exchange_dir . $filename);
                while ($read_xml->read() && $read_xml->name !== 'ТипЦены');

                while ($read_xml->name == 'ТипЦены') {
                    $xml = new SimpleXMLElement($read_xml->readOuterXML());
                    $nameprice = trim((string)$xml->Наименование);

                    if (!isset($_SESSION['exchange_type_price']['main']) && isset($xml->Ид)) {
                        $_SESSION['exchange_type_price']['main'] = (string)$xml->Ид;
                    }

                    if ($nameprice == $type_price_main) {
                        $_SESSION['exchange_type_price']['main'] = (string)$xml->Ид;
                    }
                    if ($nameprice == $type_price_opt) {
                        $_SESSION['exchange_type_price']['opt'] = (string)$xml->Ид;
                    }
                    if ($nameprice == $type_price_opt_corp) {
                        $_SESSION['exchange_type_price']['opt_corp'] = (string)$xml->Ид;
                    }
                    if ($nameprice == $type_price_bonus) {
                        $_SESSION['exchange_type_price']['bonus'] = (string)$xml->Ид;
                    }

                    $read_xml->next('ТипЦены');
                }
                //print_r($_SESSION['exchange_type_price']);
                $read_xml->close();
                unset($read_xml, $xml);
                logExchange('comlete record prices');
            }
            if (file_exists($exchange_dir . $filename)) {
                logExchange('start import offers');

                if (copy($exchange_dir . $filename, $last_dir . $filename)) {
                    logExchange('copy last file offers ' . $filename . ' в ' . $last_dir . $filename);
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

                    $_SESSION['exchange_products'] = json_decode(file_get_contents($last_dir . 'exchange_products.json'), 1);
                    $_SESSION['count_import_offers'][$filename] = 0;
                    if ($schema_version == '2.07' && !empty($_SESSION['exchange_products'])) {
                        $sql = 'UPDATE shop_price sp SET sp.enabled = "Y" WHERE sp.id IN (' . join(',', $_SESSION['exchange_products']) . ')';
                        se_db_query($sql);
                        logExchange('schema version 2.07, enabled imported products, count ' . count($_SESSION['exchange_products']));
                    }
                }

                while ($read_xml->name == 'Предложение') {
                    $xml = new SimpleXMLElement($read_xml->readOuterXML());

                    if (!empty($import51))
                        import_offers_51($xml, $default_param_name, $update_offers_exchange);
                    else
                        import_offers($xml, $default_param_name, $update_offers_exchange);

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

                if ($schema_version == '2.07' && !empty($_SESSION['exchange_products'])) {
                    $sql = 'UPDATE shop_price sp SET sp.enabled = "N" WHERE sp.id IN (' . join(',', $_SESSION['exchange_products']) . ')';
                    se_db_query($sql);
                    logExchange('schema version 2.07, disabled products (not offers), count ' . count($_SESSION['exchange_products']));
                }
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
