<?php
function module_mshop_special($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_special';
 else $__MDL_URL = 'modules/mshop_special';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_special';
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
 // :::: Определяем путь к папкам с рисунками
 $siteAddr = strtolower(preg_replace("/\/.*/i", "", $_SERVER['SERVER_PROTOCOL'])) . '://' . $_SERVER['HTTP_HOST'] .seMultiDir();
 
 $objectprice = '';
 $objectid = '';
 $objectimage = '';
 /*
 if (!empty($sitearray['language']))
     $lang = $sitearray['language'];
 else
     $lang="rus";
 //*/
 $chooseGr = array();
 if (($code = trim($section->parametrs->param14)) != '') {
     $tbl = new seTable('shop_group');
     $tbl->where("code_gr = '$code'");
     $tbl->fetchOne();
     if ($tbl->id) {
         $chooseGr[] = $cid = $tbl->id;
         do {
             $_tbl = new seTable('shop_group');
             $_tbl->where("upid IN ($cid)");
             $cid = '';
             foreach ($_tbl->getList() as $v) {
                 if ($cid != '') {
                     $cid .= ', ' . $v['id'];
                 } else {
                     $cid = $v['id'];
                 }
                 $chooseGr[] = $v['id'];
             }
             unset($_tbl);
         } while ($cid != '');
     }
     unset($tbl);
 }
 
 if (!empty($_SESSION['shopcart'])) {
     $incart = $_SESSION['shopcart'];
 } else { 
     $incart = array();
 }
 
 //print_r($incart); 
 $i = 0;
    
 foreach ($incart as $item) {
     $j = 0;
     $ordered[$i][$j] = $item['id'];
     $j++;
     $ordered[$i][$j] = $item['count'];
     $i++; 
 }   
 
 //print_r( $ordered); 
     
     
 $lang = se_getLang();
 $path_imgprice = '/images/' . $lang . '/shopprice/';
 
 
 // :::: Определяем текущую валюту
 
 //if (!empty($_GET['pricemoney'])) {
 if (getRequest('pricemoney') != '') {
     $pricemoney = htmlspecialchars(getRequest('pricemoney'), ENT_QUOTES);
     $_SESSION['pricemoney'] = $pricemoney;
     
 } else if (!empty($_SESSION['pricemoney'])) {
     $pricemoney = $_SESSION['pricemoney'];
 } else {
     $pricemoney = se_baseCurrency();
     if (empty($pricemoney)) {
         $pricemoney = 'RUR';
     }
 }
 
 
 // :::: Работаем с корзиной...
 
 if (!empty($_SESSION['shopcart'])) {
     $incart = $_SESSION['shopcart'];
 } elseif (!empty($_COOKIE['shopcart'])) {
     $shopcart = $_COOKIE['shopcart'];
 } else {
     $incart = array();
 }
 
 if (isRequest('addcart') && isRequest('specialsubmit')) {
     $shopcart = new plugin_ShopCart40();
     $shopcart->addCart();
     // Для обновления информера корзины перезагружаем страницу
     if($section->parametrs->param8=='N') {
         header("Location: " . seMultiDir() . "/{$_page}/?" . time());
     } else {
         header("Location: " . seMultiDir() . "/" . "{$section->parametrs->param4}/?" . time());
     }
     exit();
 }
 
 // ######################################################
 // ### Формирование массива данных и вывод их на экран
 // ###
 $shop_price = new seTable('shop_price');
 $shop_price->select('id');
 $common_count = $shop_price->getListCount();
 unset($shop_price);
 $tbl = new seTable();
 $tbl->from("shop_price","sp");
 $select = '';
 if ($section->parametrs->param12 == 'special') {
     $select = 'sl.id as lid,';
 }
 $tbl->select("DISTINCT sp.article, sp.price, sp.name, sp.img, sp.note, sp.text, sp.code,
                 sp.presence_count, sp.curr, sp.id, sp.flag_hit, sp.flag_new, {$select} sp.special_price, sp.discount");
 if (($section->parametrs->param12 == 'special') || ($section->parametrs->param12 == 'all')) {
     if ($section->parametrs->param12 == 'special') {
         $tbl->innerjoin("shop_leader sl", "sp.id = sl.id_price");
         $tbl->orderby("lid");
     } else {
         $tbl->leftjoin("shop_leader sl", "sp.id = sl.id_price");
     }
 }                   
 
 $tbl->innerjoin("shop_group sg", "sp.id_group = sg.id");
 $tbl->where("sg.lang = '?'", $lang);
 $tbl->andWhere('((sp.presence_count <> 0) OR (sp.presence_count IS NULL))');
 if ($section->parametrs->param12 == 'new') {
     $tbl->andWhere("sp.flag_new = 'Y'");
 }
 if ($section->parametrs->param12 == 'hit') {
     $tbl->andWhere("sp.flag_hit = 'Y'");
 }
 if ($section->parametrs->param12 == 'all') {
     $tbl->andWhere("((sp.flag_hit = 'Y') OR (sp.flag_new = 'Y') OR (sl.id_price = sp.id))");
 }
 $shopcatgr = '';
 if (($section->parametrs->param13=='Y') && isRequest('shopcatgr')) { // Отображать только товары выбранной группы каталога
     $shopcatgr = getRequest('shopcatgr', 1);
     if (!in_array($shopcatgr, $chooseGr) && count($chooseGr)) {
         $shopcatgr = 0;
     }
 } else if (count($chooseGr)) {
     $shopcatgr = join(", ", $chooseGr);
 }
 
 if (!empty($shopcatgr)) { 
     $tbl->leftjoin("shop_group_price sgp", "sgp.price_id=sp.id");
     $tbl->andWhere("(sp.id_group IN ($shopcatgr)) OR (sgp.group_id IN ($shopcatgr))"); 
 }
    
 if ($section->parametrs->param6 == 'A') {
     //Сорировка по артикулу
     //$orderby = 'ORDER BY sp.article DESC';
     $tbl->orderby("article", 1);
 }
 $scount = (int)$section->parametrs->param5; // Число выводимых объектов
 if ($scount < 1) {
     $scount = 1;
 }
 //echo $tbl->getSql();
 $resquery = $tbl->getList();
 $count = count($resquery);//se_db_num_rows($resquery);
 // Заполнение массива $rarr числами от 0 до $count-1
 $dataarr = array();
 if (!empty($resquery)) {
     // Заполнение массива $rand_keys значениями массива $rarr (вперемежку или нет).
     $rand_keys = range(0, $count - 1);
     if ($section->parametrs->param6 == "R") { // Сортировка в случайном порядке
         srand((float)microtime() * 10000);
         shuffle($rand_keys);
     }
     // $ncount - сколько записей выводить
     if ($scount > $count) { // если число выводимых объектов больше общего числа записей
         $ncount = $count;  // $ncount = числу всех записей
     } else {
         $ncount = $scount; // $ncount = числу выводимых объектов
     }
     for ($i = 0; $i < $ncount; $i++) {
         $lin = $resquery[intval($rand_keys[$i])]; 
         $none = false;
         if (!empty($ordered)) {
             for ($k = 0; $k < $common_count; $k++) {
                 if(($lin['id'] == $ordered[$k][0]) && ($lin['presence_count'] == $ordered[$k][1])) {
                     $none = true;
                 }
             }
         }
         if (!$none) {
             $lin['title'] = $lin['name'];
             unset($lin['name']);
             $goodsprice = se_MoneyConvert($lin['price'], $lin['curr'], $pricemoney);
             $lin['newprice'] = '';
             $discountproc = 0;
             if (($lin['special_price'] == 'Y') || ($lin['discount'] == 'Y')) {   
                 $shopdiscount = new plugin_shopDiscount40($lin['id']);
                 $discountproc = $shopdiscount->execute();
                 unset($shopdiscount);
             }
             if ($section->parametrs->param16 == 'Y') {
                 $goodsprice = ceil($goodsprice);
             }
             if ($discountproc > 0) {
                 $discount = $goodsprice * ($discountproc / 100);
                 if ($section->parametrs->param16 == 'Y') {
                     $discount = floor($discount);
                 }
                 $lin['newprice'] = '<span class="new_price">' . myFormatMoneySp($section, $goodsprice - $discount, $pricemoney) . '</span>';
                 $lin['oldprice'] = '<span style="text-decoration:line-through;" class="old_price">' . myFormatMoneySp($section, $goodsprice, $pricemoney) . '</span>';
             } else { 
                 $lin['newprice'] = '<span class="new_price">' . myFormatMoneySp($section, $goodsprice, $pricemoney) . '</span>';
                 $lin['oldprice'] = '';
             }
             $im = new plugin_ShopImages40();
             $lin['image_prev'] = $im->getPrevPriceImage($lin['id']);
             unset($im);
             unset($lin['img']); 
             $lin['code'] = urlencode($lin['code']);          
             $dataarr[$i] = $lin;
         }
     }
 }
 $__data->setList($section, 'objects', $dataarr);

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
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
	include $__data->include_tpl($section, "subpage_1");
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
	include $__data->include_tpl($section, "subpage_2");
	$__module_subpage['2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}