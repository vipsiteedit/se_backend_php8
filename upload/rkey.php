<?php

if (empty($_POST['hash']) or empty($_POST['key'])) exit('no');
$s = htmlspecialchars(stripslashes($_POST['hash'] . $_POST['key']));
$ver = htmlspecialchars($_POST['ver']);
$serial = htmlspecialchars($_POST['serial']);
$domain = htmlspecialchars($_POST['domain']);
chdir("../");
$path = getcwd() . "/system/.key";

if (file_exists($path))  unlink($path);

$f = fopen($path, "w+");
fwrite($f, $ver . md5($domain) . $s);
fclose($f);
echo 'ok';
