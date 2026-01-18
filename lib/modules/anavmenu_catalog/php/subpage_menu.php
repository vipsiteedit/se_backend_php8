<?php

$counts = count($item->items);
$section->parametrs->param15 = ($counts <= $section->parametrs->param15) ? $counts : $section->parametrs->param15;
$count_width = str_replace(',','.',(100 / $counts));

?>