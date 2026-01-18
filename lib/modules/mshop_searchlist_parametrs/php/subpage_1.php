<?php

$arr_field = getFieldsArray($section);  // Разворачивание списка  
src_load_params_tab1();

$arr_rep = list_parametrs_array($section);   // Разворачивание дополнительных параметров
// не выводить лишние доп. параметры, если введена стартовая группа
if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){    
    foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $nk=>$vk){
        if(strpos($nk, 'param_')!==false){
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$nk]);
            foreach($arr_rep as $nt=>$vt){
                $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'][$nt] = $nt;    
            }
        }
    }
}
//print_r($arr_field);
foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $name=>$value){
    $line = array();
    $class = "tab1";
    $include = "false";
    $line['line'] = get_search_item($section, $name, $class, $include, $_POST[$name.'_search'], $_POST[$name.'_from_search'], $_POST[$name.'_to_search']);  
    $__data->setItemList($section,'searchparam', $line); 
}
src_load_params_tab2();
// не выводить лишние доп. параметры, если введена стартовая группа
if(isset($_SESSION['SHOP_VITRINE']['START_GROUP'])){    
    foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $nk=>$vk){
        if(strpos($nk, 'param_')!==false){
            unset($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$nk]);
            foreach($arr_rep as $nt=>$vt){
                $_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'][$nt] = $nt;    
            }
        }
    }
}
foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $name=>$value){
    $line = array();
    $class = "tab2";
    $include = "false";
    $line['line'] = get_search_item($section, $name, $class, $include, $_POST[$name.'_search'], $_POST[$name.'_from_search'], $_POST[$name.'_to_search']);  
    $__data->setItemList($section,'searchparametrs', $line); 
}
//unset($_SESSION['SHOP_VITRINE']['PARAM_ARR']);
if(empty($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2']) || ($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] == "")){
    $show = 0;    
}else{
    $show = 1;
} 



if (isset($_POST['shop_search'])){ 
     
    foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $name=>$val){ 
        if(isRequest($name.'_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = getRequest($name.'_search',3);
        } 
        if(isRequest($name.'_from_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'] = getRequest($name.'_from_search',3);
          //  echo "<br> from = ".$_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'];
        }
        if(isRequest($name.'_to_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'] = getRequest($name.'_to_search',3);
          //  echo "<br> to = ".$_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'];
        }   
      /*  if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name])){  
            $_POST[$name.'_search'] = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name], ENT_QUOTES);
        } */
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'])){  
            $value_from = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'], ENT_QUOTES);
           // echo $st_name;
        } 
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'])){  
            $value_to = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'], ENT_QUOTES);
           // echo $st_name;
        } 
    } 
    
    foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $name=>$val){ 
        if(isRequest($name.'_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = getRequest($name.'_search',3); 
        } 
        if(isRequest($name.'_from_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'] = getRequest($name.'_from_search',3);
        }
        if(isRequest($name.'_to_search')){
            $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'] = getRequest($name.'_to_search',3);
        }   
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name])){  
            $st_name = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name], ENT_QUOTES);
        } 
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'])){  
            $value_from = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_from'], ENT_QUOTES);
        } 
        if(isset($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'])){  
            $value_to = htmlspecialchars($_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name.'_to'], ENT_QUOTES);
        } 
    }
    
    foreach($arr_rep as $name=>$val){
        if(isset($_POST[$name.'_search'])){    
            $_SESSION['SHOP_VITRINE']['PARAM_NAME_VAL'][$name] = join("','", $_POST[$name.'_search']);         
            $_SESSION['SHOP_VITRINE']['PARAM_VAL'][$name] = $_POST[$name.'_search'];
        }else{
            $_SESSION['SHOP_VITRINE']['PARAM_NAME_VAL'][$name] = "";
            $_SESSION['SHOP_VITRINE']['PARAM_VAL'][$name] = "";
        }     
    }
    
    if(isset($_POST['group_hidden']) && $_POST['group_hidden']!=""){
        $_SESSION['SHOP_VITRINE']['GROUP_FOR'] = $_POST['group_hidden'];
    }
    
    if(isset($_POST['selectGroup0']) && $_POST['selectGroup0']=="-1"){
        $_SESSION['SHOP_VITRINE']['GROUP_FOR'] = "";    
    }
    
    if(isset($_SESSION['SHOP_VITRINE']['GROUP_FOR'])){  
        $st_name = htmlspecialchars($_SESSION['SHOP_VITRINE']['GROUP_FOR'], ENT_QUOTES);
    } 
    
    if(isset($_POST['manufacturer_search'])){
        $_SESSION['SHOP_VITRINE']['MAN'] = join("','", $_POST['manufacturer_search']); 
        $_SESSION['SHOP_VITRINE']['MAN_VAL'] = $_POST['manufacturer_search'];
    }else{
        $_SESSION['SHOP_VITRINE']['MAN'] = "";
        $_SESSION['SHOP_VITRINE']['MAN_VAL'] = "";
    }
        
  /*  if(isset($_POST['group_search'])){
        $_SESSION['SHOP_VITRINE']['GROUP'] = join("','", $_POST['group_search']);
    }else{
        $_SESSION['SHOP_VITRINE']['GROUP'] = "";
    }  */
    
    if(isset($_POST['measure_search'])){
        $_SESSION['SHOP_VITRINE']['MES'] = join("','", $_POST['measure_search']); 
        $_SESSION['SHOP_VITRINE']['PARAM_VAL']['measure'] = $_POST['measure_search'];
    }else{
        $_SESSION['SHOP_VITRINE']['MES'] = "";
        $_SESSION['SHOP_VITRINE']['PARAM_VAL']['measure'] = "";
    }  
   
    foreach($arr_field as $name=>$val){
        if($name == "flag_hit" || $name == "flag_new" || $name == "discount"){
            if(isset($_POST['rad_tab1_'.$name]) || isset($_POST['rad_tab2_'.$name])){
                if($_POST['rad_tab1_'.$name]==="yes" || $_POST['rad_tab2_'.$name]==="yes"){
                    $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = "yes";
                }elseif($_POST['rad_tab1_'.$name]==="no" || $_POST['rad_tab2_'.$name]==="no"){
                    $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = "no";
                }elseif($_POST['rad_tab1_'.$name]==="none" || $_POST['rad_tab2_'.$name]==="none"){
                    $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = "";
                }
            }
        }
    }
    
    header("Location: ".seMultiDir()."/".$page_vitrine.'/');                
}

?>