<?php
function module_monlinenews($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/monlinenews';
 else $__MDL_URL = 'modules/monlinenews';
 $__MDL_ROOT = dirname(__FILE__).'/monlinenews';
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
 $flag = 0; 
 $moders = explode(",", $section->parametrs->param1);
 
 for ($j = 0; $j <= count($moders); $j++) {
     $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
     if (($userlogin == seUserLogin()) && (seUserLogin() != "")) {
         $flag=1;
     }
 }
 if (!$flag){
       $flag = seUserRole($section->parametrs->param36);
 }
 
 
 // конец проверки на модера 
 $moder =  (seUserGroup()  == 3) || isModerator($modername) || ($flag == 1);    
 if ($moder) {
     $editobject = 'Y';
 } else { 
     $editobject = 'N';
 }
 
 $newskod = trim($section->parametrs->param20); 
  
  if (!$newskod) {
     $section->parametrs->param20 = 'news'; 
     $__data->setVirtualPage($_page, 'show' . $section->parametrs->param20);
  }
 
  
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
 $news = new seTable('news', 'n');
 $modername = $section->parametrs->param1;
 $pagen = $section->parametrs->param3;
 $width = intval($section->parametrs->param4);
 $thumbwdth = intval($section->parametrs->param5);
 $newskod = $section->parametrs->param20;
 $nchar = intval($section->parametrs->param17); 
 $nn = 0;
 $moder = 0;
 // Подгружаем библиотеку
 require_once("lib/lib_images.php");
 // Определяем язык сайта    
 $lang = se_getlang();
 $mlang = utf8_substr($lang, 0, 2);
 // массив используемых строк  
 $IMAGE_DIR = "/images/" . $lang . "/newsimg/";
 if (!is_dir(getcwd() . "/images")) {
     mkdir(getcwd() . "/images");
 }
 if (!is_dir(getcwd() . "/images/" . $lang)) {
     mkdir(getcwd() . "/images/" . $lang);
 }
 if (!is_dir(getcwd() . $IMAGE_DIR)) {
     mkdir(getcwd() . $IMAGE_DIR);
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

