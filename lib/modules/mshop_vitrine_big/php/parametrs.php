<?php
 if (empty($section->parametrs->param50)) $section->parametrs->param50 = "shopcart";
 if (empty($section->parametrs->param273)) $section->parametrs->param273 = "";
 if (empty($section->parametrs->param60)) $section->parametrs->param60 = "Y";
 if (empty($section->parametrs->param15)) $section->parametrs->param15 = "name";
 if (empty($section->parametrs->param291)) $section->parametrs->param291 = "0";
 if (empty($section->parametrs->param183)) $section->parametrs->param183 = "N";
 if (empty($section->parametrs->param184)) $section->parametrs->param184 = "v";
 if (empty($section->parametrs->param207)) $section->parametrs->param207 = "-1";
 if (empty($section->parametrs->param243)) $section->parametrs->param243 = "N";
 if (empty($section->parametrs->param276)) $section->parametrs->param276 = "N";
 if (empty($section->parametrs->param113)) $section->parametrs->param113 = "Y";
 if (empty($section->parametrs->param237)) $section->parametrs->param237 = "60";
 if (empty($section->parametrs->param64)) $section->parametrs->param64 = "20";
 if (empty($section->parametrs->param235)) $section->parametrs->param235 = "30";
 if (empty($section->parametrs->param259)) $section->parametrs->param259 = "Y";
 if (empty($section->parametrs->param275)) $section->parametrs->param275 = "N";
 if (empty($section->parametrs->param85)) $section->parametrs->param85 = "Y";
 if (empty($section->parametrs->param269)) $section->parametrs->param269 = "N";
 if (empty($section->parametrs->param254)) $section->parametrs->param254 = "N";
 if (empty($section->parametrs->param295)) $section->parametrs->param295 = "Y";
 if (empty($section->parametrs->param294)) $section->parametrs->param294 = "";
 if (empty($section->parametrs->param292)) $section->parametrs->param292 = "";
 if (empty($section->parametrs->param306)) $section->parametrs->param306 = "N";
 if (empty($section->parametrs->param289)) $section->parametrs->param289 = "200";
 if (empty($section->parametrs->param114)) $section->parametrs->param114 = "Y";
 if (empty($section->parametrs->param115)) $section->parametrs->param115 = "N";
 if (empty($section->parametrs->param83)) $section->parametrs->param83 = "Y";
 if (empty($section->parametrs->param84)) $section->parametrs->param84 = "Y";
 if (empty($section->parametrs->param111)) $section->parametrs->param111 = "Y";
 if (empty($section->parametrs->param226)) $section->parametrs->param226 = "N";
 if (empty($section->parametrs->param206)) $section->parametrs->param206 = "100";
 if (empty($section->parametrs->param57)) $section->parametrs->param57 = "Y";
 if (empty($section->parametrs->param73)) $section->parametrs->param73 = "Y";
 if (empty($section->parametrs->param74)) $section->parametrs->param74 = "Y";
 if (empty($section->parametrs->param65)) $section->parametrs->param65 = "N";
 if (empty($section->parametrs->param55)) $section->parametrs->param55 = "Y";
 if (empty($section->parametrs->param66)) $section->parametrs->param66 = "N";
 if (empty($section->parametrs->param205)) $section->parametrs->param205 = "N";
 if (empty($section->parametrs->param274)) $section->parametrs->param274 = "#";
 if (empty($section->parametrs->param279)) $section->parametrs->param279 = "N";
 if (empty($section->parametrs->param297)) $section->parametrs->param297 = "H1";
 if (empty($section->parametrs->param246)) $section->parametrs->param246 = "Y";
 if (empty($section->parametrs->param282)) $section->parametrs->param282 = "N";
 if (empty($section->parametrs->param293)) $section->parametrs->param293 = "500";
 if (empty($section->parametrs->param286)) $section->parametrs->param286 = "350";
 if (empty($section->parametrs->param284)) $section->parametrs->param284 = "350";
 if (empty($section->parametrs->param126)) $section->parametrs->param126 = "Y";
 if (empty($section->parametrs->param129)) $section->parametrs->param129 = "Y";
 if (empty($section->parametrs->param128)) $section->parametrs->param128 = "Y";
 if (empty($section->parametrs->param133)) $section->parametrs->param133 = "Y";
 if (empty($section->parametrs->param134)) $section->parametrs->param134 = "Y";
 if (empty($section->parametrs->param305)) $section->parametrs->param305 = "auto";
 if (empty($section->parametrs->param303)) $section->parametrs->param303 = "{name} - купить по цене {new price}: описание, характеристики, фото, отзывы";
 if (empty($section->parametrs->param304)) $section->parametrs->param304 = "Купить «{название товара} {производитель}» за {новая цена}[ со скидкой {скидка}%]. {описание товара}";
 if (empty($section->parametrs->param271)) $section->parametrs->param271 = "N";
 if (empty($section->parametrs->param258)) $section->parametrs->param258 = "N";
 if (empty($section->parametrs->param233)) $section->parametrs->param233 = "Y";
 if (empty($section->parametrs->param285)) $section->parametrs->param285 = "100";
 if (empty($section->parametrs->param298)) $section->parametrs->param298 = "500";
 if (empty($section->parametrs->param217)) $section->parametrs->param217 = "Y";
 if (empty($section->parametrs->param266)) $section->parametrs->param266 = "N";
 if (empty($section->parametrs->param224)) $section->parametrs->param224 = "optovik";
 if (empty($section->parametrs->param225)) $section->parametrs->param225 = "optcorp";
 if (empty($section->parametrs->param296)) $section->parametrs->param296 = "N";
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