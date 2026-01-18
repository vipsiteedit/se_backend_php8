<?php
function module_mshop_catalog_goods($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_catalog_goods';
 else $__MDL_URL = 'modules/mshop_catalog_goods';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_catalog_goods';
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
 $flag = 0; 
 $moderi = $section->parametrs->param323;
 $moders = explode(",", $moderi);
 for ($j = 0; $j < count($moders); $j++) {
     $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
     if (($userlogin == seUserLogin())&& (seUserLogin() != "")) {
         $flag = 1;
     }
 }
 
 if  (!$flag && seusergroup()>1) {
    $flag = 1;
 }  
 
 //error_reporting(E_ALL);
 $lang = se_getlang();
 //$accessuser = (seUserGroup() > 0);
 $footer_text = '';
 $sid = session_id();   
 if ($base_group_code = trim($section->parametrs->param255)) {
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
 
 $shop_group = new seTable('shop_group');
 $shop_group->select('id,name,code_gr,picture,presence_count');
 $width_gr = intval($section->parametrs->param297);
 
 // Таблица shop_price
 //$shop_price = new seTable('shop_price');
 $width = intval($section->parametrs->param297);
 $thumbwdth = intval($section->parametrs->param298);  
 
 // Подгружаем библиотеку        
 require_once("lib/lib_images.php");
 
 // Определяем язык сайта    
 //$lang = se_getlang();
 //$mlang = utf8_substr($lang, 0, 2);
 
 // массив используемых строк  
 $IMAGE_DIR = "/images/".$lang."/shopprice/";
 
 if (!is_dir(getcwd()."/images"))
     mkdir(getcwd()."/images");
 
 if (!is_dir(getcwd()."/images/".$lang))
     mkdir(getcwd()."/images/".$lang);
 
 if (!is_dir(getcwd().$IMAGE_DIR))
     mkdir(getcwd().$IMAGE_DIR);
 
 /*
 if (empty($base_group)) {
     $base_group = intval($section->parametrs->param108);
 }
 //*/
 
 $namepage = trim($section->parametrs->param322);
 $old_format = (isset($section->parametrs->param108) || ($section->parametrs->param259 == 'N')); // Флаг старого формата
 if (!empty($old_format)) {  
     // Старый режим                      
     // Получена команда показать выбранный товар
     if (isRequest('viewgoods')) {           
         $__data->goSubName($section, 1);
         $viewgoods = getRequest('viewgoods', 3);  
     }
 } else {   // Новый режим
     // Получена команда показать выбранный товар
     if (isRequest('show')) {           
         $__data->goSubName($section, 1);
         $show = getRequest('show', 3);
         $pr = new seShopPrice();   
        // $pr = new seTable('shop_price'); 
         $pr->select('id');
         $pr->where("code = '?'", $show);
         $pr->fetchOne();
         $viewgoods = $pr->id;          
     }
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
         exit;
     }
     if (getRequest('compare') == 'off') {
         foreach ($_SESSION['SHOPVITRINE']['COMPARE'] as $id => $line) {
             if (intval($line) == getRequest('idprice', 1)) {
                 unset($_SESSION['SHOPVITRINE']['COMPARE'][$id]);
             }
         }
         echo count($_SESSION['SHOPVITRINE']['COMPARE']);
         exit;
     }
 
   //  $viewgoods = getRequest('viewgoods', 3);   
 
     if (isRequest('initvote')) {  
         $prc = new seTable('shop_price');
         $prc->find($viewgoods);
         $votes = $prc->votes; 
         //if (!empty($votes))
             echo intval($votes);
         exit;
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
             exit;       
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
 
 //=============================================
 if (isRequest('jquery' . $razdel)) {
     if (isRequest('idprice')) {
         $price_id = getRequest('idprice', 1);
         $this_id = getRequest('value', 1); 
         $i_param = getRequest('iparam', 1); 
         $i_select = floor($i_param / 100); 
     
         $shopdiscount = new plugin_shopDiscount40($price_id);   
         $discountproc = $shopdiscount->execute();    
     
         $shop_price = new seShopPrice();
         $shop_price->select('id, price, price_opt, price_opt_corp, presence_count, presence, curr, special_price, discount, measure');
         $goods = $shop_price->find($price_id);
         $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select] = $this_id; // Для сохранения состояния
         $prc = 0; 
         $cnt = 0;
 
         list($sel) = getTreeParam($section, 0, $price_id, $shop_price->presence_count, 0, false);
         
         // --- Округление и сепараторы ---
         $rounded = ($section->parametrs->param243 == 'Y'); // округление
 
         if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
             $separator = ' ';
         else
             $separator = '';
         // -------------------------------
                                    
 /*        if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
             $ptype = 1;
             $header = $section->parametrs->param227;
         } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
             $ptype = 2;
             $header = $section->parametrs->param122;
         } else {                                    // розничный покупатель
             $ptype = 0;
             $header = $section->parametrs->param121;
         }                                                             
 */
         $plugin_amount = new plugin_shopAmount40($price_id, '', 0, 1, '', $pricemoney);
                                                //$_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select], 
                                                
         
         $cnt = $plugin_amount->getPresenceCount();
         $goodsStyle = ($cnt != 0);
         
         $cnt = $plugin_amount->showPresenceCount($section->parametrs->param69);  // param69 - альтернативный текст "Есть"
         
         $realprice = $plugin_amount->showPrice(false, // discounted
                                                $rounded, // округлять ли цену
                                                $separator); // разделять ли пробелами 000 000
                                                echo 'realprice='.$realprice; 
                                                
 /*        $oldprice = $plugin_amount->showPrice(false, // discounted
                                               $rounded, // округлять ли цену
                                               $separator); // разделять ли пробелами 000 000
         echo $sel . '|' . $realprice . '|' . $cnt . '|' . $oldprice . '|' . $goodsStyle;
 */
         unset($plugin_amount);
     }
     exit();
 }
 //====================================================
 // Создаем таблицы
 createtables($__MDL_ROOT);
 
 //unset($_SESSION['SHOP_VITRINE']['type']);
 unset($_SESSION['SHOP_VITRINE']['shopsearch']);
 
 if (isRequest('correct')){
     $shop_price = new seTable('shop_price');
     $shop_price->addField('price_opt_corp', 'double(0,2)');
     $width = intval($section->parametrs->param297);
     $prev_width = intval($section->parametrs->param298);
     unset($shop_price);
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
 
  
 
 $shop_price = new seShopPrice(); 
 //$shop_price = new seTable('shop_price'); !!!!!!!!!!!!!!!!!!!!
 if (isRequest('shopcatgr')) {
     $shopcatgr = getRequest('shopcatgr', 1);
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
     $searchingroup = ''; 
 /*    
     if (!empty($_SESSION['SHOP_VITRINE']['shopsearchingroup'])) { 
         $searchingroup = $_SESSION['SHOP_VITRINE']['shopsearchingroup'];
         unset($shopcatgr); // очищаем поисковые параметры модуля catalog
         if ($searchingroup == -1)
             $searchingroup = 0;
     }
 //*/   
     if ($_SESSION['CATALOGSRH']['from']) {
         $_SESSION['SHOP_PARAMETRS']['search_price_from'] = $_SESSION['CATALOGSRH']['from'];
         unset($_SESSION['CATALOGSRH']['from']);
     }
     if ($_SESSION['CATALOGSRH']['to']) {
         $_SESSION['SHOP_PARAMETRS']['search_price_to'] = $_SESSION['CATALOGSRH']['to'];
         unset($_SESSION['CATALOGSRH']['to']);
     }
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
 
 //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!       
 
 $error_comm_message = "";
 /*
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
 
         if (!empty($old_format)) {
             header("Location: " . seMultiDir() . "/$_page/viewgoods/{$viewgoods}/#comment");
         } else {
             $pr = new seShopPrice();
             $pr->select('code');
             $pr->where('id = ?', $viewgoods);
             $pr->fetchOne();
             $show = $pr->id;
             unset($pr);
             header("Location: " . seMultiDir() . "/$_page/show/{$show}/#comment");
         }
         exit();
     } 
 } */
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}