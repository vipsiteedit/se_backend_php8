<?php
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "n";
 if (!isset($section->parametrs->param32) || $section->parametrs->param32=='') $section->parametrs->param32 = "N";
 if (!isset($section->parametrs->param37) || $section->parametrs->param37=='') $section->parametrs->param37 = "Подробнее";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = "15";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "400";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "150x150";
 if (!isset($section->parametrs->param39) || $section->parametrs->param39=='') $section->parametrs->param39 = "200x150";
 if (!isset($section->parametrs->param17) || $section->parametrs->param17=='') $section->parametrs->param17 = "250";
 if (!isset($section->parametrs->param38) || $section->parametrs->param38=='') $section->parametrs->param38 = "news";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "";
 if (!isset($section->parametrs->param31) || $section->parametrs->param31=='') $section->parametrs->param31 = "Y";
 if (!isset($section->parametrs->param45) || $section->parametrs->param45=='') $section->parametrs->param45 = "false";
 if (!isset($section->parametrs->param44) || $section->parametrs->param44=='') $section->parametrs->param44 = "3000";
 if (!isset($section->parametrs->param47) || $section->parametrs->param47=='') $section->parametrs->param47 = "false";
 if (!isset($section->parametrs->param46) || $section->parametrs->param46=='') $section->parametrs->param46 = "false";
 if (!isset($section->parametrs->param48) || $section->parametrs->param48=='') $section->parametrs->param48 = "300";
 if (!isset($section->parametrs->param49) || $section->parametrs->param49=='') $section->parametrs->param49 = "3";
 if (!isset($section->parametrs->param51) || $section->parametrs->param51=='') $section->parametrs->param51 = "slide";
 if (!isset($section->parametrs->param52) || $section->parametrs->param52=='') $section->parametrs->param52 = "false";
 if (!isset($section->parametrs->param50) || $section->parametrs->param50=='') $section->parametrs->param50 = "'auto'";
 if (!isset($section->parametrs->param53) || $section->parametrs->param53=='') $section->parametrs->param53 = "true";
 if (!isset($section->parametrs->param54) || $section->parametrs->param54=='') $section->parametrs->param54 = "true";
 if (!isset($section->parametrs->param55) || $section->parametrs->param55=='') $section->parametrs->param55 = "10";
 if (!isset($section->parametrs->param56) || $section->parametrs->param56=='') $section->parametrs->param56 = "h3";
 if (!isset($section->parametrs->param57) || $section->parametrs->param57=='') $section->parametrs->param57 = "N";
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