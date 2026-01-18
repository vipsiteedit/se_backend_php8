<?php

$selected = $plugin_shopgeo->getSelected();

$sc = new seTable('shop_contacts', 'sc');
$sc->select('DISTINCT sc.id, sc.name, sc.address, CONCAT_WS(", ", sc.phone, sc.additional_phones) AS phone, sc.image, sc.description');
$sc->where('sc.is_visible=1');

if (!empty($selected['id_city'])) {
    $sc->innerJoin('shop_contacts_geo scg', 'sc.id=scg.id_contact');
    if ($section->parametrs->param1 == 'Y') {
         $sc->andWhere('scg.id_city=?', $selected['id_city']);
    }
    $sc->addorderBy('scg.id_city=' . $selected['id_city'], 1);
}
$sc->addorderBy('sc.sort');
$list = $sc->getList();

if ($list) {
    foreach ($list as $val) {
        $__data->setItemList($section, 'contacts', $val);
    }
}
?>