<?php
function module_dme($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/dme';
 else $__MDL_URL = 'modules/dme';
 $__MDL_ROOT = dirname(__FILE__).'/dme';
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
 $specdesign = $specstyle = $specsize = false;
 $time = time() + 86400*12;
 if (isset($_GET['__delspecdesign__'])) {
     setcookie("__specdesign__", "disable", $time);
     $_SESSION['__specdesign__'] = "disable";
     $__data->go301(seMultiDir().'/'.$__data->getPageName().SE_END);
 }
 if (isset($_GET['__specdesignstyle__'])) {
     setcookie("__specdesignstyle__", $_GET['__specdesignstyle__'], $time);
     $_SESSION['__specdesignstyle__'] = $_GET['__specdesignstyle__'];
     $__data->go301(seMultiDir().'/'.$__data->getPageName().SE_END);
 }
 if (isset($_GET['__specdesignsize__'])) {
     setcookie("__specdesignsize__", $_GET['__specdesignsize__'], $time);
     $__data->go301(seMultiDir().'/'.$__data->getPageName().SE_END);
 }
 if (isset($_GET['__specdesignimage__'])) {
     setcookie("__specdesignimage__", $_GET['__specdesignimage__'], $time);
     $_SESSION['__specdesignimage__'] = $_GET['__specdesignimage__'];
     $__data->go301(seMultiDir().'/'.$__data->getPageName().SE_END);
 }
 
 if (isset($_GET['__specdesign__'])) {
     setcookie("__specdesign__", 'enable', $time);
     $_SESSION['__specdesign__'] = 'enable';
     $__data->go301(seMultiDir().'/'.$__data->getPageName().SE_END);
 } else {
     if (isset($_COOKIE["__specdesign__"]) || isset( $_SESSION['__specdesign__'])) {
         $specdesign = (!empty($_SESSION['__specdesign__'])) ? $_SESSION['__specdesign__'] : strval($_COOKIE["__specdesign__"]);
         if (!empty($_SESSION['__specdesignstyle__'])) {
             $specstyle =  $_SESSION['__specdesignstyle__']; 
         } else {
             $specstyle = (isset($_COOKIE["__specdesignstyle__"])) ? strval($_COOKIE["__specdesignstyle__"]) : '1';
         }
         if (!empty($_SESSION['__specdesignsize__'])) {
             $specsize =  $_SESSION['__specdesignsize__']; 
         } else {
             $specsize = (isset($_COOKIE["__specdesignsize__"])) ? strval($_COOKIE["__specdesignsize__"]) : '1';
         }
         if (!empty($_SESSION['__specdesignimage__'])) {
             $specimage =  $_SESSION['__specdesignimage__']; 
         } else {
             $specimage = (isset($_COOKIE["__specdesignimage__"])) ? strval($_COOKIE["__specdesignimage__"]) : 'off';
         }    
     }
 }
 if ($specdesign == 'enable') {
    if (file_exists(SE_ROOT . SE_DIR .'skin/'.delExtendFile($section->parametrs->param1).'.map')) {
        $__data->page->css = delExtendFile($section->parametrs->param1);
    }
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