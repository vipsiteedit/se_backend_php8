<?php
function module_shopmini_carts($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $_page = $__data->req->page;
 $_razdel = $__data->req->razdel;
 $_sub = $__data->req->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/shopmini_carts';
 else $__MDL_URL = 'modules/shopmini_carts';
 $__MDL_ROOT = dirname(__FILE__).'/shopmini_carts';
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
 
 //переход на субстраницу
 if (isRequest('orderfinish')){
    $__data->goSubName($section, 2);
 }
 
 //нет заказа кнопка, купить нетактивна
 if (empty($_SESSION['mshopcart'])) {
     $disabled_button = 'disabled="disabled"';
 }
 
 $error='';
 $incart = array();                                                        
 if (!file_exists("data/")) {
     mkdir("data", 0755);  
 }
 $filenamecount = 'data/count.dat';            //файл для счетчика № заказа
 //нет файла, будет
 if (!file_exists($filenamecount)) {
     file_put_contents($filenamecount, '1',FILE_APPEND);    
 }
 
 //очитстить список
 if (isRequest('shcart_clear')){
     unset($_SESSION['mshopcart']);
     $disabled_button = 'disabled="disabled"';
 }
 
 //удалить строку
 if (isRequest('dellcart')) {
     $newcart = array();
     $dellcart = $_POST['dellcart'];
     foreach($_SESSION['mshopcart'] as $key => $value) {
         if (!array_key_exists($key, $dellcart)){ 
             $newcart[$key] = $value;
         }
     }
     unset($_SESSION['mshopcart']);
     $_SESSION['mshopcart'] = $newcart;
     if(count($newcart)==0){ 
         $disabled_button = 'disabled="disabled"';
     }
 }
 
 // Перезагрузка и продолжить заказ
 if (isRequest('shcart_reload')) // || isRequest('shcart_next')) 
 {
 // Проверяем сессию и берем из нее заказ
     if (isset($_SESSION['mshopcart'])) {
         $incart = $_SESSION['mshopcart'];
         if (isRequest('countitem')) { 
             foreach($_POST['countitem'] as $key => $value) {
                 if ($value > 0) {
                     $incart[$key]['count'] = intval($value);
                 } else unset($incart[$key]);
             }
             $_SESSION['mshopcart'] = $incart;
         }
     }
 //    if (isset($_POST['shcart_next'])) {seData::getInstance()->goSubName($section, 1);}
 }
 
 //Отправка писем клиенту и админу
 if (isRequest('GoToPay')){
 
     $flag = true;
     // === проверка объектов на наличие там записей!!
 
     foreach ($section->objects as $item) {
         if (isRequest("mformtitle" . $item->id)) {
             if (isRequest("mformcheck" . $item->id)) {
                 if (empty($_POST["mformobj" . $item->id])) {
                     $flag = false;
                     $error = $section->parametrs->param23;
                     break;
                 }
             } else if (isRequest('isfile' . $item->id)) {
                 $upfile = $_FILES["mformobj".$item->id];          
                 if (is_uploaded_file($upfile['tmp_name']) && (round($upfile['size'] / 1024) > intval($section->parametrs->param24))) { 
                     $flag = false;
                     $error = $section->parametrs->param28 . $section->parametrs->param24 . 'Кб';
                     break;
                 } 
             } 
         }
     }
     // == конец проверки
 
     $mail_text = '';
     $filename = array();
     foreach ($section->objects as $item) {
         if (isset($_POST["mformtitle" . $item->id])) {
             $formt = $item->title;
             if (!isset($_POST['isfile' . $item->id])) { 
                 $formx = isset($_POST['mformobj' . $item->id]) ? strval($_POST['mformobj' . $item->id]) : '';
                 if (empty($formx)) {
                     if (isset($_POST['ischeckbox' . $item->id])) {
                         $formx = $section->parametrs->param26; // "Нет"
                     } else {
                         $formx = '-';
                     }
                 } 
 
                 $mail_text .= '<b>' . $formt . '</b>';//str_replace('<br>', "\n", $formt); 
                 $text = stripslashes($formx) . '<br>';//str_replace('<br>', "\n", stripslashes($formx)) . "\n";
             } else {                                                          
                 $uploadfld = getcwd() . '/modules/upload';
                 if (!file_exists($uploadfld."/")) {
                     mkdir($uploadfld, 0755);  
                 }
                 $upfile = &$_FILES["mformobj".$item->id];                    
                 if (is_uploaded_file($upfile['tmp_name'])) {
                     move_uploaded_file($upfile['tmp_name'], $uploadfld . '/' . $upfile['name']);    
                     $filename[] = $uploadfld . '/' . $upfile['name'];
                 }                                                             
             }
             list($type) = explode('|', $item->text1);                       
             switch ($type) {
                 case 'title':
                     $mail_text .= "<br>";
                     break;
                 case '*string':
                     $mail_text .= ": " . $text;
                     break;
                 case '*email':
                     $mail_text .= ": " . $text;
                     break;
                 case 'email':
                     $mail_text .= ": " . $text;
                     break;
                 case 'string':
                     $mail_text .= ": " . $text;
                     break;        
                 case '*list':
                     $mail_text .= ": " . $text;
                     break;
                 case 'list':
                     $mail_text .= ": " . $text;
                     break;
                 case 'field':
                     $mail_text .= ": " . $text;
                     break;
                 case 'chbox':
                     $mail_text .= ": " . $text;
                     break;
                 case 'radio':
                     $mail_text .= ": " . utf8_substr($text, utf8_strpos($text, ' ') + 1, utf8_strlen($text) - utf8_strpos($text, ' '));
                     break;
                 case 'file':
                     break;
             }
         }
     }
     $mail_text .= "<br><br>";       
     if($flag){
     $name = strip_tags(trim(getRequest('client_name',3)));
     $mail = strip_tags(trim(getRequest('client_email',3)));
     if (($name!='') && ($mail!='')) { 
         $summ = getRequest('client_pay',2);
         $site = $_SERVER['HTTP_HOST'];
 //из текущей даты вычесть 00.00.00.01.01.2012
 //$date1=explode('.',date("H.i.s.m.d.y")); 
 //$date1=mktime($date1[0],$date1[1],$date1[2],$date1[3],$date1[4],$date1[5]);    
 //$date2=mktime(0,0,0,1,1,2012);
 //$date3=($date1-$date2);     
         $numer = se_file_get_contents($filenamecount);
         $currency = $section->parametrs->param2;
         $order_list = getOrderList();
         $adminmail = $section->parametrs->param1;
 //письмо клиенту 
         $client_mail =  $section->parametrs->param19."<br>".$mail_text;
         $client_mail = str_replace('\r\n','<br>',$client_mail);
         $client_mail = str_replace('[HR]','<hr>',$client_mail);
         $client_mail = str_replace("[NAMECLIENT]", "$name", $client_mail);
         $client_mail = str_replace("[SHOP_ORDER_NUM]", "$numer", $client_mail);
         $client_mail = str_replace("[THISNAMESITE]", "$site", $client_mail);
         $client_mail = str_replace("[SHOP_ORDER_VALUE_LIST]", "$order_list", $client_mail);
         $client_mail = str_replace("[SHOP_ORDER_SUMM]", "$summ", $client_mail);
         $client_mail = str_replace("[VALUTA]", "$currency", $client_mail);                
         $from = "=?utf-8?b?" . base64_encode($section->parametrs->param17)."?= ".$_SERVER['HTTP_HOST']." <$adminmail>";
         $clientmailsend = new plugin_mail($section->parametrs->param17, $mail, $from, $client_mail,'text/html', join(';', $filename));
         $clientmailsend->sendfile();
         unset($clientmailsend);
         
 //письмо админу 
         if(!empty($adminmail)){   
             $admin_mail =  $section->parametrs->param20."<br>".$mail_text;
             $admin_mail = str_replace('\r\n','<br>',$admin_mail);
             $admin_mail = str_replace('[HR]','<hr>',$admin_mail);
             $admin_mail = str_replace("[NAMECLIENT]", "$name", $admin_mail);
             $admin_mail = str_replace("[SHOP_ORDER_NUM]", "$numer", $admin_mail);
             $admin_mail = str_replace("[THISNAMESITE]", "$site", $admin_mail);
             $admin_mail = str_replace("[SHOP_ORDER_VALUE_LIST]", "$order_list", $admin_mail);
             $admin_mail = str_replace("[SHOP_ORDER_SUMM]", "$summ", $admin_mail);
             $admin_mail = str_replace("[VALUTA]", "$currency", $admin_mail);
             $from = "=?utf-8?b?" . base64_encode($section->parametrs->param17)."?= ".$_SERVER['HTTP_HOST']." <$mail>";
             $adminmailsend = new plugin_mail($section->parametrs->param17, $adminmail, $from, $admin_mail,'text/html', join(';', $filename));
 //            $mailsend->sendfile();
             if (!$adminmailsend->sendfile()) {
                 $error = $section->parametrs->param27;
             }
         }
         foreach ($filename as $v) {
             @unlink($v);
         }
 //увеличим счетчик на +1    
         $numer = $numer + 1;
         file_put_contents($filenamecount, $numer);
         unset($_SESSION['mshopcart']);
         header("Location: ".seMultiDir().'/'.$_page.'/orderfinish/');
         exit;
     } else {
         $error=$section->parametrs->param23;
     } 
     }      
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
 //BeginSubPage1
 $__module_subpage[1]['admin'] = "";
 $__module_subpage[1]['group'] = 0;
 $__module_subpage[1]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==1 && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_1.tpl";
	$__module_subpage[1]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 //BeginSubPage2
 $__module_subpage[2]['admin'] = "";
 $__module_subpage[2]['group'] = 0;
 $__module_subpage[2]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==2 && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_2.tpl";
	$__module_subpage[2]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 //BeginSubPage3
 $__module_subpage[3]['admin'] = "";
 $__module_subpage[3]['group'] = 0;
 $__module_subpage[3]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==3 && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_3.tpl";
	$__module_subpage[3]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}