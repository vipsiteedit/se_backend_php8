<?php
function module_mshop_searchlist($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_searchlist';
 else $__MDL_URL = 'modules/mshop_searchlist';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_searchlist';
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
 $page_vitrine = (strval($section->parametrs->param106)!='') ? seMultiDir().'/'.$section->parametrs->param106.'/' : seMultiDir() .'/'.$__data->getVirtualPage('shop_vitrine').'/';
 //2
 //$a = "<a href='classdoc.html' class=goodsPathRoot >" . $section->language->lang001 . "</a>"; 
 //echo htmlspecialchars($a).'<br>';
 //echo htmlspecialchars();
 if (isRequest('ajax' . $razdel)) {
     if (getRequest('name') == 'category1') {
         $disabled_flag2 = '';
         $category1 = getRequest('value', 1);
         unset($_SESSION['CATALOGSRH']['category2']);
         $category2_list = "";
         $category2_list .= "    <option value=\"-1\">" . $section->language->lang020 . "</option>";
         if ($category1 != -1) {
             $arrG = array();
 //            $category2_list .= implode('', category_list_flat2($category1, $arrG)); // Составление списка категорий
             $category2_list .= implode('', category_list_sub($arrG, $category1, 0));
         }   
         echo $category2_list;
     } 
     if (getRequest('name') == 'category2') {
         $disabled_flag3 = '';
         $category2 = getRequest('value', 1);
         $category3_list = "";
         $category3_list .= "    <option value=\"-1\">" . $section->language->lang020 . "</option>";
         if ($category2 != -1) {
             $disabled_flag3 = '';
             $arrG = array();
             $category3_list .= implode('', category_list_sub($arrG, $category2, 0, 1));
         }
         echo $category3_list;
     } 
     exit();
 }
 // ###### Инициализируем язык и пути к рисункам
 $lang = se_getLang();
 $baseGr = 0;
 $tbl = new seTable('shop_group');
 $tbl->where("code_gr = '" . $section->parametrs->param104 . "'");
 $tbl->andWhere("lang = '$lang'");
 $tbl->fetchOne();
 $baseGr = intval($tbl->id);
 unset($tbl);
 // СОЕДИНЯЕМСЯ С БАЗОЙ
 $goods_count = 0;
 $SHOWPATH = '';
 $SHOWPARAM = '';
 $MANYPAGE = '';
 $MANYPAGECOMM = '';
 $PRICELIST = '';
 $SHOWGOODS = '';
 $PARAM_VALUTA = '';
 $PARAM_COUNTGOODS = '';
 unset($section->objects);
 $param82 = 1;
 $param83 = 3;
 $param84 = 4;
 
 // Путь к папкам с рисунками
 $path_imggroup = '/images/' . $lang . '/shopgroup/';
 $path_imgprice = '/images/' . $lang . '/shopprice/';
 $path_imgall = '/images/' . $lang . '/shopimg/'; 
 // ############################################
 $searchflag = '';
 $_SESSION['CATALOGSRH']['invitrine'] = 0;
 if (isset($_POST['clearsearch'])) { // Очищаем поля ввода
     unset($_SESSION['CATALOGSRH']['search_for']);
     unset($_SESSION['CATALOGSRH']['searchname']);
     unset($_SESSION['CATALOGSRH']['manufacture']);
     unset($_SESSION['CATALOGSRH']['category1']);
     unset($_SESSION['CATALOGSRH']['category2']);
     unset($_SESSION['CATALOGSRH']['category3']);
     unset($_SESSION['CATALOGSRH']['from']);
     unset($_SESSION['CATALOGSRH']['to']);
 } else { // кнопка очистки формы НЕ нажималась
 // ############ Заполняем массив поиска $_SESSION['CATALOGSRH'] ############
     $searchflag = '';
     if (isRequest('searchflag') && (getRequest('searchflag') == 'quicksearch')) {
     // Нажата кнопка быстрого поиска в глобальном контенте
         $searchflag = getRequest('searchflag');
         $searchflag = trim($searchflag);
         unset($_SESSION['CATALOGSRH']['manufacture']);
         unset($_SESSION['CATALOGSRH']['category1']);
         unset($_SESSION['CATALOGSRH']['category2']);
         unset($_SESSION['CATALOGSRH']['category3']);
         unset($_SESSION['CATALOGSRH']['from']);
         unset($_SESSION['CATALOGSRH']['to']);
     }
 //    if ($searchflag == '') {
 //      Поиск по... search_for
     if (isset($_POST['search_for']) && ($_POST['search_for'] == 'for_lot')) {
         $_SESSION['CATALOGSRH']['search_for'] = 'for_lot';
     } else if (isset($_POST['search_for']) && ($_POST['search_for'] == 'for_descr')) {
         $_SESSION['CATALOGSRH']['search_for'] = 'for_descr';
     } else if (isset($_POST['search_for']) && ($_POST['search_for'] == 'for_articul')) {
         $_SESSION['CATALOGSRH']['search_for'] = 'for_articul';
     }
     // Категория товаров - группа каталога ... category
     $ctgr = 0;
     if (isRequest('shopcatgr')) {
 //        $ctgr = getRequest('shopcatgr', 1);
         $tbl = new seTable('shop_group');
         $tbl->where("id = '" . getRequest('shopcatgr', 1) . "'");
         $tbl->andWhere("lang = '$lang'");
         $tbl->fetchOne();
         $ctgr = intval($tbl->id);
         unset($tbl);
     } else if (isRequest('cat')) {
         $tbl = new seTable("shop_group", "sg");
         $tbl->where("sg.code_gr = '" . urldecode(getRequest('cat', 3)) . "'");
         $tbl->andWhere("sg.lang = '$lang'");
         $tbl->fetchOne();
         $ctgr = intval($tbl->id);
         unset($tbl);
     }
     if ($baseGr && $ctgr) {
         $parent = $ctgr;
         if ($parent != $baseGr) {
             do {
                 $tbl = new seTable('shop_group');
                 $tbl->find($parent);
                 $parent = $tbl->upid;
                 unset($tbl);
             } while (($parent != $baseGr) && $parent);
         }
         if (!$parent) {
             $ctgr = $baseGr;
         }
     }
     if ($ctgr && !isRequest('search') && !isRequest('backtoshop')) {
         unset($_SESSION['CATALOGSRH']);
         $_ctgr = array();
         if (($ctgr != $baseGr) || !$baseGr) {
             do {
                 $_ctgr[] = $ctgr;
                 $tbl = new seTable("shop_group", "sg");
                 $tbl->find($ctgr);
                 $ctgr = intval($tbl->upid);
                 unset($tbl);                     
             } while ($ctgr != $baseGr);
             $all = count($_ctgr);
             if ($all > 2) {
                 $_SESSION['CATALOGSRH']['category1'] = $_ctgr[$all - 1];
                 $_SESSION['CATALOGSRH']['category2'] = $_ctgr[$all - 2];
                 $_SESSION['CATALOGSRH']['category3'] = $_ctgr[0];
             } else if ($all > 1) {
                 $_SESSION['CATALOGSRH']['category1'] = $_ctgr[$all - 1];
                 $_SESSION['CATALOGSRH']['category2'] = $_ctgr[0];
                 $_SESSION['CATALOGSRH']['category3'] = -1;
             } else {
                 $_SESSION['CATALOGSRH']['category1'] = $_ctgr[0];
                 $_SESSION['CATALOGSRH']['category2'] = -1;
                 $_SESSION['CATALOGSRH']['category3'] = -1;
             }
         }
     } else {
         // Строка поиска ...'searchname' = $_POST['word']
         if (isset($_POST['word'])) {
             $_SESSION['CATALOGSRH']['searchname'] = trim($_POST['word']); //MY
         }
         // Производитель ... manufacture
         if (isRequest('manufacture')) {
             $_SESSION['CATALOGSRH']['manufacture'] = getRequest('manufacture', 3);
         }
         // Цена, начиная с... from
         if (isRequest('from')) {
             $_SESSION['CATALOGSRH']['from'] = getRequest('from', 3);
         }
         // Цена, не более... to
         if (isset($_POST['to'])) {
             $_SESSION['CATALOGSRH']['to'] = getRequest('to', 3);
         }
         if (isRequest('category1') && (getRequest('category1', 1) != @$_SESSION['CATALOGSRH']['category1'])) { 
             // значение 1 поменялось
             $_SESSION['CATALOGSRH']['category1'] = getRequest('category1', 1);
             $_SESSION['CATALOGSRH']['category2'] = -1;
             $_SESSION['CATALOGSRH']['category3'] = -1;
         }
         if (isRequest('category2') && (getRequest('category2', 1) != $_SESSION['CATALOGSRH']['category2'])) { 
             // значение 2 поменялось
             $_SESSION['CATALOGSRH']['category2'] = getRequest('category2', 1);
             $_SESSION['CATALOGSRH']['category3'] = -1;
         }
         if (isRequest('category3') && (getRequest('category3', 1) != $_SESSION['CATALOGSRH']['category3'])) {
             // значение 3 поменялось
             $_SESSION['CATALOGSRH']['category3'] = getRequest('category3', 1);
         }
         $searchingroup = 0;
         if (isRequest('backtoshop') && ($section->parametrs->param81 != '')) {
             $_SESSION['CATALOGSRH']['invitrine'] = 1;
             if ($_SESSION['CATALOGSRH']['category1'] != -1) {
                 if ($_SESSION['CATALOGSRH']['category2'] != -1) {
                     if ($_SESSION['CATALOGSRH']['category3'] != -1) {
                         $searchingroup = $_SESSION['CATALOGSRH']['category3'];
                     } else {
                         $searchingroup = $_SESSION['CATALOGSRH']['category2'];
                     }
                 } else {
                     $searchingroup = $_SESSION['CATALOGSRH']['category1'];
                 }  
             }
             $_SESSION['SHOP_VITRINE']['CURGROUP'] = $searchingroup;
             if ($searchingroup) {
                 $tbl = new seTable('shop_group', 'sg');
                 $tbl->find($searchingroup);
                 $code = urlencode($tbl->code_gr);
                 header("Location: " . seMultiDir() . "/" . $section->parametrs->param81 . "/cat/{$code}/");
             } else {
                 header("Location: " . seMultiDir() . "/" . $section->parametrs->param81);
             }
             exit();
         }
     }
 }
 // ######## Формируем строку Искать по
 // ########
 // Решаем, какой пункт будет активным
 $sel_art = $sel_lot = $sel_descr = '';
 if (isset($_SESSION['CATALOGSRH']['search_for'])) {  // Если выбран один из пунктов
     // Формируем строки выбора
     if ($_SESSION['CATALOGSRH']['search_for'] == 'for_articul') {
         $sel_art = 'selected';
     }
     if ($_SESSION['CATALOGSRH']['search_for'] == 'for_lot') {
         $sel_lot = 'selected';
     }
     if ($_SESSION['CATALOGSRH']['search_for'] == 'for_descr') {
         $sel_descr = 'selected';
     }
 } else {// дефолтное значение, если ничего не выбрано
     switch ($section->parametrs->param89) { // значение search_for по умолчанию
         case 'art':
             $sel_art = 'selected';
             break;
         case 'lot':
             $sel_lot = 'selected';
             break;
         case 'descr':
             $sel_descr = 'selected';
             break;
         default:
             if ($section->language->lang032 != '-') {// По наименованию
                 $sel_lot = 'selected';
             } else if ($section->language->lang033 != '-') {// По коду
                 $sel_art = 'selected';
             } else if ($section->language->lang031 != '-') {// По содержанию
                 $sel_descr = 'selected';
             }
     }
 }
 $search_for_list = '';
 if (trim($section->language->lang033) != '-') {
     $search_for_list .= '<option ' . $sel_art . ' value="for_articul">' . $section->language->lang033 . '</option>';
 }
 if (trim($section->language->lang032) != '-') {
     $search_for_list .= '<option ' . $sel_lot . ' value="for_lot">' . $section->language->lang032 . '</option>';
 }
 if (trim($section->language->lang031) != '-') {
     $search_for_list .= '<option ' . $sel_descr . ' value="for_descr">' . $section->language->lang031 . '</option>';
 }
 // #############################################
 // ##########... а также переменные $search_for, $category1, $category2, $category3, $word, $manufacture, $from, $to ########
 $search_for = $word = $manufacture = $from = $to = '';
 $category1 = $category2 = $category3 = -1;
 if (isset($_SESSION['CATALOGSRH']['search_for'])) {
     $search_for = htmlspecialchars($_SESSION['CATALOGSRH']['search_for'], ENT_QUOTES);
 }
 if (isset($_SESSION['CATALOGSRH']['searchname'])) {
     $word = htmlspecialchars($_SESSION['CATALOGSRH']['searchname'], ENT_QUOTES);
 }
 if (isset($_SESSION['CATALOGSRH']['manufacture'])) {
     $manufacture = htmlspecialchars($_SESSION['CATALOGSRH']['manufacture'], ENT_QUOTES);
 }     
 if (isset($_SESSION['CATALOGSRH']['from'])) {
     $from = htmlspecialchars($_SESSION['CATALOGSRH']['from'], ENT_QUOTES);
 }
 if (isset($_SESSION['CATALOGSRH']['to'])) {
     $to = htmlspecialchars($_SESSION['CATALOGSRH']['to'], ENT_QUOTES);
 }  
 if (isset($_SESSION['CATALOGSRH']['category1'])) {
     $category1 = htmlspecialchars($_SESSION['CATALOGSRH']['category1'], ENT_QUOTES);
 }    
 if (isset($_SESSION['CATALOGSRH']['category2'])) {
     $category2 = htmlspecialchars($_SESSION['CATALOGSRH']['category2'], ENT_QUOTES);
 }
 if (isset($_SESSION['CATALOGSRH']['category3'])) {
     $category3 = htmlspecialchars($_SESSION['CATALOGSRH']['category3'], ENT_QUOTES);
 }
 // #########################################################################
 // Получаем номер группы товара
 
 if (!empty($_GET['viewgoods'])) {
     $viewgoods = intval(htmlspecialchars($_GET['viewgoods'], ENT_QUOTES));
 } else {
     $viewgoods = 0;
 }
 // Тип валюты (руб, доллары, евро)
 if (!empty($_GET['pricemoney'])) {
     $pricemoney = htmlspecialchars($_GET['pricemoney'], ENT_QUOTES);
     $_SESSION['pricemoney'] = $pricemoney;
 } else if (!empty($_POST['pricemoney'])) {
     $pricemoney = htmlspecialchars($_POST['pricemoney'], ENT_QUOTES);
     $_SESSION['pricemoney'] = $pricemoney;
 } else if (!empty($_SESSION['pricemoney'])) {
     $pricemoney = $_SESSION['pricemoney'];
 } else {
     $pricemoney = se_db_fields_item('main',"lang='$lang'",'basecurr');
     if (empty($pricemoney)) {
         $pricemoney = se_baseCurrency();
     }
 }
 $tbl = new seTable("money", "m");
 $tbl->select("m.kurs");
 $tbl->where("m.name = '$pricemoney'");
 $tbl->fetchOne();
 $cur_kurs = floatval($tbl->kurs);
 unset($tbl);
 // Число товаров, выводимых на одной странице
 if (!empty($_GET['pagen'])) {
     $pagen = htmlspecialchars($_GET['pagen'], ENT_QUOTES);
     $_SESSION['limitpage'] = $pagen;
 } else if (!empty($_POST['pagen'])) {
     $pagen = htmlspecialchars($_POST['pagen'], ENT_QUOTES);
     $_SESSION['limitpage'] = $pagen;
 } else if (!empty($_SESSION['limitpage'])) {
     $pagen = $_SESSION['limitpage']; 
 } else {
     $pagen = "30";
 }
 // Номер листа
 if (!empty($_GET['sheet'])) {
     $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES);
 } else {
     $sheet = "1";
 }
 // Сотрировка $orderby = 'aa', 'ad', 'na', 'nd', 'ma', 'md', 'pa' или 'pd'
 if (!empty($_GET['orderby'])) {
     $orderby = htmlspecialchars($_GET['orderby'], ENT_QUOTES);
     $_SESSION['orderby'] = $orderby;
 } else if (!empty($_POST['orderby'])) {
     $orderby = htmlspecialchars($_POST['orderby'], ENT_QUOTES);
     $_SESSION['orderby'] = $orderby;
 } else if (!empty($_SESSION['orderby'])) {
     $orderby = $_SESSION['orderby'];
 } else {
     $orderby = "aa";
 }
 $sorderby = $imgsort_a = $imgsort_n = $imgsort_p = '';
 $imgsort_m = $imgsort_g = '';
 $classsort_g = 'OrderPassive'; $classsort_a = 'OrderPassive'; $classsort_n = 'OrderPassive';
 $classsort_m = 'OrderPassive'; $classsort_p = 'OrderPassive';
 $sortimgasc = '<img class="imgSortOrder" src="/[module_url]asc.gif" alt="' . $section->language->lang050 . '" title="' . $section->language->lang050 . '">';
 $sortimgdesc = '<img class="imgSortOrder" src="/[module_url]desc.gif" alt="' . $section->language->lang051 . '" title="' . $section->language->lang051 . '">';
 switch ($orderby) {
     case 'ga':
         $field = 'group_name';  
         $ask = '0'; 
         $imgsort_g = $sortimgasc; 
         $classsort_g = "OrderActive";
         break;
     case 'gd':
         $field = 'group_name';  
         $ask = '1'; 
         $imgsort_g = $sortimgdesc; 
         $classsort_g = "OrderActive";
         break;
     case 'aa': 
         $field = 'article';
         $ask = '0'; 
         $imgsort_a = $sortimgasc; 
         $classsort_a = "OrderActive";
         break;
     case 'ad': 
         $field = 'article';  
         $ask = '1'; 
         $imgsort_a = $sortimgdesc; 
         $classsort_a = "OrderActive";
         break;
     case 'na':
         $field = 'name';  
         $ask = '0'; 
         $imgsort_n = $sortimgasc; 
         $classsort_n = "OrderActive";
         break;
     case 'nd':
         $field = 'name';
         $ask = '1';
         $imgsort_n = $sortimgdesc;
         $classsort_n = "OrderActive";
         break;
     case 'ma':
         $field = 'manufacturer';
         $ask = '0';
         $imgsort_m = $sortimgasc;
         $classsort_m = "OrderActive";
         break;
     case 'md':
         $field = 'manufacturer';
         $ask = '1'; 
         $imgsort_m = $sortimgdesc; 
         $classsort_m = "OrderActive";
         break;
     case 'pa':
         $field = 'price';
         $ask = '0';
         $imgsort_p = $sortimgasc;
         $classsort_p = "OrderActive";
         break;
     case 'pd':
         $field = 'price';
         $ask = '1'; 
         $imgsort_p = $sortimgdesc; 
         $classsort_p = "OrderActive";
         break;
     case 'oa':
         $field = 'price_opt';
         $ask='0'; 
         $imgsort_o = $sortimgasc; 
         $classsort_o = "OrderActive";
         break;
     case 'od':
         $field = 'price_opt';
         $ask = '1';
         $imgsort_o = $sortimgdesc;
         $classsort_o = "OrderActive";
         break;
     default:
         $field = 'article';
         $ask='0';
         $imgsort_a = $sortimgasc;
         $classsort_a = "OrderActive";
         break;
 }
 // Конец функции сортировки
 //if ($searchflag != 'quicksearch') { // Это расширенный поиск
 // ======== Формирование списка групп товаров для выпадающих строк =========
 $disabled_flag2 = $disabled_flag3 = 'disabled'; // запрещены 2-я и 3-я выпадающие строки категорий
 $category1_list = $category2_list = $category3_list = '';
 $arrG = array();
 //$category1_list = implode('', category_list_flat1($arrG)); // Составление списка категорий
 //category_list_sub(&$groups, $startGr = 0, $selCtgr = 0, $all = 0, $blank = '')
 $category1_list = implode('', category_list_sub($arrG, $baseGr, $category1));
 if ($category1 != -1) {
     $disabled_flag2 = '';
     $arrG = array();
 //    $category2_list = implode('', category_list_flat2($category1, $arrG)); // Составление списка категорий
     $category2_list = implode('', category_list_sub($arrG, $category1, $category2));
 }   
 if ($category2 != -1) {
     $disabled_flag3 = '';
     $arrG = array();
 //    $category3_list = implode('', category_list_sub3($category2, $arrG)); // Составление списка категорий
     $category3_list = implode('', category_list_sub($arrG, $category2, $category3, 1));
 }   
 // #################### Формируем блок поиска ####################
 //} // end if ($searchflag != 'quicksearch')
 // ###############################  SEARCH ##########################
   // Инициализируем строку $searchby
 $searchby = '';
   // Поиск в группе и ее подгруппах (только тех, которые могут иметь товары)
 $arrS = array();
 $arrG = array();
   // Категория (группа) товара для поиска
 if ((!empty($category3)) && ($category3 != -1) && (!$disabled_flag3)) {// Если есть выбор из 3 строки
     $arrS = get_groups_with_goods($category3, $arrG);
 } else if ((!empty($category2)) && ($category2 != -1) && (!$disabled_flag2)) {// Если выбор из 2 строки
     $arrS = get_groups_with_goods($category2, $arrG);
 } else if ((!empty($category1)) && ($category1 != -1)) { // Есть выбор из 1 строки
     $arrS = get_groups_with_goods($category1, $arrG); // Список групп для поиска
 } else if ($baseGr) {
     $arrS = get_groups_with_goods($baseGr, $arrG);
 }
 $i = 0;
 if (count($arrS) > 0) {
     $arrS = "'" . join("', '", $arrS) . "'";
     $searchby = " AND ((sg.id IN ($arrS)) OR
                        (sp.id IN (SELECT sgp.price_id 
                                     FROM shop_group_price sgp
                                     WHERE
                                         sgp.group_id IN ($arrS))))";
 }
 if (!empty($word)) {
     if (($search_for == 'for_lot')) { 
         $searchby .= " AND (`sp`.`name` LIKE '%$word%')";
     } else if ($search_for == 'for_articul') {
         $searchby .= " AND (REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`article`, ';',''),'_',''),'.',''),' ',''),'#',''),'*',''),',',''),'-','') LIKE '%".
         str_replace('-','',str_replace(';','',str_replace('_','',str_replace('.','',str_replace(' ','',str_replace('#','',str_replace('*','',str_replace(',','',$word))))))))."%')";
     } else {
         $searchby .= " AND ((`sp`.`note` LIKE '%$word%') OR (`sp`.`text` LIKE '%$word%'))";
     }
 }
 if (!empty($manufacture)) {
     $searchby .= " AND (sp.`manufacturer` LIKE '%$manufacture%')";
 }
 $from = str_replace(' ', '', $from);
 $ftdt = date("Y-m-d 00:00:00");
 if (!empty($from)) {
     $searchby .= " AND (((sp.`price` * m.kurs / $cur_kurs >= '$from') AND 
                              ((ssp.newproc IS NULL) OR (ssp.expires_date < '$ftdt'))) OR 
                              (((sp.`price` * m.kurs / $cur_kurs * (100 - ssp.newproc) / 100) >= '$from') AND NOT 
                              (ssp.newproc IS NULL) AND 
                              (ssp.expires_date >= '$ftdt')))";
 
 }
 $to = str_replace(' ', '', $to);
 if (!empty($to)) {
     $searchby .= " AND (((sp.`price` * m.kurs / $cur_kurs <= '$to') AND 
                         ((ssp.newproc IS NULL) OR (ssp.expires_date < '$ftdt'))) OR 
                         (((sp.`price` * m.kurs / $cur_kurs * (100 - ssp.newproc) / 100) <= '$to') AND NOT 
                         (ssp.newproc IS NULL) AND 
                         (ssp.expires_date >= '$ftdt')))";
 }
 // ###############################  END SEARCH ##########################
 
 // #####################################################
 // ######################## ДОБАВЛЕНИЕ ТОВАРА В КОРЗИНУ ######################
 // ########### Добавление в корзину (shopcart)
 if (isRequest('addcart')) {   
     $shopcart = new plugin_ShopCart40();
     $shopcart->addCart();
     // Для обновления информера корзины перезагружаем страницу
     $shopcart_pg = trim($section->parametrs->param50);
     if (!empty($shopcart_pg)) {
         header("Location: " . seMultiDir() . "/$shopcart_pg/?" . time());
     } else {
         header("Location: " . $_SERVER['REQUEST_URI'] . '?' . time());
     }
     exit();
 }
 
 // ################### конец добавления товаров в корзину ###################

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
 //BeginSubPage3
 $__module_subpage['3']['admin'] = "";
 $__module_subpage['3']['group'] = 0;
 $__module_subpage['3']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='3' && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_3");
	$__module_subpage['3']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}