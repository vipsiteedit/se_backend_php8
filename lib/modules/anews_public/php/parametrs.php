<?php
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "n";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "";
 if (!isset($section->parametrs->param36) || $section->parametrs->param36=='') $section->parametrs->param36 = "publication";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "N";
 if (!isset($section->parametrs->param37) || $section->parametrs->param37=='') $section->parametrs->param37 = "Подробнее";
 if (!isset($section->parametrs->param35) || $section->parametrs->param35=='') $section->parametrs->param35 = "Y";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "15";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "400";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "150x150";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "250";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "news";
 if (!isset($section->parametrs->param31) || $section->parametrs->param31=='') $section->parametrs->param31 = "Y";
 if (!isset($section->parametrs->param34) || $section->parametrs->param34=='') $section->parametrs->param34 = "";
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