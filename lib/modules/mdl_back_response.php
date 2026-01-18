<?php
function module_back_response($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/back_response';
 else $__MDL_URL = 'modules/back_response';
 $__MDL_ROOT = dirname(__FILE__).'/back_response';
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
 //сохранить в csv-файл
 if(isRequest('saves')){ 
     $poisk = getRequest('ide', 3);
     $poisk = urldecode($poisk);
     $tab = new seTable('back_response');
     $tab->select('l_name, f_name, s_name, email, phone');
     $tab->where("`id_subscribe`='?'",$poisk);
     $tablist = $tab->getList(); 
     $filename = 'export.csv';
     $lines = array();
     $fp = se_fopen($filename, 'w');
     foreach ($tablist as $fields) {
         $str = $fields['l_name'].";".$fields['f_name'].";".$fields['s_name'].";".$fields['email'].";".$fields['phone']."\n"; 
         fwrite($fp, $str);
     }
     fclose($fp); 
 
     header("Content-Type: text/csv");
     header('Content-Disposition: attachment; filename="'.$filename.'"');
     header('Content-Encoding: none');
  //   header("Content-Transfer-Encoding: binary ");
     header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
 
     $strfin = '';
     $lines = se_file($filename); 
     foreach ($lines as $line) {
         $strfin .= $line."\n";
     } 
     $strfin = "\xEF\xBB\xBF".$strfin;
     echo $strfin;    
     exit;  
 }
 
 //проверка на доступ к сервису
 $err='';
 $disabled_button = '';
 $flag = 0;
 $idpod = trim($section->parametrs->param4);
 if(empty($idpod)){
     $err=$section->language->lang001;
     $disabled_button = 'disabled="disabled"';    
 }
 
 //кто пришел
 if(seUserGroup()==3){
     $flag = 1;
 }
 
 //установим время
 $dt = explode('.',$section->parametrs->param5);
 $date = date("F j, Y 0:0:00", mktime(0, 0, 0, $dt[1], $dt[0], $dt[2])); 
 
 //создание талицы в БД, если нет
 $sql = "CREATE TABLE IF NOT EXISTS `back_response` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `id_subscribe` varchar(40) NOT NULL,
   `l_name` varchar(30),
   `f_name` varchar(30),
   `s_name` varchar(30),
   `email` varchar(40),
   `phone` varchar(30),
   `comments` text,
   `created_at` timestamp  ,
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
 se_db_query($sql);
 
 //сохранение записи в БД
 if(isRequest('query_time')){
   $scribe = $section->parametrs->param4;
   $last = getRequest('selast', 3);
   $first = getRequest('sefirst', 3);
   $second = getRequest('sesecond', 3);
   $mail = strip_tags(getRequest('semail', 3));
   $phone = getRequest('sephone', 3);    
   $comm = getRequest('secomm', 3);  
 //  echo $phone.'-1-';                 
   //проверка на существование такого клиента
   $client = new seTable('back_response');      
   $client->select('email');                     
   $client->where("`email`='?'",$mail);
   $lists = $client->getList();                    
   if(!empty($lists)){    
     echo $section->language->lang008;    
     exit;
   }          
   $sql = "INSERT INTO back_response (id_subscribe,l_name,f_name,s_name,email,phone) VALUES( '{$scribe}','{$last}','{$first}','{$second}','{$mail}','{$phone}')";
   mysql_query($sql);
   exit;    
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