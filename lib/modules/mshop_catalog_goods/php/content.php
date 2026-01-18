<?php

$mess_no_tovar=$section->parametrs->param272;
$shop_price->select('id,code,article,name,id_group,price,img,note,text,presence_count,id_manufacturer,manufacturer,curr,date_manufactured,enabled');
$shop_price->select('sp.*, sm.id AS `analogs`');
$shop_price->innerjoin('shop_group sg', 'sp.id_group=sg.id');
$shop_price->leftjoin('shop_price_param spp', 'spp.price_id=sp.id');
$shop_price->leftjoin('shop_param spar', 'spar.id=spp.param_id');
$shop_price->leftjoin('shop_sameprice sm', '(sm.id_price=sp.id) OR (sm.id_acc=sp.id)');
$shop_price->leftjoin('shop_group_price sgp', 'sgp.price_id=sp.id');
$shop_price->where("sg.lang = '?'", $lang);
$shop_price->andWhere("sp.enabled = 'Y'");
$shop_price->andWhere("sg.active = 'Y'");
if ($section->parametrs->param254 == 'Y') {// Залежавшийся товар
    $shop_price->andWhere("sp.unsold = 'Y'");
}
//$shop_price_list = $shop_price->getList();

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

if ($section->parametrs->param279 == 'Y') {
    $scount = (int)$section->parametrs->param280; // Число выводимых объектов
    if ($scount < 1)
        $scount = 1;
    
    $shop_price->innerjoin("shop_leader sl", "sp.id = sl.id_price");
}
else
{ 
// Дополняем запрос в зависимости от поисковых данных
  // Придти может от mshop_searhlist или от mshop_groups
  $shopsearch = $_SESSION['CATALOGSRH']['searchname'];  
  $search_for = $_SESSION['CATALOGSRH']['search_for'];
  if (!empty($shopsearch)) { // Пришло от mshop_searchlist
    if ($search_for == 'for_lot')
      $shop_price->andWhere("(sp.name LIKE '%{$shopsearch}%')");
    elseif ($search_for == 'for_articul')
      $shop_price->andWhere("(sp.article LIKE '%{$shopsearch}%')");  
    else
      $shop_price->andWhere("((sp.note LIKE '%{$shopsearch}%') OR (sp.text LIKE '{$shopsearch}%'))");
  }
  elseif (!empty($_SESSION['SHOP_VITRINE']['shopsearch'])) { // Пришло от mshop_groups
    $shopsearch = $_SESSION['SHOP_VITRINE']['shopsearch'];
    $shop_price->andWhere("((sp.article LIKE '{$shopsearch}%') OR (sp.name LIKE '{$shopsearch}%') OR 
                        (sp.note LIKE '{$shopsearch}%') OR (sp.text LIKE '{$shopsearch}%'))");
  }
             
  $manufacturer = $_SESSION['CATALOGSRH']['manufacturer'];
  if (!empty($manufacturer))
    $shop_price->andWhere("(sp.manufacturer LIKE '%{$manufacturer}%')"); 
 
  // Поиск товара по параметрам  (строка запроса "Мощность=12.5..13.00|Производительность=5..6")
if (!empty($_SESSION['SHOP_VITRINE']['shopsearchparams'])) {        
    $shopsearchparams = $_SESSION['SHOP_VITRINE']['shopsearchparams']; 
    $shopsearchparams = str_replace(',', '.', $shopsearchparams);
    // Расшифровываем поисковую строку
    $paramarray = array();
    if (strpos($shopsearchparams, '|')) {
        $paramslist = explode('|', $shopsearchparams);
    }
    foreach($paramslist as $itemparam) {
        list($paramname, $paramarray) = explode('=', $itemparam);    
            $paramarray = explode('..', $paramarray);
        if (!empty($paramarray[0]) && !empty($paramarray[1])) {        
            $shop_price->andWhere("(REPLACE(spp.value,',','.') BETWEEN {$paramarray[0]} AND {$paramarray[1]})");
        }
        elseif (!empty($paramarray[0]) && empty($paramarray[1])) {         
            $shop_price->andWhere("(REPLACE(spp.value,',','.') >= {$paramarray[0]})");
        }
        elseif (empty($paramarray[0]) && !empty($paramarray[1])) {         
            $shop_price->andWhere("(REPLACE(spp.value,',','.') <= {$paramarray[1]})");
        }
        if (!empty($paramarray)) {
            $shop_price->andWhere("spar.nameparam='?'", $paramname);
        }
    }    
    unset($paramarray, $paramslist, $shopsearchparams);
}
                 
if ($isSearchlist) {
    if ($searchingroup) {// Модуль расширенного поиска
        $shop_price->andwhere("((sp.id_group IN (".$searchingroup.")) OR (sgp.group_id IN (".$searchingroup.")))");
    }
    elseif (($section->parametrs->param60 != 'Y') && empty($_SESSION['CATALOGSRH']['manufacturer'])) // при невыбранной группе запрещено показывать
        $shop_price->where('false');                                                   // и не выбран производитель 
}    
else {   
    if ($shopcatgr) // Модуль каталога 
    { 
        $shop_price->andwhere("((sp.id_group IN (".$shopcatgr.")) OR (sgp.group_id IN (".$shopcatgr.")))"); }    
    elseif ($section->parametrs->param60 != 'Y') // Не выбрана группа и запрещено показывать при невыбранной группе
        $shop_price->where('false'); // Ничего не показываем
}


$price_from = $price_to = $otherPg = 0; 
if (isRequest('search_price_from')) {
    if (getRequest('search_price_from', 2) > 0) { 
        $price_from = getRequest('search_price_from', 2);//se_MoneyConvert(getRequest('search_price_from', 2), $pricemoney, se_baseCurrency());
        $otherPg = 1;
    }
    $_SESSION['SHOP_PARAMETRS']['search_price_from'] = $price_from;
} else if (!empty($_SESSION['SHOP_PARAMETRS']['search_price_from'])) {
    if (($price_from = $_SESSION['SHOP_PARAMETRS']['search_price_from'])) {
        $otherPg = 1;
    }
}
if (isRequest('search_price_to')) { 
    if (getRequest('search_price_to', 2) > 0) {
        $price_to = getRequest('search_price_to', 2);//se_MoneyConvert(getRequest('search_price_to', 2), $pricemoney, se_baseCurrency());
        $otherPg = 1;
    }
    $_SESSION['SHOP_PARAMETRS']['search_price_to'] = $price_to;
} else if (!empty($_SESSION['SHOP_PARAMETRS']['search_price_to'])) {
    if (($price_to = $_SESSION['SHOP_PARAMETRS']['search_price_to'])) {
        $otherPg = 1;
    }
}
} // конец if ($section->parametrs->param279 != 'Y')

if ($section->parametrs->param279 == 'Y') { // Показывать только спецпредложения
    if ($section->parametrs->param281 == 'A') { //Сорировка по артикулу
        $shop_price->orderby("article", $ask);
    }
}
else
    $shop_price->orderby($field, $ask);    

$shop_price->groupby("sp.id");

// при выборе конкретной группы не выводились страницы
// echo $price->getSQL();
if ($otherPg) {
    $curunit = 0;
    $beg = ($curpg - 1) * $strlimit + 1;
    $end = $beg + $strlimit;
    $pricelist = $shop_price->getList();
} else {
    $SE_NAVIGATOR = $shop_price->pageNavigator($strlimit);
    $pricelist = $shop_price->getList();
}
$goodscount = count($pricelist);

$datalist = array();
unset($section->objects);
$style = true;
$nchar = intval($section->parametrs->param237);
if (!$nchar) {
    $nchar = 60;
}

// ==== Выборка только (10) значений в случае режима спецпредложения и сортировка их в случайном порядке, если надо. 
if ($section->parametrs->param279 == 'Y') { // Режим спецпредложения

    $dataarr = array();
    
    if (!empty($pricelist)) {
        // $ncount - сколько записей выводить
        if ($scount > $goodscount) { // если число выводимых объектов больше общего числа записей
            $ncount = $goodscount;  // $ncount = числу всех записей
        } else {
            $ncount = $scount; // $ncount = числу выводимых объектов
        }  
        
        // Заполнение массива $rand_keys значениями массива $rarr (вперемежку или нет).
        $rand_keys = range(0, $goodscount - 1);

        if ($section->parametrs->param281 == "R") { // Сортировка в случайном порядке   
            srand((float)microtime() * 10000);
            shuffle($rand_keys);
        }
    
        for ($i = 0; $i < $ncount; $i++) {
            $lin = $pricelist[intval($rand_keys[$i])]; 
            $dataarr[$i] = $lin;
        }
        
        unset($pricelist);
        $pricelist = $dataarr;
    }
}
// ==================================

if (!empty($id)){
        $shop_price-> find($id);         
                         
        //получить содержание новости
        $filename   = $shop_price->img;
}       

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

foreach($pricelist as $spl){

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    
    // --- Округление и сепараторы ---
    $rounded = ($section->parametrs->param243 == 'Y'); // округление

    if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
        $separator = ' ';
    else
        $separator = '';
    // -------------------------------
                                                         
                   
    $price_id = $spl['id'];    
    $plugin_amount = new plugin_shopAmount40($spl['id'], '', 0, 1,'' , $pricemoney);
                                           //$_SESSION['SHOP_VITRINE']['selected'][$price_id]['1']
    $maxcount = $plugin_amount->getPresenceCount();
    
    $realprice =  $plugin_amount->getPrice(true);
    
    $price_disc = $plugin_amount->showPrice(false, // discounted
                                           $rounded, // округлять ли цену
                                           $separator); // разделять ли пробелами 000 000 
    $spl['newprice'] = '';       
/*    $pr_without_disc = $plugin_amount->showPrice(false, // discounted
                                          $rounded, // округлять ли цену
                                          $separator); // разделять ли пробелами 000 000

    $discount = $plugin_amount->getDiscount();
                  
    if (($discount > 0) && ($section->parametrs->param113 == 'Y')) // отображать поле "Старая цена"
        $spl['newprice'] .= '
          <span style="text-decoration:line-through;" class="old_price" id="old_price_'.$section->id.'_'.$spl['id'].'">'.
            $pr_without_disc.'</span>';
*/
    $spl['newprice'] .= '<span class="new_price" id="price_'.$section->id.'_'.$spl['id'].'">'.$price_disc.'</span>';                         

    unset($plugin_amount);
    
    
    if ($otherPg) {
        if (($price_from > $realprice) || (($price_to < $realprice) && $price_to)) {
            continue;
        }
        ++$curunit;
        if (($curunit < $beg) || ($curunit >= $end)) {
            continue;
        }
    }
    $spl['compare'] = '';
    foreach($_SESSION['SHOPVITRINE']['COMPARE'] as $id=>$line){
        if ($line == $spl['id']){
            $spl['compare'] = 'checked';   break;
        }
    }             

    $spl['style'] = ($style = !$style) ? "tableRowOdd" : "tableRowEven"; // четные
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!        

     $spl['edit'] = " ";
   //  $notetext = $spl['text'];
     $id = $spl['id'];
     $name = $spl['name'];
     $code = $spl['code'];
     $article = $spl['article'];
     $manufacturer = $spl['manufacturer'];
     $date_manufactured = $spl['date_manufactured'];
     $note = $spl['note'];
     $price_fields_text = $spl['text'];
     $price = $spl['price'];
     $imagepr = $spl['img'];
     $presence_count = $spl['presence_count'];
     $id_group = $spl['id_group'];
     $unit  = $spl['unit'];
   //  $curr = $spl['curr'];
    
    $im = new plugin_ShopImages40();
    $spl['image_prev'] = $im->getPrevPriceImage($spl['id']);
     
        if (!empty($imagepr)){
            $_imnames = explode(".", $spl['img']);
            $_image = $_imnames[0] . "_prev." . $_imnames[1];
            $spl['image_prev'] = $IMAGE_DIR . $_image;   
        }
        
    
    $spl['name'] = htmlspecialchars($spl['name'], ENT_QUOTES);
        
    if ($old_format){
        $spl['linkshow'] = seMultiDir() . "/{$_page}/viewgoods/{$spl['id']}/";
    }else{
        $spl['linkshow'] = seMultiDir() . "/{$_page}/show/{$spl['code']}/";
    }  
    
    list($spl['count'], $goodStyle) = getShopTextCount($section, $spl, $maxcount);  
        
    $__data->setItemList($section,'shop_price', $spl);
}     


//удаление товара через панель редактрования
    $delete_id = getRequest('delete', 1);
   // if (($editobject!='N')&& (!empty($delete_id))){
    if (!empty($delete_id)){
            $shop_price-> find($delete_id);
            $filename   = $shop_price->img;
            $group = $shop_price->id_group;
                  if (!empty($filename)){ 
                    $temp = explode(".",$filename);
                    $delprevimg = $temp[0]."_prev.".$temp[1];
                    $delprevimg = getcwd().$IMAGE_DIR.$delprevimg;
                    $filename   = getcwd().$IMAGE_DIR.$filename;
                    if (file_exists($delprevimg)) @unlink($delprevimg);
                    if (file_exists($filename)) @unlink($filename);
                }
                $shop_price-> delete($delete_id);
                recalculatGroup($group); 
                Header("Location: /".$_page.'/?'.time());
                exit();
    }      
                                              
?>