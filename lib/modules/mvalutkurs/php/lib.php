<?php

 function cpyN($str, $n = 0) {
  $_str = "";
  for (; $n; --$n) {
   $_str .= $str;
  }
  return $_str;
 }
 function beatiful($html, $nl = "\r\n", $level = "\t", $count = 0) {
  $_html = $html;
  $res = "";
  while (preg_match("/([\w\W]*?)(<\s*([\w]+)\s*[^>]*?>)([\w\W]*)/i", $_html, $tag)) {
   $begin = trim($tag[1]); 
   $_tag = $tag[2]; 
   $code_tag = $tag[3]; 
   $end = trim($tag[4]); 
   $res .= $begin . cpyN($level, $count) . $_tag . $nl;
   preg_match("/([\w\W]*?)<\/$code_tag>([\w\W]*)/i", $end, $tag);
   $intags = trim($tag[1]);
   $_html = trim($tag[2]);
   $res .= beatiful($intags, $nl, $level, $count + 1) . $nl . cpyN($level, $count) . "</$code_tag>";
   if ($_html) {
    $res .= $nl;
   }
  }
  if ($_html) {
   $res .= cpyN($level, $count) . $_html;
  }
  return $res;
 }
?>