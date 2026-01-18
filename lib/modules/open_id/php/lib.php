<?php

if (!function_exists('genRandomPassword')){
    function genRandomPassword ($len=6, $char_list='a-z,0-9') {
  $chars = array();
  // предустановленные наборы символов
  $chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
  $chars['A-Z'] = strtoupper($chars['a-z']);
  $chars['0-9'] = '0123456789';
  $chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';
  
  // набор символов для генерации
  $charset = '';
  // пароль
  $password = '';
  
  if (!empty($char_list)) {
   $char_types = explode(',', $char_list);
   
   foreach ($char_types as $type) {
    if (array_key_exists($type, $chars)) {
     $charset .= $chars[$type];
    } else {
     $charset .= $type;
    }
   }
  }
  
  for ($i=0; $i<$len; $i++) {
   $password .= $charset[ rand(0, strlen($charset)-1) ];
  }
  
  return $password;
    }
}
?>