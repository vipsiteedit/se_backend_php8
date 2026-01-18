<?php

if (!function_exists('getHtmlCatalog')) {
function getHtmlCatalog($groups, $select_groups = array(), $page = '', $show_count = true, $type = 0, $level = 0) {
    $html = '';
    switch ($level) {
       case 1: $html .= '<div class="submenu">'; break;
       case 2: $html .= '<div class="submenu1">'; break;
       case 3: $html .= '<div class="submenu2">'; break;
       case 4: $html .= '<div class="submenun">'; break;
    }
    if ($level > 3)
        $html .= '<div class="submenun">';        
        
    foreach ($groups as $val) {
        $active = false;
        if (!empty($page))
            $val['link'] = seMultiDir() . '/' . $page . $val['link'];
        if (!empty($select_groups) && in_array($val['id'], $select_groups)) {
            $html .= '<a class="menu menuActive" href="' . $val['link'] . '"><span class="TextActiveMenu">' . $val['name'];
            $active = true;
        }
        else {
            $html .= '<a class="menu" href="' . $val['link'] . '"><span class="TextItemMenu">' . $val['name'];
        }
        if ($show_count && !empty($val['count']))
            $html .= ' <span class="count">(' . $val['count'] . ')</span>';
        $html .= '</span></a>';
        if (!empty($val['menu']) && ($type == '0' || $active)) {
            $html .= getHtmlCatalog($val['menu'], $select_groups, $page, $show_count, $type, $level + 1);
        }
    }
    if ($level)
        $html .= '</div>';
    return $html;
}
}
?>