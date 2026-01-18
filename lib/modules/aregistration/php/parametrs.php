<?php
 if (!isset($section->parametrs->param1) || $section->parametrs->param1=='') $section->parametrs->param1 = "{$__data->prj->vars->adminmail}";
 if (!isset($section->parametrs->param2) || $section->parametrs->param2=='') $section->parametrs->param2 = "1";
 if (!isset($section->parametrs->param3) || $section->parametrs->param3=='') $section->parametrs->param3 = " ";
 if (!isset($section->parametrs->param4) || $section->parametrs->param4=='') $section->parametrs->param4 = "N";
 if (!isset($section->parametrs->param5) || $section->parametrs->param5=='') $section->parametrs->param5 = "home.html";
 if (!isset($section->parametrs->param20) || $section->parametrs->param20=='') $section->parametrs->param20 = "Уважаемый(ая) [USERNAME]\\r\\nВы зарегистрировались на сайте [SITENAME]\\r\\n\\r\\nВаши авторизационные данные:\\r\\nЛогин: [USERLOGIN]\\r\\nПароль: [USERPASSWORD]\\r\\n\\r\\n ";
 if (!isset($section->parametrs->param23) || $section->parametrs->param23=='') $section->parametrs->param23 = "С Уважением, \\r\\nАдминистрация сайта [SITENAME]";
 if (!isset($section->parametrs->param24) || $section->parametrs->param24=='') $section->parametrs->param24 = "Уважаемый Администратор сайта [SITENAME]!\\r\\nУ Вас зарегистрировался пользователь [USERNAME]\\r\\nЕго регистрационные данные:\\r\\nЛогин: [USERLOGIN]\\r\\nE-mail: [USEREMAIL]";
 if (!isset($section->parametrs->param33) || $section->parametrs->param33=='') $section->parametrs->param33 = "license.html";
 if (!isset($section->parametrs->param46) || $section->parametrs->param46=='') $section->parametrs->param46 = "Y";
 if (!isset($section->parametrs->param47) || $section->parametrs->param47=='') $section->parametrs->param47 = "Y";
 if (!isset($section->parametrs->param48) || $section->parametrs->param48=='') $section->parametrs->param48 = "+7(999) 999-9999";
 if (!isset($section->parametrs->param51) || $section->parametrs->param51=='') $section->parametrs->param51 = "N";
 if (!isset($section->parametrs->param52) || $section->parametrs->param52=='') $section->parametrs->param52 = "Нажимая кнопку «Отправить», я принимаю условия <a class=\"lnkLicense\" href=\"/license/\" target=\"_blank\">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных";
 if (!isset($section->parametrs->param53) || $section->parametrs->param53=='') $section->parametrs->param53 = "N";
 if (!isset($section->parametrs->param54) || $section->parametrs->param54=='') $section->parametrs->param54 = "Принимаю <a href=\"#\" target=\"_blank\">условия</a>";
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