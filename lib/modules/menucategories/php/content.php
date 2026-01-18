<?php

require_once (SE_CORE . 'classes/seMenu.class.php');

//print_r ('11111');
           
    $PRICEMENU = "";
    $menulist = array();               
    $menulist = mcat_parseGroup($section);                
        // print_r($menulist);
    $thisparam = trim($section->parametrs->param15);
    if($_SESSION[$section->parametrs->param15.'EditCtg'] == true){
        $typmenu = intval($section->parametrs->param30);    
    }else{
       $typmenu = intval($section->parametrs->param14);
    }
    $item = getRequest($thisparam, 3);
    $menugroup = new seMenu($item, $menulist, true, $typmenu);
    $PRICEMENU = $menugroup->execute();    
                                         
?>