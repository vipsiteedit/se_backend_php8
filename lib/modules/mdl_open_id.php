<?php
function module_open_id($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/open_id';
 else $__MDL_URL = 'modules/open_id';
 $__MDL_ROOT = dirname(__FILE__).'/open_id';
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
 $file_path = getcwd().'/system/logs/open_id.php';
 if(!file_exists($file_path)){
     $err = '';
     $sql = " CREATE TABLE IF NOT EXISTS `se_loginza` (
         `id` int(10) unsigned NOT NULL auto_increment,
         `uid` varchar(50) NOT NULL,
         `user_id` int(10) unsigned NOT NULL,
         `identity` varchar(255) NOT NULL,
         `provider` varchar(255) NOT NULL,
         `email` varchar(20) default NULL,
         `photo` varchar(255),
         `real_user_id` int(10),
         `updated_at` timestamp NOT NULL ,
         `created_at` timestamp NOT NULL ,
         PRIMARY KEY  (`id`)
         ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
     se_db_query($sql); 
     $err .= se_db_error();
     
     if (!se_db_is_field('se_loginza', 'photo')) {
         se_db_query("ALTER TABLE `se_loginza` ADD `photo` varchar(255)");
     }
     $err .= se_db_error();
     
     if (!se_db_is_field('se_loginza', 'real_user_id')) {
         se_db_query("ALTER TABLE `se_loginza` ADD `real_user_id` int(10)");
     }
     $err .= se_db_error();
     
     if(!$err) {
         if(!is_dir(getcwd().'/system/logs/')) mkdir(getcwd().'/system/logs/');
         file_put_contents('system/logs/open_id.php', '');
     }
 }
 
 //error_reporting(E_ALL);
 require_once $__MDL_ROOT.'/loginzaapi.class.php';
 require_once $__MDL_ROOT.'/loginzauserprofile.class.php';
 $LoginzaAPI = new LoginzaAPI();
 $err = '';
 $lang = se_getLang();
 $uri = $_SERVER["REQUEST_URI"];
 
 $path_reload = ($section->parametrs->param34 != '') ? seMultiDir()."/".$section->parametrs->param34.'/' : '';
 $host = (empty($path_reload)) ? urlencode(_HOST_.$uri) : urlencode(_HOST_.$path_reload);
 
 $list = 'facebook,vkontakte,odnoklassniki,twitter,google,mailruapi';         
 $url = 'http://loginza.ru/api/widget?token_url='.$host.'&providers_set='.$list.'&lang='.$lang;
 
  // запрос пришел с субстраницы
 if(isRequest('sending')){                         
     $key = 1; 
     $user_loginza = 1;    
 } 
           
 // проверка переданного токена
 if (!empty($_POST['token'])) {                   
  // получаем профиль авторизованного пользователя
  $UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);
     
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
         $__data->goSubName($section, 'p1');    
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
 
                 if(seUserGroup()>0){ 
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
             if(seUserGroup()>0){               
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
         }
 }
 //     $LoginzaAPI->debugPrint($UserProfile);
 
 if(seUserGroup()>0){                 
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
     $enter =  "  <div class='logoutblock'>";
     if($lists['photo']){        //если есть аватарка
         $enter .= "<span class='title'><img class='title_img' src='".$lists['photo']."'></span>";
     } else {
         $enter .= "<span class='title'><img class='title_img' src='"._HOST_."/".$__MDL_URL.'/nofoto.gif'."'></span>";
     }
     $enter .= " <div class='invitation'>".$section->parametrs->param14."<span class='username'>[NAMEUSER]</span></div>";     
     if(seUserGroup()!=3){
         $enter .= " <div class='soc_link'>
                     <span class='soc_link_a'>
                         <a href='".$url."'>".$section->parametrs->param18."</a>";
         if($extra_avatar){           //если есть доступ к другим соц. сетям
         
             $enter .= " <a href='?id_account'>".$section->parametrs->param26."</a>";
         }
         $enter .= " </span>";
         if($extra_avatar){           //если есть доступ к другим соц. сетям
             $enter .= " <span class='extra_images'><span class='extra_title'>".$section->parametrs->param13."</span>".$extra_avatar."</span>";
         }
         $enter .= " </div>";
      } 
         $enter .= " <a class='links' href='?logout'>".$section->parametrs->param15."</a>
                 </div>";  
 
 // пользователь не авторизован
 } else {
     $enter = "  <div class='loginblock'>";
     
     if(($section->parametrs->param23 == 'f') || ($section->parametrs->param23 == 't')){        //если Форма авторизации или Оба варианта
         
         $enter .= "<div class='openIdlogin'><form style='margin: 0;' action='".$path_reload."' method='post'>
                         <span class='title'>".$section->parametrs->param3."</span>
                         <input type='hidden' value='true' name='authorize'>
                         <input class='authorlogin' name='authorlogin' value='".$section->parametrs->param21."' onfocus='if(this.value==\"".$section->parametrs->param21."\") this.value=\"\"'  onblur='if(this.value==\"\") this.value=\"".$section->parametrs->param21."\"'>
                         <input class='authorpassw' type='password' name='authorpassword' value='".$section->parametrs->param22."' onfocus='if(this.value==\"".$section->parametrs->param22."\") this.value=\"\"'  onblur='if(this.value==\"\") this.value=\"".$section->parametrs->param22."\"'>
                         <input class='buttonSend loginsend' type='submit' value='".$section->parametrs->param10."' name='GoToAuthor'>";
         if($section->parametrs->param24 == 'y'){                        
             $enter .= " <div class='authorSave'>
                             <input id='authorSaveCheck' type='checkbox' value='1' name='authorSaveCheck'>
                             <label for='authorSaveCheck' class='authorSaveWord'>".$section->parametrs->param19."</label>
                         </div>";
         }
             $enter .= " </form>";
         if($section->parametrs->param25 == 'y'){            
             $enter .= " <a class='links regi' href='".seMultiDir()."/".$section->parametrs->param4."/'>".$section->parametrs->param20."</a>";
         }
         if($section->parametrs->param32!='') $enter .= " <a class='links remem' href='".seMultiDir()."/".$section->parametrs->param32."/'>".$section->parametrs->param33."</a>";                       
         $enter .= " </div>";
     };
     if(($section->parametrs->param23 == 's') || ($section->parametrs->param23 == 't')){        //если Сервис авторизации или Оба варианта
         $enter .= " <div class='openIdBlock'>";
         if($section->parametrs->param6 == 's') {
             $enter .= "<span class='loginblocktxt'>".$section->parametrs->param2."</span>";
             $enter .= "<a href='".$url."' class='loginzain'><img class='imgs' src='"._HOST_."/".$__MDL_URL.'/social_add.png'."'/></a></div>";
         } else {
             $enter .= "<span class='loginblocktxt'>".$section->parametrs->param2."</span>";
             $enter .= "<a href='".$url."' class='loginzain'>".$section->parametrs->param1."</a></div>";
         }
     }    
         $enter .= "</div>";    
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
         $__data->goSubName($section, 'p2');
     
 }                                                             
 //список                  
 if(isRequest('id_account')){                                  
     $__data->goSubName($section, 'p2');
     
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
 //BeginSubPagep2
 $__module_subpage['p2']['admin'] = "";
 $__module_subpage['p2']['group'] = 0;
 $__module_subpage['p2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='p2' && file_exists($__MDL_ROOT . "/tpl/subpage_p2.tpl")){
	include $__MDL_ROOT . "/php/subpage_p2.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_p2");
	$__module_subpage['p2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagep2
 //BeginSubPagep1
 $__module_subpage['p1']['admin'] = "";
 $__module_subpage['p1']['group'] = 0;
 $__module_subpage['p1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='p1' && file_exists($__MDL_ROOT . "/tpl/subpage_p1.tpl")){
	include $__MDL_ROOT . "/php/subpage_p1.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_p1");
	$__module_subpage['p1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagep1
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}