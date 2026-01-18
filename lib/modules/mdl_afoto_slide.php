<?php
function module_afoto_slide($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/afoto_slide';
 else $__MDL_URL = 'modules/afoto_slide';
 $__MDL_ROOT = dirname(__FILE__).'/afoto_slide';
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
   $section->objectcount = intval($section->parametrs->param1);
 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 // include show.tpl
 ob_start();
 if ($razdel == $_razdel && $__data->req->object){
 $record = $__data->getObject($section, $__data->req->object);
 include $__data->include_tpl($section, "show");
 }
 $__module_content['show'] =  ob_get_contents();
 ob_end_clean();
 //BeginSubPagehead
 $__module_subpage['head']['admin'] = "";
 $__module_subpage['head']['group'] = 0;
 $__module_subpage['head']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='head' && file_exists($__MDL_ROOT . "/tpl/subpage_head.tpl")){
	include $__MDL_ROOT . "/php/subpage_head.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_head");
	$__module_subpage['head']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagehead
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

