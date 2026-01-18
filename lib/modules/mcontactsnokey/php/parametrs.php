<?php
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "Y";
 if (empty($section->parametrs->param13)) $section->parametrs->param13 = ", ";
 if (empty($section->parametrs->param16)) $section->parametrs->param16 = "100%";
 if (empty($section->parametrs->param17)) $section->parametrs->param17 = "500px";
 if (empty($section->parametrs->param6)) $section->parametrs->param6 = "{$__data->prj->vars->sitecompany}";
 if (empty($section->parametrs->param7)) $section->parametrs->param7 = "{$__data->prj->vars->sitephone}";
 if (empty($section->parametrs->param8)) $section->parametrs->param8 = "{$__data->prj->vars->sitepostcode}";
 if (empty($section->parametrs->param9)) $section->parametrs->param9 = "{$__data->prj->vars->siteregion}";
 if (empty($section->parametrs->param10)) $section->parametrs->param10 = "{$__data->prj->vars->sitelocality}";
 if (empty($section->parametrs->param11)) $section->parametrs->param11 = "{$__data->prj->vars->siteaddr}";
 if (empty($section->parametrs->param18)) $section->parametrs->param18 = "N";
 if (empty($section->parametrs->param19)) $section->parametrs->param19 = "9";
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