<?php

$selected = $plugin_shopgeo->getSelected();

$name_city = $selected['city'];

$full_name = $selected['country'] . ($selected['region'] ? (', ' . $selected['region']) : '') . ', ' . $selected['city'];


if (!empty($selected['confirm'])) {
    $section->parametrs->param2 = 'N';
}
?>
