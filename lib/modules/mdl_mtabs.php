<?php
function module_mtabs($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mtabs';
 else $__MDL_URL = 'modules/mtabs';
 $__MDL_ROOT = dirname(__FILE__).'/mtabs';
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
 // Получаем номер раздела к которому был обращен запрос
 
 
 // Получаем номер записи которую хочет получить пользователь
 // и попутно определяем запрос был отправлен скриптом или нет
 if (isRequest('obj') && $razdel == $_razdel) {
 // Если запрос отправлен не скриптом
     $obj = getRequest('obj',1);
 } else {
 // Если запрос отправлен скриптом
     if (isRequest('load')) {
     $obj = getRequest('load',1);
     }
 }
 
 // Если запроса вообще не происходило и переменная не найдена назначаем ей значение по умолчанию
 if(!$obj) {
     $obj = 0;
 }
 
 
 
 if(isRequest('load') && $razdel == $_razdel){
     $razd = md5($razdel.$_page);
     $_SESSION['last_select_object']['r'.$razd] = $obj;
     
     
 } else {
     // Модуль определяет запрос пришел для него или нет
     if ($razdel == $_razdel) {
         $razd = md5($razdel.$_page);
     // Если запрос пишел для этого модуля, то записываем номер записи которую выбрал пользователь
         if ($obj) $_SESSION['last_select_object']['r'.$razd] = $obj;
     } else {
     // Если запрос пришел не для этого молуля, то извлекаем номер записи которую пользователь выбрал в предыдущий раз
         $razd = md5($razdel.$_page);
         $obj = $_SESSION['last_select_object']['r'.$razd];
 }
 }
 
 //echo $razd;
 
 
  
 
 
 if (isRequest('load') && $_razdel == $razdel) {
     foreach($section->objects as $record)
         if (($record->id) == $obj){
             $record->note = tabs_parser_link($record->note);
             $record->text = tabs_parser_link($record->text);
             //$record->note = replace_link($record->note);
             $record->image_prev = "/".SE_DIR .$record->image_prev;    
             break;
         }
  include $__MDL_ROOT . "/tpl/subpage_virtualshow.tpl";      
     exit();
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
 // include show.tpl
 ob_start();
 if ($razdel == $_razdel && $__data->req->object){
 $record = $__data->getObject($section, $__data->req->object);
 include $__data->include_tpl($section, "show");
 }
 $__module_content['show'] =  ob_get_contents();
 ob_end_clean();
 //BeginSubPagelinkbox
 $__module_subpage['linkbox']['admin'] = "";
 $__module_subpage['linkbox']['group'] = 0;
 $__module_subpage['linkbox']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='linkbox' && file_exists($__MDL_ROOT . "/tpl/subpage_linkbox.tpl")){
	include $__MDL_ROOT . "/php/subpage_linkbox.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_linkbox");
	$__module_subpage['linkbox']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagelinkbox
 //BeginSubPagevirtualshow
 $__module_subpage['virtualshow']['admin'] = "";
 $__module_subpage['virtualshow']['group'] = 0;
 $__module_subpage['virtualshow']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='virtualshow' && file_exists($__MDL_ROOT . "/tpl/subpage_virtualshow.tpl")){
	include $__MDL_ROOT . "/php/subpage_virtualshow.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_virtualshow");
	$__module_subpage['virtualshow']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagevirtualshow
 //BeginSubPagevkladki
 $__module_subpage['vkladki']['admin'] = "";
 $__module_subpage['vkladki']['group'] = 0;
 $__module_subpage['vkladki']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='vkladki' && file_exists($__MDL_ROOT . "/tpl/subpage_vkladki.tpl")){
	include $__MDL_ROOT . "/php/subpage_vkladki.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_vkladki");
	$__module_subpage['vkladki']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagevkladki
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
 //BeginSubPagetextbox
 $__module_subpage['textbox']['admin'] = "";
 $__module_subpage['textbox']['group'] = 0;
 $__module_subpage['textbox']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='textbox' && file_exists($__MDL_ROOT . "/tpl/subpage_textbox.tpl")){
	include $__MDL_ROOT . "/php/subpage_textbox.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_textbox");
	$__module_subpage['textbox']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagetextbox
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}