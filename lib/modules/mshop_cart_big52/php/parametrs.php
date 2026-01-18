<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "catalogue";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "clientaccount";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "N";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "N";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "Y";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "Самовывоз";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "0";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "Y";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "Y";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "Y";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "N";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "optovik";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "optcorp";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "80";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "Y";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "Y";
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