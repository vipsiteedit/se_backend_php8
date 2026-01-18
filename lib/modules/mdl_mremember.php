<?php
function module_mremember($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mremember';
 else $__MDL_URL = 'modules/mremember';
 $__MDL_ROOT = dirname(__FILE__).'/mremember';
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
 $error_message ="";
 if (isset($_POST['GoToRemember'])){
     $user = new seUser();
     $person= new sePerson();
     $name = getRequest('name', 3);
     $email = getRequest('email');
   
     if (empty($name) || empty($email)) { 
         $error_message = $section->language->lang008;
     } else { 
       $admemail= trim($section->parametrs->param1);
       $user->where("username='?'",$name);
       $user->fetchOne();
       if ($user->id) {
          $person->find($user->id);
          if ($person->email == $email){
      
             $newpassw = substr(md5(time()),0,6);
             $user->tmppassw = md5($newpassw);
             $user->save();
       
             $login = $user->username;
             $passw = $newpassw;
             $last_name=se_db_output($person->last_name);
             $first_name=se_db_output($person->first_name);
             $email = $person->email;
             
             $mail_text = $section->parametrs->param4;
             $mail_text = str_replace("[USERNAME]", "{$last_name} {$first_name}", $mail_text);
             $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
             $mail_text = str_replace("[USERLOGIN]", $login, $mail_text);
             $mail_text = str_replace("[USERPASSWORD]", $passw, $mail_text);
             $mail_text = str_replace("[USEREMAIL]", $email, $mail_text);
             $mail_text = str_replace('\r\n', "\r\n", $mail_text);
 
             $name = trim($_SERVER['HTTP_HOST']);
      
             $from = "=?utf-8?b?" . base64_encode($name) . "?= <".$admemail.'>'; 
             $subject =  $section->language->lang010;
             $mailsend = new plugin_mail($subject, trim($email), $from, $mail_text);
             if ($mailsend->sendfile()){
             
                 if ($section->parametrs->param15=='Y'){
                     $mail_text =$section->parametrs->param11;
                     $mail_text = str_replace('\r\n', "\r\n", $mail_text);
                     $mail_text = str_replace("[USERNAME]", "{$last_name} {$first_name}", $mail_text);
                     $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
                     $mail_text = str_replace("[USERLOGIN]", $login, $mail_text);
                     $mail_text = str_replace("[USERPASSWORD]", $_passw, $mail_text);
                     $mail_text = str_replace("[USEREMAIL]", $_email, $mail_text);
 
                     $emailfrom = 'noreply@' . $_SERVER['HTTP_HOST'];
                     $from = "=?utf-8?b?" . base64_encode($name) . "?= <".$emailfrom.'>'; 
                     $subject =  $section->language->lang010;
 
                     $mailsend = new plugin_mail($subject, $admemail, $from, $mail_text);
                     $mailsend->sendfile();
                  }  
                     header("Location: /{$_page}/{$razdel}/sub1/?email={$email}");
                     exit;
             }
          } else $error_message = $section->language->lang009;
       }
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