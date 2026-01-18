<?php
error_reporting(0);
$data = array();
$answer = array();

if (!empty($_SERVER['REDIRECT_HTTPS']) && $_SERVER['REDIRECT_HTTPS'] == 'on')
    $HTTP = 'https://';
else
    if (!empty($_SERVER['REQUEST_SCHEME'])) {
        $HTTP = $_SERVER['REQUEST_SCHEME'].'://';
    } else {
        $HTTP = ((!$_SERVER['HTTPS'] || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://');
    }

$data['project'] = $_SERVER['HTTP_HOST'];
$data['hostApi'] = $HTTP . $_SERVER['HTTP_HOST'];
$answer['status'] = "ok";
$answer['data'] = $data;
echo json_encode($answer);