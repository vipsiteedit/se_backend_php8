<?php

$counts = count($item->items);
$counts = ($counts <= $section->parametrs->param15) ? $counts : $section->parametrs->param15;
$count_width = str_replace(',','.',(100 / $counts));

?>