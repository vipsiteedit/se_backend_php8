<?php

$hidden = ($section->parametrs->param32 == 'N' && (!empty($_GET['f']) || !empty($_GET['q'])));

$page_brand = $psb->isPageBrand();

$link = array(
    'catalog' => array(
        'url' => seMultiDir() . '/' . $shoppath .  URL_END,
        'active' => !$page_brand,
    ),
    'brand' => array(
        'url' => seMultiDir() . '/' . $shoppath . '/brand' .  URL_END,
        'active' => $page_brand,
    ),
);

if ($page_brand) {
    $brand = $psb->getSelected();
}
?>