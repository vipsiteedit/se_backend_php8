<?php
function module_mblog_posts($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mblog_posts';
 else $__MDL_URL = 'modules/mblog_posts';
 $__MDL_ROOT = dirname(__FILE__).'/mblog_posts';
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
 //echo $_SERVER['DOCUMENT_ROOT'];
 dbUpdate();
 $group_num = intval($section->parametrs->param15);
 //$lang = substr(SE_DIR, 0, strlen(SE_DIR) - 1); // заносим в переменную подсайт многосайтового
 //if ($lang == '') { 
 $lang = se_getLang();
 //}
 //se_db_query("UPDATE se_blog_posts SET lang = '$lang'");
 $mlang = substr($lang, 0, 2);//а это значение для формочки ввода текста там 2 символа всего Серега сказал что оно всегда будет совподать, с наlим хоть я и спорил с ним.
 $user_id = seUserId();
 $user_group = seUserGroup();
 $multidir = seMultiDir();
 $_page = $__data->getPageName(); 
 // Проверка не модер ли нас посетил который прописан в параметрах
 $flagmoders = 0; 
 $moderi = $section->parametrs->param2;
 $moders = explode(",", $moderi);
 for ($j = 0; $j < count($moders); $j++) {
     $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
     if (($userlogin == seUserLogin()) && (seUserLogin() != "") or ($user_group == 3)) {
         $flagmoders = 1;
     }
 }
 // конец проверки на модера 
 if ($flagmoders == 1) {
     $_SESSION['editor_images_access'] = 1; 
 }  
 $month_arr = explode(',', $section->language->lang001);
 if (isRequest('post')) {
     $postname = getRequest('post');
     $__data->goSubName($section, 'show'); // На просмотр поста 
 }
 
 if (isRequest('addpost')) {
     $id = 0;
     $__data->goSubName($section, 'edit'); // На просмотр поста 
 }
 
 if (isRequest('editpost')) {
     $id = getRequest('editpost', 1);
     $__data->goSubName($section, 'edit'); // На просмотр поста 
 }
 
 
 if (isRequest('showlink')) {
     include $__MDL_ROOT . "/php/subpage_comment.php";
     include $__MDL_ROOT . "/tpl/subpage_comment.tpl";
     exit();
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
 //BeginSubPageshow
 $__module_subpage['show']['admin'] = "";
 $__module_subpage['show']['group'] = 0;
 $__module_subpage['show']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='show' && file_exists($__MDL_ROOT . "/tpl/subpage_show.tpl")){
	include $__MDL_ROOT . "/php/subpage_show.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_show");
	$__module_subpage['show']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageshow
 //BeginSubPageedit
 $__module_subpage['edit']['admin'] = "";
 $__module_subpage['edit']['group'] = 0;
 $__module_subpage['edit']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='edit' && file_exists($__MDL_ROOT . "/tpl/subpage_edit.tpl")){
	include $__MDL_ROOT . "/php/subpage_edit.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_edit");
	$__module_subpage['edit']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageedit
 //BeginSubPageaction
 $__module_subpage['action']['admin'] = "";
 $__module_subpage['action']['group'] = 0;
 $__module_subpage['action']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='action' && file_exists($__MDL_ROOT . "/tpl/subpage_action.tpl")){
	include $__MDL_ROOT . "/php/subpage_action.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_action");
	$__module_subpage['action']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageaction
 //BeginSubPagecomment
 $__module_subpage['comment']['admin'] = "";
 $__module_subpage['comment']['group'] = 0;
 $__module_subpage['comment']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='comment' && file_exists($__MDL_ROOT . "/tpl/subpage_comment.tpl")){
	include $__MDL_ROOT . "/php/subpage_comment.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_comment");
	$__module_subpage['comment']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagecomment
 //BeginSubPagescripts
 $__module_subpage['scripts']['admin'] = "";
 $__module_subpage['scripts']['group'] = 0;
 $__module_subpage['scripts']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='scripts' && file_exists($__MDL_ROOT . "/tpl/subpage_scripts.tpl")){
	include $__MDL_ROOT . "/php/subpage_scripts.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_scripts");
	$__module_subpage['scripts']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagescripts
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}