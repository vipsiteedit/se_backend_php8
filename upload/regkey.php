<?php

if (empty($_POST['key'])) exit('no');
$s = substr(htmlspecialchars($_POST['key']), 0, 42);
$ver = htmlspecialchars($_POST['ver']);
$serial = htmlspecialchars($_POST['serial']);
$domain = htmlspecialchars($_POST['domain']);
chdir("../");
$path = getcwd() . "/system/.rkey";

if (file_exists($path))  unlink($path);

$f = fopen($path, "w+");
fwrite($f, $ver . md5($domain) . $s);
fclose($f);
echo 'ok';
