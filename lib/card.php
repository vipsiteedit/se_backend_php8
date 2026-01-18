<?php

function checkcard($pin, $hash = '')
{

    $sid = session_id() . $hash;
    $pin_dir = $_SERVER['DOCUMENT_ROOT'] . '/system/pin/';

    if (file_exists($pin_dir . $sid . '.dat')) {
        $npin = join('', file($pin_dir . $sid . '.dat'));
        return ($npin == $pin);
    } else
        return false;
}
