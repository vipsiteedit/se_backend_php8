<?php

$tbl = new seTable("forum_area","fa");
$tbl->where("lang='?'" , $lang);   // выводим только нужны язык
$tbl->orderby("order_id");

$ra = $tbl->getList();
unset($tbl);
if (!empty($ra)) {
    foreach ($ra as $adm_forum) {
        $data = array(
            'area_id' => htmlspecialchars($adm_forum['id'], ENT_QUOTES),
            'ar_order_id' => $adm_forum['order_id'],
            'name' => htmlspecialchars($adm_forum['name'], ENT_QUOTES)
        );
        $tbl = new seTable("forum_forums","ff");
        
        $tbl->where("id_area = '?'", $data['area_id']);
        $tbl->andwhere("lang='?'" , $lang);   // выводим только нужны язык
        $tbl->orderby(order_id);
        $rf = $tbl->getList();
        unset($tbl);
        foreach ($rf as $result) {
            $data1 = array(
                'id_forum' => $result['id'],
                'name' => htmlspecialchars($result['name'], ENT_QUOTES), 
                'forder_id' => $result['order_id'], 
                'description' => htmlspecialchars($result['description'], ENT_QUOTES) 
            );
            $tbl = new seTable("forum_topic","ft");
            $tbl->where("id_forums = '?'", $data1['id_forum']);
            $rt = $tbl->getList();
            unset($tbl);
            $data1['count'] = count($rt); 
            $__data->setItemList($section, 'topics'.$data['area_id'], $data1);            
        }
        $__data->setItemList($section, 'alldata', $data);
    }
}
?>