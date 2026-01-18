<?php

if (!function_exists('getTreeListGroup')) {
    function getTreeListGroup(&$list, $start, &$shopcatgr, $shopsearch = '') {
        if (!$shopcatgr && !strlen($shopsearch)) {
            return array();
        }
//        echo "[$start]-[$shopcatgr]-[$shopsearch]<br>";
        $shgroup = new seTable("shop_group", "sg");//seShopGroup();
        $shgroup->select("sg.name,sg.id,sg.code_gr,sg.upid");
        if (strlen($shopsearch)) {
            $shgroup->innerjoin("shop_price sp", "sp.id_group = sg.id");
            $shgroup->where("sp.article LIKE '%{$shopsearch}%'");
            $shgroup->orWhere("sp.name LIKE '%{$shopsearch}%'");
            $shgroup->orWhere("sp.note LIKE '%{$shopsearch}%'");
            $shgroup->orWhere("sp.text LIKE '%{$shopsearch}%'");
///            echo $shgroup->getSQL() . '<br>';
            $shgroup->fetchOne();
            $shopcatgr = $shgroup->id;
        } else { 
            $shgroup->find($shopcatgr);
        }
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
    function getTreeGroup(&$list, $shopcatgr) {
        $shgroup = new seShopGroup();
        $shgroup->select('id, scount');
        $shgroup->where('upid = ?', $shopcatgr);
        $shgroup->andwhere("active = 'Y'");
        $glist = $shgroup->getList();
        foreach ($glist as $item) {
            $list[] = $item;
            getTreeGroup($list, $item['id']);
        // Перебираем деревья до встречи с группой
        }
    }
}

if (!function_exists('getCountGoods')) {
    function getCountGoods($shopcatgr) {
        $list = array();
        getTreeGroup($list, $shopcatgr);
        $scount = 0;
        foreach ($list as $value) {
            $scount += $value['scount'];
        }
        return $scount;
    }
}

?>