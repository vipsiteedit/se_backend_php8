<?php
function module_manketa($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/manketa';
 else $__MDL_URL = 'modules/manketa';
 $__MDL_ROOT = dirname(__FILE__).'/manketa';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	$__langlist = simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml');
	append_simplexml($section->language, $__langlist);
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   $__name = strval($__name);
	   $__value = strval($section->traslates->$__name);
	   if (!empty($__value))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 $sid = session_id();
 $PAGE = seMultiDir() . '/' . $_page;
 if (!defined('RAZDEL')) {
     define('RAZDEL', $razdel);
 }
 $ank_err_text = $mail_text = $name = $address = $phone =  $email = '';
 $emailAdmin = '';
 $entertext = $section->parametrs->param4;
 $closetext = $section->parametrs->param5;
 //капча
 $captcha = '<img class="ank_img" style="width:150px; height:30px" alt="captcha" src="/lib/cardimage.php?session=' . $sid . '&' . time() . '">';
 //ловим данные с формы
 $uploadfld = getcwd() . '/modules/upload';
 if (!file_exists(getcwd() . '/modules')) {
     mkdir(getcwd() . '/modules');
 }  
 
 if (!file_exists($uploadfld)) {
     mkdir($uploadfld);
 }  
 if (isRequest('name')) { 
     $name = getRequest('name', 3);
 }
 if (isRequest('address')) { 
     $address = getRequest('address', 3);
 }
 if (isRequest('phone')) { 
     $phone = getRequest('phone', 3);
 }
 if (isRequest('email')) { 
     $email = getRequest('email', 3);
 }                                         
 
 if ((!empty($section->parametrs->param1)) && ($section->parametrs->param1 != '[%adminmail%]')) {
     $emailAdmin = $section->parametrs->param1;
 }
 //print_r($section->objects);
 //echo '<br>';
 $i = 0;
 if (!empty($section->objects)) {
     foreach ($section->objects as $id=>$obit) {
         if (trim($obit->image_alt) == ''){
             $obit->image_alt = htmlspecialchars(strip_tags($obit->title));
         }
         $i = $obit->id;
         if (isRequest('formobj' . $i)) {   
             list($field,) = explode('|', $obit->text1);
             if ($field == 'chbox') {       
                 $obit->text2 = 'checked';
             } else {  
                 if (strpos($obit->note , "\n") !== false) {   
                    $obit->note = str_replace(getRequest('formobj' . $i, 3), '*' . getRequest('formobj' . $i, 3), str_replace('*', '', $obit->note));
                 } else {
                     $obit->note = str_replace('|' , '&#124;' , getRequest('formobj' . $i, 3));
                 }           
             }
         }
     }    
 }

   $section->objectcount = intval(1000);
 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPagelicense
 $__module_subpage['license']['admin'] = "";
 $__module_subpage['license']['group'] = 0;
 $__module_subpage['license']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='license' && file_exists($__MDL_ROOT . "/tpl/subpage_license.tpl")){
	include $__MDL_ROOT . "/php/subpage_license.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_license");
	$__module_subpage['license']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagelicense
 //BeginSubPage1
 $__module_subpage['1']['admin'] = "";
 $__module_subpage['1']['group'] = 0;
 $__module_subpage['1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='1' && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_1");
	$__module_subpage['1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

