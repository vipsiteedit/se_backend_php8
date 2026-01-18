<?php
 if (!isset($section->parametrs->param0) || $section->parametrs->param0=='') $section->parametrs->param0 = "30";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "1";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "125";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "100";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "300";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "365";
 if (!isset($section->parametrs->param37) || $section->parametrs->param37=='') $section->parametrs->param37 = "250";
 if (!isset($section->parametrs->param38) || $section->parametrs->param38=='') $section->parametrs->param38 = "2000";
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
