<?php
function module_exchange($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $_page = $__data->req->page;
 $_razdel = $__data->req->razdel;
 $_sub = $__data->req->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/exchange';
 else $__MDL_URL = 'modules/exchange';
 $__MDL_ROOT = dirname(__FILE__).'/exchange';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	append_simplexml($section->language,simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml'));
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   if (!empty($section->traslates->$__name))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 //header ( "Content-type: text/html; charset=utf-8" );
 //error_reporting(E_ALL);
  
 $exchange_time_start = microtime(true);
 
 $exchange_user = $section->parametrs->param12;                        //имя пользователя (логин) для авторизации
 $exchange_pass = $section->parametrs->param13;                        //пароль пользователя для авторизации         
 
 if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && ($_SERVER['PHP_AUTH_USER'] == $exchange_user && $_SERVER['PHP_AUTH_PW'] == $exchange_pass)){
     //echo 'success';
 }
 else{
     exit;
 }
 
 $exchange_dir = getcwd()."/exchange_dir/";              //директория для записи файлов импорта
 if (!is_dir($exchange_dir))       
     mkdir($exchange_dir);         
 
 $orders_dir = $exchange_dir.'orders/';   
 if (!is_dir($orders_dir))       
     mkdir($orders_dir);
                                   
 $lang_exchange = se_getLang();                          //язык для импорта
 $zip_files = $section->parametrs->param1;                                  //использовать zip сжатие  [yes, no]
 $limit_filesize = 2000000;                              //максимальный размер пакета для передачи
 $type_price_main = $section->parametrs->param2;                            //наименование типов цен (типовое соглашение) для импорта ценовых предложений
 $type_price_opt = $section->parametrs->param34;
 $type_price_opt_corp = $section->parametrs->param33;
 $type_price_bonus = 'test';//$section->parametrs->param35;
 $manufacturer = $section->parametrs->param3;                               //наименование доп. реквизита (по 1с) используемого как производитель
 $default_param_name = $section->parametrs->param4;                         //название параметра товара для неизвестных характеристик ценового предложения
 $type_code_goods = $section->parametrs->param5;                            //тип записи кода товаров [translit, article, id, barcode] 
 $type_code_groups = $section->parametrs->param28;                          //тип записи кода групп [translit, id] транслировать, либо GUID по 1с
 $max_execution_time = 30;//(int)$section->parametrs->param6;               //максимальное время обработки одного пакета
 $parent_group = $section->parametrs->param7;                               //код родительской группы для всех импортируемых групп
 $status_accept = $section->parametrs->param8;                              //изменить статус если заказ проведен в 1с ['Y', 'N', 'K', 'P', false]
 $new_date_order = ($section->parametrs->param9 == 'Y');                    //записывать дату заказа на сайте датой по 1с [true, false]
 $new_date_payee = ($section->parametrs->param10 == 'Y');                   //записывать дату оплаты на сайте датой по 1с [true, false]
 $main_import_image = $section->parametrs->param11;                         //основная картинка товара (первая, либо последняя в 1с) [first, last]
 
 $ex_group_name = ($section->parametrs->param14 == 'Y');                    //дополнительная синхронизация групп товаров по наименованию
 $ex_catalog_name = $section->parametrs->param15;                           //дополнительная синхронизация товаров
 $date_export_orders = date('Y-m-d H:i:s', strtotime($section->parametrs->param16));  //дата (дата добавления) с которой необходимо начать экспорт закзов
 
 
 $update_groups_exchange = array(                        //данные которые необходимо обновлять при импорте групп товаров
     'name' => ($section->parametrs->param18 == 'Y')
 );
 
 $update_offers_exchange = array(                        //данные которые необходимо обновлять при импорте ценовых предложений
     'price' => ($section->parametrs->param25 == 'Y'),
     'count' => ($section->parametrs->param26 == 'Y'),
     'delete' => ($section->parametrs->param27 == 'Y')    
 );
 //$default_count = ($section->parametrs->param37 == 'Y'); 
 
 $update_goods_exchange = array(                         //данные которые необходимо обновлять при импорте товаров
     'name' => ($section->parametrs->param19 == 'Y'),
     'group' => ($section->parametrs->param36 == 'Y'),
     'article' => ($section->parametrs->param20 == 'Y'),
     'manufacturer' => ($section->parametrs->param21 == 'Y'),
     'note' => ($section->parametrs->param22 == 'Y'),
     'text' => ($section->parametrs->param38 == 'Y'),
     'main_image' => ($section->parametrs->param23 == 'Y'),
     'more_image' => ($section->parametrs->param24 == 'Y'),
     'code' => ($section->parametrs->param29 == 'Y'),
     'measure' => ($section->parametrs->param30 == 'Y'),
     'weight' => ($section->parametrs->param31 == 'Y'),
     'delete' => ($section->parametrs->param27 == 'Y')
 );
 
 $upd_orders = ($section->parametrs->param40 == 'Y'); 
 
 
 if (isRequest('type') && getRequest('type')  == 'sale'){
     
     if(getRequest('type') == 'sale' && getRequest('mode') == 'checkauth'){
         echo "success\n";
         echo session_name()."\n";
         echo session_id()."\n";
     }
     
     if(getRequest('type') == 'sale' && getRequest('mode') == 'init'){
         $mask = "exchange_dir/orders/*.*";
         foreach (glob($mask) as $filename) { 
             $del_img = getcwd().'/'.$filename;
             @unlink($del_img);
         }
         echo "zip=$zip_files\n";
         echo "file_limit=$limit_filesize\n";
 
     }
     
     if(getRequest('type') == 'sale' && getRequest('mode') == 'query'){
         
         if(!se_db_is_field('shop_order', 'id_exchange')){
             se_db_add_field('shop_order', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');        
         }
         if(!se_db_is_field('shop_order', 'number_1c')){
             se_db_add_field('shop_order', 'number_1c', 'VARCHAR(20) DEFAULT NULL');        
         }         
         if(!se_db_is_field('shop_order', 'date_exchange')){
             se_db_add_field('shop_order', 'date_exchange', "timestamp NOT NULL default '0000-00-00 00:00:00'");        
         }
         if(!se_db_is_field('shop_price', 'id_exchange')){
             se_db_add_field('shop_price', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');        
         }
         header ("Content-type: text/xml; charset=utf-8");
         echo "\xEF\xBB\xBF";
         
         $list_currencies = array();        
         if (!empty($section->parametrs->param39)){
             foreach(explode(',', trim($section->parametrs->param39)) as $currency){
                 @list($curr_shop, $curr_1c) = explode('-', trim($currency));
                 if ($curr_shop && $curr_1c)
                     $list_currencies[(string)trim($curr_shop)] = trim($curr_1c);
             }
         }
         
         echo get_orders_xml($date_export_orders, $list_currencies, $upd_orders);                
     }
     
     if(getRequest('type') == 'sale' && getRequest('mode') == 'file'){
         
         $filename = basename(getRequest('filename', 3));
         if (!$file = se_fopen($orders_dir.$filename, 'ab')){
             echo "failure\n"; 
             echo "Не удается записать файл: $filename\n";
             exit;    
         }
         $data_file = se_file_get_contents('php://input');
         fwrite($file, $data_file);
         fclose($file);
         
         if (substr($filename, -4) == '.zip'){
             $new_filename = unzip($filename, $orders_dir);
             unlink($orders_dir.$filename);
             $filename = $new_filename;
         }
         
         $xml = simplexml_load_file($orders_dir.$filename); 
 
         foreach($xml->Документ as $order){
             update_order($order, $status_accept, $new_date_order, $new_date_payee);
         }
         echo "success\n";    
     }
     
     if(getRequest('type') == 'sale' && getRequest('mode') == 'success'){
         
         echo "success";
             
     }  
     exit;
 }
 
 if (isRequest('type') && getRequest('type') == 'catalog'){
    
     if(getRequest('type') == 'catalog' && getRequest('mode') == 'checkauth'){
         echo "success\n";
         echo session_name()."\n";
         echo session_id()."\n";
     }
     
     if(getRequest('type') == 'catalog' && getRequest('mode') == 'init'){
         $mask = "exchange_dir/*.*";
         foreach (glob($mask) as $filename) { 
             $del_img = getcwd().'/'.$filename;
             @unlink($del_img);
         }
         
         echo "zip=$zip_files\n";
         echo "file_limit=$limit_filesize\n";
 
     }
      
     if(getRequest('type') == 'catalog' && getRequest('mode') == 'file'){
         $filename = basename(getRequest('filename', 3));
         if (!$file = se_fopen($exchange_dir.$filename, 'ab')){
             echo "failure\n"; 
             echo "Не удается записать файл: $filename\n";    
         }
         $data_file = se_file_get_contents('php://input');
         fwrite($file, $data_file);
         fclose($file);
         
         if (!isset($_SESSION['unzip_file_exchange']) && $zip_files == 'yes'){
             if (substr($filename, -4) == '.zip')
                 $_SESSION['unzip_file_exchange'] = $filename;
             else 
                 $_SESSION['unzip_file_exchange'] = 'no';
         }
         unset($_SESSION['exchange_import_groups'], $_SESSION['exchange_manufacturer']);
         $_SESSION['count_import_product'] = 0;
         $_SESSION['count_import_offers'] = 0;
         
         echo "success\n";
 
     }
     
     if(getRequest('type') == 'catalog' && getRequest('mode') == 'import'){
         if (isset($_SESSION['unzip_file_exchange']) && $_SESSION['unzip_file_exchange'] != 'no'){           
             $filename = $_SESSION['unzip_file_exchange'];
             unzip($filename, $exchange_dir);
             unlink($exchange_dir.$filename);
             $_SESSION['unzip_file_exchange'] = 'no';  
         }
         $filename = basename(getRequest('filename', 3));
         if ($filename == 'import.xml'){
             if (file_exists($exchange_dir.$filename) && !isset($_SESSION['exchange_import_groups'])){
                 
                 if(!se_db_is_field('shop_group', 'id_exchange')){
                     se_db_add_field('shop_group', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');        
                 }
                 
                 $read_xml = new XMLReader;
                 $read_xml->open($exchange_dir.$filename);
 
                 while ($read_xml->read() && $read_xml->name !== 'Классификатор');
 
                 $xml = new SimpleXMLElement($read_xml->readOuterXML());
                 $read_xml->close();
                 if ($parent_group){
                     $groups = new seTable('shop_group');
                     $groups->select('id');
                     $groups->where("code_gr = '?'", $parent_group);
                     $groups->fetchOne();
                     $id_group = $groups->id;
                     unset($groups);
                 }
                 import_groups($xml, $id_group, $type_code_groups, $lang_exchange, $ex_group_name, $update_groups_exchange);
                 import_properties($xml, $manufacturer);
                 $_SESSION['exchange_import_groups'] = 'complete';
             }
             if(file_exists($exchange_dir.$filename)){
             
                 if(!se_db_is_field('shop_price', 'id_exchange')){
                     se_db_add_field('shop_price', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');        
                 }
             
                 $image_dir = getcwd().'/images/';
                 if (!is_dir($image_dir))       
                     mkdir($image_dir);
                 
                 $image_dir_lang = $image_dir.$lang_exchange.'/';
                 if (!is_dir($image_dir_lang))       
                     mkdir($image_dir_lang);
                 
                 if (!is_dir($image_dir_lang.'shopprice/'))       
                     mkdir($image_dir_lang.'shopprice/');
                 
                 $read_xml = new XMLReader;
                 $read_xml->open($exchange_dir.$filename);
   
                 while ($read_xml->read() && $read_xml->name !== 'Товар');
                 
                 if (isset($_SESSION['count_import_product']) && $_SESSION['count_import_product'] > 0){
                     for ($i = 0; $i < $_SESSION['count_import_product']; $i++){
                         $read_xml->next('Товар');
                     }
                 }
                 else{
                     $_SESSION['count_import_product'] = 0;    
                 }
                 
                 while ($read_xml->name == 'Товар'){
                     $xml = new SimpleXMLElement($read_xml->readOuterXML());
                     import_catalog($xml, $exchange_dir, $main_import_image, $type_code_goods, $lang_exchange, $ex_catalog_name, $update_goods_exchange);
                     $_SESSION['count_import_product']++;
                     
                     $exchange_time_current = microtime(true);
                     if (ceil($exchange_time_current - $exchange_time_start) >= $max_execution_time){
                         $read_xml->close();
                         unset($read_xml, $xml);
                         echo "\xEF\xBB\xBF";
                         echo "progress\n";
                         echo "Количество импортированных товаров: ".$_SESSION['count_import_product'];
                         exit;
                     }
                     else{
                         $read_xml->next('Товар');
                     }
                 }
                 $read_xml->close();
             }
             else{
                 echo "\xEF\xBB\xBF";
                 echo 'файл import.xml не существует';
                 exit;
             }            
             echo "success\n";    
         }
         elseif($filename == 'offers.xml'){
 
             if (file_exists($exchange_dir.$filename) && !isset($_SESSION['exchange_type_price'])){
                 $read_xml = new XMLReader;
                 $read_xml->open($exchange_dir.$filename);
                 while ($read_xml->read() && $read_xml->name !== 'ТипЦены');
     
                 while ($read_xml->name == 'ТипЦены'){
                     $xml = new SimpleXMLElement($read_xml->readOuterXML());
                     
                     if (!isset($_SESSION['exchange_type_price']['main']) && isset($xml->Ид)){
                         $_SESSION['exchange_type_price']['main'] = $xml->Ид;
                     }
                     
                     switch (trim($xml->Наименование)){
                         case trim($type_price_main):
                             $_SESSION['exchange_type_price']['main'] = $xml->Ид;
                             break;
                         case trim($type_price_opt):
                             $_SESSION['exchange_type_price']['opt'] = $xml->Ид;
                             break;
                         case trim($type_price_opt_corp):
                             $_SESSION['exchange_type_price']['opt_corp'] = $xml->Ид;
                             break;                        
                         case trim($type_price_bonus):
                             $_SESSION['exchange_type_price']['bonus'] = $xml->Ид;
                             break;
                     }
                     
                     $read_xml->next('ТипЦены');    
                 }
                 $read_xml->close();
                 unset($read_xml, $xml);    
             }
             
             if(file_exists($exchange_dir.$filename)){
             
                 if(!se_db_is_field('shop_price_param', 'id_exchange')){
                     se_db_add_field('shop_price_param', 'id_exchange', 'VARCHAR(50) DEFAULT NULL');        
                 }
                 
                 $read_xml = new XMLReader;
                 $read_xml->open($exchange_dir.$filename);
                 while ($read_xml->read() && $read_xml->name !== 'Предложение');
                 
                 if (isset($_SESSION['count_import_offers']) && $_SESSION['count_import_offers'] > 0){
                     for ($i = 0; $i < $_SESSION['count_import_offers']; $i++){
                         $read_xml->next('Предложение');
                     }
                 }
                 else{
                     $_SESSION['count_import_offers'] = 0;    
                 }
                 
                 while ($read_xml->name == 'Предложение'){
                     $xml = new SimpleXMLElement($read_xml->readOuterXML());
                     import_offers($xml, $default_param_name, $update_offers_exchange);
                     $_SESSION['count_import_offers']++;
                     
                     $exchange_time_current = microtime(true);
                     if (ceil($exchange_time_current - $exchange_time_start) >= $max_execution_time){
                         $read_xml->close();
                         unset($read_xml, $xml);
                         echo "\xEF\xBB\xBF";
                         echo "progress\n";
                         echo "Количество импортированных предложений: ".$_SESSION['count_import_offers'];
                         exit;
                     }
                     
                     $read_xml->next('Предложение');
                                       
                 }
                 $read_xml->close();
                 unset($read_xml, $xml);                                            
             }
             else{
                 echo "\xEF\xBB\xBF";
                 echo 'файл offers.xml не существует';
                 exit;
             }
             echo "success\n";
         }        
     }    
     exit;    
 }

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}