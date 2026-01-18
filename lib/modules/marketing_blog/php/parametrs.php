<?php
 if (empty($section->parametrs->param21)) $section->parametrs->param21 = "{$__data->prj->vars->adminmail}";
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "blog";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "1";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "15";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "0";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "text";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "300";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "U";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "N";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "0";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "";
 if (empty($section->parametrs->param12)) $section->parametrs->param12 = "popup";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = "N";
 if (empty($section->parametrs->param23)) $section->parametrs->param23 = "1280";
 if (empty($section->parametrs->param14)) $section->parametrs->param14 = "1";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "0";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "0";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "0";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "0";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "A";
 if (empty($section->parametrs->param20)) $section->parametrs->param20 = "N";
 if (empty($section->parametrs->param22)) $section->parametrs->param22 = "N";
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