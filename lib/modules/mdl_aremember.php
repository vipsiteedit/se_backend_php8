<?php
function module_aremember($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/aremember';
 else $__MDL_URL = 'modules/aremember';
 $__MDL_ROOT = dirname(__FILE__).'/aremember';
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
 $time = time();
 $sid = session_id();
 $path = seMultiDir()."/".$section->parametrs->param2."/";
  
 if (isRequest("ajax".$section->id."_pass")){
     $name = getRequest('name', 3);
     if (empty($name)) {
         $error_message = $section->language->lang006;
     } else {
         require_once getcwd() . "/lib/card.php";                   
         if (!checkcard(getRequest('pin', 1))) {
             $error_message = $section->language->lang007;
         }
     }                                  
     if ($error_message == '') { 
         $user = new seTable('se_user', 'su');
         $user->select('su.id');
         $user->innerjoin('person p', 'p.id=su.id');
         $user->where("su.username='?' OR p.email='?'", $name);
         $user->fetchOne();
         $user_id = $user->id;
         if ($user_id) {
             $user = new seUser();
             $person= new sePerson();
             $admemail= trim($section->parametrs->param1);
 
             $user->find($user_id);
             $person->find($user_id);
 //         if ($person->email == $email){
             $newpassw = substr(md5(time()), 0, 6);
             $user->tmppassw = md5($newpassw);
             $user->save();
             $login = $user->username;
             $passw = $newpassw;
             $last_name = se_db_output($person->last_name);
             $first_name = se_db_output($person->first_name);
             $email = $person->email;
             $mail_text = $section->parametrs->param4;
             $mail_text = str_replace("[USERNAME]", "{$last_name} {$first_name}", $mail_text);
             $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
             $mail_text = str_replace("[USERLOGIN]", $login, $mail_text);
             $mail_text = str_replace("[USERPASSWORD]", $passw, $mail_text);
             $mail_text = str_replace("[USEREMAIL]", $email, $mail_text);
             $mail_text = str_replace('\r\n', "\r\n", $mail_text);
             $namehost = trim($_SERVER['HTTP_HOST']);
             $from = "=?utf-8?b?" . base64_encode($namehost) . "?= <" . $admemail . '>'; 
             $subject = $section->parametrs->param14;
             $mailsend = new plugin_mail($subject, trim($email), $from, $mail_text);
             if ($mailsend->sendfile()) {
 
                 if ($section->parametrs->param15 == 'Y') {
                     $mail_text = $section->parametrs->param11;
                     $mail_text = str_replace('\r\n', "\r\n", $mail_text);
                     $mail_text = str_replace("[USERNAME]", "{$last_name} {$first_name}", $mail_text);
                     $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
                     $mail_text = str_replace("[USERLOGIN]", $login, $mail_text);
                     $mail_text = str_replace("[USERPASSWORD]", $passw, $mail_text);
                     $mail_text = str_replace("[USEREMAIL]", $email, $mail_text);
                     $emailfrom = 'noreply@' . $_SERVER['HTTP_HOST'];
                     $from = "=?utf-8?b?" . base64_encode($namehost) . "?= <" . $emailfrom . '>'; 
                     $subject =  $section->parametrs->param14;
                     $mailsend = new plugin_mail($subject, $admemail, $from, $mail_text);
                     $mailsend->sendfile();
                 }  
                 $error_message = 'OK';
                 //header("Location: ".seMultiDir()."/{$_page}/sendconfirm/{$email}/");
                 //exit;
             } else $error_message = $section->language->lang004;
         } else $error_message = $section->language->lang005;
     }
     echo $error_message;
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
 //BeginSubPageconfirm
 $__module_subpage['confirm']['admin'] = "";
 $__module_subpage['confirm']['group'] = 0;
 $__module_subpage['confirm']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='confirm' && file_exists($__MDL_ROOT . "/tpl/subpage_confirm.tpl")){
	include $__MDL_ROOT . "/php/subpage_confirm.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_confirm");
	$__module_subpage['confirm']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageconfirm
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}