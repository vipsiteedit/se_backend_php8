<?php
function module_madmin_forum($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/madmin_forum';
 else $__MDL_URL = 'modules/madmin_forum';
 $__MDL_ROOT = dirname(__FILE__).'/madmin_forum';
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
 $lang = substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
 if ($lang=="")  
     $lang = se_getLang();     // если язык незивестен значит он русский

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
 //BeginSubPage2
 $__module_subpage['2']['admin'] = "";
 $__module_subpage['2']['group'] = 0;
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
 $__module_subpage['5']['group'] = 0;
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
 //BeginSubPage8
 $__module_subpage['8']['admin'] = "";
 $__module_subpage['8']['group'] = 0;
 $__module_subpage['8']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='8' && file_exists($__MDL_ROOT . "/tpl/subpage_8.tpl")){
	include $__MDL_ROOT . "/php/subpage_8.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_8");
	$__module_subpage['8']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage8
 //BeginSubPage9
 $__module_subpage['9']['admin'] = "";
 $__module_subpage['9']['group'] = 0;
 $__module_subpage['9']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='9' && file_exists($__MDL_ROOT . "/tpl/subpage_9.tpl")){
	include $__MDL_ROOT . "/php/subpage_9.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_9");
	$__module_subpage['9']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage9
 //BeginSubPage10
 $__module_subpage['10']['admin'] = "";
 $__module_subpage['10']['group'] = 0;
 $__module_subpage['10']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='10' && file_exists($__MDL_ROOT . "/tpl/subpage_10.tpl")){
	include $__MDL_ROOT . "/php/subpage_10.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_10");
	$__module_subpage['10']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage10
 //BeginSubPage11
 $__module_subpage['11']['admin'] = "";
 $__module_subpage['11']['group'] = 0;
 $__module_subpage['11']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='11' && file_exists($__MDL_ROOT . "/tpl/subpage_11.tpl")){
	include $__MDL_ROOT . "/php/subpage_11.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_11");
	$__module_subpage['11']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage11
 //BeginSubPage12
 $__module_subpage['12']['admin'] = "";
 $__module_subpage['12']['group'] = 0;
 $__module_subpage['12']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='12' && file_exists($__MDL_ROOT . "/tpl/subpage_12.tpl")){
	include $__MDL_ROOT . "/php/subpage_12.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_12");
	$__module_subpage['12']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage12
 //BeginSubPage13
 $__module_subpage['13']['admin'] = "";
 $__module_subpage['13']['group'] = 0;
 $__module_subpage['13']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='13' && file_exists($__MDL_ROOT . "/tpl/subpage_13.tpl")){
	include $__MDL_ROOT . "/php/subpage_13.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_13");
	$__module_subpage['13']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage13
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}