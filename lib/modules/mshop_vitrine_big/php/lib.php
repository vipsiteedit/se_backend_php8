<?php

//error_reporting(E_ALL); 
if (!function_exists('createtables')){
function createtables($path) {
    $tables = array('shop_param','shop_price_param');
    foreach($tables as $table){
        if (se_db_num_rows(se_db_query("SHOW COLUMNS FROM `{$table}`")) == 0){
        //se_yaml_to_sql($ymlfile, $outpath = '', $foreign_keys = true, $add_values = false)
           $sqllist = explode("###", se_yaml_to_sql($path.'/'.$table.'.yml', '', true));
           foreach($sqllist as $sql){
                se_db_query($sql); echo se_db_error();
           }
        }
    }
}}

if (!function_exists('getTreeShopGroup')){
// Выбирает id всех вложенных подгрупп группы $shopcatgr
// ** $shopcatgr - значение id группы, из которой выбираем подгруппы
// ** возвращает массив id-шников всех подгрупп
function getTreeShopGroup($shopcatgr){
    $list = array();
    $shgroup = new seShopGroup();
    $shgroup->select('id');
    $shgroup->where('upid=?', $shopcatgr);
    $glist = $shgroup->getList();    
    foreach($glist as $item){
       if ($item['id'])
          $list = array_merge($list, getTreeShopGroup($item['id']));
                                  
       $list[] = $item['id'];     
        // Перебираем деревья до встречи с группой
    }
    return $list;
}}

if (!function_exists('getParamImg')){
// Возвращает имя картинки imgparam для заданного $id_price и $id_param
// возвращает $list = array(parent_id, imgparam, imgparam_mid, imgparam_prev, imgparam_alt);
// Если картинки в доп.параметрах нет, возвращает пустые значения

function getParamImg($id_price, $id_param){
    $list = array();
             
    $spp = new seTable('shop_price_param');
    $spp->select('parent_id, imgparam, imgparam_alt');
    $spp->find($id_param);
                    
    $parent_id = $spp->parent_id;
    $imgparam = $spp->imgparam;
    $imgparam_alt = $spp->imgparam_alt;
                 
    if (!empty($imgparam)) { 
       
        $list['parent_id'] = $parent_id;
        $list['imgparam'] = $imgparam;
        $list['imgparam_alt'] = $imgparam_alt;
        
        return $list;

    }
    else {
    // Нет картинки - идем на уровень выше
        if (!empty($parent_id)) {
        // Есть еще уровни дерева выше 
            $list = getParamImg($id_price, $parent_id);   
            
            return $list;
        } 
        else {
            $list['parent_id'] = '';
            $list['imgparam'] = '';
            $list['imgparam_alt'] = '';
        
            return $list;
        }
    }
}}


if (!function_exists('getShopTextCount')) {
    function getShopTextCount($section, $goods, $pcount) {
        $goodStyle = 1;
        if (($goods['presence_count'] != '') && ($goods['presence_count'] != -1)) {   
            if (($pcount == '') || ($pcount == -1) || ($goods['presence_count'] < $pcount)) {
                $pcount = $goods['presence_count'];
            }
            if (!$pcount) {
                if (!empty($goods['presence'])) {
                    $pcount = $goods['presence'];
                } else {
                    $pcount = $section->parametrs->param294;
                }
                $goodStyle = 0;
            } else {
                $pcount .= '&nbsp;' . $goods['measure'];            
            }
        } else if (($pcount == '') || ($pcount == -1)) {                              
            if (!empty($goods['presence'])) {
                $pcount = $goods['presence'];
            } else {
                $pcount = $section->language->lang017;
            }
        } else {                                     
            if($pcount=='0'){
                $goodStyle = 0;
                if($section->parametrs->param294 != ''){
                    $pcount = $section->parametrs->param294;
                }
            }                                
            $pcount .= '&nbsp;' . $goods['measure'];  
        }
        return array($pcount, $goodStyle);
    }
}

//сортировка по полю
if (!function_exists('getVitineSelectField')) {
function getVitineSelectField($section, $sortval){
    $field = substr($sortval, 0, 1);
    $OPTIONS = '';
    $sortarr = array();
//    $sortarr[] = array('g','group_name',$section->language->lang004);
    $sortarr[] = array('a','article',$section->language->lang005);
    $sortarr[] = array('n','name',$section->language->lang006);
    $sortarr[] = array('m','manufacturer',$section->language->lang007);
    $sortarr[] = array('p','price',$section->language->lang008);
    $sortarr[] = array('c','presence_count_adopt',$section->language->lang009);
    $sortarr[] = array('r','created_at',$section->language->lang010);

    foreach($sortarr as $s){
        $select = ($field == $s[0]) ? ' selected' : '';
        $OPTIONS .= '<option value="'.$s[0].'"'.$select.'>'.$s[2]."</option>\n";
    }
    return $OPTIONS;
}}

//сортировка по направлению
if (!function_exists('getVitineSelectAsc')) {
function getVitineSelectAsc($section, $sortval){
    $asc = substr($sortval, 1, 1);
    $OPTIONS = '';
    $sortarr = array();
    $sortarr[] = array('a',$section->language->lang011);
    $sortarr[] = array('d',$section->language->lang012);

    foreach($sortarr as $s){
        $select = ($asc == $s[0]) ? ' selected' : '';
        $OPTIONS .= '<option value="'.$s[0].'"'.$select.'>'.$s[1]."</option>\n";
    }
    return $OPTIONS;
}}

function dumpp($val){
    echo "<pre>";
    print_r($val);
    echo "</pre>";
}

if (!function_exists('getTreeParam')) {
    function getTreeParam($section, $parent_id=0, $price_id, $count, $i_param = 0, $setblock = true, $fulldesc=0, $mdl_url) {                                                 
        $sellist = '';         
        $spp = new seTable('shop_price_param', 'spp');
        $spp->select('spp.param_id, spp.count, sp.nameparam'); 
        $spp->innerjoin('shop_param sp', 'spp.param_id = sp.id');
        $spp->where("price_id = '?'", $price_id);
    
        if ($parent_id == 0) {
            $spp->andwhere('spp.parent_id IS NULL');
        } else {
            $spp->andwhere("spp.parent_id = '?'", $parent_id);
        }
        if($section->parametrs->param295=='N'){
            $spp->andwhere("(spp.count <> 0 OR spp.count IS NULL)");
        }
        $spp->groupby('param_id');
        $sel_id = 1;
        $paramline = $spp->getlist();     
        if (!empty($paramline)) {
            if (($parent_id == 0) && ($i_param == 0)) {
                unset($_SESSION['SHOP_VITRINE']['paramcount'][$price_id]);
                if ($setblock) {
                    $sellist .= '<div id="prm_' . $section->id . '_' . $price_id . '">';
                }
            }
            foreach ($paramline as $line) {
                $param_id = $line['param_id'];    
                $nameparam = $line['nameparam'];  
                if ($parent_id == 0) { 
                    $sellist .= '<div class="paramsLineBlock">';
                }
                // Список значений для заданного уровня иерархии
                $spp = new seTable('shop_price_param', 'spp');
                $spp->select('id, value, (SELECT COUNT(id) 
                                            FROM `shop_price_param` 
                                            WHERE 
                                                `parent_id` = spp.id) AS `cnt`, count'); 
                $spp->where("price_id = '?'", $price_id);
                if ($parent_id == 0) {
                    $spp->andwhere('parent_id IS NULL');
                } else {
                    $spp->andwhere("parent_id = '?'", $parent_id);
                }
                $spp->andwhere("param_id = '?'", $param_id);
                if($section->parametrs->param295=='N'){
                    $spp->andwhere("(count <> 0 OR count IS NULL)");
                }
                $spp->orderby('value');                
                $spplist = $spp->getList();      
                if (!empty($spplist)) {
                    $_SESSION['SHOP_VITRINE']['paramcount'][$price_id][$sel_id]++;
                    if ((intval($parent_id) == 0) && ($i_param == 0)) {
                        $ni_param = $sel_id * 100;
                    } else {
                        $ni_param = $i_param;
                        $sel_id = floor($ni_param / 100);
                    }    
                    $prm = new plugin_shopparams40(0);
                    $selected = ($_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id]); // выбранный в предыдущий раз пункт данного селекта
                    $selected = $prm->getPreviousParamId($selected, $ni_param - ($sel_id * 100));                
                    $first_next = false;
                    if (count($spplist) > 1) {
                        foreach ($spplist as $item) {
                            if (($item['cnt'] > 0) && ($selected == $item['id'])) {
                                $first_next = true;
                            }
                        }
                        $nameid = ($first_next) ? 'p' . $i_param . '_' . $price_id : 'addcartparam[]';
                        $sellist .= '<span class="goodsParam"><span class="goodsParamTitle">' . $nameparam . '</span>
                                    <select class="goodsParamSelect" name="' . $nameid . '" id="p' . $ni_param . '_' . $price_id . '"';      
                        $sellist .= ' onChange="loadParam(\'' . $section->id . '\',' . ($ni_param + 1) . ',' . $price_id . ',this.value,'.$fulldesc.', \''.$section->language->lang040.'\', \''.$section->language->lang041.'\', \''.$mdl_url.'\');';
                        $sellist .= '">';
                        foreach ($spplist as $item) {                      
                            if ($selected == '') { // Если не выбран никакой,
                                $selected = $item['id']; // тогда выбираем первый 
                            }
                            if ($selected != $item['id']) {
                                $sellist .= '<option value="' . $item['id'] . '">' . trim($item['value']) . '</option>';
                            } else {
                                $sellist .= '<option selected value="' . $item['id'] . '">' . trim($item['value']) . '</option>';
                                if (!$first_next) {
                                    $_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id] = $item['id'];
                                }
                            }  
                        }                                                                                                          
                        $sellist .= '</select><span id="p' . $ni_param . '_' . $price_id . 'dots" style="display: none">
                                    <img src="/'.$mdl_url.'/preloader_foto.gif" width="14" height="14"></span></span>';
                        list($sel) = getTreeParam($section, $selected, $price_id, $count, $ni_param + 1, $setblock, $fulldesc, $mdl_url); 
                        // Субселект на первый выбор селекта
                        $sellist .= $sel;
                    } else {
                        $sellist .= '<span class="goodsParam"><span class="goodsParamTitle">' . $nameparam . '</span>';
                        foreach ($spplist as $item) {
                            $sellist .= '<span class="goodsParamValue"> ' . trim($item['value']) . '</span>';
                            $sellist .= '<input type="hidden" name="addcartparam[]" value="' . $item['id'] . '">';
                            //$selected = $_SESSION['SHOP_VITRINE']['selected'][$price_id][$sel_id] = $item['id'];           
                            $selected = $item['id'];
                            break;
                        }
                        $sellist .='</span>';
                        list($sel) = getTreeParam($section, $selected, $price_id, $count, $ni_param + 1, $setblock, $fulldesc, $mdl_url); 
                        $sellist .= $sel;
                    }   
                    unset($spp);
                }
                $sel_id++;
                if ($parent_id == 0) { 
                    $sellist .= '</div>';
                }
            }   
            if (($parent_id == 0) && ($i_param == 0) && $setblock) {
                $sellist .= '</div>';
            }
        }
        if ((intval($parent_id) == 0) && !empty($_SESSION['SHOP_VITRINE']['selected'][$price_id])) {
            $paramlist = 'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]);
            $prm = new plugin_shopparams40($paramlist);
            list($price, $count) = $prm->getRootPrice();
        }                                           
        return array($sellist, $price='', $count);       
    }
}

if(!function_exists('is_referer_vitrine')){
function is_referer_vitrine($page){
    if (isset($_SERVER['HTTP_REFERER'])){
        list(,$rpage) = explode('://', $_SERVER['HTTP_REFERER']);
        $rpage = explode('/', $rpage);
        if (seMultiDir() != ''){
            $rpage = $rpage[2];
        }else{ 
            $rpage = $rpage[1];
        } 
        return ($page == $rpage);
    }
    return;
  // return $page;
  /* if($rpage==""){
    return $page;
   }else{
    return ($page == $rpage);
   } */
}}
?>