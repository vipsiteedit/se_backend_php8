<?php

if (!function_exists('getMyGroups')) {
//    function getMyGroups($section, $groups, $cur, $upid, $level = 0, $oth_groups = array()) {
    function getMyGroups($section, $groups, $upid, $level = 0, $oth_groups = array()) {
        $tbl = new seTable('shop_group', 'sg');
        $tbl->select("sg.*,                            
                            (SELECT COUNT(sg2.id)
                                FROM shop_group sg2
                                WHERE
                                   (sg2.upid = sg.id) AND
                                   (sg2.lang = sg.lang) AND
                                   (sg2.active = 'Y')) AS subgr");
        if ($upid) {
            $tbl->where("sg.upid = '$upid'");
        } else {                      
            $tbl->where("((sg.upid = '0') OR (sg.upid IS NULL))");
        }
        $tbl->andWhere("sg.lang = '?'", se_getlang());
        $tbl->andWhere("sg.active = 'Y'");
        $tbl->orderby("position");
//echo $tbl->getSQL() . '<br>';
        foreach ($tbl->getList() as $v) {
            $groups[$v['id']] = array(
                                    'id' => $v['id'],
                                    'code' => $v['code_gr'],
                                    'name' => trim($v['name']),
                                    'image' => trim($v['picture']),
                                    'count' => intval($v['scount']),
                                    'mycount' => intval($v['scount']),
//                                    'choose' => 0,
                                    'choose' => in_array($v['id'], $oth_groups),
                                    'parent' => $upid,
                                    'level' => $level,
                                    'sub' => intval($v['subgr'])
                                );
       
            if ((($section->parametrs->param13 != 'Y') && ($section->parametrs->param10 == 'Y')) || 
//                in_array($v['id'], $oth_groups) || 
//                ($groups[$v['id']]['choose'] == 2)) {
                $groups[$v['id']]['choose']) {
//                $groups = getMyGroups($section, $groups, $cur, $v['id'], $level + 1, $oth_groups);
                $groups = getMyGroups($section, $groups, $v['id'], $level + 1, $oth_groups);
                if (isset($groups[$upid]) && ((($section->parametrs->param13 != 'Y') && ($section->parametrs->param10 == 'Y')))) {
                    $groups[$upid]['count'] += $groups[$v['id']]['count'];
                }
            }
        }
        unset($tbl);
        return $groups;
    }
}

?>
