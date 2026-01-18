<?php
function module_mshop_groups($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_groups';
 else $__MDL_URL = 'modules/mshop_groups';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_groups';
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
 $typeofvitrine = $__data->getVirtualPage('choose_vitrine_'.$section->parametrs->param19);
 //plugin_shopsearch40::getInstance();
 
 $shopcatgr = $shopsearch = '';
 $query = (!empty($_SERVER['SCRIPT_URL'])) ? $_SERVER['SCRIPT_URL'] : '';
 if (strpos($query, '//')) {
     header('HTTP/1.1 301 Moved Permanently');
     header("Location: " . str_replace('//', '/', $query));
     exit();
 }
 
 $lang = se_getlang();
 // Вывод списка подсказок к поиску  
 if (isRequest('jquery_shop_search') && isRequest('shopsearch')) {                              
 // Обработка для скриптов поиска
     $list = array();
     $shopcatgr = getRequest('shopcat', 1);
     $list[] = array('id' => $shopcatgr, 'scount' => 0);
     if ($section->parametrs->param26 == 'Y') {
         getTreeGroup($list, $shopcatgr);
     }
     $newlist = array();
     foreach ($list as $item) {
         $newlist[] = $item['id'];
     }
     $shopcatgr = join(',', $newlist);
     unset($list, $newlist);
     $shopsearch = str_replace('*', '%', getRequest('shopsearch', 3)); 
     $shopsearch = strtr($shopsearch, array('&quot;' => '"', '&#39;' => "'"));
     $shopsearch = addslashes($shopsearch);
     $ssp = new seTable('shop_price', 'sp');
     $ssp->select('DISTINCT sp.`article`');
     $ssp->innerjoin("shop_group sg", "sg.id = sp.id_group");
     $ssp->where("sp.`article` LIKE '%{$shopsearch}%'");
     $ssp->andWhere("sp.enabled = 'Y'");
     $ssp->andWhere("sg.lang = '$lang'");
     if ($shopcatgr != 0) {
         $ssp->andWhere("sp.id_group in (?)", $shopcatgr);
     }
 //    $sql = $ssp->getSQL();
     $list = $ssp->getList(0, 10);
 //    $sql .= '<br>' . count($list);
     $listres = array();
     foreach ($list as $id => $val) {
         $listres[] = $val['article'];
     }
     $listres = array_unique($listres);
     unset($ssp);
     $ssp = new seTable('shop_price', 'sp');
     $ssp->select('DISTINCT sp.`name`');
     $ssp->innerjoin("shop_group sg", "sg.id = sp.id_group");
     $ssp->where("sp.`name` LIKE '%{$shopsearch}%'");
     $ssp->andWhere("sp.enabled = 'Y'");
     $ssp->andWhere("sg.lang = '$lang'");
     if ($shopcatgr != 0) {
         $ssp->andWhere("sp.id_group in (?)", $shopcatgr);
     }
 //    $sql .= '<br>' . $ssp->getSQL();
     $list = $ssp->getList(0,10);
 //    $sql .= '<br>' . count($list);
     foreach ($list as $val) {
         $listres[] = $val['name'];
     }
     $listres = array_unique($listres);    
     unset($ssp);
     $ssp = new seTable('shop_price', 'sp');
     $ssp->select('DISTINCT sp.`name`');
 //    $ssp->select('DISTINCT LEFT(sp.`note`, 200) as  note');
     $ssp->innerjoin("shop_group sg", "sg.id = sp.id_group");
     $ssp->where("sp.`note` LIKE '%{$shopsearch}%' OR sp.`text` LIKE '%{$shopsearch}%'");
     $ssp->andWhere("sp.enabled = 'Y'");
     $ssp->andWhere("sg.lang = '$lang'");
     if ($shopcatgr != 0) {
         $ssp->andWhere("sp.id_group in (?)", $shopcatgr);
     }
 //    $sql .= '<br>' . $ssp->getSQL();
     $list = $ssp->getList(0,10);
 //    $sql .= '<br>' . count($list);
     foreach ($list as $val) {
         $listres[] = $val['note'];
     }
     $listres = array_unique($listres);
     unset($ssp);
     $ssp = new seTable('shop_price', 'sp');
     $ssp->select('DISTINCT sp.`name`');
 //    $ssp->select('DISTINCT LEFT(sp.`text`, 200) as  `text`');
     $ssp->innerjoin("shop_group sg", "sg.id = sp.id_group");
 //    $ssp->where("`text` LIKE '{$shopsearch}%' OR `text` LIKE '%{$shopsearch}%'");
     $ssp->where("sp.`text` LIKE '%{$shopsearch}%'");
     $ssp->andWhere("sp.enabled = 'Y'");
     $ssp->andWhere("sg.lang = '$lang'");
     if ($shopcatgr != 0) {
         $ssp->andWhere("sp.id_group in (?)", $shopcatgr);
     }
 //    $sql .= '<br>' . $ssp->getSQL();
     $list = $ssp->getList(0,10);
 //    $sql .= '<br>' . count($list);
 //    echo $sql;
 //    exit();
     foreach ($list as $val) {
         $listres[] = $val['text'];
     }
     $listres = array_unique($listres);
 //    echo "<ul style=\"margin:5px;\">";
     $count_item = 1;
     foreach ($listres as $item) {
         if ($count_item > 10) {
             break;
         }
         $count_item++;
         list($res,) = explode("\r\n", $item);
 //        echo "<li style=\"list-style-type:none;\" onclick=\"fill('{$res}');\">{$res}</li>";
         $res2 = htmlspecialchars (htmlspecialchars($res, ENT_QUOTES), ENT_NOQUOTES );
                   
         echo "<div onclick=\"auto('{$res2}'), fill('{$res2}');\">{$res}</div>";
     }
 //    echo "</ul>";
     exit();
 }
 // Обработка модуля
 if (($basegroup = trim($section->parametrs->param27))) {
     $tbl = new seTable("shop_group", "sg");
     $tbl->where("sg.code_gr = '$basegroup'");
     $tbl->fetchOne();
     $basegroup = intval($tbl->id);
     unset($tbl);
 }
 if (!$basegroup) {
     $basegroup = intval($section->parametrs->param2);
 }
 $tbl = new seTable("shop_group", "sg");
 $tbl->find($basegroup);
 
 $old_format = isset($section->parametrs->param2);
 
 if ($section->parametrs->param28 == 'Y' && !$old_format){
     $basecode = strval($tbl->code_gr);
 } else {
     $basecode = strval($tbl->id);
 }
 
 unset($tbl);
 if (isRequest('shopcatgr')) {
     $shopcatgr = getRequest('shopcatgr', 1);    
     if ($section->parametrs->param28 == 'Y' && !$old_format) {
         header('HTTP/1.1 301 Moved Permanently');
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
 } else if (isRequest('cat')) {
     $tbl = new seTable("shop_group", "sg");
     $tbl->where("sg.code_gr = '" . urldecode(getRequest('cat', 3)) . "'");
     $tbl->fetchOne();
     $shopcatgr = intval($tbl->id);
     unset($tbl);
 }
 if (!$shopcatgr) {
     $shopcatgr = $basegroup; 
 }
 //$shopcatgr = intval(getRequest('shopcatgr', 1));
 if ($shopcatgr == 0) {
     unset($_SESSION['SHOP_PARAMETRS']);
 }
 
 $search_price_from = $search_price_to = '';
 if (isRequest('search_price_from')) {
     if (getRequest('search_price_from', 2) > 0) {
         $search_price_from = getRequest('search_price_from', 2); 
     }
     $_SESSION['SHOP_PARAMETRS']['search_price_from'] = $search_price_from;//getRequest('search_price_from', 2);
 } else if (isset($_SESSION['SHOP_PARAMETRS']['search_price_from'])) {
     $search_price_from = $_SESSION['SHOP_PARAMETRS']['search_price_from'];
 }
 if (isRequest('search_price_to')) { 
     if (getRequest('search_price_to', 2) > 0) {
         $search_price_to = getRequest('search_price_to', 2);   
     }
     $_SESSION['SHOP_PARAMETRS']['search_price_to'] = $search_price_to;//getRequest('search_price_to', 2);
 } else if (isset($_SESSION['SHOP_PARAMETRS']['search_price_to'])) {
     $search_price_to = $_SESSION['SHOP_PARAMETRS']['search_price_to'];
 }
 
 $fndGrp = '';
 if (isRequest('shopsearch')) {
     $fndGrp = $shopsearch = trim(str_replace('&#039;', "'", str_replace('&quot;', '"', getRequest('shopsearch', 3))));
     $_SESSION['SHOP_VITRINE']['shopsearch'] = $shopsearch;
     if($shopsearch)
         $_SESSION['SHOP_SEARCH']['text'] = $shopsearch;
 } else {
     $shopsearch = $_SESSION['SHOP_VITRINE']['shopsearch'];
     unset($_SESSION['SHOP_SEARCH']['text']);
 }
 // Таблица групп каталога
 $path_imggroup = '/images/' . $lang . '/shopgroup/';
 
 if (isRequest('shopcatgr') || isRequest('cat')) {
     // Удаление строки поиска
     unset($_SESSION['SHOP_VITRINE']['shopsearch']);
     unset($_SESSION['SHOP_SEARCH']['text']);
 }
 //echo "[$basegroup]=[$shopcatgr]=[$fndGrp]<br>";
 $strspace = $section->parametrs->param17;
 $treelist = array();
 getTreeListGroup($treelist, $basegroup, $shopcatgr, $fndGrp);
 $SHOWPATH = '';
 if (trim($section->parametrs->param19) == '') $shoppath = $__data->getPageName();
 else $shoppath = $section->parametrs->param19;
 foreach ($treelist as $id => $line) {
     if ($id != 0) {
         if ($section->parametrs->param28 == 'Y' && !$old_format){
             $SHOWPATH = '<a class="lnkPath" href="' . seMultiDir() . "/$shoppath/cat/" . urlencode($line['code']) . '/">' . $line['name'].
                 '</a><span class="separPath">' . $strspace . "</span>" . $SHOWPATH;
         } else {
             $SHOWPATH = '<a class="lnkPath" href="' . seMultiDir() . "/$shoppath/shopcatgr/" . $line['id'] . '/">' . $line['name'].
                 '</a><span class="separPath">' . $strspace . "</span>" . $SHOWPATH;
         }
     } else {
         $SHOWPATH = $line['name'] . $SHOWPATH;
     }
 }
 
 
 if ($section->parametrs->param28 == 'Y' && !$old_format) {
     if  ($basecode != '') {
         $link = seMultiDir() . "/$shoppath/cat/" . urlencode($basecode) . '/';
     } else {
         $link = seMultiDir() . "/$shoppath/";
     }
 } else {
     $link = seMultiDir() . "/$shoppath/shopcatgr/" . $basecode . '/';
 }
 
 
 if (!empty($treelist)) {
     $SHOWPATH = '<a class="lnkPath" href="' . $link . '">' . $section->parametrs->param5 . '</a>'.
         '<span class="separPath">' . $strspace . '</span>' . $SHOWPATH;
 } else {
     $SHOWPATH = '<a class="lnkPath" href="' . $link . '">' . $section->parametrs->param5 . '</a>';
 }
 //*/ 
 // Читаем текущую группу
 if ($shopcatgr != $basegroup && $shopcatgr) {
 // Если это не корневая ветка, то выводим ее картинку, имя и комментарий
     $thisgroup = new seTable('shop_group', 'sg');
 //    $thisgroup->select('name, commentary, picture, keywords');
     $thisgroup->find($shopcatgr);
     $thisgroup_name = $thisgroup->name;
     if (trim($thisgroup->title) == '') {
         $thisgroup->title = $thisgroup_name;
     }
     $__data->page->titlepage = htmlspecialchars($thisgroup->title);
     $__data->page->keywords = htmlspecialchars(trim($thisgroup->keywords));
     $thisgroup_commentary = strval($thisgroup->commentary);
     
     if ($thisgroup->description)
         $__data->page->description = htmlspecialchars(strval($thisgroup->description));
     else
         $__data->page->description = htmlspecialchars(strval($thisgroup_commentary));
     
     if (($thisgroup_image_alt = trim($thisgroup->picture_alt)) == '') {
         $thisgroup_image_alt = se_db_output($thisgroup->name);       
     }   
     // иначе выводим картинку
     if (trim($thisgroup->picture)!='') {
         $thisgroup_image = $path_imggroup . $thisgroup->picture;
     } else {
         $thisgroup_image = '';
     }
     unset($thisgroup);
     $subgroups = new seTable('shop_group','sg');
     $subgroups->select('id, name, scount, commentary, title, picture as image, picture_alt, code_gr AS code');
     $subgroups->where('upid=?', $shopcatgr);
     $subgroups->andWhere("active='Y'");
     $subgroups->orderby('position');
     $sgrouplist = $subgroups->getlist();
 
         // Рисуем подгруппы, разделенные "|"
     $iicount = count($sgrouplist);
     foreach ($sgrouplist as $linesub) {
         if ($section->parametrs->param28 == 'Y' && !$old_format) {
             $linesub['link'] = seMultiDir() . "/$_page/cat/" . urlencode($linesub['code']) . '/';
         } else {
             $linesub['link'] = seMultiDir() . "/$_page/shopcatgr/" . $linesub['id'] . '/';
         }
 //        $linesub['link'] = seMultiDir() . "/$_page/shopcatgr/" . $linesub['id'] . '/';
 //        $linesub['link'] = seMultiDir() . "/$_page/cat/" . $linesub['code'] . '/';
 //        $linesub['title'] = ($linesub['title'] != '') ? $linesub['title'] : $linesub['name'];
         if (empty($linesub['commentary'])) $linesub['commentary']  = '';
         if (empty($linesub['picture_alt'])) {
             $linesub['image_alt'] = se_db_output($linesub['name'] . "\r\n" . strip_tags($linesub['commentary']));
         } else { 
             $linesub['image_alt'] = htmlspecialchars($linesub['picture_alt']);
         }
         if (!empty($linesub['image'])) {
             $linesub['image'] = $path_imggroup . $linesub['image'];
         } else if (file_exists('.' . $__MDL_URL . '/no_foto.gif')) {
             $linesub['image'] = $__MDL_URL.'/no_foto.gif';
         } else {
             $linesub['image'] = '';
         }
         $linesub['scount'] = $linesub['scount'] + getCountGoods($linesub['id']);
         $__data->setItemList($section, 'subgroups', $linesub);
     }
 } else {
 // Чтение подгрупп текущей группы
     if ($shopcatgr == 0) {
         $wheregr = "AND ((upid=0) OR (upid IS NULL))";
     } else {
         $wheregr = "AND upid IN ('$shopcatgr')";
         $tbl = new seTable("shop_group", "sg");
         $tbl->select('name,title,keywords,commentary');
         $tbl->find($shopcatgr);
         if (trim($tbl->title) == ''){
             $tbl->title = strval($tbl->name);
         }
         $__data->page->titlepage = $tbl->title;
 
         $__data->page->keywords = trim($tbl->keywords);
         
         if ($tbl->description)
             $__data->page->description = htmlspecialchars($tbl->description);
         else
             $__data->page->description = htmlspecialchars($tbl->commentary);
             
         unset($tbl);
     }    
     $shgroup = new seTable('shop_group', 'sg');
     $shgroup->select('id,picture as image, name, commentary, title, position, `scount` as `count`, code_gr AS code, picture_alt');
     $shgroup->where("lang = '{$lang}' {$wheregr}");
     $shgroup->andWhere("active = 'Y'");
     $shgroup->groupby('id');   
     $shgroup->orderby('position');
     $grouplist = $shgroup->getList();
     unset($shgroup);
     if (!empty($grouplist)) {
     // Создаем список подгрупп
         if (trim($section->parametrs->param19) != '') {
             $newpage = $section->parametrs->param19;
         } else {
             $newpage = $_page;
         }
         foreach ($grouplist as $line) {
             if (($line['title'] = trim($line['picture_alt'])) == '') {
                 $line['title'] = se_db_output($line['name']);// . 'dfdf';
             }
             //if ($shopcatgr != $basegroup) { 
                   // показывать фотогрфии подгрупп в списке подгрупп
 //            $line['link'] = seMultiDir() . "/$newpage/shopcatgr/" . $line['id'] . '/';
             if ($section->parametrs->param28 == 'Y' && !$old_format){
                 $line['link'] = seMultiDir() . "/$newpage/cat/" . urlencode($line['code']) . '/';
             } else {
                 $line['link'] = seMultiDir() . "/$newpage/shopcatgr/" . $line['id'] . '/';
             }
             //$line['link'] = seMultiDir() . "/$newpage/cat/" . $line['code'] . '/';
             if (empty($line['picture_alt'])) {
                 $line['image_alt'] = se_db_output($line['name'] . "\r\n" . strip_tags($line['commentary']));
             } else { 
                 $line['image_alt'] = htmlspecialchars($line['picture_alt']);
             }
             if (!empty($line['image'])) {
                 $line['image'] = $path_imggroup . $line['image'];
             } elseif (file_exists('.' . $__MDL_URL . '/no_foto.gif')) {
                 $line['image'] = $__MDL_URL . '/no_foto.gif';
             } else {
                 $line['image'] = '';
             }
           // $shopcatgr == $basegroup, значит корневая группа
           // ######################## КОРНЕВОЙ КАТАЛОГ ############################
           // Рисуем список подгрупп, резделенных "|"
             $subgroups = new seTable('shop_group', 'sg');
             $subgroups->select('id, name, picture as image, code_gr AS code, title');
             $subgroups->where('upid=?', $line['id']);
             $subgroups->andWhere("active = 'Y'");
             $subgroups->orderby('position');
             $sgrouplist = $subgroups->getlist();
             $ii = 0;
     // Рисуем подгруппы, разделенные "|"
             $iicount = count($sgrouplist);
             $maxcount = intval($section->parametrs->param4);
             foreach ($sgrouplist as $linesub) {
                 if ($ii >= $maxcount) {
                     $linesub['end'] = $section->parametrs->param3;
                     break;
                 }
                 $linesub['end'] = '';
                 if ($section->parametrs->param28 == 'Y' && !$old_format) {
                     $linesub['link'] = seMultiDir() . "/$newpage/cat/" . urlencode($linesub['code']) . '/';
                 } else {
                     $linesub['link'] = seMultiDir() . "/$newpage/shopcatgr/" . $linesub['id'] . '/';
                 }
 //                $linesub['image_alt'] = se_db_output($linesub['name'] . "\r\n" . $linesub['commentary']);
                 if (empty($linesub['commentary'])) $linesub['commentary'] = '';
                 if (empty($linesub['picture_alt'])) {
                     $linesub['image_alt'] = se_db_output($linesub['name'] . "\r\n" . strip_tags($linesub['commentary']));
                 } else { 
                     $linesub['image_alt'] = htmlspecialchars($linesub['picture_alt']);
                 }
                 if (!empty($linesub['image'])) {
                     $linesub['image'] = $path_imggroup . $linesub['image'];
                 } elseif (file_exists('.' . $__MDL_URL . '/no_foto.gif')) {
                     $linesub['image'] = $__MDL_URL . '/no_foto.gif';
                 } else {
                     $linesub['image'] = '';
                 }
                 $linesub['vline'] = '';
                 if ($ii < $iicount - 1) {
                     $linesub['vline'] = '<span class="vline">' . $section->parametrs->param7 . ' </span>';
                 }
                 $ii++;
                 $__data->setItemList($section, 'subgroups' . $line['id'], $linesub);
             }
     //         }
             $__data->setItemList($section, 'groups', $line);
         }
     }
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