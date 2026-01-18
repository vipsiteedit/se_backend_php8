<?php

$groups = array();
if ($baseGr) {
    $groups[] = $baseGr;
    $gr = array($baseGr);
    do {
        $tbl = new seTable('shop_group', 'sg');
        $tbl->select("sg.id");
        $tbl->andWhere("sg.lang = '$lang'");
        $tbl->where("sg.upid IN ('" . join("', '", $gr) . "')");
        $gr = array();
        foreach ($tbl->getList() as $v) {
            $gr[] = $v['id'];
            $groups[] = $v['id'];
        }
    } while (count($gr));
}
$tbl = new seTable("shop_price", "sp");
$tbl->select("DISTINCT sp.manufacturer AS name");
$tbl->where("sp.lang = '$lang'");
if ($baseGr) {
    $grs = "'" . join("', '", $groups) . "'";
    $tbl->andWhere("((sp.id_group IN ($grs)) OR 
                     (sp.id IN 
                        (SELECT sgp.price_id 
                            FROM shop_group_price sgp
                            WHERE
                                sgp.group_id IN ($grs))))");
}
foreach ($tbl->getList() as $v) {
    if (!empty($v['name'])) {
        $v['sel'] = intval($manufacture == $v['name']);
        $__data->setItemList($section, 'manufacture', $v);
    }
}
unset($tbl);

?>