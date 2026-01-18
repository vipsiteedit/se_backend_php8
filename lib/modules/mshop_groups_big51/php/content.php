<?php

$parents = $psg->getParents($shopcatgr, true);

$link = seMultiDir() . "/$shoppath/";
$SHOWPATH = '<a class="lnkPath" href="' . $link . '">' . $section->parametrs->param5 . '</a>';  
  
if (!empty($parents)) {
    while (!empty($parents)) {
        $val = array_pop($parents);        
             
        $SHOWPATH .=  '<span class="separPath">' . $section->parametrs->param17 . '</span> <a class="lnkPath" href="' . seMultiDir() . "/$shoppath/cat/" . urlencode($val['code']) . '/">' . $val['name'].'</a>';
    }
}
?>