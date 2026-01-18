<?php
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "rotate";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "special";
 if (!isset($section->parametrs->param50) || $section->parametrs->param50=='') $section->parametrs->param50 = "";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "10";
 if (!isset($section->parametrs->param48) || $section->parametrs->param48=='') $section->parametrs->param48 = "200x200";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "all";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "name";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "asc";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "catalogue";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "shopcart";
 if (!isset($section->parametrs->param14) || $section->parametrs->param14=='') $section->parametrs->param14 = "";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "N";
 if (!isset($section->parametrs->param46) || $section->parametrs->param46=='') $section->parametrs->param46 = "Y";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "blockGoodsInfo";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "N";
 if (!isset($section->parametrs->param23) || $section->parametrs->param23=='') $section->parametrs->param23 = "N";
 if (!isset($section->parametrs->param22) || $section->parametrs->param22=='') $section->parametrs->param22 = "Y";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "Y";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "Y";
 if (!isset($section->parametrs->param25) || $section->parametrs->param25=='') $section->parametrs->param25 = "N";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "N";
 if (!isset($section->parametrs->param26) || $section->parametrs->param26=='') $section->parametrs->param26 = "Y";
 if (!isset($section->parametrs->param49) || $section->parametrs->param49=='') $section->parametrs->param49 = "N";
 if (!isset($section->parametrs->param51) || $section->parametrs->param51=='') $section->parametrs->param51 = "N";
 if (!isset($section->parametrs->param34) || $section->parametrs->param34=='') $section->parametrs->param34 = "h";
 if (!isset($section->parametrs->param35) || $section->parametrs->param35=='') $section->parametrs->param35 = "3";
 if (!isset($section->parametrs->param36) || $section->parametrs->param36=='') $section->parametrs->param36 = "1";
 if (!isset($section->parametrs->param42) || $section->parametrs->param42=='') $section->parametrs->param42 = "true";
 if (!isset($section->parametrs->param37) || $section->parametrs->param37=='') $section->parametrs->param37 = "1000";
 if (!isset($section->parametrs->param38) || $section->parametrs->param38=='') $section->parametrs->param38 = "false";
 if (!isset($section->parametrs->param39) || $section->parametrs->param39=='') $section->parametrs->param39 = "10";
 if (!isset($section->parametrs->param40) || $section->parametrs->param40=='') $section->parametrs->param40 = "false";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      while (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $__result = $__data->prj->vars->$m[1];
        $__value = str_replace($m[0], $__result, $__value);
      }
      $section->parametrs->$__name = $__value;
     }
   }
?>
