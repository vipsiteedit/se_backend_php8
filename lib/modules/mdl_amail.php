<?php
function module_amail($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/amail';
 else $__MDL_URL = 'modules/amail';
 $__MDL_ROOT = dirname(__FILE__).'/amail';
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
 $admin = trim(strval($section->parametrs->param1));
 $admins = array();
 if($admin != '') {
     $admins = explode(",", $admin);
     foreach($admins as $key=>$line) {
         $line = trim($line);
         if(!se_CheckMail($line) || ($line == '')) 
             unset($admins[$key]);
     }
 } else {
     $globalerr = $section->parametrs->param13;
     $glob_err_stryle = "Disabled";  
 }
 
 if (empty($admins)) {
     $globalerr = $section->parametrs->param13;
     $glob_err_stryle = "Disabled";  
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
 //BeginSubPagesend
 $__module_subpage['send']['admin'] = "";
 $__module_subpage['send']['group'] = 0;
 $__module_subpage['send']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='send' && file_exists($__MDL_ROOT . "/tpl/subpage_send.tpl")){
	include $__MDL_ROOT . "/php/subpage_send.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_send");
	$__module_subpage['send']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesend
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

