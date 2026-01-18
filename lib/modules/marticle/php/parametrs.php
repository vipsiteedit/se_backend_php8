<?php
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "30";
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "";
 if (!isset($section->parametrs->param15) || $section->parametrs->param15=='') $section->parametrs->param15 = "Yes";
 if (!isset($section->parametrs->param28) || $section->parametrs->param28=='') $section->parametrs->param28 = "30";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "[namepage]";
 if (!isset($section->parametrs->param36) || $section->parametrs->param36=='') $section->parametrs->param36 = "Гость";
 if (!isset($section->parametrs->param37) || $section->parametrs->param37=='') $section->parametrs->param37 = "Уважаемый, Админ! \\r\\n На сайте [SITENAME] в статье [NAMETITLE] был добавлен комментарий";
 if (!isset($section->parametrs->param38) || $section->parametrs->param38=='') $section->parametrs->param38 = "В вашей статье [NAMETITLE], написанной на сайте [SITENAME] был добавлен комментарий";
 if (!isset($section->parametrs->param39) || $section->parametrs->param39=='') $section->parametrs->param39 = "Yes";
 if (!isset($section->parametrs->param40) || $section->parametrs->param40=='') $section->parametrs->param40 = "No";
 if (!isset($section->parametrs->param42) || $section->parametrs->param42=='') $section->parametrs->param42 = "Yes";
 if (!isset($section->parametrs->param45) || $section->parametrs->param45=='') $section->parametrs->param45 = "No";
 if (!isset($section->parametrs->param44) || $section->parametrs->param44=='') $section->parametrs->param44 = "";
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
