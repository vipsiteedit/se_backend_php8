<?php
function module_yandexmaps($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $_page = $__data->req->page;
 $_razdel = $__data->req->razdel;
 $_sub = $__data->req->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/yandexmaps';
 else $__MDL_URL = 'modules/yandexmaps';
 $__MDL_ROOT = dirname(__FILE__).'/yandexmaps';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	append_simplexml($section->language,simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml'));
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   if (!empty($section->traslates->$__name))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 // Подгружаем графическую библиотеку
 require_once("lib/lib_images.php");
 
     // определяем свой стиль или стандартный
     if ($section->parametrs->param44=='s') {$p44='s';} else {$p44='"'.$section->parametrs->param44.'"';}
     if ($section->parametrs->param43=='s') {$p43='s';} else {$p43='"'.$section->parametrs->param43.'"';}
     if ($section->parametrs->param42=='s') {$p42='s';} else {$p42='"'.$section->parametrs->param42.'"';}
 
 
  // Проверка не модер ли нас посетил который прописан в параметрах
 $flagADM = 0; 
 $moderi = $section->parametrs->param28;
 
 $moders = explode(",", $moderi);
 
 for ($j = 0; $j <= count($moders); $j++) 
   {
   $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
   if (($userlogin==seUserLogin()) && (seUserLogin()!="")) {
   $flagADM=1;
   
     }
     
   }
 // конец проверки на модера 
 
  if (seUserGroup() >= "3") 
 {
 $flagADM=1;
 }
 // проверили ни админ ли нас посетил
 
 
 
 se_db_connect();
 
  $sql = " CREATE TABLE IF NOT EXISTS `yandexmaps` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `x` char(9) default NULL,
   `y` char(9) default NULL,
   `text` text NOT NULL,
   `image1` varchar(20) NOT NULL,
   `image2` varchar(20) NOT NULL,
   `lang` char(3) NOT NULL,
   `user_id` int(10) unsigned default NULL,
   `updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
   `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
   PRIMARY KEY  (`id`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;" ;
   se_db_query($sql);
  

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
 $__module_subpage[1]['admin'] = "";
 $__module_subpage[1]['group'] = 0;
 $__module_subpage[1]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==1 && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_1.tpl";
	$__module_subpage[1]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 //BeginSubPage2
 $__module_subpage[2]['admin'] = "";
 $__module_subpage[2]['group'] = 0;
 $__module_subpage[2]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==2 && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_2.tpl";
	$__module_subpage[2]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 //BeginSubPage3
 $__module_subpage[3]['admin'] = "";
 $__module_subpage[3]['group'] = 0;
 $__module_subpage[3]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==3 && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_3.tpl";
	$__module_subpage[3]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
 //BeginSubPage4
 $__module_subpage[4]['admin'] = "";
 $__module_subpage[4]['group'] = 0;
 $__module_subpage[4]['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub==4 && file_exists($__MDL_ROOT . "/tpl/subpage_4.tpl")){
	include $__MDL_ROOT . "/php/subpage_4.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_4.tpl";
	$__module_subpage[4]['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage4
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}