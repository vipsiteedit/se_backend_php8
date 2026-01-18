<?php
 if (empty($section->parametrs->param33)) $section->parametrs->param33 = "rotate";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "special";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "10";
 if (empty($section->parametrs->param48)) $section->parametrs->param48 = "200x200";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "Y";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "name";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "asc";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "catalogue";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "shopcart";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "";
 if (empty($section->parametrs->param29)) $section->parametrs->param29 = "N";
 if (empty($section->parametrs->param46)) $section->parametrs->param46 = "Y";
 if (empty($section->parametrs->param45)) $section->parametrs->param45 = "Y";
 if (empty($section->parametrs->param32)) $section->parametrs->param32 = "blockGoodsInfo";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "N";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "N";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "Y";
 if (empty($section->parametrs->param24)) $section->parametrs->param24 = "Y";
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "Y";
 if (empty($section->parametrs->param25)) $section->parametrs->param25 = "N";
 if (empty($section->parametrs->param27)) $section->parametrs->param27 = "N";
 if (empty($section->parametrs->param26)) $section->parametrs->param26 = "Y";
 if (empty($section->parametrs->param49)) $section->parametrs->param49 = "radio";
 if (empty($section->parametrs->param50)) $section->parametrs->param50 = "Y";
 if (empty($section->parametrs->param34)) $section->parametrs->param34 = "h";
 if (empty($section->parametrs->param35)) $section->parametrs->param35 = "3";
 if (empty($section->parametrs->param36)) $section->parametrs->param36 = "1";
 if (empty($section->parametrs->param42)) $section->parametrs->param42 = "true";
 if (empty($section->parametrs->param37)) $section->parametrs->param37 = "1000";
 if (empty($section->parametrs->param38)) $section->parametrs->param38 = "false";
 if (empty($section->parametrs->param39)) $section->parametrs->param39 = "10";
 if (empty($section->parametrs->param40)) $section->parametrs->param40 = "false";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->vars->$m[1];
      }
     }
   }
?>