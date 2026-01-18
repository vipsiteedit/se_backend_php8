<?php
function module_mmail_001($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mmail_001';
 else $__MDL_URL = 'modules/mmail_001';
 $__MDL_ROOT = dirname(__FILE__).'/mmail_001';
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
 $entertext = $section->parametrs->param7;
 $closetext = $section->parametrs->param8;
 if (!empty($section->parametrs->param13))
     $col = intval($section->parametrs->param13);
 else
     $col = 2500;
 
 $ml001_errtxt = $ml001_name = $ml001_email = $ml001_note = '';
 
 
 if ((!empty($section->parametrs->param1)) and ($section->parametrs->param1 != '[%adminmail%]'))
     $emailAdmin = $section->parametrs->param1;
 /*
 if ($_sub)
 {
     $referer=explode("/",urldecode($_SERVER['HTTP_REFERER']));
     $refer_page=explode('&',$referer[3]);
     if (preg_match("/^home.* /", PAGE))
         $refer_page[0] = 'home';
     if (!preg_match("/$_page/",$refer_page[0]) or ($referer[2] != $_SERVER['HTTP_HOST']))
     {
         Header('Location: /'.$_page);
         exit();
     }
 }
 //*/

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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}