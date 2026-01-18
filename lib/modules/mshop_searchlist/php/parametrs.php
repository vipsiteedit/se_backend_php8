<?php
 if (!isset($section->parametrs->param89)) $section->parametrs->param89 = "lot";
 if (!isset($section->parametrs->param50)) $section->parametrs->param50 = "shopcart";
 if (!isset($section->parametrs->param81)) $section->parametrs->param81 = "catalogue";
 if (!isset($section->parametrs->param104)) $section->parametrs->param104 = "";
 if (!isset($section->parametrs->param77)) $section->parametrs->param77 = "N";
 if (!isset($section->parametrs->param57)) $section->parametrs->param57 = "Y";
 if (!isset($section->parametrs->param90)) $section->parametrs->param90 = "Есть";
 if (!isset($section->parametrs->param55)) $section->parametrs->param55 = "Y";
 if (!isset($section->parametrs->param56)) $section->parametrs->param56 = "Y";
 if (!isset($section->parametrs->param79)) $section->parametrs->param79 = "Y";
 if (!isset($section->parametrs->param105)) $section->parametrs->param105 = "catalogue";
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