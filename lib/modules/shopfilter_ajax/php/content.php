<?php

$page_vitrine = trim($section->parametrs->param7);
if (empty($page_vitrine)){
    $page_vitrine = $__data->getVirtualPage('shop_vitrine');
} 

$page_vitrine = seMultiDir() . '/' . $page_vitrine . '/';

if (isRequest('cat'))
    $page_vitrine .= 'cat/' . getRequest('cat') . '/';
?>