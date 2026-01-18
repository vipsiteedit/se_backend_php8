<?php

$token = $_POST['token'];
if (!empty($token) && strlen($token) == 32) {
     $token = md5($token);
     if (!is_dir(dirname(__FILE__) . '/data')) mkdir(dirname(__FILE__) . '/data');
     $fp = fopen(dirname(__FILE__) . '/data/' . $token . '.sid', "w+");
     fclose($fp);
     echo "OK";
}
