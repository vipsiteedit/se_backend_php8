<?php
function module_mforum($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mforum';
 else $__MDL_URL = 'modules/mforum';
 $__MDL_ROOT = dirname(__FILE__).'/mforum';
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
 if (!SE_DB_ENABLE) return;
 $lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
 if ($lang=="")  
     $lang = se_getLang();     // если язык незивестен значит он русский
 
 function getmicrotime(){ 
     list($usec, $sec) = explode(" ",microtime()); 
     return ((float)$usec + (float)$sec); 
     } 
 
 updateDB($section);
 
 if (time() - intval($_SESSION['msgAddTime']) >= 3600 ) {
     $tbl = new seTable("forum_attached", "fa");
     $tbl->where("id_msg = '0'");
     foreach ($tbl->getList() as $v) {
         if (file_exists(getcwd() . "/modules/forum/upload/" . $v['file'])) {
             unlink(getcwd() . "/modules/forum/upload/" . $v['file']);
         }
         se_db_query("DELETE FROM forum_attached
                         WHERE
                             file = '" . $v['file'] . "'");
     }
     unset ($tbl);
 }
 
 $iconssmiles = '/' . ltrim ($url_module, '/');
 $month_R = array ("01"=>$section->language->lang174, "02"=>$section->language->lang175, "03"=>$section->language->lang176, 
                   "04"=>$section->language->lang177, "05"=>$section->language->lang178, "06"=>$section->language->lang179, 
                   "07"=>$section->language->lang180, "08"=>$section->language->lang181, "09"=>$section->language->lang182,
                   "10"=>$section->language->lang183, "11"=>$section->language->lang184, "12"=>$section->language->lang185);
 $SESSIONBLOCK = 'Y';
 $msgOfPart = htmlspecialchars($section->parametrs->param1);
 $thmOfPart = htmlspecialchars($section->parametrs->param99);
 $msgMaxLength = htmlspecialchars($section->parametrs->param2);
 $msgMaxLengthTopic = htmlspecialchars($section->parametrs->param3);
 $nameForum = htmlspecialchars($section->parametrs->param4);
 $quoteStringLength = 50;
 $timeActive = htmlspecialchars($section->parametrs->param5);
 $unregName = htmlspecialchars($section->language->lang190);
 $haltView = htmlspecialchars($section->parametrs->param7);
 //$btnNew = htmlspecialchars($section->parametrs->param8);
 //$btnReply = htmlspecialchars($section->parametrs->param9);
 //$btnEdit = htmlspecialchars($section->parametrs->param10);
 //$btnModer = htmlspecialchars($section->parametrs->param11);
 //$btnDel = htmlspecialchars($section->parametrs->param12);
 //$btnGoTopic = htmlspecialchars($section->parametrs->param13);
 //$btnPathStrl = htmlspecialchars($section->parametrs->param14);
 $maxFilesAttached = intval(htmlspecialchars($section->parametrs->param16));
 $maxFilesAttachedSize = intval(htmlspecialchars($section->parametrs->param17));
 $maxFilesAttachedUser = intval(htmlspecialchars($section->parametrs->param18));
 $smod = false;   
 $uid = seUserId();
 $enable = 0;
 if (seUserGroup() == 3) {
     $uid = -1; //Администратор сайта
 }
 if (empty($uid)) {
     $uid = 0;
 } else if ($uid == -1) {
     $nick = $section->parametrs->param91;    
 } else {
     $tbl = new seTable("person","p");
     $tbl->select("p.id, su.username  AS nick, 
                     CONCAT_WS(' ', p.last_name, p.first_name, p.sec_name) AS realname,
                     CONCAT_WS(', ', c.name, t.name) AS location,
                     p.email, p.icq");
     $tbl->leftjoin("country c", "c.id = p.country_id");
     $tbl->leftjoin("town t", "t.id = p.town_id");
     $tbl->innerjoin("se_user su", "su.id = p.id");
     $tbl->where("p.id = '?'", $uid);
     $mydata = $tbl->fetchOne();
     unset($tbl);
     $nick = trim($mydata['nick']);
 }
 $sid = session_id ();
 $time = time ();
 if ($uid) { //Если зарегистрированный пользователь
   //Ищем пользователя в базе форума
     $tbl = new seTable("forum_users");
     $tbl->where("id_author = '?'",$uid);
     $ru = $tbl->getList();
     unset($tbl);
 
     if (!count($ru)) {
         $flag = false;
         $count = 1;
         while (!$flag) {
             $tbl = new seTable("forum_users","fu");
             $tbl->where("nick = '?'",$nick);
             if (count($tbl->getList())) {
                 $nick = $mydata['nick'].$count; 
             } else {
                 $flag = true;
             }
             unset($tbl);
             ++$count;
         }
         $tbl = new seTable("forum_users","fu");          
         $tbl->id_author = $uid;
         $tbl->id_status = 1;
         $tbl->nick = $nick;
         if ($uid >= 0) {
             $tbl->realname = trim($mydata['realname']);
             $tbl->location = trim($mydata['location']);
             $tbl->email = trim($mydata['email']);
             $tbl->icq = trim($mydata['icq']);            
         } else {
             $tbl->smoderator = 'Y';
         }
         $tbl->registered = $time;
         $tbl->last = $time;
         $tbl->save();
         unset($tbl);
         $tbl = new seTable("forum_users", "fu");
         $tbl->where("id_author = '?'", $uid);
         $tbl->fetchOne();
         $uid = $tbl->id;
         unset($tbl);
     } else {
         list($user) = $ru;
         $uid = $user['id'];
         $nick = $user['nick'];
         if ($user['smoderator'] == "Y") {
             $smod = true; 
         } else {
             $smod = false;
         }
         if ($user['enabled'] == "N") {
             $enable = 1;
         }
     }
 //Определяем, когда заходили последний раз
     $tbl = new seTable("forum_users");
     $tbl->select('last');
     $tbl->find($uid);
     $last = $tbl->last;     
     unset($tbl);  
 
     $tbl = new seTable("forum_session");
     $tbl->where("sid='?'",$sid);
     $session = $tbl->fetchOne();
     unset($tbl);
     
     if (!empty($session) || ($session['id_users']==0)) {
         $tbl = new seTable("forum_users","fu");
         $tbl->find($uid);
         $tbl->last = $time;
         $tbl->save();
     } else {
         $lastVisit = @$session['date_time_last'];
     }
     if ((time() - intval($session['date_time'])) < 0) {
         $enable = 2;
     }
 } else {
     $nick = $unregName;
     $lastVisit = 0;
 }
 
 //Удаляем все сессии, время которых > $timeActive секунд и свою
 $date_time = time()-$timeActive;
 
 //Добавляем нашу сессию
 unset($tbl);
 se_db_delete("forum_session", "date_time<'$date_time' OR `sid`='{$sid}'", false);
 $date_time = time();
 //Проверяем, если пользователь - робот поисковых систем
 if (isset($_SERVER['HTTP_USER_AGENT'])) {
     $namerobot = forum_getrobots($_SERVER['HTTP_USER_AGENT']);
 } else {
     $namerobot = "other";
 }
 $tbl = new seTable("forum_session","fs");
 $tbl->sid            = $sid; 
 $tbl->id_users       = $uid;
 $tbl->namerobot      = $namerobot;
 $tbl->date_time      = $date_time;
 $tbl->date_time_last = $lastVisit;
 $tbl->save();
 unset($tbl);
 $tbl = new seTable("forum_session","fs");
 $tbl->select("id_users, nick, fu.id");
 $tbl->innerjoin("forum_users fu","fs.id_users = fu.id");
 $tbl->where("namerobot='other'");
 $tbl->orderby("`fs`.`date_time`");
 $rs = $tbl->getList(-1);
 unset($tbl);
 $regUser = array();
 foreach($rs as $session) {
     $regUser[$session['id_users']] = $session['nick'];
 }
 //Считаем роботов        
 $tbl = new seTable("forum_session","fs");
 $tbl->select('COUNT(*) AS `cnt`');
 $tbl->where("id_users = 0");
 $tbl->andWhere("namerobot = 'other'");
 $tbl->fetchOne();
 $guest = $tbl->cnt;
 unset($tbl);
 
 $tbl = new seTable("forum_session","fs");
 $tbl->select("DISTINCT namerobot");
 $tbl->where("id_users = 0");
 $tbl->andWhere("namerobot<>'other'");
 $tbl->andWhere("namerobot<>''");
 $tbl->orderby("date_time");
 $rs = $tbl->getList();
 unset($tbl);
 $robots = array();
 foreach($rs as $r) {
     $__data->setItemList($section, 'robots', 
         array(
             'name'   => $r['namerobot'],
             'notend' => ((count($robots)<count($rs)-1)?',':'')
         )
     );
     $robots[] = $r['namerobot'];
 }
 $allrobots = count($robots);
 
 $curday = date("d");
 $curmonth = $month_R[date("m")];
 $curyear = date("Y");
 $curtime = date("H:i"); 
 $nameForum = htmlspecialchars($nameForum);
 if (isRequest('id')) {
     $ext_id = getRequest('id',1);
 }
 $reg_users = count($regUser);
 $all_users = $guest + $reg_users;
 if ($reg_users) {
     $i = 0;
     foreach ($regUser as $id => $user) {
         $__data->setItemList($section, 'regusers', 
             array(
                 'id'   => $id,
                 'name' => trim($user),
                 'notend' => (($i<$reg_users-1)?',&nbsp;':'') 
             )
         );
         ++$i;
     }
 }
 //Считаем всех пользователей
 $tbl = new seTable('forum_users','fu');
 $tbl->select("count(*) as ucount");
 $tbl->fetchOne();
 $forum_user_reg = $tbl->ucount;
 unset($tbl);
                                 

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
 //BeginSubPage6
 $__module_subpage['6']['admin'] = "";
 $__module_subpage['6']['group'] = 0;
 $__module_subpage['6']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='6' && file_exists($__MDL_ROOT . "/tpl/subpage_6.tpl")){
	include $__MDL_ROOT . "/php/subpage_6.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_6.tpl";
	$__module_subpage['6']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage6
 //BeginSubPage7
 $__module_subpage['7']['admin'] = "";
 $__module_subpage['7']['group'] = 0;
 $__module_subpage['7']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='7' && file_exists($__MDL_ROOT . "/tpl/subpage_7.tpl")){
	include $__MDL_ROOT . "/php/subpage_7.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_7.tpl";
	$__module_subpage['7']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage7
 //BeginSubPage8
 $__module_subpage['8']['admin'] = "";
 $__module_subpage['8']['group'] = 0;
 $__module_subpage['8']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='8' && file_exists($__MDL_ROOT . "/tpl/subpage_8.tpl")){
	include $__MDL_ROOT . "/php/subpage_8.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_8.tpl";
	$__module_subpage['8']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage8
 //BeginSubPage9
 $__module_subpage['9']['admin'] = "";
 $__module_subpage['9']['group'] = 0;
 $__module_subpage['9']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='9' && file_exists($__MDL_ROOT . "/tpl/subpage_9.tpl")){
	include $__MDL_ROOT . "/php/subpage_9.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_9.tpl";
	$__module_subpage['9']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage9
 //BeginSubPage10
 $__module_subpage['10']['admin'] = "";
 $__module_subpage['10']['group'] = 0;
 $__module_subpage['10']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='10' && file_exists($__MDL_ROOT . "/tpl/subpage_10.tpl")){
	include $__MDL_ROOT . "/php/subpage_10.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_10.tpl";
	$__module_subpage['10']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage10
 //BeginSubPage11
 $__module_subpage['11']['admin'] = "";
 $__module_subpage['11']['group'] = 0;
 $__module_subpage['11']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='11' && file_exists($__MDL_ROOT . "/tpl/subpage_11.tpl")){
	include $__MDL_ROOT . "/php/subpage_11.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_11.tpl";
	$__module_subpage['11']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage11
 //BeginSubPage12
 $__module_subpage['12']['admin'] = "";
 $__module_subpage['12']['group'] = 0;
 $__module_subpage['12']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='12' && file_exists($__MDL_ROOT . "/tpl/subpage_12.tpl")){
	include $__MDL_ROOT . "/php/subpage_12.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_12.tpl";
	$__module_subpage['12']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage12
 //BeginSubPage13
 $__module_subpage['13']['admin'] = "";
 $__module_subpage['13']['group'] = 0;
 $__module_subpage['13']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='13' && file_exists($__MDL_ROOT . "/tpl/subpage_13.tpl")){
	include $__MDL_ROOT . "/php/subpage_13.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_13.tpl";
	$__module_subpage['13']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage13
 //BeginSubPage14
 $__module_subpage['14']['admin'] = "";
 $__module_subpage['14']['group'] = 0;
 $__module_subpage['14']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='14' && file_exists($__MDL_ROOT . "/tpl/subpage_14.tpl")){
	include $__MDL_ROOT . "/php/subpage_14.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_14.tpl";
	$__module_subpage['14']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage14
 //BeginSubPage15
 $__module_subpage['15']['admin'] = "";
 $__module_subpage['15']['group'] = 0;
 $__module_subpage['15']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='15' && file_exists($__MDL_ROOT . "/tpl/subpage_15.tpl")){
	include $__MDL_ROOT . "/php/subpage_15.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_15.tpl";
	$__module_subpage['15']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage15
 //BeginSubPage16
 $__module_subpage['16']['admin'] = "";
 $__module_subpage['16']['group'] = 0;
 $__module_subpage['16']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='16' && file_exists($__MDL_ROOT . "/tpl/subpage_16.tpl")){
	include $__MDL_ROOT . "/php/subpage_16.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_16.tpl";
	$__module_subpage['16']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage16
 //BeginSubPage17
 $__module_subpage['17']['admin'] = "";
 $__module_subpage['17']['group'] = 0;
 $__module_subpage['17']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='17' && file_exists($__MDL_ROOT . "/tpl/subpage_17.tpl")){
	include $__MDL_ROOT . "/php/subpage_17.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_17.tpl";
	$__module_subpage['17']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage17
 //BeginSubPage18
 $__module_subpage['18']['admin'] = "";
 $__module_subpage['18']['group'] = 0;
 $__module_subpage['18']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='18' && file_exists($__MDL_ROOT . "/tpl/subpage_18.tpl")){
	include $__MDL_ROOT . "/php/subpage_18.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_18.tpl";
	$__module_subpage['18']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage18
 //BeginSubPage19
 $__module_subpage['19']['admin'] = "";
 $__module_subpage['19']['group'] = 0;
 $__module_subpage['19']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='19' && file_exists($__MDL_ROOT . "/tpl/subpage_19.tpl")){
	include $__MDL_ROOT . "/php/subpage_19.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_19.tpl";
	$__module_subpage['19']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage19
 //BeginSubPage20
 $__module_subpage['20']['admin'] = "";
 $__module_subpage['20']['group'] = 0;
 $__module_subpage['20']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='20' && file_exists($__MDL_ROOT . "/tpl/subpage_20.tpl")){
	include $__MDL_ROOT . "/php/subpage_20.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_20.tpl";
	$__module_subpage['20']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage20
 //BeginSubPage21
 $__module_subpage['21']['admin'] = "";
 $__module_subpage['21']['group'] = 0;
 $__module_subpage['21']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='21' && file_exists($__MDL_ROOT . "/tpl/subpage_21.tpl")){
	include $__MDL_ROOT . "/php/subpage_21.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_21.tpl";
	$__module_subpage['21']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage21
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}