<?php

if(!function_exists('getReqSearch')){
function getReqSearch($name='article', $request, $groups='') {
    $lang = se_getLang();
    $shop_price = new seTable('shop_price','sp');
    $shop_price->select("DISTINCT sp.{$name}"); //,sp.lang,sp.id_group,sp.enabled,sg.id,sg.active,sg.lang
    $shop_price->Where("sp.{$name} LIKE '%?%'", $request);
    if($groups != ''){
        $shop_price->innerjoin('shop_group sg','sp.id_group = sg.id');
        $shop_price->andWhere("sp.id_group IN(?)", $groups);
        $shop_price->andWhere("sg.lang = '?'",$lang);
        $shop_price->andWhere("sg.active = 'Y'");
    } else {
        $shop_price->andwhere("sp.lang = '?'",$lang);
    } 
    $shop_price->andWhere("sp.enabled = 'Y'");
    $shop_price_list = $shop_price->getList(0, 10);
    //echo $shop_price->getSql();
   
    $arr = array();
    foreach($shop_price_list as $val){
        $arr[] = $val[$name];
    } 
    return $arr;
}}



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
        } else {
            return $rez['upid'];                   
        }
    } else {
        return $err; 
                                     
    }
  //  echo "<br>3 = ".$groups->getSql(); 
}
}
?>