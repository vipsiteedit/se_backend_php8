<?php
function module_mshop_quicksearch($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_quicksearch';
 else $__MDL_URL = 'modules/mshop_quicksearch';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_quicksearch';
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
 $md = seMultiDir();
 
 $srch_page = (trim($section->parametrs->param17) != '') ? trim($section->parametrs->param17) : $_page;
 if($srch_page == $__data->prj->vars->startpage)
     $srch_page = '';
 else
     $srch_page .= '/';
 $srch_page = seMultiDir() . '/' . $srch_page;
 $srch = plugin_shopsearch40::getInstance();
  
 if (get('quicksearchGo')) {
     $srch_type = $srch->get('text','type');
     $srch_text = $srch->get('text','string');
 } 
 
 if(!$srch_type)
 switch($section->parametrs->param16) { // значение search_for по умолчанию
     case 'art': 
         $srch_type = 'article';
         break;
     case 'lot':
         $srch_type = 'name';
         break;
     case 'descr':
         $srch_type = 'text+note';
         break;
     case 'com':
         $srch_type = '';
         break;
     default:
         $srch_type = '';
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}