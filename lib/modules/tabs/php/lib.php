<?php

if (!function_exists('tabs_parser_link')){
function tabs_parser_link($text){
    $text = preg_replace("/([\"\'])([\w\d\-_]+)\.html/u", "$1".seMultiDir()."/$2/", $text);
    $text = str_replace(array('&#124;','&#10;',"\n\n"), array('|',"\n", "\n"), $text);
    $text = preg_replace("/([\"\'])(images|skin|files)\//", "$1/".SE_DIR."$2/", $text);
    return $text;
}}
?>