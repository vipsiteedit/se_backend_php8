<?php

$phone = $section->parametrs->param2;
$phone_href = '';

if ($selected = $plugin_shopgeo->getSelected()) {
    if (!empty($selected['id_city'])) {
        $sc = new seTable('shop_contacts', 'sc');
        $sc->select('sc.id, sc.name, sc.address, sc.phone, sc.image, sc.description');
        $sc->innerJoin('shop_contacts_geo scg', 'sc.id=scg.id_contact');
        $sc->where('sc.is_visible=1');
        $sc->andWhere('scg.id_city=?', $selected['id_city']);
        $sc->addorderBy('sc.sort');
        if ($sc->fetchOne()) {
            $phone = $sc->phone;
        }
    }
}

if ($phone) {
    $phone_href = preg_replace('/[^\d\+]/', '', $phone);
    if ($phone_href[0] == '8') {
        $phone_href = '+7' . substr($phone_href, 1);
    }
}

/*
$( document ).ajaxSuccess(function(event, xhr, settings) {
    if (xhr.responseJSON && xhr.responseJSON.set) {
        console.log('выбран новый город');
    }
})
*/

?>