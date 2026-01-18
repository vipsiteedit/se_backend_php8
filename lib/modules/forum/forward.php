<?php

$link=str_replace("\n", "", htmlspecialchars($_GET['link'], ENT_QUOTES));

$link=urldecode(str_replace("&amp;", "&", $link));

Header("Location: $link");
exit();
?>