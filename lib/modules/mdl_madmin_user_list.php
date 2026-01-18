<?php
function module_madmin_user_list($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/madmin_user_list';
 else $__MDL_URL = 'modules/madmin_user_list';
 $__MDL_ROOT = dirname(__FILE__).'/madmin_user_list';
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
 if (seUserGroup() < 3) {
     $__data->goSubName($section, 7);
 }
 $person = new sePerson();
 $user = new seUser();
 $_user = getRequest('user', VAR_INT);
 $group = simplexml_load_file(SE_ROOT . '/projects/' . SE_DIR . 'project.xml');
 $grouplist = $group->groupusers;
 $grouplist = explode('|', $grouplist);
 if (!isset($_SESSION['ADMINUSRCH'])) {
     $_SESSION['ADMINUSRCH'] = array(
                                     'orderby' => '',
                                     'sortway' => 0,
                                     'where' => '',
                                     'searchtype' => '',
                                     'search' => '',
                                     'ugrp' => array(),
                                     'otherGroup' => 0,
                                     'searchmy' => '',
                                     'errorRes' => ''
                                 );
 }
 foreach ($_SESSION['ADMINUSRCH'] as $k => $v) {
     if (!in_array($k, array('errorRes'))) {
         $$k = &$_SESSION['ADMINUSRCH'][$k];
     }    
 }      
 $errorRes = '';
 if ($_SESSION['ADMINUSRCH']['errorRes'] != '') {
     $errorRes = $_SESSION['ADMINUSRCH']['errorRes'];
     $_SESSION['ADMINUSRCH']['errorRes'] = '';
 }
 $monthLstnm = array($section->language->lang017, $section->language->lang018, $section->language->lang019, $section->language->lang020,
                     $section->language->lang021, $section->language->lang022, $section->language->lang023, $section->language->lang024, 
                     $section->language->lang025, $section->language->lang026, $section->language->lang027, $section->language->lang028);
 $printPages = array();
 $pagesStep = 0;

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPage1
 $__module_subpage['1']['admin'] = "";
 $__module_subpage['1']['group'] = "3";
 $__module_subpage['1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='1' && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_1");
	$__module_subpage['1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 //BeginSubPage2
 $__module_subpage['2']['admin'] = "";
 $__module_subpage['2']['group'] = "3";
 $__module_subpage['2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='2' && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_2");
	$__module_subpage['2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 //BeginSubPage3
 $__module_subpage['3']['admin'] = "";
 $__module_subpage['3']['group'] = 0;
 $__module_subpage['3']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='3' && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_3");
	$__module_subpage['3']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
 //BeginSubPage4
 $__module_subpage['4']['admin'] = "";
 $__module_subpage['4']['group'] = 0;
 $__module_subpage['4']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='4' && file_exists($__MDL_ROOT . "/tpl/subpage_4.tpl")){
	include $__MDL_ROOT . "/php/subpage_4.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_4");
	$__module_subpage['4']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage4
 //BeginSubPage5
 $__module_subpage['5']['admin'] = "";
 $__module_subpage['5']['group'] = "3";
 $__module_subpage['5']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='5' && file_exists($__MDL_ROOT . "/tpl/subpage_5.tpl")){
	include $__MDL_ROOT . "/php/subpage_5.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_5");
	$__module_subpage['5']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage5
 //BeginSubPage6
 $__module_subpage['6']['admin'] = "";
 $__module_subpage['6']['group'] = 0;
 $__module_subpage['6']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='6' && file_exists($__MDL_ROOT . "/tpl/subpage_6.tpl")){
	include $__MDL_ROOT . "/php/subpage_6.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_6");
	$__module_subpage['6']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage6
 //BeginSubPage7
 $__module_subpage['7']['admin'] = "";
 $__module_subpage['7']['group'] = 0;
 $__module_subpage['7']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='7' && file_exists($__MDL_ROOT . "/tpl/subpage_7.tpl")){
	include $__MDL_ROOT . "/php/subpage_7.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_7");
	$__module_subpage['7']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage7
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}