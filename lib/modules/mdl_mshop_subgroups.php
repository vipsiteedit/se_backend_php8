<?php
function module_mshop_subgroups($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_subgroups';
 else $__MDL_URL = 'modules/mshop_subgroups';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_subgroups';
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
 //BeginSubPagepath
 $__module_subpage['path']['admin'] = "";
 $__module_subpage['path']['group'] = 0;
 $__module_subpage['path']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='path' && file_exists($__MDL_ROOT . "/tpl/subpage_path.tpl")){
	include $__MDL_ROOT . "/php/subpage_path.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_path");
	$__module_subpage['path']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagepath
 //BeginSubPagesegeneral
 $__module_subpage['segeneral']['admin'] = "";
 $__module_subpage['segeneral']['group'] = 0;
 $__module_subpage['segeneral']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='segeneral' && file_exists($__MDL_ROOT . "/tpl/subpage_segeneral.tpl")){
	include $__MDL_ROOT . "/php/subpage_segeneral.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_segeneral");
	$__module_subpage['segeneral']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesegeneral
 //Final PHP
 
 if (isRequest('sheet') && ($page_num = getRequest('sheet', 1)) > 1){
     //if (empty($__data->page->titlepage))
     //    $__data->page->titlepage = $__data->page->title;
     //$__data->page->titlepage .= $section->language->lang005  . ' ' . $page_num;
     //$__data->page->title .= $section->language->lang005 . $page_num;
     $__data->page->description .= $section->language->lang005 . ' ' . $page_num; 
 }

 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

