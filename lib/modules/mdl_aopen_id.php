<?php
function module_aopen_id($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/aopen_id';
 else $__MDL_URL = 'modules/aopen_id';
 $__MDL_ROOT = dirname(__FILE__).'/aopen_id';
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
 //  обновление табл в БД                              
 updateOpenId();
 
 //  для избежания конфликтов
 if(!in_array($section->id, $_SESSION['loginza']['module_id']))
     $_SESSION['loginza']['module_id'][] = intval($section->id);
 
 //error_reporting(E_ALL);
 if (!class_exists('LoginzaAPI')){
     require_once $__MDL_ROOT.'/loginzaapi.class.php';
     require_once $__MDL_ROOT.'/loginzauserprofile.class.php';
 }
     $LoginzaAPI = new LoginzaAPI();
 $err = '';
 $lang = se_getLang();
 $uri = $_SERVER["REQUEST_URI"];
 $host = urlencode(_HOST_.$uri); 
 $list = 'facebook,vkontakte,odnoklassniki,twitter,google,mailruapi';         
 $url = '//loginza.ru/api/widget?token_url='.$host.'&providers_set='.$list.'&lang='.$lang;
 
if(!empty($section->parametrs->param34)){
    if(isRequest('GoToAuthor')){
        check_session(true);
        $_authorpassword = trim(getRequest('authorpassword', 3));
        $_authorlogin = trim(getRequest('authorlogin', 3));

        //  cookie
        if (isset($_POST['authorSaveCheck'])) {
        setcookie("authorlogin", $_authorlogin, time() + 3600 * 24 * 30, "/");
        setcookie("authorpassword", md5($_authorpassword), time() + 3600 * 24 * 30, "/");
        }

        $site_res = GetLogin(true, $_authorlogin, md5($_authorpassword),
        $__data->prj->adminlogin, $__data->prj->adminpassw, $__data->page->groupsname, -1);

        if ($site_res) {
            $SESSION_VARS['AUTH_USER'] = $_authorlogin;
            $SESSION_VARS['AUTH_PW'] = md5($_authorpassword);
            $linkstr = str_replace('login', '', $_SERVER["QUERY_STRING"]);
            $linkstr = UrlToLine($linkstr);
            $__data->go302(seMultiDir() . '/' . $section->parametrs->param34 . URL_END);
        }
    }
}

  // запрос пришел с субстраницы
 if(isRequest('sending')){                         
     $key = 1; 
     $user_loginza = 1;    
 } 
           
 // проверка переданного токена
 if (!empty($_POST['token'])) {                   
     // получаем профиль авторизованного пользователя
     $UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);
 //  дебагер
 //$LoginzaAPI->debugPrint($UserProfile);
 
     if(isset($UserProfile->error_type) && !empty($UserProfile->error_type)) {
         if(in_array($section->id, $_SESSION['loginza']['module_id'])) {
             $key = array_search($section->id, $_SESSION['loginza']['module_id']);
             unset($_SESSION['loginza']['module_id'][$key]);
         }
         if(empty($_SESSION['loginza']['module_id'])) return;
         $__data->go302(seMultiDir()."/".$_page."/?".time());
         exit;      
     }
     
     $req['username'] = 'login'.$UserProfile->uid;                 
     $req['last_name'] = $UserProfile->name->last_name;
     $req['first_name'] = $UserProfile->name->first_name;
     if (($req['last_name'] == '') && ($req['first_name'] == '')) {                                       
         list($req['first_name'],$req['last_name']) = explode(' ', $UserProfile->name->full_name);
     }
     $req['key'] = $UserProfile->uid;
     $req['identity'] = $UserProfile->identity;
     $req['provider'] = $UserProfile->provider;
     $req['email'] = $UserProfile->email;
     $req['avatar'] = $UserProfile->photo;
 
         unset($list);
         $uid = $req['key'];                 
         $tlb = new seTable('se_loginza');
         $tlb->select('user_id, uid');
         $tlb->where("uid='$uid'");
         $list = $tlb->fetchOne();
         unset($tlb);
 
     if(($req['email'] == '') && (!$list)){
         $__data->goSubName($section, 1);    
     } else {
         $passw = genRandomPassword();   //пароль
         $req['confirm'] = $req['passw'] = $passw; 
         $flag = 1;           
     }
 } elseif(isset($key)){    
     $req['key'] = getRequest('keys', 0);
     $req['identity'] = getRequest('inden', 3);
     $req['provider'] = getRequest('prov', 3);
     $req['username'] = 'login'.$req['key'];                 
     $passw = genRandomPassword();   //пароль
     $req['confirm'] = $req['passw'] = $passw;            
     $req['last_name'] = getRequest('lnames', 3);
     $req['first_name'] = getRequest('fnames', 3);
     $req['email'] = getRequest('emails', 3);
     $req['avatar'] = getRequest('ava', 3);
     unset($key);                                       
     $flag = 1;
 } elseif(isset($_GET['logout'])){
     // выход пользователя
     unset($_SESSION['loginza']);
     check_session(true);
 }
 
 if($flag == 1){
         // запоминаем профиль пользователя в сессию или создаем локальную учетную запись пользователя в БД
 //        $_SESSION['loginza']['profile'] = $UserProfile;
         unset($list);
         $uid = $req['key'];                 
         $tlb = new seTable('se_loginza');
         $tlb->select('id, user_id, real_user_id,uid, photo');
         $tlb->where("uid='$uid'");
         $list = $tlb->fetchOne();
        
         $user = new seUser();
         if(!$list){                         
             $groupname = '';
             $new_user_id = $user->registration($req, 0, 1, $groupname);     
             $_SESSION['loginza']['user_id'] = $new_user_id;             
             if ($new_user_id > 0) {
                 $tlb->insert;
                 $tlb->uid = $req['key'];
                 $tlb->user_id = $new_user_id;
                 $tlb->real_user_id = $new_user_id;
                 $tlb->identity = $req['identity'];
                 $tlb->provider = $req['provider'];
                 $tlb->email = $req['email'];
                 $tlb->photo = $req['avatar'];
                 $tlb->save(); 
                 
                 //изменить username
                 $user->update('username', "'login".$new_user_id."'"); 
                 $user->where("id=?", $new_user_id); 
                 $user->save();  
 
                 $tlb->select('max(id) as id');
                 $list = $tlb->fetchOne();
                 $_SESSION['loginza']['is_photo'] = $list['id'];
 
                 if(seUserGroup() || seUserId()){ 
                     $men = seUserId();
                     $tlb->update('user_id', $men); 
                     $tlb->where("uid='?'", $uid); 
                     $tlb->save();                  
                     $_SESSION['loginza']['user_id'] = $men;
                               
                 } else {
                 
                     //взять username из БД
                     $name = $user->select('username')->find($new_user_id);
                     $who = $name['username'];
                 
                     //отправить письмо                                
                     $text = "Регистрация на сайте ";
                     $client_mail = 'Здравствуйте,  '.$req['first_name'].'!\r\n \r\nРегистрация прошла успешно. Ваши данные:\r\nЛогин: '.$who.'\r\nПароль: '.$req['passw'];
                     $client_mail = str_replace('\r\n','<br>',$client_mail);
                     $from = "=?utf-8?b?" . base64_encode("$text")."?= ".$_SERVER['HTTP_HOST'].'<noreply@'.$_SERVER['HTTP_HOST'].'>';
                     $mailsend = new plugin_mail($text, $req['email'], $from, $client_mail,'text/html');   
                     $mailsend->sendfile();
                 }
             }                
         } else {
             if(seUserGroup() || seUserId()){               
                     $men = seUserId();
                     $tlb->update('user_id', $men); 
                     $tlb->where("uid='?'", $uid); 
                     $tlb->save();          
                     $_SESSION['loginza']['user_id'] = $men;                                                               
                               
             } else {                                                        
                 $_SESSION['loginza']['user_id'] = $list['user_id'];
             }    
             $_SESSION['loginza']['is_photo'] = $list['id'];
         }  
 
         //авторизация на сайте
         $new_user_id = $_SESSION['loginza']['user_id'];
         if (($new_user_id > 0) && (seUserGroup() == 0)) {                    
             $arr = $user->select('*')->find($new_user_id);
             $person = $user->getPerson();                 
             check_session(true);   //ЗАКРЫТЬ СЕССИЮ
               $auth['IDUSER'] = $new_user_id;
               $auth['GROUPUSER'] = 1;
               $login = $auth['USER'] = $person->first_name." ".$person->last_name;
               $auth['AUTH_USER'] = $user->username;
               $auth['AUTH_PW'] = $user->password;
               $auth['ADMINUSER'] = '';
             check_session(false, $auth); //ОТКРЫТЬ СЕССИЮ
             $_SESSION['loginza']['is_auth'] = 1;
             if(!empty($section->parametrs->param34)){
                $__data->go302(seMultiDir() . '/' . $section->parametrs->param34 . URL_END);
             }
         }
 }
 
 if(seUserGroup() || seUserId()){                 
     $_SESSION['loginza']['is_auth'] = 1;
     $_SESSION['loginza']['user_id'] = seUserId();
 } else {unset($_SESSION['loginza']);}
   
 if (!empty($_SESSION['loginza']['is_auth'])) { 
 // пользователь авторизован
     //получить аватар
     $id_avatar = $_SESSION['loginza']['is_photo'];
     $avatar = new seTable('se_loginza');    
     $avatar->select('id, photo');
     $avatar->where("id=?", $id_avatar);
     $lists = $avatar->fetchOne();
     
     //поиск зарегеных аккаунтов
     $extra_avatar = '';
     $new_user_id = $_SESSION['loginza']['user_id'];
     $account = new seTable('se_loginza','sl');
     $account->select('id, user_id, real_user_id, provider, photo');
     $account->where("user_id='$new_user_id'"); 
     $acc = $account->getList();           
     foreach($acc as $item){        
         $fio = new seUser();
         $fio->select('*')->find($item['real_user_id']);
         $pers = $fio->getPerson();
         $fio = $pers->first_name." ".$pers->last_name;
         $idd = $item['id'];
         if(stripos($item['provider'], 'facebook')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/facebook.png'."' alt='".$fio."' title='".$fio."'>";
         } elseif(stripos($item['provider'], 'twitter')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/twitter.png'."' alt='".$fio."' title='".$fio."'>";
         } elseif(stripos($item['provider'], 'vkontakte')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/vkontakte.png'."' alt='".$fio."' title='".$fio."'>";
         } elseif(stripos($item['provider'], 'google')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/google.png'."' alt='".$fio."' title='".$fio."'>";
         } elseif(stripos($item['provider'], 'mail')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/mailruapi.png'."' alt='".$fio."' title='".$fio."'>";
         } elseif(stripos($item['provider'], 'odnoklassniki')){
             $extra_avatar .= "<img class='icon_img' src='"._HOST_."/".$__MDL_URL.'/odnoklassniki.png'."' alt='".$fio."' title='".$fio."'>";
         }
     };       
   
     //вывод на экран
     $lists_photo = ($lists['photo']) ? ($lists['photo']) : false;
     $seUserGroup = (!seUserGroup() && seUserId()) ? '1' : seUserGroup();
     $enter = 'logout';  
 
 // пользователь не авторизован
 } else {
     $reg_link = seMultiDir()."/".$section->parametrs->param4."/";
     $restore_link = seMultiDir()."/".$section->parametrs->param32."/";
     $enter = "login";
 }
 
 //удалиение связанных аккаунтов
 $er = '';
 $gotos = 0;
 $time = time();
 //удаление
 if(isRequest('deletes_openid')){
     $del = getRequest('deletes_openid', 1);
     $from = getRequest('fromuser', 1);         
     $delete = new seTable('se_loginza');
     $delete->select('id, real_user_id, user_id');
     $delete->where("`id`=$del"); 
     $who = $delete->fetchOne();
     //var_dump($who); 
     $delete->delete($del); 
     unset($delete);  
     if($who['user_id'] == $who['real_user_id']){                    //если удаляет текущий аккаунт
         unset($_SESSION['loginza']);
         check_session(true);  
         Header("Location: ".seMultiDir()."/".$_page."/?".time());
         Exit;  
     } 
         $__data->goSubName($section, 2);
     
 }                                                             
 //список                  
 if(isRequest('id_account')){                                  
     $__data->goSubName($section, 2);
     
 }
 //конец удалиения связанных аккаунтов

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPage2
 $__module_subpage['2']['admin'] = "";
 $__module_subpage['2']['group'] = 0;
 $__module_subpage['2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='2' && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_2");
	$__module_subpage['2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
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