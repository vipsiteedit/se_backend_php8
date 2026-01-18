<?php
function module_filecatalog($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/filecatalog';
 else $__MDL_URL = 'modules/filecatalog';
 $__MDL_ROOT = dirname(__FILE__).'/filecatalog';
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
 global $num;
 $mas = array();
 $req = getRequestList($mas, '', VAR_STRING);
 $req = getRequestList($req, 'page', VAR_WORD);
 $_object = getRequest('object', 1);
 $group_num = intval($section->parametrs->param1);
 $ACCESS_ON = (seUserGroup() >= $group_num);
 if (!isset($_desc)) {
     $_desc = $_page;
 }
 $_desc = se_db_input($_desc);
 $nObj = $section->parametrs->param0;
 $nChars = $section->parametrs->param2;
 $pagen = $section->parametrs->param0;
 $edkey = $section->parametrs->param6;
 $edtitle = $section->parametrs->param7;
 $themelected = utf8_substr(getRequest('themeselected', VAR_STRING), 0, 60);
 //  обработка случая когда пользователь хочет увидеть все обьявления и выбирает значение по умолчанию в списке городов
 // Подгружаем графическую библиотеку
 require_once("lib/lib_images.php");
 se_db_connect();
 // Создаем таблицу если таковой нет
 //$lang = se_getlang();
 //$mlang = substr($lang,0,2);
 // массив используемых строк
 $IMAGE_DIR = "/images/filecatalog/";
 if (!is_dir(getcwd() . "/images")) {
     mkdir(getcwd() . "/images");
 }
 if (!is_dir(getcwd() . "/images/filecatalog")) {
     mkdir(getcwd() . "/images/filecatalog");
 }
 //if (isRequest('create_table')) {
 $sql = "CREATE TABLE IF NOT EXISTS `filecatalogmsg` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `idfiles` int(10) unsigned default NULL,
   `message` text,
   `author` varchar(40) NOT NULL,
   `updated_at` timestamp NOT NULL on update CURRENT_TIMESTAMP,
   `created_at` timestamp  ,
   PRIMARY KEY  (`id`),
   KEY `id_author` (`idfiles`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44";
 se_db_query($sql);
 $sql = " CREATE TABLE IF NOT EXISTS `filecatalog` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `lang` char(3) NOT NULL,
   `user_id` int(10) unsigned default NULL,
   `page` char(255) default NULL,
   `date` char(10) default NULL,
   `name` char(70) default NULL,
   `url` text,
   `nameurl` text,
   `skrin` text,
   `short` char(250) default NULL,
   `text` text,
   `img` varchar(20) NOT NULL,
   `statistics` int(11) default NULL,
   `viewing` int(11) default NULL,
   `rating` char(20) NOT NULL,
   `pole1` char(255) default NULL,
   `pole2` char(255) default NULL,
   `pole3` char(255) default NULL,
   `pole4` char(255) default NULL,
   `pole5` char(255) default NULL,
   `pole6` char(255) default NULL,
   `pole7` char(255) default NULL,
   `pole8` char(255) default NULL,
   `pole9` char(255) default NULL,
   `pole10` char(255) default NULL,
   `updated_at` timestamp NOT NULL ,
   `created_at` timestamp NOT NULL ,
   PRIMARY KEY  (`id`),
   KEY `lang` (`lang`),
   KEY `user_id` (`user_id`)
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47";
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
 $__module_subpage['1']['admin'] = "";
 $__module_subpage['1']['group'] = 0;
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
 $__module_subpage['2']['group'] = 0;
 $__module_subpage['2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='2' && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_2.tpl";
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
	include $__MDL_ROOT . "/tpl/subpage_3.tpl";
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
	include $__MDL_ROOT . "/tpl/subpage_4.tpl";
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
	include $__MDL_ROOT . "/tpl/subpage_5.tpl";
	$__module_subpage['5']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage5
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}