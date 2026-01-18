<?php

if (!function_exists('getTreeListGroup')) {
    function getTreeListGroup(&$list, $start, &$shopcatgr) {
        if (!$shopcatgr && !strlen($shopsearch)) {
            return array();
        }
//        echo "[$start]-[$shopcatgr]-[$shopsearch]<br>";
        $shgroup = new seTable("shop_group", "sg");//seShopGroup();
        $shgroup->select("sg.name,sg.id,sg.code_gr,sg.upid");
        $shgroup->find($shopcatgr);
        if ($shgroup->id) {
            $list[] = array('name' => $shgroup->name, 'id' => $shopcatgr, 'code' => $shgroup->code_gr);
            if (($shgroup->upid != $start) && (intval($shgroup->upid) != 0)) {
                getTreeListGroup($list, $start, $shgroup->upid);
            } else {
                return $list;
            }
        } else {
            return array();
        }
    }
}

if (!function_exists('getTreeGroup')) {
    function getTreeGroup($list, $shopcatgr) {
        $shgroup = new seShopGroup();
        $shgroup->select('id, scount');
        $shgroup->where('upid = ?', $shopcatgr);
        $shgroup->andwhere("active = 'Y'");
        $glist = $shgroup->getList();
        foreach ($glist as $item) {
            $list[] = $item;
            $list = getTreeGroup($list, $item['id']);
        // Перебираем деревья до встречи с группой
        }
        return $list;
    }
}

if (!function_exists('getCountGoods')) {
    function getCountGoods($shopcatgr) {
        $list = array();
        $list = getTreeGroup($list, $shopcatgr);
        $scount = 0;
        foreach ($list as $value) {
            $scount += $value['scount'];
        }
        return $scount;
    }
}

?>