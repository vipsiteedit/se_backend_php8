<?php
function module_guest($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/guest';
 else $__MDL_URL = 'modules/guest';
 $__MDL_ROOT = dirname(__FILE__).'/guest';
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
 // echo 898;
 $userAccess = seUserGroup();
 $dateerror = "";
 getRequestList($__request, 'usrmail,usrname,note', VAR_NOTAGS); 
 
 $month_name = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
 $adminmail = $section->parametrs->param1;
 //coздаем тут всё
 if (!file_exists("data/"))
 {
     mkdir("data");
 }
 
 $filename = "data/m".$_page."_".$razdel.".dat";
 $filenameip = "data/m".$_page."_".$razdel.".ip.dat";
 $sessfile = "data/m".$_page."_".$razdel.".sess.dat";
 $oldfilename = "data/{$_page}_{$razdel}.dat";
 
 //перекодируем старую гостевую в новую
 if ((!file_exists(getcwd().'/'.$filename) || !se_filesize(getcwd().'/'.$filename)) 
     && file_exists(getcwd().'/'.$oldfilename)) {
    to_utf8($oldfilename, $filename);
 }
 
 

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
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
	include $__MDL_ROOT . "/tpl/subpage_1.tpl";
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
	include $__MDL_ROOT . "/tpl/subpage_2.tpl";
	$__module_subpage['2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}