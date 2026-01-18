<?php

//$section->parametrs->param8 = (!seUserId() && !seUserGroup()) ? seMultiDir() . '/' . $__data->getPageName().URL_END.'#login/'.$section->parametrs->param8  : seMultiDir() . '/' . $section->parametrs->param8 . URL_END;
$isAuth = (seUserId() < 1 && seUserGroup() == 0);
?>