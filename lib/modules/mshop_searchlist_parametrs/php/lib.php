<?php

if (!function_exists(ShowCatalog_array_search_key)){
function ShowCatalog_array_search_key($val, $arr) {
    $res = array();
    if (is_array($arr))
        foreach ($arr as $key => $value) if ($value == $val) $res[] = $key;
    return $res;
}}

if (!function_exists('getFieldsArray')){
function getFieldsArray($section){
    // array('имя',тип (0 - строка, 1 - чекбокс, 2 - производитель, 3 - диапазон значений, 4 - радиокнопки, 5 - список))
    $arr = array('name'=>array($section->language->lang022,0),
    'note'=>array($section->language->lang025, 0),
    'text'=>array($section->language->lang026,0),
    'group'=>array($section->language->lang053,5),
    'manufacturer'=>array($section->language->lang051,2),
    'presence_count'=>array($section->language->lang028, 3),
    'price'=>array($section->language->lang048, 3),
    'price_opt'=>array($section->language->lang029, 3),
    'price_opt_corp'=>array($section->language->lang030, 3),
    'measure'=>array($section->language->lang052, 1),
    'weight'=>array($section->language->lang033,3),
    'volume'=>array($section->language->lang034, 3),
    'discount'=>array($section->language->lang037,4),
    'flag_new'=>array($section->language->lang038,4),
    'flag_hit'=>array($section->language->lang039,4),
    'article'=>array($section->language->lang023, 0),
    'code'=>array($section->language->lang024,0) 
    );
    
    
    return $arr;
}
}

if (!function_exists('get_search_item')){
// Имя поля, Значение по умолчаению, массив полей
function get_search_item($section, $name, $class, $include, $value = '', $value_from = '', $value_to = ''){ 
   
    $param_id = 0;
  if (strpos($name, 'param_')!==false){
        $param_id = intval(substr($name, 6));
        $sel_param = array();
        $sel_param[$name][0] = srh_get_paramName($param_id);
       // $sel_array[$name][0] = list_parametrs_second($param_id);
        $sel_param[$name][1] = 2; 
   } else      
   // $selarr = getFieldsArr($section); 
    $sel_array = getFieldsArray($section);
    $sel_param = list_parametrs_array($section);
   //$params_list .= implode('', list_parametrs($name, $arrP));
   
   if(intval($sel_param[$name][1] == 1) || intval($sel_array[$name][1] == 1)){
        if ($param_id == 0){
            $shop = new seTable('shop_price','sp');
            $shop->select("DISTINCT sp.measure AS mes");
            $shop->orderby("mes");
            $shop->where("sp.measure<>''");
            $shop_in_list = $shop->getList();
        } else {
            $arrGroup = array();
            $shop_in_list = srh_getParamValues($param_id, join(',', $arrGroup));
        }
        foreach($shop_in_list as $val){
           // $checked = ($value == 'on') ? ' checked' : '';
           if(isset($_SESSION['SHOP_VITRINE']['PARAM_VAL'][$name])){
                if(in_array($val['mes'],$_SESSION['SHOP_VITRINE']['PARAM_VAL'][$name])){
                    $checked = "checked";
                }else{
                    $checked = "";
                }   
           }  
               // $checked = ($val['mes'] == $value) ? ' checked' : '';
             
            if($val['mes']!= ""){
            $typefields .= '<div class="checkBlock">'.'<input type="checkbox" class="CheckInpClass" name="'.$name.'_search[]" 
            id="'.$name.'_'.$val['mes'].'" value="'.$val['mes'].'"'.$checked.'>'.
            '<label for="'.$name.'_'.$val['mes'].'" style="cursor: pointer;" class="titleParam">'.$val['mes'].'</label>'.'</div>';
            }
        }
       // $typefields = '<input type="checkbox" class="CheckClass" name="'.$name.'_search" id="'.$name.'_search"'.$checked.'>'.$title_val; 
   }elseif(intval($sel_array[$name][1] == 2)){
        $shop_man = new seTable('shop_price','sp');
        $shop_man->select("DISTINCT sp.manufacturer AS man");
      //  $shop_man->select("id");
        $start_group = explode(",",$_SESSION['SHOP_VITRINE']['START_GROUP']);
        $tree_group = join("','",$start_group);
        if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){
            $shop_man->where("id_group IN('{$tree_group}')");
        }
        $shop_man->orderby("man", 0);
        $shop_man_list = $shop_man->getList();
        foreach($shop_man_list as $val){
           // $checked = ($value == 'on') ? ' checked' : '';
            if(isset($_SESSION['SHOP_VITRINE']['MAN_VAL'])){
                if(in_array($val['man'],$_SESSION['SHOP_VITRINE']['MAN_VAL'])){
                    $checked = "checked";
                }else{
                    $checked = "";
                }
            }
            if($val['man'] != ""){
                $typefields .= '<div class="checkBlock">'.'<input type="checkbox" class="CheckInpClass '.$name.'_CheckClass" name="'.$name.'_search[]" 
                id="'.$name.'_'.$val['man'].'" value="'.$val['man'].'" '.$checked.'>'.
                '<label for="'.$name.'_'.$val['man'].'" style="cursor: pointer;" class="titleParam">'.$val['man'].'</label>'.'</div>';
            }
        }
   }elseif(intval($sel_array[$name][1] == 5)){
        $lang = se_getLang();
        $groups = new seTable('shop_group','sg');                          
        $groups->select('*');                
        $start1 = trim($section->parametrs->param106);                    
        $start = getStartCategory($start1);          //существует ли такой код группы 
        if(($start=='err')&&($start1!='')){ 
            $__data->goSubName($section, 1);
        } 
        if($start1!=''){                                                
            $groups->where("`id`='$start'");
            $groups->andwhere("`lang` = '$lang'");
        }else{ 
            $groups->where("`lang` = '$lang'");
        }
        $groups->andwhere("`active`='Y'");
        $group_list = $groups->getList();         //   echo $groups->getSql();
        //$__data->setList($section, 'grps', $group);
        $grps = '';
        $grps .= "  <select name='selectGroup' class='sel_comon sel_0' onchange='selectgroup(this.value, 0)'>
                <option value='-1'>".$section->parametrs->param156."</option>";
        foreach($group_list as $val){ 
            if (is_array($value)) {
                $checked = (in_array($val['id'], $value)) ? ' checked' : '';
            } else {
                $checked = ($val['id'] == $value) ? ' checked' : '';
            } 
            $grps .= "  <option value='".$val['id']."''.$checked.'>".$val['name']."</option>";
        }          
        $grps .= "  </select>";  
        unset($groups); 
        
        $typefields .= '<div class="selectBlockGroup">'.$grps.'</div>'.
        '<div id="sel_group" class="selectBlock">'.$grps_extra.'</div>';
   } elseif(intval($sel_array[$name][1] == 3)) {
        $typefields = '<div class="rangeBlock">'.'<span class="new_elem_from">'.$section->parametrs->param67.'</span>'.'<input type="text" class="InpClassRange" 
        size="5" maxlenght="10" name="'.$name.'_from_search" id="amount_from_'.$name.'_'.$class.'" value="'.$value_from.'">'
        .'<span class="new_elem_to">'.$section->parametrs->param68.'</span>'.'<input type="text" class="InpClassRange" size="5" 
        maxlenght="10" name="'.$name.'_to_search" id="amount_to_'.$name.'_'.$class.'" value="'.$value_to.'">'.
        '<div id="num_'.$name.'_'.$class.'" style="width:200px;" class="sliderValue">'.
        '<table width="100%" style="border:0px solid;">
        <tr><td align="left"><span class="sliderMinMax">'.MaxMinValue('MIN('.$name.')').'</span></td>'.
        '<td align="right"><span class="sliderMinMax">'.MaxMinValue('MAX('.$name.')').'</span></td></tr></table>'
        .'</div>'.
        '<div id="slider-range-'.$name.'_'.$class.'" style="width:200px;" class="sliderClass"></div>'.'</div>';  
   } elseif(intval($sel_array[$name][1] == 4)) {
       
        $checked_none = "checked";
        if($_POST['rad_tab1_'.$name] === "yes"){
            $checked_yes = "checked";
            $checked_no = "";
            $checked_none = "";
        }elseif($_POST['rad_tab1_'.$name] === "no"){
            $checked_no = "checked";
            $checked_yes = "";
            $checked_none = "";
        }elseif($_POST['rad_tab1_'.$name] === "none"){
            $checked_yes = "";
            $checked_no = "";
            $checked_none = "checked";
        }   
        
        $typefields = '<div class="radioBlock">'.'<div>'.
        '<input type="radio" name="rad_'.$class.'_'.$name.'" id="rad1_'.$class.'_'.$name.'" class="radioClass" value="yes" '.$checked_yes.'>'.
        '<span class="title_radio">'.'<label for="rad1_'.$class.'_'.$name.'" style="cursor: pointer;">'.$section->parametrs->param149.'</label>'.'</span>'.'</div>'.
        '<div>'.'<input type="radio" name="rad_'.$class.'_'.$name.'" id="rad2_'.$class.'_'.$name.'" class="radioClass" value="no" '.$checked_no.'>'.'<span class="title_radio">'.
        '<label for="rad2_'.$class.'_'.$name.'" style="cursor: pointer;">'.$section->parametrs->param150.'</label>'.'</span>'.'</div>'.
        '<div>'.'<input type="radio" name="rad_'.$class.'_'.$name.'" id="rad3_'.$class.'_'.$name.'" class="radioClass" value="none" '.$checked_none.'>'.
        '<span class="title_radio">'.'<label for="rad3_'.$class.'_'.$name.'" style="cursor: pointer;">'.$section->parametrs->param151.'</label>'.'</span>'.'</div>'.'</div>';                       
   } elseif(intval($sel_array[$name][1] == 0)) {
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name])){
            $value = $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name];
        }else{
            $value = "";
        }
        $typefields = '<input type="text" class="InpClass" name="'.$name.'_search" id="'.$name.'_search" value="'.$value.'">';   
   }    
   if(strpos($name, 'param_')!==false){
   
        foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $val=>$vall){
            if(($name == $val) && ($class == "tab1")){
                $checked = "checked";
            }
        }
        foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $val=>$vall){
            if(($name == $val) && ($class == "tab2")){
                $checked = "checked";
            }
        }
        
        $result1 = '<span class="btnCheckClass"><input type="checkbox" name="check_'.$name.'_'.$class.'" id="check_'.$name.'_'.$class.'" 
        class="CheckEditClass mc_checkInp_'.$name.' check_Inp_'.$class.'" onClick="checkSyns(\''.$name.'\');" '.$checked.'></span>'.
        '<span style="cursor: pointer;" class="new_title_field" 
        id="show_link_'.$name.'" onClick="showHide(\''.$name.'\',\''.$class.'\');">'.
        '<img id="img1_'.$class.'_'.$name.'" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">'                       
        .'<img id="img2_'.$class.'_'.$name.'" style="cursor: pointer; display: none" src="[module_url]filters0.gif">'
        .'&nbsp;'.$sel_param[$name][0].'</span>'
        .'</div>'.
        ' <div class="new_elem" id="block_'.$class.'_'.$name.'" style="display:none">'.$typefields.'</div></div>';;
        
        $result2 = '<span style="cursor: pointer;" class="new_title_field" 
        id="show_link_'.$name.'">'.
        '<img id="img1_'.$class.'_'.$name.'" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">'                       
        .'<img id="img2_'.$class.'_'.$name.'" style="cursor: pointer; display: none" src="[module_url]filters0.gif">'
        .'&nbsp;'.$sel_param[$name][0].'</span>'
        .'</div>'.
        ' <div class="new_elem" id="block_'.$class.'_'.$name.'" style="display:none">'.$typefields.'</div></div>';
         
        if($include == "true"){
            $result = '<div id="new_'.$name.'" class="fieldSearch">'.'<div>'.$result1;    
        }else{
            $result = '<div id="new_'.$name.'" class="fieldSearch">'.'<div>'.$result2;
        }
   }else{
   
        $checked = "";
   
         foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $val=>$vall){
            if(($name == $val) && ($class == "tab1")){
                $checked = "checked";
            }
         } 
         foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $val=>$vall){
            if(($name == $val) && ($class == "tab2")){
                $checked = "checked";
            }
         }  
        
        $result1 = '<span class="btnCheckClass"><input type="checkbox" name="check_'.$name.'_'.$class.'" id="check_'.$name.'_'.$class.'" 
        class="CheckEditClass mc_checkInp_'.$name.' check_Inp_'.$class.'" onClick="checkSyns(\''.$name.'\');" '.$checked.'></span>'.
        '<span style="cursor: pointer;" class="new_title_field" 
        id="show_link_'.$name.'" onClick="showHide(\''.$name.'\',\''.$class.'\');">'.
        '<img id="img1_'.$class.'_'.$name.'" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">'                       
        .'<img id="img2_'.$class.'_'.$name.'" style="cursor: pointer; display: none" src="[module_url]filters0.gif">'
        .'&nbsp;'.$sel_array[$name][0].'</span>'
        .'</div>'.
        ' <div class="new_elem" id="block_'.$class.'_'.$name.'" style="display:none">'.$typefields.'</div>
        </div>';;
        
        $result2 = '<span style="cursor: pointer;" class="new_title_field" 
        id="show_link_'.$name.'">'.
        '<img id="img1_'.$class.'_'.$name.'" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">'                       
        .'<img id="img2_'.$class.'_'.$name.'" style="cursor: pointer; display: none" src="[module_url]filters0.gif">'
        .'&nbsp;'.$sel_array[$name][0].'</span>'
        .'</div>'.
        ' <div class="new_elem" id="block_'.$class.'_'.$name.'" style="display:none">'.$typefields.'</div>
        </div>';
        
        if($include == "true"){
            $result = '<div id="new_'.$name.'" class="fieldSearch">'.'<div>'.$result1;
        }else{
            $result = '<div id="new_'.$name.'" class="fieldSearch">'.'<div>'.$result2;
        }
   }
   return $result;
} 
}    

if(!function_exists('MaxMinValue')){
function MaxMinValue($value_tbl){
    $shop_val_tbl = new seTable('shop_price');
    $shop_val_tbl->select($value_tbl);
    $start_group = explode(",",$_SESSION['SHOP_VITRINE']['START_GROUP']);
    $tree_group = join("','",$start_group);
    if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){
        $shop_val_tbl->where("id_group IN('{$tree_group}')");
    }
    $shop_val_list = $shop_val_tbl->getList();
    foreach($shop_val_list as $name=>$value){
        foreach($value as $val){
            $val_shop = $val;
        }
    }
    return round($val_shop);
}
}

if(!function_exists('middleRange')){
function middleRange($name){
    $nval = (MaxMinValue('MAX('.$name.')') - MaxMinValue('MIN('.$name.')')) / 2;
    return $nval;
}
}

if (!function_exists('shop_catalog_group_cn')){
function shop_catalog_group_cn($ParentID, $arr_cat, $countgr) {
    $arr_k = ShowCatalog_array_search_key($arr_cat['id'][$ParentID], $arr_cat['upid']);
    if (!empty($arr_k))
        foreach ($arr_k as $k => $val)
            $countgr += shop_catalog_group_cn($val, $arr_cat, $arr_cat['cn'][$val]);
    return $countgr;
}}

if (!function_exists('get_groups_with_goods')){
// #### Функция возаращает массив групп и всей иерархии вложенных подгрупп, имеющих товары
// Параметры:
//     $node_id - номер id данной группы;
//     $arrGroups - указатель на массив, в который складываются
//                  уникальные значения id подгрупп иерархии.
function get_groups_with_goods($node_id, &$arrGroups) {
  $arrSgr = array(); // Массив групп, в которых есть товары (`typegroup`='s')
  $row_sgr = se_db_fields_item('shop_group', "id='$node_id'", 'typegroup');
  if ($row_sgr['typegroup'] == 's')
    if (!in_array($node_id, $arrSgr))  // если id группы нет в массиве,
        $arrSgr[] = $node_id;          // то помещаем ее туда
  // Выбираем слой подгрупп, непосредственно подчиняющихся данной группе
  $ssql = "SELECT `id` FROM `shop_group` WHERE `upid`='".$node_id."'";
  $sql_res = se_db_query($ssql);
  if (se_db_num_rows($sql_res) > 0)
    // работаем с каждой выбранной подгруппой слоя
    while ($row_group = se_db_fetch_array($sql_res)) {
      if (!in_array($row_group['id'], $arrGroups)) { // если id группы нет в массиве,
        $arrGroups[] = $row_group['id'];          // то помещаем ее туда,
        $arrSgr = array_merge($arrSgr, get_groups_with_goods($row_group['id'], $arrGroups));
                                        // затем находим массив подгрупп, имеющих товары
                                        // и объединяем с массивом $arrSgr
      } // end if
    } // end while
  return $arrSgr;
}
}

if (!function_exists('category_list_flat')){
// #### Функция возвращает массив строк вида:
// <option selected value="11">Оригинальные запчасти</option>
// <option value="12">Неоригинальные запчасти</option>
function category_list_flat($cat_id = 1, $up_gr_id = 0,  $start_gr_code = '', $blank = '') {
    $arrGroups = array(); 
    $arrCatList = array();
    // язык
    if ($up_gr_id < 1 && $cat_id > 1) return array();
    $lang = se_getLang();
    // Выбираем слой подгрупп, непосредственно подчиняющихся данной группе
    if (!empty($start_gr_code)) {
            $gtab = new seTable("shop_group","sg");
            $gtab->select("id");
            $gtab->where("code_gr='?'", $start_gr_code);
            $gtab->fetchOne();
            $up_gr_id = $gtab->id;
            unset($gtab); 
    }    
    
    $tbl = new seTable("shop_group","sg");
    $tbl->select("id, name");
    $tbl->where("lang = '?'",$lang);
    if($up_gr_id == 0)
        $tbl->andwhere("upid IS NULL");
    else 
        $tbl->andwhere("upid=?", $up_gr_id);
    
    $sql_res = $tbl->getList();
    unset($tbl);    

    $category_id = intval($_SESSION['CATALOGSRH']['category'.$cat_id]);
    if (count($sql_res)>0) {
        // работаем с каждой выбранной подгруппой слоя
        foreach ($sql_res as $row_group) {
            if ($row_group['id'] < 1) continue;
            if (!in_array($row_group['id'], $arrGroups)) { // если id группы нет в массиве,
                $arrGroups[] = $row_group['id'];          // то помещаем ее туда,
                $selected = ($category_id && $category_id == $row_group['id']) ? ' selected' : '';
                $arrCatList[] = '<option value="'.$row_group['id'].'"'.$selected.'>'.$blank.htmlspecialchars($row_group['name'], ENT_QUOTES).'</option>';
                if ($cat_id > 2) {
                    $tbl = new seTable("shop_group","sg");
                    $tbl->where("upid = '?'",$row_group['id']);
//              if (se_db_is_item('shop_group', 'upid='.$row_group['id']))
                    if ($tbl->fetchOne()) {
                        if ($row_group['id'] > 0) {
                            $arrCatList = array_merge($arrCatList,
                                        category_list_flat(3, $row_group['id'],'','&nbsp;&nbsp;&nbsp;&nbsp;'));
                                        // затем находим массив подгрупп
                                        // и объединяем с массивом $arrCatList
                        }
                    }
                    unset($tbl);
                }
            } 
        } 
    }
    return $arrCatList;
}
}

if(!function_exists('getArrayOfCategory')){
function getArrayOfCategory($val) {
    $lang = se_getLang();
    $massiv = '';                   //список категории
    $group = new seTable('shop_group','sg');
    $group->select('id, upid');    
    if($val=='NO'){
        $group->where("`upid` IS NULL");
    } else {
        $group->where("`upid`='$val'");
        $massiv .= "$val";
    }
    $group->andwhere("`lang` = '$lang'");
    $group->andwhere("`active`='Y'");
    $subcat = $group->getList();                  
    if($subcat){                    //если есть подгруппы
        foreach($subcat as $item){
            if($massiv==''){
                $massiv .= getArrayOfCategory($item['id']);                
            } else {
                $massiv .= ",".getArrayOfCategory($item['id']);                
            }
        }
    } 
   // echo "<br>2 = ".$group->getSql();
    return $massiv;
}     
}               

if(!function_exists('getStartCategory')){
function getStartCategory($cat) {
    $lang = se_getLang();
    $err = 'err';
    $groups = new seTable('shop_group','sg');
    $groups->select('*');                
    $groups->where("`code_gr` = '$cat'");
    $groups->andwhere("`lang` = '$lang'");
    $groups->andwhere("`active`='Y'");
    $rez = $groups->fetchOne();         
    if($rez){                                      
        if($rez['upid']==NULL){
            return $rez['id']; 
           // echo "rez1 = ".$rez['id'];                    
        } else {
            return $rez['upid'];                  
          //  echo "rez2 = ".$rez['upid']; 
        }
    } else {
        return $err; 
                                     
    }
  //  echo "<br>3 = ".$groups->getSql(); 
}
}

if(!function_exists('srh_setCategoryReques')){
        // Категории товаров
function srh_setCategoryReques($cat_id, $value = 0) {
     if(!$value) $value = getRequest('category'.$cat_id, 1);
     if ($value &&
            ($value != $_SESSION['CATALOGSRH']['category'.$cat_id])) {// Значение 1 поменялось
               for($i = $cat_id + 1; $i < 4; $i ++)
                    unset($_SESSION['CATALOGSRH']['category'.$i]);

                $_SESSION['CATALOGSRH']['category'.$cat_id] = $value;
                if ($value > 0 || $cat_id == 1)
                    $_SESSION['SHOP_VITRINE']['shopsearchingroup'] = $value;
    }
}}    

if(!function_exists('srh_getCategory')){
        // Категории товаров
function srh_getCategory($cat_id = 0) {
        if ($cat_id) {
            if(isset($_SESSION['CATALOGSRH']['category'.$cat_id]))
            return intval($_SESSION['CATALOGSRH']['category'.$cat_id]);
        } else {
            if(intval($_SESSION['SHOP_VITRINE']['shopsearchingroup'])){
               return intval($_SESSION['SHOP_VITRINE']['shopsearchingroup']);
            }
        }
}}  

if (!function_exists('shop_catalog_param_on')){
function shop_catalog_param_on($ParamID, $arr_cat, $countpr) {
    $arr_k = ShowCatalog_array_search_key($arr_cat['id'][$ParamID], $arr_cat['nameparam']);
    if (!empty($arr_k))
        foreach ($arr_k as $k => $val)
            $countpr += shop_catalog_param_on($val, $arr_cat, $arr_cat['on'][$val]);
    return $countpr;
}}


if (!function_exists('srh_get_paramName')) {
// #### Функция возвращает массив строк вида:
// <option selected value="param_11">Оригинальные запчасти</option>
// <option value="12">Неоригинальные запчасти</option>
function srh_get_paramName($param_id=0) {
    $tbl = new seTable("shop_param","sp");
    $tbl->select("nameparam");
    $tbl->find($param_id);
    return $tbl->nameparam;
}
}  


if (!function_exists('list_parametrs')) {
function list_parametrs($param_id=0, &$arrParams) {
    $arrParamList = '';
    // язык
    $lang = se_getLang();
    // Выбираем слой параметров
    $tbl = new seTable("shop_param","sp");
    $tbl->select("id, nameparam");
    if ($param_id)
        $tbl->where("id = '?'", $param_id);
    $sql_res = $tbl->getList();               
    unset($tbl);
    if (count($sql_res)>0) {
        foreach ($sql_res as $row_param) {
            if (!in_array($row_param['id'], $arrParams)) { // если id группы нет в массиве,
            if(!array_key_exists('param_'.$row_param['id'], $_SESSION['SHOP_VITRINE']['ALL_SEARCH'])){
                $arrParams[] = $row_param['id'];          // то помещаем ее туда,
             //  $selected = (isset($_SESSION['SHOP_VITRINE']['shopsearchparams']) 
                 //           && $_SESSION['SHOP_VITRINE']['shopsearchparams'] == $row_param['id']) ? ' selected' : '';       
                  //  $arrParamList .= '<div id="param_'.$row_param['id'].'">'.htmlspecialchars($row_param['nameparam'], ENT_QUOTES).'</div>';
                    $arrParamList .= '<div id="param_'.$row_param['id'].'">'.$row_param['nameparam'].'</div>';
            }   
            }
        }
    }
    return $arrParamList;
}
}      

if (!function_exists('list_parametrs_array')) {
function list_parametrs_array($section) {
    $arrParamList = '';
    // язык
    $lang = se_getLang();
    // Выбираем слой параметров
    $tbl = new seTable("shop_param","sp");
    $tbl->select("DISTINCT sp.id, sp.nameparam");
    $tbl->innerjoin('shop_price_param spp','sp.id = spp.param_id');
    $start_group = explode(",",$_SESSION['SHOP_VITRINE']['START_GROUP']);
    $tree_group = join("','",$start_group);
    if ($param_id)
        $tbl->where("id = '?'", $param_id);
    if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){
    $tbl->where("spp.price_id IN(SELECT id FROM shop_price WHERE id_group IN('{$tree_group}'))");
    }  
    $sql_res = $tbl->getList();               
    unset($tbl);
    if (count($sql_res)>0) {
        foreach ($sql_res as $row_param) {
           // $arrParams[] = array();          
               // $arrParams[] = "param_".$row_param['id'];
                $arrParams["param_".$row_param['id']] = array($row_param['nameparam'],1);
        }
    }
    return $arrParams;
}
}   


if (!function_exists('srh_getParamValues')) {
// если $groups_id == '' ищем во всех группах
function srh_getParamValues($param_id, $groups_id = ''){
   $tbl = new seTable("shop_price_param","spp");
   $tbl->select("DISTINCT `value` AS mes");
   $start_group = explode(",",$_SESSION['SHOP_VITRINE']['START_GROUP']);
   $tree_group = join("','",$start_group);
   if (empty($groups_id)){
    if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){
    $tbl->where("param_id IN('{$param_id}')");
    $tbl->andWhere("price_id IN(SELECT id FROM shop_price WHERE id_group IN('{$tree_group}')) ORDER BY mes+0");
    }else{
    $tbl->where("param_id IN('{$param_id}') ORDER BY mes+0");
    }
   }else{
    if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){
    $tbl->where("param_id IN('{$param_id}')");
    $tbl->andwhere("price_id IN (SELECT id FROM shop_price WHERE id_group IN ('$groups_id'))");
    $tbl->andWhere("price_id IN(SELECT id FROM shop_price WHERE id_group IN('{$tree_group}')) ORDER BY mes+0");
    }else{
    $tbl->where("param_id IN('{$param_id}')");
    $tbl->andwhere("price_id IN (SELECT id FROM shop_price WHERE id_group IN ('$groups_id')) ORDER BY mes+0");
    }
   }
    //$tbl->orderby("mes+0");
   $param_list = $tbl->getList();  
 //  echo $tbl->getSQL(); 
   return $param_list; 
}
}  

if(!function_exists('src_store_params_tab1')){
function src_store_params_tab1(){
    if (!is_dir('data')) mkdir('data');                                                                       
    $sfile = serialize($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1']); 
    
    $fp = se_fopen('data/all_search.dat','w+');
    flock($fp, LOCK_EX);
    fwrite($fp, $sfile);  
    flock($fp, LOCK_UN);
    fclose($fp);
   // ftruncate($fp, 0);
}}

if(!function_exists('src_load_params_tab1')){
function src_load_params_tab1(){
    $file = join('', se_file('data/all_search.dat'));
    $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] = unserialize($file);
}}

if(!function_exists('src_store_params_tab2')){
function src_store_params_tab2(){
    if (!is_dir('data')) mkdir('data');                                                                       
    $sfile = serialize($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2']); 
    
    $fp = se_fopen('data/all_search_tab.dat','w+');
    flock($fp, LOCK_EX);
    fwrite($fp, $sfile);  
    flock($fp, LOCK_UN);
    fclose($fp);
    // ftruncate($fp, 0);
}}

if(!function_exists('src_load_params_tab2')){
function src_load_params_tab2(){
    $file = join('', se_file('data/all_search_tab.dat'));
    $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] = unserialize($file);
}}

if(!function_exists('is_referer_page')){
function is_referer_page($page){
    list(,$rpage) = explode('://', $_SERVER['HTTP_REFERER']);
    $rpage = explode('/', $rpage);
    if (seMultiDir() != '')
      $rpage = $rpage[2];
    else $rpage = $rpage[1];
  
    return ($page == $rpage);
}}

/* if(!function_exists('index_array_elem')){
function index_array_elem($what){
    $position = array_search($what, getFieldsArr($section));
    if($position !== false){
        unset(getFieldsArr($section));    
    }    
}
}   */
?>