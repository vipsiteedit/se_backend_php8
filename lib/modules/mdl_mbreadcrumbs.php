<?php
function module_mbreadcrumbs($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mbreadcrumbs';
 else $__MDL_URL = 'modules/mbreadcrumbs';
 $__MDL_ROOT = dirname(__FILE__).'/mbreadcrumbs';
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
 $buterpath =  $__data->getPathLinks($section->parametrs->param1, $section->title);                                            

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //Final PHP
   if (isRequest('jquery'.$razdel)){
    if (getRequest('name')=='showcontent'){
         unset($_SESSION['MESSAGE']);
         $_SESSION['MESSAGE']['menu'] = getRequest('value');
         if ($company_id){ 
             if (getRequest('value')=='write'){
                 include $__MDL_ROOT . "/php/subpage_1.php";
                 include $__MDL_ROOT . "/tpl/subpage_1.tpl";
                 exit;
             }
             if (getRequest('value')=='view'){
                 include $__MDL_ROOT . "/php/subpage_2.php";
                 include $__MDL_ROOT . "/tpl/subpage_2.tpl";
                 exit;
             }
         }
    
    }
 }

 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}