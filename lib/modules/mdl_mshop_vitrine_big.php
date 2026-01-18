<?php
function module_mshop_vitrine_big($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_vitrine_big';
 else $__MDL_URL = 'modules/mshop_vitrine_big';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_vitrine_big';
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
 //виртуальная страница
 if ($section->parametrs->param306 == 'Y') {
   $__data->setVirtualPage($_page, 'shop_vitrine', true);
   $virtual_page = $_page;
 } else {
     $virtual_page = $__data->getVirtualPage('shop_vitrine');
     if (empty($virtual_page)) {
         $virtual_page = $_page;
         $__data->setVirtualPage($_page, 'shop_vitrine');
     }
 }
 
 // устанавливает какая витрина стоит (для модуля Каталог групп интернет-магазина)
 $__data->setVirtualPage('Y', 'choose_vitrine_'.$_page);
 
 //для сравнения товаров
 $forcompare = urlencode($_SERVER['REQUEST_URI']);
 
 // Флаг старого формата
 $old_format = intval(isset($section->parametrs->param108) || ($section->parametrs->param259 == 'N')); 
 
 //инициализация плагина
 $psg = new plugin_shopgoods40($_page,'', trim($section->parametrs->param273), $old_format);
 
 //текст подвала
 $footer_text = $psg->footertext;
 
 //текущая валюта сайта
 $pricemoney = $psg->pricemoney; 
 
 $analoggood = '';       //нужно для определения осн. картинки от аналог. и сопут. товаров
     
 $lang = se_getlang();                 //временно
 $accessuser = (seUserGroup() > 0);    //в HTML
 
 // ########### Добавление в корзину (shopcart) без ajax
 if (isRequest('addcart') && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {       
     $shopcart = new plugin_ShopCart40();
     // Принудительно записываем парамеры в класс
     // Также допускаются использовать 'id' - ID товара и  count - число товаров
     $keycard = getRequest('addcart',1);
     $shopcart->addCart(array('param'=>$_SESSION['SHOP_VITRINE']['selected'][$keycard]));   
     // Кладем в корзину                                       
     // Для обновления информера корзины перезагружаем страницу
     if ($section->parametrs->param85 == 'Y') {                                                                 
         if (strpos($section->parametrs->param50, "://") === false) {                                           
             header("Location: " . seMultiDir() . "/".$section->parametrs->param50."/?".time());
         } else {                                                                              
             $idfile = md5(session_id() . $_SERVER['HTTP_HOST']);
             $shopcart->savesession();
             header("Location: " . seMultiDir() . "/".$section->parametrs->param50."?idcart=" . $idfile . '&' . time());
         }
     } else {                                                                                  
         header("Location: " . $_SERVER['REQUEST_URI'] . '?' . time());
     }
     exit();
 }
 
 //сравнение товаров
 if (isRequest('ajax_compare')) {
     if (getRequest('compare') == 'on') {
         $find = false;
         foreach ($_SESSION['SHOPVITRINE']['COMPARE'] as $id => $line) {
             if ($line == getRequest('idprice', 1)) {
                 $find = true; 
                 break;
             }
         }
         if ($find==false) {
             $_SESSION['SHOPVITRINE']['COMPARE'][] = getRequest('idprice', 1);
         }
     }
     if (getRequest('compare') == 'off') {
         foreach ($_SESSION['SHOPVITRINE']['COMPARE'] as $id => $line) {
             if (intval($line) == getRequest('idprice', 1)) {
                 unset($_SESSION['SHOPVITRINE']['COMPARE'][$id]);
             }
         }
 //        header("Location: ".$_SERVER['REQUEST_URI']);
 //        exit();
     }   
     $url = urldecode(getRequest('pages', 3));
     header("Location: ".$url);
     exit;
 }
 
 // Старый режим                      
 // Получена команда показать выбранный товар
 if (isRequest('viewgoods')) {           
     $__data->goSubName($section, 'show');
     $viewgoods = getRequest('viewgoods', 3);
     if ($old_format) {
         $pr = new seShopPrice();
         $pr->select('code, enabled');
         $pr->find($viewgoods);
         //проверка на существование и на статус активный товара
         if(($pr->enabled == 'N') || (!$pr->enabled)){
             Header('404 Not Found', true, 404);
             exit();
         }
         header("HTTP/1.1 301 Moved Permanently"); 
         header("Location: ".seMultiDir().'/'.$_page.'/show/'.urlencode($pr->code).'/');
         exit;
     }
 }
 // Новый режим
 // Получена команда показать выбранный товар
 if (isRequest('show')) {           
     $__data->goSubName($section, 'show');
     $show = urldecode(getRequest('show', 3));
     $pr = new seShopPrice();
     $pr->select('id, enabled');
     $pr->where("code = '?'", $show);
     $pr->fetchOne();
     //проверка на существование и на статус активный товара
     if(($pr->enabled == 'N') || (!$pr->enabled)){
         $__data->go404();
     }
     $viewgoods = $pr->id;          
 }
 
 //голосование
 if (isRequest('ajax'.$razdel)) {
     //запрос на голосование
     if (isRequest('initvote')) {  
         echo intval($psg->GoodsVotes($viewgoods));      //получить голос
         exit();
     }
     $vote = getRequest('vote');
     if (seUserGroup() == 0) {
         echo 'err_auth'; // Пользователь не авторизовался
         exit();
     } else if (empty($_SESSION['VOTED'][$viewgoods])) {  
         if (!empty($vote)) {                   
                echo $psg->GoodsVotes($viewgoods,$vote);     //сохранить голос и возвратить текущий рейтинг 
             exit();       
         }
     } else {
         echo 'err_double_vote'; // Попытка повторного голосования    
     }
     exit();
 }
 
 //изменился параметр
 if (isRequest('jquery' . $razdel)) {
     if (isRequest('idprice')) {
         $price_id = getRequest('idprice', 1);
         $this_id = getRequest('value', 1); 
         $i_param = getRequest('iparam', 1); 
         $i_select = floor($i_param / 100); 
         $type = getRequest('ttypes', 1);
         $shopdiscount = new plugin_shopDiscount40($price_id);   
         $discountproc = $shopdiscount->execute();    
     
         $price = new seShopPrice();
         $price->select('id, price, price_opt, price_opt_corp, presence_count, presence, curr, special_price, discount, measure, img, img_alt'); 
         $goods = $price->find($price_id);          
         $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select] = $this_id; // Для сохранения состояния
         $prc = 0; 
         $cnt = 0;
         list($sel, $paramprc, $cnt) = getTreeParam($section, 0, $price_id, $price->presence_count, 0, false, $type, $__MDL_URL);
        
          // --- Округление и сепараторы ---
         $rounded = ($section->parametrs->param243 == 'Y'); // округление
 
         if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
             $separator = ' ';
         else
             $separator = '';
                                    
         if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
             $ptype = 1;
             $header = $section->language->lang018;
         } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
             $ptype = 2;
             $header = $section->language->lang019;
         } else {                                    // розничный покупатель
             $ptype = 0;
             $header = $section->language->lang008;
         }
         
        $paramlist = 'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]);                                                                                                                                             
         $plugin_amount = new plugin_shopAmount40($price_id, '', $ptype, 1, 
                                                $paramlist, 
                                                $pricemoney);
         $maxcnt = $plugin_amount->getPresenceCount();      
         $goodsStyle = ($maxcnt != 0);        
         //$cnt = $plugin_amount->showPresenceCount($section->language->lang017);  // param69 - альтернативный текст "Есть"        
         list($cnt, $goodsStyle) = getShopTextCount($section, $goods, $maxcnt);
         
         $realprice = $plugin_amount->showPrice(true, // discounted
                                                $rounded, // округлять ли цену
                                                $separator); // разделять ли пробелами 000 000
         $oldprice = $plugin_amount->showPrice(false, // discounted
                                               $rounded, // округлять ли цену
                                               $separator); // разделять ли пробелами 000 000        
         $res_img = getParamImg($price_id, $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select]);
         //$path_imgall = '/images/'.se_getLang().'/shopimg/';
         //$path_imgmain = '/images/'.se_getLang().'/shopprice/';     
         if (empty($res_img['imgparam'])) {                                         
             if($goods['img']!=''){                             
                 list($res_img['imgparam1'],) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param293), 'w', $section->parametrs->param292); //$path_imgmain.$goods['img'];
                 list($res_img['imgparam_mid'], ) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param286), 'w', $section->parametrs->param293);
                 list($res_img['imgparam_prev'], ) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param286));    
                 if($section->parametrs->param282=='N'){
                     $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam1']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
                 }
                 if($section->parametrs->param282=='L'){
                     $rezult = "<a class=\"cloud-zoom\" href=\"{$res_img['imgparam1']}\" id=\"zoom1\" rel=\"position: 'right', adjustX: 10, adjustY: 0, showTitle: false, zoomWidth: ".$section->parametrs->param284."\">
                     <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\" title=\"{$res_img['imgparam_alt']}\">
                 </a>";
                 }
                 if($section->parametrs->param282=='Z'){
                     $rezult = "<div id=\"photo\">
                     <a id=\"lightbox-foto1\" rel=\"lightbox-foto\" href=\"{$res_img['imgparam1']}\" title=\"{$res_img['imgparam_alt']}\">
                         <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\">
                     </a>
                 </div>";            
                 }
             } else {                                   
                 $is = new plugin_ShopImages40();
                 $res_img['imgparam'] = $res_img['imgparam1'] = $res_img['imgparam_mid'] = $res_img['imgparam_prev'] = $is->getFullFromImage($goods['img']);
                 $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
             }
         //есть доп картинка        
         } else {                                                                       
             list($res_img['imgparam1'],) = $psg->getGoodsImage($res_img['imgparam'], intval($section->parametrs->param293), 'w', $section->parametrs->param292); //$path_imgall.$res_img['imgparam'];     
             list($res_img['imgparam_mid'],) = $psg->getGoodsImage($res_img['imgparam'], intval($section->parametrs->param286), 'w', $section->parametrs->param292); //"/lib/image.php?img={$res_img['imgparam']}&size={$section->parametrs->param286}";  
             list($res_img['imgparam_alt'],) = strip_tags($res_img['imgparam_alt']);
             if($section->parametrs->param282=='N'){
                 $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam1']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
             }
             if($section->parametrs->param282=='L'){
                 $rezult = "<a class=\"cloud-zoom\" href=\"{$res_img['imgparam1']}\" id=\"zoom1\" rel=\"position: 'right', adjustX: 10, adjustY: 0, showTitle: false, zoomWidth: ".$section->parametrs->param284."\">
                     <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\" title=\"{$res_img['imgparam_alt']}\">
                 </a>";
             }
             if($section->parametrs->param282=='Z'){
                 $rezult = "<div id=\"photo\">
                     <a id=\"lightbox-foto1\" rel=\"lightbox-foto\" href=\"{$res_img['imgparam1']}\" title=\"{$res_img['imgparam_alt']}\">
                         <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\">
                     </a>
                 </div>";            
             }
         }
                                                                                  
         echo $sel . '|' . $realprice . '|' . $cnt . '|' . $oldprice . '|' . $goodsStyle .'|'. 
              $res_img['imgparam1'] .'|'. $res_img['imgparam_mid'] .'|'. $res_img['imgparam_prev'] .'|'. 
              $res_img['imgparam_alt'] .'|'. $rezult;                
         unset($plugin_amount);
     }
     exit();
 }   
 
 // Создаем таблицы
 createtables($__MDL_ROOT);
 
 unset($_SESSION['shop_vitrine']['all_search']['search_live_group']);
 
 if (isRequest('correct')){
     $price = new seTable('shop_price');
     $price->addField('price_opt_corp', 'double(0,2)');
     unset($price);
 }   
 
 if (isRequest('typetable')) {
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = 't';
 }   
 
 if (isRequest('typevitrine')) {
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = 'v';
 }   
 /*if (!empty($_SESSION['SHOP_VITRINE']['type'])) {
     $section->parametrs->param184 = $_SESSION['SHOP_VITRINE']['type'];
 } else {
     $_SESSION['SHOP_VITRINE']['type'] = $section->parametrs->param184;
 } */ 
 
 if (!empty($_SESSION['SHOP_VITRINE'.$_page.$razdel]['type']))
     $section->parametrs->param184 = strval($_SESSION['SHOP_VITRINE'.$_page.$razdel]['type']);
 else  
     $_SESSION['SHOP_VITRINE'.$_page.$razdel]['type'] = strval($section->parametrs->param184);
 
 if (isRequest('shopsearch')) {
     // $_SESSION['shop_vitrine']['all_search']['search_live_group'] = getRequest('shopsearch', 3);
     $_SESSION['shop_vitrine']['all_search']['search_live_group'] = getRequest('shopsearch', 3); 
     unset($_SESSION['shop_vitrine']['all_search']['name']);
     $_SESSION['catalogsrh']['invitrine'] = 0;
 }
 
 
 if (isRequest('shopcatgr')) {
     $shopcatgr = getRequest('shopcatgr', 1);
     if (!$old_format) {
         if ($shopcatgr) {
             $tbl = new seTable("shop_group", "sg");
             $tbl->select('code_gr');
             $tbl->find($shopcatgr);
             $catgr = $tbl->code_gr;
             header('Location: '.seMultiDir().'/'.$_page.'/cat/'.$catgr.'/');
         } else {
             //header('Location: '.seMultiDir() . "/$_page/");
             $new_pa = seMultiDir().'/'.$_page.'/';
             $__data->go301($new_pa);
         }
         exit();
     }
 } else {
     $tbl = new seTable('shop_group', 'sg');
     $tbl->where("sg.code_gr = '" . getRequest('cat', 3) . "'");
     $tbl->fetchOne();
     $shopcatgr = $tbl->id;
     unset($tbl);
 }  
 
 list(,$rpagen) = explode('://', $_SERVER['HTTP_REFERER']);
     $rpagen = explode('/', $rpagen);
     if (seMultiDir() != ''){
       $rpagen = $rpagen[2];
     }else{ $rpagen = $rpagen[1];}
     if($rpagen != $_SESSION['page_razdel'] && $rpagen != ""){
         $_SESSION['inrazdel'] = 0;
     }elseif($rpagen != $_SESSION['page_razdel'] && $rpagen == "" && $_SESSION['page_razdel']!="home"){
         $_SESSION['inrazdel'] = 0;
     } 
 
 if(!is_referer_vitrine($_page)){
    if($_SESSION['inrazdel']==0){ 
         unset($_SESSION['shop_vitrine']['all_search']);
     } 
 } 
 
 if (!empty($_SESSION['shop_vitrine']['all_search']['search_live_group'])) {
     $_SESSION['catalogsrh']['invitrine'] = 0;
     $_SESSION['SHOP_VITRINE']['CURGROUP'] = $shopcatgr;
 } else if ($shopcatgr != $_SESSION['SHOP_VITRINE']['VCURGROUP']) {
     $_SESSION['SHOP_VITRINE']['VCURGROUP'] = $shopcatgr;
     $_SESSION['catalogsrh']['invitrine'] = 0;
     unset($_SESSION['shop_vitrine']['from_group']);
     if($_SESSION['inrazdel']!=1 && !empty($_SESSION['SHOP_VITRINE']['VCURGROUP'])){
         unset($_SESSION['shop_vitrine']['all_search'], $_SESSION['shop_vitrine']['param_val'], $_SESSION['shop_vitrine']['man_val']);
     }
 } 
 
 if(!empty($_SESSION['shop_vitrine']['group_val']['start_group'])){
     $_SESSION['shop_vitrine']['all_search']['start_group'] = $_SESSION['shop_vitrine']['group_val']['start_group'];
 }
 
 if (!empty($_SESSION['shop_vitrine']['all_search'])){
     $search_all = $_SESSION['shop_vitrine']['all_search'];
     $psg->setSearch($search_all);
 } 
 
 // Модуль каталога XOR Модуль расширенного поиска!
 $isSearchlist = $_SESSION['CATALOGSRH']['invitrine'];
 if ($isSearchlist) { // значит работаем с новым расширенным поиском
     
     $searchingroup = '';                              
     
     if (!empty($_SESSION['SHOP_VITRINE']['shopsearchingroup'])) { 
         $searchingroup = $_SESSION['SHOP_VITRINE']['shopsearchingroup'];
         unset($shopcatgr); // очищаем поисковые параметры модуля catalog
         if ($searchingroup == -1)
             $searchingroup = 0;
     }
     else    
         $searchingroup = $shopcatgr;//getRequest('shopcatgr', 1);
         
     if (!$searchingroup && $base_group) {  
         $searchingroup = $base_group;
     }
     if ($searchingroup || ($section->parametrs->param60 == 'Y') || $_SESSION['CATALOGSRH']['manufacturer']) {
         if ($section->parametrs->param60 == 'Y') {   
             $list = getTreeShopGroup($searchingroup);
         } else {
             $list = array();
         }   
         if (!empty($searchingroup)) {
             $list[] = $searchingroup;
         }
         $searchingroup = join(',', array_unique($list)); // $searchingroup превращается в array
         unset($list);  
     }
 } else { // значит работаем с каталогом - очищаем все поисковые параметры модуля searchlist
 
     unset($_SESSION['CATALOGSRH']);
     unset($_SESSION['SHOP_VITRINE']['shopsearchparams']);
   //  unset($_SESSION['SHOP_VITRINE']['ALL_SEARCH']);
             
     if (empty($shopcatgr) && $base_group) {// Если указана базовая группа, а $shopcatgr == 0, то берем базовую
         $shopcatgr = $base_group;
     }
     if ($shopcatgr || ($section->parametrs->param60 == 'Y')) {
         $list = array(); 
                  
         if ($section->parametrs->param60 == 'Y') {   
             $list = getTreeShopGroup($shopcatgr); 
         } 
         if (!empty($shopcatgr)) {
             $list[] = $shopcatgr;          
         }                         
         $shopcatgr = join(',', array_unique($list));  
         unset($list);
     }     
 } 
 
          
                                                    
 if (isRequest('GoToComment') && seUserGroup()) {
     $error_comm_message = "";
     $comm_note = trim(getRequest('comm_note', 3));
     if ($comm_note == '') {
         $error_comm_message = "<br><span class=\"errorcomment\">{$section->language->lang048}</span>"; 
     }
     if (empty($error_comm_message)) {
         $psg->saveGoodsComment($viewgoods,$comm_note,$section->language->lang049);              //сохранить комментари в БД
         if (!empty($old_format)) {
             header("Location: " . seMultiDir() . "/$_page/viewgoods/{$viewgoods}/#comment");
         } else {
             $show = urlencode($show);
             header("Location: " . seMultiDir() . "/$_page/show/{$show}/#comment");
         }
         exit();
     } 
 }
 
 $nchar = intval($section->parametrs->param237);
 if (!$nchar) {
     $nchar = 60;
 }
 
 //сортировка по полям
 $sortval = $psg->getSortVal();
 $order_a = ($sortval == 'aa') ? 'ad' : 'aa';
 $order_n = ($sortval == 'na') ? 'nd' : 'na';
 $order_m = ($sortval == 'ma') ? 'md' : 'ma';
 $order_p = ($sortval == 'pa') ? 'pd' : 'pa';
 $sortimgasc = '<img class="imgSortOrder" src="/' . $__MDL_URL . '/asc.gif" alt="asc">';
 $sortimgdesc = '<img class="imgSortOrder" src="/' . $__MDL_URL . '/desc.gif" alt="desc">';
        
 $imgsort_x = 'imgsort_' . substr($sortval, 0, 1);
 $$imgsort_x = (substr($sortval,1,1) == 'a') ? $sortimgasc : $sortimgdesc;
 $classsort_x = 'classsort_' . substr($sortval, 0, 1);
 $$classsort_x = "OrderActive";

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
 //BeginSubPagespecification
 $__module_subpage['specification']['admin'] = "";
 $__module_subpage['specification']['group'] = 0;
 $__module_subpage['specification']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='specification' && file_exists($__MDL_ROOT . "/tpl/subpage_specification.tpl")){
	include $__MDL_ROOT . "/php/subpage_specification.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_specification");
	$__module_subpage['specification']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagespecification
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}