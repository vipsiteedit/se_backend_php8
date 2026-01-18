<?php

if (!function_exists('Anketa_SpecChars')){
    function Anketa_SpecChars($str) {
        $res = '';
        $arr_search = Array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;');
        $arr_replc = Array('&', '"', '\'', '<', '>');
        $res = str_replace($arr_search, $arr_replc, $str);
        return $res;
    }
}


if (!function_exists('Anketa_CheckMail')){
    function Anketa_CheckMail($name) {
        if (preg_match("/[0-9a-zA-Z_\.-]+@([0-9a-z_\-^\.]+\.[a-z]{2,6})$/i", $name, $matches)) {
            return true;
        }
        return false;
    }
}


?>