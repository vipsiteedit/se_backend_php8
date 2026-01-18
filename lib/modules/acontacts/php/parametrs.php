<?php
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "Y";
 if (!isset($section->parametrs->param13) || $section->parametrs->param13=='') $section->parametrs->param13 = ", ";
 if (!isset($section->parametrs->param6) || $section->parametrs->param6=='') $section->parametrs->param6 = "{$__data->prj->vars->sitecompany}";
 if (!isset($section->parametrs->param7) || $section->parametrs->param7=='') $section->parametrs->param7 = "{$__data->prj->vars->sitephone}";
 if (!isset($section->parametrs->param8) || $section->parametrs->param8=='') $section->parametrs->param8 = "{$__data->prj->vars->sitepostcode}";
 if (!isset($section->parametrs->param9) || $section->parametrs->param9=='') $section->parametrs->param9 = "{$__data->prj->vars->siteregion}";
 if (!isset($section->parametrs->param10) || $section->parametrs->param10=='') $section->parametrs->param10 = "{$__data->prj->vars->sitelocality}";
 if (!isset($section->parametrs->param11) || $section->parametrs->param11=='') $section->parametrs->param11 = "{$__data->prj->vars->siteaddr}";
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
