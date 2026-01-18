<?php
function module_marketing_blog($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/marketing_blog';
 else $__MDL_URL = 'modules/marketing_blog';
 $__MDL_ROOT = dirname(__FILE__).'/marketing_blog';
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
 //  перенаправление на подробный просмотр
     $blogpage = (($section->parametrs->param6!='tabs') && ($section->parametrs->param6!='text')) ? 'text' : strval($section->parametrs->param6);
     $__data->setVirtualPage($_page, 'blog'.$blogpage);
 
 //  установка для модуля поиска
     $__data->setVirtualPage('Y', 'blogSearch');
     
 //  загрузка картинок
     $verifyToken = md5('unique_salt' . getRequest('timestamp',3));
     if (isRequest('ajax_'.$section->id.'_uploadfile') && (getRequest('token',3) == $verifyToken)) {  
         //  проверка content/type
         $imageinfo = getimagesize($_FILES['Filedata']['tmp_name']);
         $mime = array('image/gif', 'image/jpeg', 'image/png');
         if (!in_array($imageinfo['mime'],$mime)) {
             echo "error1";
             exit;
         }
         
         //  проверка по расширению
         $ext = end(explode('.',$_FILES['Filedata']['name'])); 
         $filetypes = array('jpg','gif','png','jpeg','JPG', 'GIF', 'PNG', 'JPEG');
         if (!in_array($ext,$filetypes)) {
             echo "error2";
             exit;
         } 
 
         //  проверка по размеру
         if ($_FILES['Filedata']['size'] > (intval($section->parametrs->param14)*1024*1024)) {
             echo "error4";
             exit;        
         }
 
         //  проверка кто пришел
         $id = getRequest('refferer',1);
         if ($id == -1) {
             $user_folder = 'admin';
         } else if ($id > 0) {
             $per = new seTable('se_user');
             $per->find($id);
             $per->fetchOne();
             if ($per->id) {
                 $username = getTranslitName($per->username);
                 $user_folder = strtolower($username);
             } else {
                 echo 'error5';
                 exit;
             }
         } else {
             echo 'error6';
             exit;
         }
         $uploaddir = personalFolder($user_folder);   
         $fileName = substr(basename($_FILES['Filedata']['name']),0,-4);
         $fileName = getTranslitName($fileName) . '.' . $ext;
         $file = $uploaddir . $fileName; 
         
         //  проверка дубликат названии картинок
         if (file_exists(getcwd() . $file)) {
             $file = substr($file,0,-4);
             $file = $file . '_' . strtotime("now") . '.' . $ext;
         }
         if (file_exists(getcwd() . $file)) {
             echo "error";
             exit;
         }
         
         if (move_uploaded_file($_FILES['Filedata']['tmp_name'], getcwd() . $file)) { 
             ThumbCreate(getcwd() . $file, getcwd() . $file, 's', (int)$section->parametrs->param23);
             echo "success|";
             $foto->num = intval(microtime(true)*100);
             $foto->text = '';
             $foto->alt = '';
             include $__MDL_URL."/tpl/subpage_addphoto.tpl"; 
         } else {
         echo "error";
         }
  
         exit;
     }
 
 //  для filemanager from tinymce
     if (isset($_GET['filemanager'])) {  
         include_once $__MDL_URL.'/editor.php';
          if (seUserGroup() == 3) {
              $user_folder = 'admin';
          } else if (seUserId() > 0) {
              $user_folder = strtolower(seUserLogin());
          }
         new BlogEditor($user_folder);     
      }
 
     if (isset($_GET['getpagelist'])) {
         $pageslist = array();
         foreach ($__data->pages as $page){
             $pagelist[] = array(
                 'title'=>$page->title.' - '.strval($page['name']),
                 'value'=>SE_MULTI_DIR.'/'.strval($page['name']).'/'
             );
         }
         echo json_clear_utf8(json_encode($pagelist));
         exit;
     }
 
 //  удалить картинку информера
     if (isRequest('ajax_delImg')){
         $id = getRequest('id', 1);
         $tlb = new seTable('blog_posts');
         $tlb->find($id);
 
         unlink(getcwd().'/images/blog/'.$tlb->picture);      
     
         $tlb->picture = '';
         $tlb->save();
         exit;    
     }
 
 //  получить форму Оставить комментарий
     if(isRequest('ajax'.$section->id.'_comment_form')){
         $id = getRequest('id', 1);
         $user_id = seUserId();
         $cid = session_id();
         $isCapcha = ($section->parametrs->param9=='Y') ? 1 : 0;
         include $__MDL_URL."/php/subpage_comment.php";
         include $__MDL_URL."/tpl/subpage_comment.tpl";
         exit();
     }
 
 //  отобразить форму Оставить комментарий при комментировании другого коммента
     if(isRequest('ajax'.$section->id.'_show_comment')){
         $id = getRequest('com', 1);
         $user_id = seUserId();
         $cid = session_id();
         $isCapcha = ($section->parametrs->param9=='Y') ? 1 : 0;
         include $__MDL_URL."/php/subpage_comment.php";
         include $__MDL_URL."/tpl/subpage_comment.tpl";
         exit();
     }
     
 //  основная информация
     blogUpdate();
     $lang = (SE_DIR == '') ? se_getLang() : substr(SE_DIR,0,-1);
     $mlang = (SE_DIR == '') ? substr($lang, 0, 2) : 'ru'; //  а это значение для формочки ввода текста
     $user_id = intval(seUserId());
     $user_group = intval(seUserGroup());
     $group_num = intval($section->parametrs->param3);
     $multidir = seMultiDir();
     $_page = $__data->getPageName(); 
     $redirect = $multidir.'/'.$_page.'/?'.time();
     $thisPageLink = $multidir.'/'.$__data->getVirtualPage('blog'.$blogpage).'/';  //  перенаправление на подробное описание
 //    $thisPageLink = $multidir.'/'.$_page.'/';
     $count_char = (int)$section->parametrs->param5;
     $min_len_text = (int)$section->parametrs->param15;
     $max_len_text = (int)$section->parametrs->param16;
     $min_count_foto = (int)$section->parametrs->param17;
     $max_count_foto = (int)$section->parametrs->param18;
     unset($_SESSION['editor_images_access']);                                   //  очень нужно для редактирования поста
     $newbbs_add_link = intval($user_group >= $group_num);                       //  разрешение на добавление нового поста
 
     $bloggerAccess = bloggerAccess($section,$user_group,$user_id);
     
 //  Вызов окна настройки поста
     if(isRequest('ajax'.$section->id.'_modal')) {
         if($user_group == 3){
             $postId = getRequest('id', 1);
             if ($postId) {
                 include $__MDL_ROOT . "/php/subpage_setting.php";
                 include $__MDL_ROOT . "/tpl/subpage_setting.tpl";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  рейтинг поста
     if(isRequest('ajax'.$section->id.'_post_rate')) {
         if($user_id > 0){
             $post = getRequest('postid', 1);
             $rates = new seTable('blog_post_rating');
             $rates->select('id, rating');
             $rates->where("`id_user`=?", $user_id);
             $rates->andwhere("`id_post`=?", $post);
             $rates->fetchOne();
             if(!$rates->id || ($rates->rating == 0)){
                 $rates->id_post = $post;
                 $rates->id_user = $user_id;
                 $rates->rating = getRequest('rate', 1);
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  изменить рейтинг поста    
     if(isRequest('ajax_post_changeRate')) {
         if($user_group == 3){
             $post = getRequest('postid', 1);
             $rates = new seTable('blog_posts');
             $rates->find($post);
             if($rates->id){
                 $rates->rating_correction = (int)$rates->rating_correction + getRequest('correction', 1);
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  рейтинг комментария
     if(isRequest('ajax'.$section->id.'_comment_rate')) {
         if($user_id > 0){
             $com = getRequest('com', 1);
             $posts = getRequest('postid', 1);
             $rates = new seTable('blog_comment_rating');
             $rates->select('id, rating');
             $rates->where("`id_user`=?", $user_id);
             $rates->andwhere("`id_comment`=?", $com);
             $rates->andwhere("`id_post`=?", $posts); 
             $rates->fetchOne();
             if(!$rates->id || ($rates->rating == 0)){
                 $rates->id_post = $posts;
                 $rates->id_user = $user_id;
                 $rates->id_comment = $com;
                 $rates->rating = getRequest('rate', 1);
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
     
 //  изменить рейтинг комментария
     if(isRequest('ajax_comment_changeRate')) {
         if($user_group == 3){
             $com = getRequest('postid', 1);
             $rates = new seTable('blog_comments');
             $rates->select('id, rating_correction');
             $rates->where("`id`=?", $com);
             $rates->fetchOne();
             if($rates->id){
                 $rates->rating_correction = (int)$rates->rating_correction + getRequest('correction', 1);
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  добавить в избранное (закладки)
     if(isRequest('ajax'.$section->id.'_post_favorite')) {
         if($user_id > 0){
             $posts = getRequest('postid', 1);
             $rates = new seTable('blog_post_rating');
             $rates->select('id, favorite');
             $rates->where("`id_user`=?", $user_id);
             $rates->andwhere("`id_post`=?", $posts);  
             $rates->fetchOne();
             if(!$rates->id || ($rates->favorite == 0)){
                 $rates->id_post = $posts;
                 $rates->id_user = $user_id;
                 $rates->favorite = 1;
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  убрать из избранного (закладок)
     if(isRequest('ajax'.$section->id.'_post_unfavorite')) {
         if($user_id > 0){
             $posts = getRequest('postid', 1);
             $rates = new seTable('blog_post_rating');
             $rates->select('id, favorite');
             $rates->where("`id_user`=?", $user_id);
             $rates->andwhere("`id_post`=?", $posts); 
             $rates->fetchOne();
             if($rates->favorite == 1){
                 $rates->id_post = $posts;
                 $rates->id_user = $user_id;
                 $rates->favorite = 0;
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 //  подписать на блоггера
     if(isRequest('ajax'.$section->id.'_post_setsubsribe')) {
         $user_id = seUserId();
         if($user_id > 0){
             $blogger = getRequest('uid', 1);
             $subscribe = new seTable('blog_user_subscribe');
             $subscribe->select('id');
             $subscribe->where("`id_user`=?", $user_id);
             $subscribe->andwhere("`id_blogger`=?", $blogger);
             $subscribe->fetchOne();
             if(!$subscribe->id){
                 $subscribe->id_blogger = $blogger;
                 $subscribe->id_user = $user_id;
                 $subscribe->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
 
 // просмотр поста 
     if (isRequest('post')) {
         $postname = urldecode(getRequest('post', 3));
         $__data->goSubName($section, 'show');
     }
 
 
 //  изменить кол-во просмотра поста    
     if(isRequest('ajax_post_changeView')) {
         if($user_group == 3){
             $post = getRequest('postid', 1);
             $rates = new seTable('blog_posts');
             $rates->find($post);
             if($rates->id){
                 $rates->views = getRequest('correction', 1);
                 $rates->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
     }
     
 //  изменить статус записи (visible)
     if(isRequest('ajax'.$section->id.'_switchButton')) {
         if (($bloggerAccess[0] == 'admin') || ($bloggerAccess[0] == 'moderator')) {
             $post = getRequest('postid', 1);
             $action = getRequest('action', 3);
             $action = ($action == 'on') ? 'Y' : 'N';
             $switcher = new seTable('blog_posts');
             $switcher->find($post);
             if($switcher->id){
                 $switcher->visible = $action;
                 $switcher->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
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
 //BeginSubPageblog
 $__module_subpage['blog']['admin'] = "";
 $__module_subpage['blog']['group'] = 0;
 $__module_subpage['blog']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='blog' && file_exists($__MDL_ROOT . "/tpl/subpage_blog.tpl")){
	include $__MDL_ROOT . "/php/subpage_blog.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_blog");
	$__module_subpage['blog']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageblog
 //BeginSubPageedittext
 $__module_subpage['edittext']['admin'] = "";
 $__module_subpage['edittext']['group'] = 0;
 $__module_subpage['edittext']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='edittext' && file_exists($__MDL_ROOT . "/tpl/subpage_edittext.tpl")){
	include $__MDL_ROOT . "/php/subpage_edittext.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_edittext");
	$__module_subpage['edittext']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageedittext
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
 //BeginSubPageinfo
 $__module_subpage['info']['admin'] = "";
 $__module_subpage['info']['group'] = 0;
 $__module_subpage['info']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='info' && file_exists($__MDL_ROOT . "/tpl/subpage_info.tpl")){
	include $__MDL_ROOT . "/php/subpage_info.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_info");
	$__module_subpage['info']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageinfo
 //BeginSubPagegoods
 $__module_subpage['goods']['admin'] = "";
 $__module_subpage['goods']['group'] = 0;
 $__module_subpage['goods']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='goods' && file_exists($__MDL_ROOT . "/tpl/subpage_goods.tpl")){
	include $__MDL_ROOT . "/php/subpage_goods.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_goods");
	$__module_subpage['goods']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegoods
 //BeginSubPagesetting
 $__module_subpage['setting']['admin'] = "";
 $__module_subpage['setting']['group'] = 0;
 $__module_subpage['setting']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='setting' && file_exists($__MDL_ROOT . "/tpl/subpage_setting.tpl")){
	include $__MDL_ROOT . "/php/subpage_setting.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_setting");
	$__module_subpage['setting']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesetting
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
 //BeginSubPageaddphoto
 $__module_subpage['addphoto']['admin'] = "";
 $__module_subpage['addphoto']['group'] = 0;
 $__module_subpage['addphoto']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='addphoto' && file_exists($__MDL_ROOT . "/tpl/subpage_addphoto.tpl")){
	include $__MDL_ROOT . "/php/subpage_addphoto.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_addphoto");
	$__module_subpage['addphoto']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageaddphoto
 //BeginSubPageaddvideo
 $__module_subpage['addvideo']['admin'] = "";
 $__module_subpage['addvideo']['group'] = 0;
 $__module_subpage['addvideo']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='addvideo' && file_exists($__MDL_ROOT . "/tpl/subpage_addvideo.tpl")){
	include $__MDL_ROOT . "/php/subpage_addvideo.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_addvideo");
	$__module_subpage['addvideo']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageaddvideo
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}