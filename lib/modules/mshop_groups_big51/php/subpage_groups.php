<?php

if ($basegroup) {
    $groups = $psg->getChilds($basegroup);
}
else {
    $groups = $psg->getMainGroups();
}
    
if (!empty($groups)) {
    $maxcount = (int)$section->parametrs->param4;
    $psi = new plugin_ShopImages('group');
    foreach ($groups as $val) {
        $group = array();
        $group['id'] = $val['id'];
        $group['name'] = $val['name'];
        $group['title'] = se_db_output($group['name']);
        $group['link'] = seMultiDir() . '/' . $shoppath . '/cat/' . urlencode($val['code']) . '/';
        
        if (empty($val['picture_alt'])) {
            $group['image_alt'] = se_db_output($val['name']);
        } 
        else { 
            $group['image_alt'] = htmlspecialchars($val['picture_alt']);
        }
            
        if (!empty($val['image'])) {
            $group['image'] = $psi->getPictFromImage($val['image'], $section->parametrs->param28);
        }
        
        if ($group['sub'] = !empty($val['children'])) {
            $sub_groups = $psg->getChilds((int)$val['id']);
            $iicount = count($sub_groups);
            $ii = 0;
            foreach ($sub_groups as $sub) {
                $ii++;
                $group['end'] = '';
                $subgroup = array();
                $subgroup['id'] = $sub['id'];
                $subgroup['name'] = $sub['name'];
                
                if ($ii == $maxcount && $iicount > $maxcount) {
                    $subgroup['end'] = $section->parametrs->param3;
                }
                
                if ($ii > $maxcount) {
                    break;
                }
                $subgroup['title'] = se_db_output($sub['name']);
                
                $subgroup['link'] = seMultiDir() . "/$shoppath/cat/" . urlencode($sub['code']) . '/';

                if (empty($sub['picture_alt'])) {
                    $subgroup['image_alt'] = se_db_output($sub['name']);
                } 
                else { 
                    $subgroup['image_alt'] = htmlspecialchars($sub['picture_alt']);
                }
            
                if (!empty($sub['image'])) {
                    $subgroup['image'] = $psi->getPictFromImage($sub['image'], $section->parametrs->param29);
                } 

                $__data->setItemList($section, 'subgroups' . $val['id'], $subgroup);
            }
        }
            
        $__data->setItemList($section, 'groups', $group);
    }
}

?>