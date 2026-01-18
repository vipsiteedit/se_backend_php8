<?php
function module_header_start($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/header_start';
 else $__MDL_URL = 'modules/header_start';
 $__MDL_ROOT = dirname(__FILE__).'/header_start';
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
  //капча
 $captcha='<img class="ml001_secimg" alt="capcha" style="width:150px; height:30px" src="/lib/cardimage.php?session='.$sid.'&'.time().'">';
 
 $emailAdmin = '';
 $entertext = $section->parametrs->param10;
 $closetext = $section->parametrs->param11;
 if (!empty($section->parametrs->param12))
     $col = intval($section->parametrs->param13);
 else
     $col = 2500;
 
 $ml001_errtxt = $ml001_name = $ml001_phone = $ml001_email = $ml001_note = '';
 
 $uploadfld = getcwd() . '/modules/upload';
 if (!file_exists(getcwd() . '/modules')) {
     mkdir(getcwd() . '/modules');
 }
 
 if (!file_exists($uploadfld)) {
     mkdir($uploadfld);
 }  
 
 $admin = trim(strval($section->parametrs->param15));
 $admins = array();
 if($admin != '') {
     $admins = explode(",", $admin);
     foreach($admins as $key=>$line) {
         $line = trim($line);
         if(!se_CheckMail($line) || ($line == '')) 
             unset($admins[$key]);
     }
 }
 
 if(!empty($admins)) {
     $emailAdmin = implode(",", $admins);
 }

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPageform
 $__module_subpage['form']['admin'] = "";
 $__module_subpage['form']['group'] = 0;
 $__module_subpage['form']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='form' && file_exists($__MDL_ROOT . "/tpl/subpage_form.tpl")){
	include $__MDL_ROOT . "/php/subpage_form.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_form");
	$__module_subpage['form']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageform
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
 //BeginSubPagemessage
 $__module_subpage['message']['admin'] = "";
 $__module_subpage['message']['group'] = 0;
 $__module_subpage['message']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='message' && file_exists($__MDL_ROOT . "/tpl/subpage_message.tpl")){
	include $__MDL_ROOT . "/php/subpage_message.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_message");
	$__module_subpage['message']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemessage
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}