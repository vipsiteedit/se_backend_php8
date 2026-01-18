<?php

        $path_imggroup = '/images/' . se_getlang() . '/shopgroup/';
        $def = $level = $cur_lvl = 0;
        $result = '';
        $itemid = 0;
        foreach ($res as $v) {
            $itemid++;
            $v['choose'] = !empty($parents) && in_array($v['id'], $parents);
            $v['itemid'] = $itemid;
            $v['def'] = $def;
            if (!$def) {
                $level = $cur_lvl = $v['level'];           
                $def = 1;
            } else {
                if ($v['level'] > $cur_lvl) {
                   $v['show_type'] = 'item';
                   $v['isparent'] = $res[$v['parent']]['choose'];
                } else {
                    for (; $cur_lvl > $v['level']; --$cur_lvl) {
                        $__data->setItemList($section, 'menuend'.$itemid, array('item'));
                    }
                }
                $cur_lvl = $v['level'];
            }
            $v['txtsh'] = 1;
            $v['showimg'] = 0;
            if (($v['image'] != '') && file_exists(getcwd() . $path_imggroup . $v['image']) &&
                (($section->parametrs->param7 == 3) || (($section->parametrs->param7 == 2) && ($v['level'] == $level)))) {
                $v['showimg'] = 1;
                if ($section->parametrs->param8 != 'Y') {
                    $v['txtsh'] = 0;
                }
            }
            $__data->setItemList($section, 'menulist', $v);
        }
        for (; $cur_lvl > $level; --$cur_lvl) {
            $__data->setItemList($section, 'menuend', array('item'));
        }

?>
