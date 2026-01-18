<?php

if (!function_exists('getHtmlCatalogPicture')) {
function getHtmlCatalogPicture($section, $groups, $select_groups = array(), $level = 0, $id_up) {
    $html = '';
    if ($level)
        $html .= '<div class="submenu submenu' . $level . ' submenu_mu' . $id_up . '">';
    //$image_dir = '/images/' . se_getlang() . '/shopgroup/';
    $groupimages = new plugin_ShopImages('group');
    foreach ($groups as $val) {
        $active = false;
        
        $val['link'] = seMultiDir(). '/' . trim($section->parametrs->param1) . $val['link'];
        
        $html .= '<div class="menuUnit menuLevel' . $level . ' menuUnit' . $val['id'] . '">';

        $html .= '<a class="menu menu' . $level; 
        
        if (!empty($select_groups) && in_array($val['id'], $select_groups)) {
            $html .= ' menuActive';
            $active = true;
        }
        
        if ($section->parametrs->param10 == 'Y')
            $html .= ' menu-action" data-id="'.$val['id'].'" data-code="'.$val['code'].'" data-count="'.$val['count'].'" data-level="'.$level;
        
        $html .= '" href="' . $val['link'] . '">';
        
        
        if (!empty($val['image']) && (($section->parametrs->param7 == 3) || ($section->parametrs->param7 == 2 && $level == 0))) {
            $val['image_alt'] = ($val['image_alt']) ? $val['image_alt'] : htmlspecialchars($val['name']);
            $img = $groupimages->getPictFromImage($val['image']);
            $html .=  '<img src="' . $img . '" title="' . $val['image_alt'] . '" alt="' . $val['image_alt'] . '">';
        }    
        
        $html .=  '<span class="span">' . $val['name'];
        
        if ($section->parametrs->param2 == 'Y' && !empty($val['count']))
            $html .= ' <span class="count">(' . $val['count'] . ')</span>';
        
        $html .= '</span></a>';
        
        if (!empty($val['menu']) && ($section->parametrs->param4 == '0' || $active)) {
            $html .= getHtmlCatalogPicture($section, $val['menu'], $select_groups, $level + 1, $val['id']);
        }
        $html .= '</div>';
    }
    if ($level)
        $html .= '</div>';
    return $html;
}
}

?>