<?php
function module_mshop_vitrine_big51($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_vitrine_big51';
 else $__MDL_URL = 'modules/mshop_vitrine_big51';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_vitrine_big51';
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
 $user_group = seUserGroup();
 $user_id = seUserId();
 //виртуальная страница
 $__data->setVirtualPage($__data->getPageName(), 'shop_vitrine', ($section->parametrs->param324 == 'Y'));
 $virtualpage = $__data->getVirtualPage('shop_vitrine');
 if ($__data->getPageName() != $virtualpage && isRequest('show')) {
    $__data->go301(seMultiDir() . '/' . $virtualpage . '/show/' . getRequest('show').'/');
 }
 
 //устанавливает какая витрина стоит (для модуля Каталог групп интернет-магазина)
 $__data->setVirtualPage('Y', 'choose_vitrine_'.$_page);
 $__data->setVirtualPage($section->parametrs->param273, 'choose_group_'.$_page);
 
 if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
     $price_type = 1;
 } 
 elseif ($section->parametrs->param224 == seUserGroupName()) { // optovik  
     $price_type = 2;
 } 
 else {                          
     $price_type = 0;
 } 
 
 $rounded = ($section->parametrs->param243 == 'Y');
 
 $separator = ($section->parametrs->param276 == 'Y') ? ' ' : '';
 
 // Добавление в корзину (shopcart) без ajax
 if (isRequest('addcart') && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {       
     $shopcart = new plugin_ShopCart();
     $shopcart->addCart();                                  
     
     // Для обновления информера корзины перезагружаем страницу
     if ($section->parametrs->param85 == 'Y')                                                                
         $__data->go301(seMultiDir() . '/' . $section->parametrs->param50 . '/?' . time());        
     else                                                                                 
         //header("Location: " . $_SERVER['REQUEST_URI'] . '?' . time());
         $__data->go301($_SERVER['REQUEST_URI']);
     exit();
 } 
 
 //инициализация плагина  
 $psg = new plugin_shopgoods($virtualpage, '', trim($section->parametrs->param273));
 
 if ((isRequest('ajax'.$razdel)) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
     $response = array();
     if (isRequest('param')) {
         $param = getRequest('param', 1);
         $value = getRequest('value', 1);
         $id_price = getRequest('id_price', 1);
         $group = getRequest('group', 1);
         $response = array();
     
         $plugin_modifications = new plugin_shopmodifications($id_price, $section->parametrs->param318 == 'Y');
         $response['params'] = $plugin_modifications->changeModifications($group, $param, $value);
         $response['type'] = (string)$section->parametrs->param310;
         $selected = !empty($_SESSION['modifications'][$id_price]) ? $_SESSION['modifications'][$id_price] : '';    
     
         $plugin_amount = new plugin_shopamount($id_price, '', $price_type, 1, $selected);
     
         $response['count'] = (string)$plugin_amount->getPresenceCount();
         $response['presence'] = (string)$plugin_amount->showPresenceCount($section->language->lang021, $section->language->lang020);
         $response['price'] = array(
             'new' => $plugin_amount->showPrice(true, $rounded, $separator),
             'old' => $plugin_amount->showPrice(false, $rounded, $separator)
         );
         //картинки выбранных модификаций
         if ((string)$section->parametrs->param313 == 'Y')
             $response['images'] = $plugin_modifications->getImages($selected);
     }
     elseif (isRequest('review')) {
         $id_price = $psg->getGoodsId();
         if ($id_price && $user_id) {
             if ($psg->saveReview(getRequest('review', 4), $id_price, $user_id)) {
                 $response['success'] = 'Отзыв успешно добавлен.';
                 $response['count'] = $psg->getCountReviews($id_price);
             }
         } 
     }
     elseif (isRequest('vote')) {
         $id_review = getRequest('id', 1);
         if (!empty($id_review) && !empty($user_id)) {
             $vote = getRequest('vote', 1);
             if ($likes = $psg->voteReview($id_review, $user_id, $vote)) {
                 $response = $likes;
                 $response['success'] = true;
             } 
         }                  
     }
     elseif (isRequest('get_reviews')) {
         $id_price = $psg->getGoodsId();
         $sort = isRequest('reviews_sort') ? getRequest('reviews_sort') : (string)$section->parametrs->param322;
         $offset = getRequest('offset', 1);
         $count = isRequest('all') ? -1 : (int)$section->parametrs->param321;
         $review_list = $psg->getGoodsReviews($id_price, $offset, $count, $sort, isRequest('asc'));
         if (!empty($review_list)) {
             ob_start();
             include $__MDL_ROOT . "/php/subpage_reviewslist.php";
             include $__MDL_ROOT . "/tpl/subpage_reviewslist.tpl";
             $response['reviews'] = ob_get_contents();
             ob_end_clean();
             $response['count'] = (int)$psg->getCountReviews($id_price);
             //$response['sort'] = $sort;
             $response['offset'] = $offset + count($review_list);
             $response['direction'] = isRequest('asc') ? '<i class="asc">↑</i>' : '<i class="desc">↓</i>';
         }
     }
     echo json_encode($response);
     exit();        
 }
  
 //текст подвала
 $footer_text = $psg->footertext;
                                 
 $analoggood = '';   //нужно для определения осн. картинки от аналог. и сопут. товаров
 
 // Получена команда показать выбранный товар
 if (isRequest('show')) {           
     $show = urldecode(getRequest('show', 3));
     $pr = new seShopPrice();
     $pr->select('id, enabled');
     $pr->where("code = '?'", $show);
     $pr->fetchOne();
     //проверка на существование и на статус активный товара
     if(($pr->enabled == 'N') || (!$pr->enabled)){
         $__data->go404();
     }
     else {
         $__data->goSubName($section, 'show');
         $viewgoods = $pr->id;
     }          
 }
 
 if (isRequest('typetable')) {
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = 't';
 }   
 elseif (isRequest('typevitrine')) {
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = 'v';
 }   
 
 if (!empty($_SESSION['SHOP_VITRINE'.$_page.$razdel]['type']))
     $section->parametrs->param184 = strval($_SESSION['SHOP_VITRINE'.$_page.$razdel]['type']);
 else  
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = strval($section->parametrs->param184);
 
 if (isRequest('shopcatgr')) {
     $shopcatgr = getRequest('shopcatgr', 1);
     if ($shopcatgr) {
         $tbl = new seTable("shop_group", "sg");
         $tbl->select('code_gr');
         $tbl->find($shopcatgr);
         $catgr = $tbl->code_gr;
         header('Location: '.seMultiDir().'/'.$_page.'/cat/'.urlencode($catgr).'/');
     } else {
         $new_pa = seMultiDir().'/'.$_page.'/';
         $__data->go301($new_pa);
     }
     exit();
 } else {
     $tbl = new seTable('shop_group', 'sg');
     $tbl->where("sg.code_gr = '?'", urldecode(getRequest('cat', 3)));
     $tbl->fetchOne();
     $shopcatgr = $tbl->id;
     unset($tbl);
 } 
                                                    
 if (isRequest('GoToComment') && $user_group) {
     $error_comm_message = "";
     $comm_note = trim(getRequest('comm_note', 3));
     if ($comm_note == '') {
         $error_comm_message = "<br><span class=\"errorcomment\">{$section->language->lang039}</span>"; 
     }
     if (empty($error_comm_message)) {
         $psg->saveGoodsComment($viewgoods, $comm_note, $section->language->lang040);              //сохранить комментари в БД
         header("Location: " . seMultiDir() . "/$_page/show/{$show}/#comment");
         exit();
     } 
 }
 
 $nchar = intval($section->parametrs->param237);
 if (!$nchar) {
     $nchar = 60;
 }                   

   $section->objectcount = intval($section->parametrs->param64);
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
 //BeginSubPageanalogs
 $__module_subpage['analogs']['admin'] = "";
 $__module_subpage['analogs']['group'] = 0;
 $__module_subpage['analogs']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='analogs' && file_exists($__MDL_ROOT . "/tpl/subpage_analogs.tpl")){
	include $__MDL_ROOT . "/php/subpage_analogs.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_analogs");
	$__module_subpage['analogs']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageanalogs
 //BeginSubPageaccomp
 $__module_subpage['accomp']['admin'] = "";
 $__module_subpage['accomp']['group'] = 0;
 $__module_subpage['accomp']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='accomp' && file_exists($__MDL_ROOT . "/tpl/subpage_accomp.tpl")){
	include $__MDL_ROOT . "/php/subpage_accomp.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_accomp");
	$__module_subpage['accomp']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageaccomp
 //BeginSubPagegoodlist
 $__module_subpage['goodlist']['admin'] = "";
 $__module_subpage['goodlist']['group'] = 0;
 $__module_subpage['goodlist']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='goodlist' && file_exists($__MDL_ROOT . "/tpl/subpage_goodlist.tpl")){
	include $__MDL_ROOT . "/php/subpage_goodlist.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_goodlist");
	$__module_subpage['goodlist']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagegoodlist
 //BeginSubPagemodifications
 $__module_subpage['modifications']['admin'] = "";
 $__module_subpage['modifications']['group'] = 0;
 $__module_subpage['modifications']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='modifications' && file_exists($__MDL_ROOT . "/tpl/subpage_modifications.tpl")){
	include $__MDL_ROOT . "/php/subpage_modifications.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_modifications");
	$__module_subpage['modifications']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemodifications
 //BeginSubPagefeatures
 $__module_subpage['features']['admin'] = "";
 $__module_subpage['features']['group'] = 0;
 $__module_subpage['features']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='features' && file_exists($__MDL_ROOT . "/tpl/subpage_features.tpl")){
	include $__MDL_ROOT . "/php/subpage_features.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_features");
	$__module_subpage['features']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagefeatures
 //BeginSubPagereviews
 $__module_subpage['reviews']['admin'] = "";
 $__module_subpage['reviews']['group'] = 0;
 $__module_subpage['reviews']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='reviews' && file_exists($__MDL_ROOT . "/tpl/subpage_reviews.tpl")){
	include $__MDL_ROOT . "/php/subpage_reviews.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_reviews");
	$__module_subpage['reviews']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagereviews
 //BeginSubPagespecial
 $__module_subpage['special']['admin'] = "";
 $__module_subpage['special']['group'] = 0;
 $__module_subpage['special']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='special' && file_exists($__MDL_ROOT . "/tpl/subpage_special.tpl")){
	include $__MDL_ROOT . "/php/subpage_special.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_special");
	$__module_subpage['special']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagespecial
 //BeginSubPagereviewslist
 $__module_subpage['reviewslist']['admin'] = "";
 $__module_subpage['reviewslist']['group'] = 0;
 $__module_subpage['reviewslist']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='reviewslist' && file_exists($__MDL_ROOT . "/tpl/subpage_reviewslist.tpl")){
	include $__MDL_ROOT . "/php/subpage_reviewslist.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_reviewslist");
	$__module_subpage['reviewslist']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagereviewslist
 //BeginSubPagetext
 $__module_subpage['text']['admin'] = "";
 $__module_subpage['text']['group'] = 0;
 $__module_subpage['text']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='text' && file_exists($__MDL_ROOT . "/tpl/subpage_text.tpl")){
	include $__MDL_ROOT . "/php/subpage_text.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_text");
	$__module_subpage['text']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagetext
 //BeginSubPagevitrine
 $__module_subpage['vitrine']['admin'] = "";
 $__module_subpage['vitrine']['group'] = 0;
 $__module_subpage['vitrine']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='vitrine' && file_exists($__MDL_ROOT . "/tpl/subpage_vitrine.tpl")){
	include $__MDL_ROOT . "/php/subpage_vitrine.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_vitrine");
	$__module_subpage['vitrine']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagevitrine
 //BeginSubPagetable
 $__module_subpage['table']['admin'] = "";
 $__module_subpage['table']['group'] = 0;
 $__module_subpage['table']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='table' && file_exists($__MDL_ROOT . "/tpl/subpage_table.tpl")){
	include $__MDL_ROOT . "/php/subpage_table.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_table");
	$__module_subpage['table']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagetable
 //BeginSubPagesocial
 $__module_subpage['social']['admin'] = "";
 $__module_subpage['social']['group'] = 0;
 $__module_subpage['social']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='social' && file_exists($__MDL_ROOT . "/tpl/subpage_social.tpl")){
	include $__MDL_ROOT . "/php/subpage_social.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_social");
	$__module_subpage['social']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesocial
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}