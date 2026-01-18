<?php

if (!function_exists('Mail001_UnSpecChars')){
function Mail001_UnSpecChars($str)
{
    $res = '';
    $arr_search = Array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;');
    $arr_replc = Array('&', '"', '\'', '<', '>');
    $res = str_replace($arr_search, $arr_replc, $str);
    return $res;
}}

if (!function_exists('CheckMail')){
function CheckMail($name)
{
        if (preg_match("/[0-9a-z_\-]+@([0-9a-z_\-^\.]+\.[a-z]{2,4})$/i", $name, $matches))
 //           if (getmxrr($matches[1], $arr))
            return true;
   return false;
}}   

if (!function_exists('se_getcharset')){
function se_getcharset()
{
 return  'utf-8'; 
}}
?>