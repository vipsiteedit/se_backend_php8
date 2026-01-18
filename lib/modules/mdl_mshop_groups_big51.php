<?php
function module_mshop_groups_big51($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_groups_big51';
 else $__MDL_URL = 'modules/mshop_groups_big51';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_groups_big51';
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
 $psg = plugin_shopgroups::getInstance();
 
 $shopcatgr = $psg->getGroupId(trim($section->parametrs->param27));
 
 $basegroup = (int)$psg->getId(trim($section->parametrs->param27));
 
 $shoppath = empty($section->parametrs->param19) ? $_page : (string)$section->parametrs->param19;

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPagegeneral
 $__module_subpage['general']['admin'] = "";
 $__module_subpage['general']['group'] = 0;
 $__module_subpage['general']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='general' && file_exists($__MDL_ROOT . "/tpl/subpage_general.tpl")){
	include $__MDL_ROOT . "/php/subpage_general.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_general");
	$__module_subpage['general']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegeneral
 //BeginSubPagegroups
 $__module_subpage['groups']['admin'] = "";
 $__module_subpage['groups']['group'] = 0;
 $__module_subpage['groups']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='groups' && file_exists($__MDL_ROOT . "/tpl/subpage_groups.tpl")){
	include $__MDL_ROOT . "/php/subpage_groups.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_groups");
	$__module_subpage['groups']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegroups
 //BeginSubPagesgroups
 $__module_subpage['sgroups']['admin'] = "";
 $__module_subpage['sgroups']['group'] = 0;
 $__module_subpage['sgroups']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='sgroups' && file_exists($__MDL_ROOT . "/tpl/subpage_sgroups.tpl")){
	include $__MDL_ROOT . "/php/subpage_sgroups.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_sgroups");
	$__module_subpage['sgroups']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesgroups
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}