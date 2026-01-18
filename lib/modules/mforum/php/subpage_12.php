<?php

$link = str_replace ("\n", "", getRequest ('link',3));//], ENT_QUOTES));
$link = urldecode (str_replace("&amp;", "&", $link));
Header("Location: ".seMultiDir().$link);
exit();
// 12

?>