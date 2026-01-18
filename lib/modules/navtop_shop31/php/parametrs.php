<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "catalogue";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "Y";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "0";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "1";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "50";
 if (!isset($section->parametrs->param21) || $section->parametrs->param21=='') $section->parametrs->param21 = "50";
 if (!isset($section->parametrs->param18) || $section->parametrs->param18=='') $section->parametrs->param18 = "8";
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "...";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "N";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "Y";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "1";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = "Y";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "4";
 if (!isset($section->parametrs->param16) || $section->parametrs->param16=='') $section->parametrs->param16 = "001";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "n";
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