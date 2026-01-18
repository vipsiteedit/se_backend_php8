<?php
function module_menucategories($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/menucategories';
 else $__MDL_URL = 'modules/menucategories';
 $__MDL_ROOT = dirname(__FILE__).'/menucategories';
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
 //error_reporting(E_ALL);
 
   $lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
   if ($lang=="") {
     $lang="rus";
   }
  // конец проверки на модера 
 $flag = 0; 
 $moderi = $section->parametrs->param11;
 $moders = explode(",", $moderi);
 for ($j = 0; $j < count($moders); $j++) {
     $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
     if (($userlogin == seUserLogin())&& (seUserLogin() != "")) {
         $flag = 1;
     }
 }
 
 if  (!$flag && seusergroup()>1) {
    $flag = 1;
 }
 
 //$lang = se_getlang();
 //$mlang = utf8_substr($lang, 0, 2);                       
 
     if (isRequest('AddTo') && getRequest('part_id')==$razdel){                  
         header ("Location: ".seMultiDir()."/$_page/{$section->id}/sub1/");     
     }
     
     if (isRequest('End') && getRequest('part_id', 1)==$razdel){
         unset($_SESSION[$section->parametrs->param15.'EditCtg']);
         header ("Location: ?".time());
     }
     
     if (isRequest('EditCtg')){
         $_SESSION[$section->parametrs->param15.'EditCtg'] = true;
     }
     $editor = (isset($_SESSION[$section->parametrs->param15.'EditCtg']) && $_SESSION[$section->parametrs->param15.'EditCtg']);        
 
 if($flag == 0){
     $_SESSION[$section->parametrs->param15.'EditCtg'] = false;
 }
     
 $sql="
 CREATE TABLE IF NOT EXISTS `".$section->parametrs->param24."categories` (
   `id` int(10) unsigned NOT NULL auto_increment,
    `lang` varchar(15) NOT NULL default 'rus',
   `upid` int(10) unsigned default NULL,
   `url` char(255) default NULL,
   `name` char(255) default NULL,
   `keywords` char(255) default NULL,
   `description` char(255) default NULL,
   `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
   `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
   PRIMARY KEY  (`id`),
   KEY `lang` (`lang`),
   KEY `upid` (`upid`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
  " ;
  se_db_query($sql); 
 
 $sql=" 
 ALTER TABLE `".$section->parametrs->param24."categories`
   ADD CONSTRAINT `".$section->parametrs->param24."categories_ibfk_1` FOREIGN KEY (`upid`) REFERENCES `".$section->parametrs->param24."categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
 
   
 
   " ;
  se_db_query($sql); 
    
   

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