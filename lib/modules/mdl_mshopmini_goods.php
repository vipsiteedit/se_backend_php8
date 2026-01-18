<?php
function module_mshopmini_goods($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshopmini_goods';
 else $__MDL_URL = 'modules/mshopmini_goods';
 $__MDL_ROOT = dirname(__FILE__).'/mshopmini_goods';
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
 //if (!SE_DB_ENABLE) return;
 $incart = array();
 if (!empty($_SESSION['mshopcart'])){
     $incart = $_SESSION['mshopcart'];
 } 
 else if (!empty($_COOKIE['mshopcart'])){
         $incart = $_COOKIE['mshopcart'];
 }
 
 $test = $_SESSION['SHOP_MINI_PAGE']['page'];                  
 if(($_SESSION['SHOP_MINI_PAGE']['ref'] == 0) || (!$_SESSION['SHOP_MINI_PAGE']['ref'])){
     $_SESSION['SHOP_MINI_PAGE']['page'] = $_SERVER['HTTP_REFERER'];      
 } else {
     $_SESSION['SHOP_MINI_PAGE']['ref'] = 0;
     $_SESSION['SHOP_MINI_PAGE']['page'] = $_SESSION['SHOP_MINI_PAGE']['page1'];
 }
 
 if (isRequest('addcartspecial') && getRequest('partid')==$razdel) {
 
     if (empty($incart) || !is_array($incart)) 
     {
         $incart=array();
     }
     $code = getRequest('addcartspecial');
 
         foreach($section->objects as $price)
         {
             if (strval($price->id) == $code)
             {                                                             
                 if (!empty($price->text1)) $scode = '_'.$price->text1;
                 else $scode = '_'.$code;
                 if (!empty($incart[$scode]['count'])) {
                     $incart[$scode]['count'] += 1;    
                 } else {
                     $incart[$scode]['article'] = strval($price->text1);
                     $incart[$scode]['count'] = 1;
                     $price->field = str_replace(",", ".", $price->field);     //обработка цены
                     $price->field = str_replace(' ', '', $price->field);
                     $incart[$scode]['price'] = floatval($price->field * $section->parametrs->param10);
                     $incart[$scode]['name'] = strval($price->title);
                 }
                 break;
             }
         }
     $_SESSION['mshopcart'] = $incart;
     //print_r($_SESSION['mshopcart']);
     if($section->parametrs->param8=='Y'){
         header("Location:".seMultiDir()."/".$section->parametrs->param4."/"); 
         exit();
     } else {
         $_SESSION['SHOP_MINI_PAGE']['ref'] = 1;
         $_SESSION['SHOP_MINI_PAGE']['page1'] = $test;
         header("Location:".$_SERVER['HTTP_REFERER']); 
         exit();    
     }
 }    
 

   $section->objectcount = intval($section->parametrs->param5);
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

