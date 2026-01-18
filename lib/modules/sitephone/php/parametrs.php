<?php
 if (empty($section->parametrs->param1)) $section->parametrs->param1 = "blue";
 if (empty($section->parametrs->param2)) $section->parametrs->param2 = "1";
 if (empty($section->parametrs->param3)) $section->parametrs->param3 = "phone";
 if (empty($section->parametrs->param4)) $section->parametrs->param4 = "callme";
 if (empty($section->parametrs->param5)) $section->parametrs->param5 = "nr8yWeClfwsDogFriHup87aZG7po96GvxR4s5GNkbeYAuC2NivbpClolsNGBTs6p";
   foreach($section->parametrs as $__paramitem){
    foreach($__paramitem as $__name=>$__value){
      if (empty($__value)){
      }
      if (preg_match("/\[%([\w\d\-]+)%\]/u", $__value, $m)!=false){
        $section->parametrs->$__name = $__data->prj->$m;
      }
     }
   }
?>