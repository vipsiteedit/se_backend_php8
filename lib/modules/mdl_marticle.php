<?php
function module_marticle($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/marticle';
 else $__MDL_URL = 'modules/marticle';
 $__MDL_ROOT = dirname(__FILE__).'/marticle';
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
 $time = time();
 $sid = session_id();
 if (!defined('PAGE'))
     define('PAGE', $_page);
 
 if (!defined('RAZDEL'))
     define('RAZDEL', $razdel);  
 $_object = $__data->req->object;
 
 // получаем емайл автора 
 $obj = $__data->getObject($section, $_object);
 $authemail = $obj->text1;
 $nametitle = $obj->title;
 // *******
 
 $ident=utf8_substr(md5($section->parametrs->param33),0,20);//se_db_input($section->parametrs->param33)),0,20);
 
 $reload_flag = false;
 
 $COMMENTS = $art_errtxt = $name = $email = $note = '';
 $adminEmail = trim($section->parametrs->param1);
 $sid = session_id();
 $access = false;
 if (seUserId()) {
     $access = Article_CheckAuth($section->parametrs->param3);
 }
 //комментарии
 $_SESSION['error_message'] = null;
 $part_id_section = $section->id;
 $error_message='';
 if (isRequest('GoTo')) {
     $_SESSION['comm_message']['name'] = getRequest('name',3);
     $_SESSION['comm_message']['email'] = getRequest('email');
     $_SESSION['comm_message']['note'] = getRequest('note',3);  
 
 //проверка полей
     if (getRequest('name', 3) == '') { 
         $error_message = $section->language->lang029; 
     } else if (((getRequest('email') != '') && !se_CheckMail(getRequest('email'))) || (getRequest('email')=='')) { 
         $error_message = $section->language->lang030;
     } else if (getRequest('note', 3) == '') { 
         $error_message .= $section->language->lang031; 
     }
          
  //проверка антиспама  
         else if (isset($_POST['pin'])) {
             if (empty($_POST['pin'])) {
                 $error_message .= $section->language->lang015;
             } else {
                 require_once getcwd()."/lib/card.php";
                 if (!checkcard($_POST['pin'])) {
                   $error_message .= $section->language->lang014;
                 }
             }
         }    
  // конец проверки антиспасма   
     if (empty($error_message)) {
 //проверка на уязвимсоти
     $commname = getRequest('name',3);
     $commemail = getRequest('email');
     $commnote = getRequest('note',3);
 //капча
     @$_pin = trim(getRequest('pin'));
     require_once getcwd()."/lib/card.php";
 
     if (checkcard($_pin) || ($section->parametrs->param15 == "No")) {   
         if (empty($_SESSION['error_message'])) {
             $table = new seTable('article_comm');   
             $table->ident   = $ident;
             $table->comm_id = $_object;
             $table->name    = $commname;
             $table->email   = $commemail;
             $table->comment = $commnote;
             $table->time    = time(); 
             $table->save();             
             // Отправляем письмецо админу что статью прокоментировали
             if ($section->parametrs->param39 == "Yes" && se_CheckMail($section->parametrs->param1)) {
                 $adminmail = (string)$section->parametrs->param1;
                 $mail_text = (string)$section->parametrs->param37;
                 $mail_text = str_replace("\\r\\n", "\r\n", $mail_text);
                 $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
                 $mail_text = str_replace("[NAMETITLE]", $nametitle, $mail_text);
                 $mail_text .= "\r\n\r\n".$commnote;
 
                 $email = $commemail;
                 if ($commemail == $adminmail) $email = 'noreply@' . $_SERVER['HTTP_HOST'];
                 $from = "=?utf-8?b?" . base64_encode($commname) . "?= <".$email.'>'; 
                 $subject =  $section->language->lang034 .': '.$nametitle.' '. $_SERVER['HTTP_HOST'];
                 $adminmail = (string)$section->parametrs->param1;
 
                 $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
                 if ($mailsend->sendfile()){
                 }
    
                 $mail_text = "";
                 $headers  = "";
             }    
             if ($section->parametrs->param40 == "Yes" && se_CheckMail($authemail)) {
                 $adminmail = $authemail;
                 $mail_text = $section->parametrs->param38;
                 $mail_text = str_replace("\\r\\n", "\r\n", $mail_text);
                 $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
                 $mail_text = str_replace("[NAMETITLE]", $nametitle, $mail_text);
                 $mail_text .= "\r\n\r\n".$commnote;
 
                 $email = $commemail;
                 if ($commemail == $adminmail) { 
                     $email = 'noreply@' . $_SERVER['HTTP_HOST'];
                 }
                 $from = "=?utf-8?b?" . base64_encode($commname) . "?= <".$email.'>'; 
                 $subject =  $section->language->lang034 .': '. $nametitle.' '. $_SERVER['HTTP_HOST'];
                 $adminmail = (string)$section->parametrs->param1;
             
                 $mailsend = new plugin_mail($subject, $adminmail, $from, $mail_text);
                 if ($mailsend->sendfile()){
                 }
             }            
             $_SESSION['comm_message'] = array();                                    
             $reload_flag = true;
             unset($table);
             Header("Location: ".seMultiDir()."/{$_page}/{$razdel}/{$_object}/");
             exit;
         }
     } else
             $error_message = '<div class=\"error\">'.$section->language->lang032.'</div>';
    }  
 }
 //первоначальные парметры
     if (!empty($_SESSION['comm_message']['name'])) {
         $name = $_SESSION['comm_message']['name']; 
     } else {
 //        $name = $section->parametrs->param36;
     }
     if (!empty($_SESSION['comm_message']['email'])) {
         $email = $_SESSION['comm_message']['email'];
     } else {
         $email = "";
     }
     if (!empty($_SESSION['comm_message']['note'])) {
         $note = $_SESSION['comm_message']['note'];
     } else {
         $note = "";
     }
     
         
 if(isset($_GET['article'.$part_id_section]) && !empty($_POST['id'])){
     $id = (int)$_POST['id'];
     if ($id == 0) {
         echo 0;
     } else {
         $del = new seTable('article_comm');
         $del->where('`id`=?', $id);
         $del->andWhere('`ident`="?"', $ident);
         $del->deleteList();
         echo $_POST['id'];
     }
     exit;
 }    

   $section->objectcount = intval($section->parametrs->param2);
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
 //BeginSubPagesub1
 $__module_subpage['sub1']['admin'] = "";
 $__module_subpage['sub1']['group'] = 0;
 $__module_subpage['sub1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='sub1' && file_exists($__MDL_ROOT . "/tpl/subpage_sub1.tpl")){
	include $__MDL_ROOT . "/php/subpage_sub1.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_sub1");
	$__module_subpage['sub1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesub1
 //BeginSubPagesub2
 $__module_subpage['sub2']['admin'] = "";
 $__module_subpage['sub2']['group'] = 0;
 $__module_subpage['sub2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='sub2' && file_exists($__MDL_ROOT . "/tpl/subpage_sub2.tpl")){
	include $__MDL_ROOT . "/php/subpage_sub2.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_sub2");
	$__module_subpage['sub2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesub2
 //BeginSubPagesub3
 $__module_subpage['sub3']['admin'] = "";
 $__module_subpage['sub3']['group'] = 0;
 $__module_subpage['sub3']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='sub3' && file_exists($__MDL_ROOT . "/tpl/subpage_sub3.tpl")){
	include $__MDL_ROOT . "/php/subpage_sub3.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_sub3");
	$__module_subpage['sub3']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesub3
 //BeginSubPagesub4
 $__module_subpage['sub4']['admin'] = "";
 $__module_subpage['sub4']['group'] = 0;
 $__module_subpage['sub4']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='sub4' && file_exists($__MDL_ROOT . "/tpl/subpage_sub4.tpl")){
	include $__MDL_ROOT . "/php/subpage_sub4.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_sub4");
	$__module_subpage['sub4']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesub4
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

