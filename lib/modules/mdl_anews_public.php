<?php
function module_anews_public($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/anews_public';
 else $__MDL_URL = 'modules/anews_public';
 $__MDL_ROOT = dirname(__FILE__).'/anews_public';
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
 $__data->setVirtualPage($_page, 'show'.$section->parametrs->param20);
 //определим модератора
  $time = time();
 
  // Проверка не модер ли нас посетил который прописан в параметрах
  
 if (getRequest('page')=='show'.$section->parametrs->param20){    
    $id = $__data->req->param[2];
    $__data->goSubName($section, 'show');
 } else {                                 
     if (isRequest('show_to')) {   
         $__data->goSubName($section, 'show');
         $id = getRequest('show_to', 1);
         $__data->req->razdel =$razdel;
     } else {                     
         $id = getRequest('id', 1); 
 
     } 
     if(!$editobject){
         if ($id && $__data->req->razdel == $razdel) {
             header("HTTP/1.1 301 Moved Permanently");
             header("Location: ".seMultiDir().'/shownews/'.$id.'/');
         }
     }
 }
 
 $lang = se_getlang();
 $opt = array('size_image'=>$section->parametrs->param5, 'size_fullimage'=>$section->parametrs->param4, 'lang'=>$lang);
 $limit = intval($section->parametrs->param3);
 $clnews = plugin_news::getInstance($opt);
 $nchar = intval($section->parametrs->param17);

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPageshow
 $__module_subpage['show']['admin'] = "";
 $__module_subpage['show']['group'] = 0;
 $__module_subpage['show']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='show' && file_exists($__MDL_ROOT . "/tpl/subpage_show.tpl")){
	include $__MDL_ROOT . "/php/subpage_show.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_show");
	$__module_subpage['show']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageshow
 //BeginSubPageedit
 $__module_subpage['edit']['admin'] = "";
 $__module_subpage['edit']['group'] = 0;
 $__module_subpage['edit']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='edit' && file_exists($__MDL_ROOT . "/tpl/subpage_edit.tpl")){
	include $__MDL_ROOT . "/php/subpage_edit.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_edit");
	$__module_subpage['edit']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageedit
 //BeginSubPagescripts
 $__module_subpage['scripts']['admin'] = "";
 $__module_subpage['scripts']['group'] = 0;
 $__module_subpage['scripts']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='scripts' && file_exists($__MDL_ROOT . "/tpl/subpage_scripts.tpl")){
	include $__MDL_ROOT . "/php/subpage_scripts.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_scripts");
	$__module_subpage['scripts']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagescripts
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}