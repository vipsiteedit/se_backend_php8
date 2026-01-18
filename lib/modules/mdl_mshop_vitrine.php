<?php
function module_mshop_vitrine($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_vitrine';
 else $__MDL_URL = 'modules/mshop_vitrine';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_vitrine';
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
 // устанавливает какая витрина стоит (для модуля Каталог групп интернет-магазина)
 $__data->setVirtualPage('N', 'choose_vitrine_'.$_page);
 
 $section->parametrs->param282 = 'N'; // Выключает возможность режима лупы и увеличенной фотографии
                   // закомментировать для включения.
 
 $lang = se_getlang();
 $accessuser = (seUserGroup() > 0);
 $footer_text = '';
 $sid = session_id();   
 if ($base_group_code = trim($section->parametrs->param273)) {
     $tbl = new seTable("shop_group", "sg");
     $tbl->select('id, footertext');
     $tbl->where("sg.code_gr = '$base_group_code'");
     $tbl->fetchOne();
     $base_group = intval($tbl->id);
     $footer_text = $tbl->footertext;
     unset($tbl);         
 } else {
     $base_group = 0;
 }
 if (empty($base_group_code)) {
     $base_group_code = '0';
 }
 $old_format = intval(isset($section->parametrs->param108) || ($section->parametrs->param259 == 'N')); // Флаг старого формата
 
 // Старый режим                      
 // Получена команда показать выбранный товар
 if (isRequest('viewgoods')) {           
     $__data->goSubName($section, 1);
     $viewgoods = getRequest('viewgoods', 3);
     if (empty($old_format)) {
         $pr = new seShopPrice();
         $pr->select('code, enabled');
         $s = $pr->find($viewgoods);
         ;
     //проверка на существование и на статус активный товара
     if(($pr->enabled == 'N') || (!$pr->enabled)){
         Header('404 Not Found', true, 404);
         exit();
     }
         header("HTTP/1.1 301 Moved Permanently");
         header("Location: ".seMultiDir().'/'.$_page.'/show/'.$pr->code.'/');
     }
 }
 // Новый режим
 // Получена команда показать выбранный товар
 if (isRequest('show')) {           
     $__data->goSubName($section, 1);
     $show = getRequest('show', 3);
     $pr = new seShopPrice();
     $pr->select('id, enabled');
     $pr->where("code = '?'", $show);
     $pr->fetchOne();
     //проверка на существование и на статус активный товара
     if(($pr->enabled == 'N') || (!$pr->enabled)){
         Header('404 Not Found', true, 404);
         exit();
     }
     $viewgoods = $pr->id;          
 }
 
                                             
 if (isRequest('ajax'.$razdel)) {
     if (getRequest('compare') == 'on') {
         $find = false;
         foreach ($_SESSION['SHOPVITRINE']['COMPARE'] as $id => $line) {
             if ($line == getRequest('idprice', 1)) {
                 $find = true; 
                 break;
             }
         }
         if (!$find) {
             $_SESSION['SHOPVITRINE']['COMPARE'][] = getRequest('idprice', 1);
         }
         echo count($_SESSION['SHOPVITRINE']['COMPARE']);
         exit();
     }
     if (getRequest('compare') == 'off') {
         foreach ($_SESSION['SHOPVITRINE']['COMPARE'] as $id => $line) {
             if (intval($line) == getRequest('idprice', 1)) {
                 unset($_SESSION['SHOPVITRINE']['COMPARE'][$id]);
             }
         }
         echo count($_SESSION['SHOPVITRINE']['COMPARE']);
         exit();
     }
     if (isRequest('initvote')) {  
         $prc = new seTable('shop_price');
         $prc->find($viewgoods);
         $votes = $prc->votes; 
         //if (!empty($votes))
             echo intval($votes);
         exit();
     }
     $vote = getRequest('vote');
     if (seUserGroup() == 0) {
         echo 'err_auth'; // Пользователь не авторизовался
         exit();
     } else if (empty($_SESSION['VOTED'][$viewgoods])) {  
         if (!empty($vote)) {                   
             $_SESSION['VOTED'][$viewgoods] = 1; // Признак того, что пользователь уже проголосовал
             $prc = new seTable('shop_price');
             $prc->find($viewgoods);
             $votes = $prc->votes + $vote;
             $prc->update('votes', "'{$votes}'");
             $prc->where('id=?', $viewgoods);
             $prc->save();
             echo $votes;
             exit();       
         }
     } else {
         echo 'err_double_vote'; // Попытка повторного голосования    
     }
     exit();
 }
 
 if (isRequest('pricemoney')) {
     $pricemoney = $_SESSION['pricemoney'] = getRequest('pricemoney');
 } elseif (empty($_SESSION['pricemoney'])) {
     $_SESSION['pricemoney'] = $basecurr = se_baseCurrency();
     $pricemoney = $basecurr;
 } else {
     $pricemoney = $_SESSION['pricemoney'];
 }
 //------------------------------
 $curpg = 1;
 $showpages = 0;
 if (isRequest('sheet')) {
     $curpg = getRequest('sheet', 1);
 } 
 $curunit = 0;
 $tbl = new seTable("money", "m");
 $tbl->where("m.name = '$pricemoney'");
 $tbl->andWhere("m.date_replace = (SELECT MAX(m2.date_replace)
                                     FROM money m2
                                     WHERE
                                         m2.name = m.name)");
 $moneysize = $tbl->kurs;
 unset($tbl);
 //------------------------------
 
 if (isRequest('jquery' . $razdel)) {
     if (isRequest('idprice')) {
         $price_id = getRequest('idprice', 1);
         $this_id = getRequest('value', 1); 
         $i_param = getRequest('iparam', 1); 
         $i_select = floor($i_param / 100); 
     
         $shopdiscount = new plugin_shopDiscount40($price_id);   
         $discountproc = $shopdiscount->execute();    
     
         $price = new seShopPrice();
         $price->select('id, price, price_opt, price_opt_corp, presence_count, presence, curr, special_price, discount, measure');
         $goods = $price->find($price_id);
         $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select] = $this_id; // Для сохранения состояния
         $prc = 0; 
         $cnt = 0;
 
        // list($sel, $paramprc, $cnt) = getTreeParam($section, 0, $price_id, $price->presence_count, 0, false);
      //   list($cnt, $goodStyle) = getShopTextCount($section, $goods, $cnt);  
         
         // $show_addcart = (($section->parametrs->param233 != 'Y') || $goodStyle) ? 'display: inline' : 'display: none';
 
  //       list(, , $realprice, $oldprice) = getShopActualPrice($section, $goods, $paramprc); 
  //       echo $sel . '|' . myFormatMoney($section, $realprice, $pricemoney) . '|' . $cnt . '|' . myFormatMoney($section, $oldprice, $pricemoney) . '|' . $goodStyle;
         
      
      
      
         list($sel, $paramprc, $cnt) = getTreeParam($section, 0, $price_id, $price->presence_count, 0, false);
                                             
         // --- Округление и сепараторы ---
         $rounded = ($section->parametrs->param243 == 'Y'); // округление
 
         if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
             $separator = ' ';
         else
             $separator = '';
         // -------------------------------
                                    
         if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
             $ptype = 1;
             $header = $section->parametrs->param227;
         } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
             $ptype = 2;
             $header = $section->parametrs->param122;
         } else {                                    // розничный покупатель
             $ptype = 0;
             $header = $section->parametrs->param121;
         }
         
             $paramlist = 'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]);
 
 
 
                                                                      
         $plugin_amount = new plugin_shopAmount40($price_id, '', $ptype, 1, 
                                                $paramlist, 
                                                $pricemoney);
         
         $cnt = $plugin_amount->getPresenceCount();
         $goodsStyle = ($cnt != 0);
         
         $cnt = $plugin_amount->showPresenceCount($section->parametrs->param69);  // param69 - альтернативный текст "Есть"
         
         $realprice = $plugin_amount->showPrice(true, // discounted
                                                $rounded, // округлять ли цену
                                                $separator); // разделять ли пробелами 000 000
 //                                               echo 'realprice='.$realprice; 
                                                
         $oldprice = $plugin_amount->showPrice(false, // discounted
                                               $rounded, // округлять ли цену
                                               $separator); // разделять ли пробелами 000 000
         echo $sel . '|' . $realprice . '|' . $cnt . '|' . $oldprice . '|' . $goodsStyle;
         unset($plugin_amount);
     }
     exit();
 }
 
 
 // Создаем таблицы
 createtables($__MDL_ROOT);
 
 
 //unset($_SESSION['SHOP_VITRINE']['type']);
 unset($_SESSION['SHOP_VITRINE']['shopsearch']);
 // Определим, нужно ли обязательно добавлять дополнительный параметр в отображение
 if ($section->parametrs->param231 != 'N') 
 {   // старый режим
     $price = new seShopPrice();
     $price->select('id');
     $price->where('orig_numbers <> ""');
     $price->andWhere('orig_numbers IS NOT NULL');
     $price->fetchOne();
     $paramsRequired = $price->isFind();
 } 
 else 
 {   // новый режим 
     $spp = new seTable('shop_price_param', 'spp');
     $spp->select('id');
     $spp->fetchOne(); 
     $paramsRequired = $spp->isFind();   
 }
 
 
 if (isRequest('correct')){
     $price = new seTable('shop_price');
     $price->addField('price_opt_corp', 'double(0,2)');
     unset($price);
 }
 
 $morephoto = '';
 if (isRequest('typetable')) {
     $_SESSION['SHOP_VITRINE']['type'] = 't';
 }
 
 if (isRequest('typevitrine')) {
     $_SESSION['SHOP_VITRINE']['type'] = 'v';
 }
 if (!empty($_SESSION['SHOP_VITRINE']['type'])) {
     $section->parametrs->param184 = $_SESSION['SHOP_VITRINE']['type'];
 }
 
 if (isRequest('shopsearch')) {
     $_SESSION['SHOP_VITRINE']['shopsearch'] = getRequest('shopsearch', 3);
 }
 
 unset($SE);
 $SE->objects[0] = array();
 $SE->objects[1] = array();
 $SE->objectstyle[0] = array();
 $SE->objectstyle[1] = array();
 $SE->vars['img_block'] = '';
 $SE->vars['cat_path'] = '';
 
 $show->addcart = "Y";
 $show->presentcount = 'Y';
 
 $strlimit = intval($section->parametrs->param64);
 if (!$strlimit) {
     $strlimit = 30;
 }
 
 $price = new seShopPrice();
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
             header('Location: '.seMultiDir() . "/$_page");
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
 
 if (!empty($_SESSION['SHOP_VITRINE']['shopsearch'])) {
     $_SESSION['CATALOGSRH']['invitrine'] = 0;
     $_SESSION['SHOP_VITRINE']['CURGROUP'] = $shopcatgr;
 } else if ($shopcatgr != $_SESSION['SHOP_VITRINE']['VCURGROUP']) {
     $_SESSION['SHOP_VITRINE']['VCURGROUP'] = $shopcatgr;
     $_SESSION['CATALOGSRH']['invitrine'] = 0;
     unset($_SESSION['SHOP_VITRINE']['shopsearch'], $_SESSION['SHOP_PARAMETRS']);
 }
 // Модуль каталога XOR Модуль расширенного поиска!
 //$isSearchlist = isRequest('search');  
 $isSearchlist = $_SESSION['CATALOGSRH']['invitrine'];
 if ($isSearchlist) { // значит работаем с новым расширенным поиском
     
     if ($_SESSION['CATALOGSRH']['from']) {
         $_SESSION['SHOP_PARAMETRS']['search_price_from'] = $_SESSION['CATALOGSRH']['from'];
         unset($_SESSION['CATALOGSRH']['from']);
     }
     if ($_SESSION['CATALOGSRH']['to']) {
         $_SESSION['SHOP_PARAMETRS']['search_price_to'] = $_SESSION['CATALOGSRH']['to'];
         unset($_SESSION['CATALOGSRH']['to']);
     }
     
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
 /*
     unset($_SESSION['SHOP_VITRINE']['shopsearchingroup']);
     unset($_SESSION['CATALOGSRH']['manufacturer']);
     unset($_SESSION['CATALOGSRH']['category1']);
     unset($_SESSION['CATALOGSRH']['category2']);
     unset($_SESSION['CATALOGSRH']['category3']);
     unset($_SESSION['CATALOGSRH']['from']);
     unset($_SESSION['CATALOGSRH']['to']);
 //*/
     unset($_SESSION['CATALOGSRH']);
     unset($_SESSION['SHOP_VITRINE']['shopsearchparams']);
             
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
                
                                     
 // ########### Добавление в корзину (shopcart)
 if (isRequest('addcart')) {   
     //print_r($_POST['addcartparam']);
     $shopcart = new plugin_ShopCart40();
     // Принудительно записываем парамеры в класс
     // Также допускаются использовать 'id' - ID товара и  count - число товаров
     $shopcart->addCart(array('param'=>$_SESSION['SHOP_VITRINE']['selected'][getRequest('addcart',1)]));   
     // Кладем в корзину   
                                     
     // Для обновления информера корзины перезагружаем страницу
     if ($section->parametrs->param85 == 'Y') {
         if (strpos($section->parametrs->param50, "://") === false) {
             header("Location: /{$section->parametrs->param50}/?".time());
         } else {
             $idfile = md5(session_id() . $_SERVER['HTTP_HOST']);
             $shopcart->savesession();
             header("Location: /{$section->parametrs->param50}?idcart=" . $idfile . '&' . time());
         }
     } else {
         header("Location: " . $_SERVER['REQUEST_URI'] . '?' . time());
     }
     exit();
 }
 
 $error_comm_message = "";
 if (isRequest('GoToComment') && seUserGroup()) {
     $comm_note = trim(getRequest('comm_note', 3));
     if ($comm_note == '') {
         $error_comm_message = "<br><span class=\"errorcomment\">{$section->parametrs->param215}</span>"; 
     }
     if (empty($error_comm_message)) {
         $comments = new seTable('shop_comm');
         $comments->id_price = $viewgoods;
         if (seUserGroup() < 3) {
             $person = new seTable('person');
             $person->select('email, last_name, first_name, sec_name');
             $person->find(seUserId());
             $comments->name = trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
             $comments->email = $person->email;
             unset($person);
         } else {
             $comments->name = $section->parametrs->param216;//trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
             $comments->email = '';//$person->email;
         }
         $comments->commentary = $comm_note;//getRequest('comm_note', 3);
         $comments->date = date('Y-m-d', time());
         $comments->save();
 /*
         se_db_query("INSERT INTO `shop_comm`(`id_price`, `name`, `email`, `commentary`, `date`)
                         VALUES('" . $aboutgoods['id'] . "', '" . $_POST['comm_name'] . "', '" . $_POST['comm_email'] . "', '" . $_POST['comm_note'] . "', '" . date("Y-m-d") . "');");
         $_SESSION['comm_message'] = array();
 //*/
         if (!empty($old_format)) {
             header("Location: " . seMultiDir() . "/$_page/viewgoods/{$viewgoods}/#comment");
         } else {
            /* $pr = new seShopPrice();
             $pr->select('code');
             $pr->where('id = ?', $viewgoods);
             $pr->fetchOne();
             $show = $pr->code;
             unset($pr);*/
             header("Location: " . seMultiDir() . "/$_page/show/{$show}/#comment");
         }
         exit();
     } 
 }
 $sortval = 'ia';
 if (isRequest('orderby')) {
     $sortval = getRequest('orderby');
 }
 
 
 $sortimgasc = '<img class="imgSortOrder" src="/' . $__MDL_URL . '/asc.gif" alt="asc">';
 $sortimgdesc = '<img class="imgSortOrder" src="/' . $__MDL_URL . '/desc.gif" alt="desc">';
 
 $order_a = ($sortval == 'aa') ? 'ad' : 'aa';
 $order_n = ($sortval == 'na') ? 'nd' : 'na';
 $order_m = ($sortval == 'ma') ? 'md' : 'ma';
 $order_p = ($sortval == 'pa') ? 'pd' : 'pa';
 
 $imgsort_x = 'imgsort_' . substr($sortval, 0, 1);
 $$imgsort_x = (substr($sortval,1,1) == 'a') ? $sortimgasc : $sortimgdesc;
 $ask = (substr($sortval,1,1) == 'a') ? '0' : '1';
 $classsort_x = 'classsort_' . substr($sortval, 0, 1);
 $$classsort_x = "OrderActive";
 $field_arr = array('id', 'article', 'name');
 $field = in_array($section->parametrs->param15, $field_arr) ? $section->parametrs->param15 : 'article';
  
 switch ($sortval) {
   case 'ga': { $field = 'group_name'; } break;
   case 'gd': { $field = 'group_name';} break;
   case 'aa': { $field = 'article';} break;
   case 'ad': { $field = 'article';} break;
   case 'na': { $field = 'name';} break;
   case 'nd': { $field = 'name';} break;
   case 'ma': { $field = 'manufacturer';} break;
   case 'md': { $field = 'manufacturer';} break;
   case 'pa': { $field = 'price';} break;
   case 'pd': { $field = 'price';} break;
 }

   $section->objectcount = intval($section->parametrs->param64);
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}