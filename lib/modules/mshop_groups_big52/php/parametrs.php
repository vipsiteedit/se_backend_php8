<?php
 if (!isset($section->parametrs->param19) || $section->parametrs->param19=='') $section->parametrs->param19 = "catalogue";
 if (!isset($section->parametrs->param27) || $section->parametrs->param27=='') $section->parametrs->param27 = "";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "2";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "M";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "/";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = ", ";
 if (!isset($section->parametrs->param31) || $section->parametrs->param31=='') $section->parametrs->param31 = "Y";
 if (!isset($section->parametrs->param34) || $section->parametrs->param34=='') $section->parametrs->param34 = "N";
 if (!isset($section->parametrs->param35) || $section->parametrs->param35=='') $section->parametrs->param35 = "Y";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "200x200";
 if (!isset($section->parametrs->param29) || $section->parametrs->param29=='') $section->parametrs->param29 = "100x100";
 if (!isset($section->parametrs->param30) || $section->parametrs->param30=='') $section->parametrs->param30 = "350x350";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "Y";
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
