<?php
error_reporting(0);

$pin = "";
for ($i = 1; $i <= 5; $i++) $pin .= mt_rand(0, 9);


$time = time();
if (!isset($_GET['session']))
  exit;
$hash = htmlspecialchars($_GET['hash'], ENT_QUOTES);
$hash = ($hash) ? $hash : '';
$sid = htmlspecialchars($_GET['session'], ENT_QUOTES) . $hash;
$pin_dir  = $_SERVER["DOCUMENT_ROOT"] . '/system/pin/';
$font_dir = $_SERVER["DOCUMENT_ROOT"] . '/lib/fonts/';


if (!is_dir($pin_dir)) mkdir($pin_dir, 0770);
if (chdir($pin_dir)) {
  $d = opendir(".");
  while (($f = readdir($d)) !== false) {
    if (is_file($f) && (filemtime($f) + 600 < time())) unlink($f);
  }
  closedir($d);
  chdir("..");
}


$fp = fopen($pin_dir . $sid . '.dat', "w+");
fwrite($fp, $pin);
fclose($fp);

define("HORIZ", 150); //250
define("VERT", 30); //55

$im = imagecreate(HORIZ, VERT);
$cbg = imagecolorallocate($im, 255, 255, 255);
imagecolortransparent($im, $cbg);
$colblack = imagecolorallocate($im, 0, 0, 0);

// �����
for ($i = 1; $i <= 10; $i++) {
  $r = mt_rand(0, 255);
  $g = mt_rand(0, 255);
  $b = mt_rand(0, 255);
  $c[$i] = imageColorAllocate($im, $r, $g, $b);
}
$r = mt_rand(100, 255);
$g = mt_rand(100, 255);
$b = mt_rand(100, 255);
$c[0] = imageColorAllocate($im, $r, $g, $b);

imageFill($im, 1, 1, $c[0]);

for ($i = 0; $i <= HORIZ; $i += $dx) {
  $dx = mt_rand(5, 20);
  imageline($im, $i, 0, $i, VERT - 1, $colblack);
}

for ($i = 0; $i <= VERT; $i += $dy) {
  $dy = mt_rand(5, 20);
  imageline($im, 0, $i, HORIZ - 1, $i, $colblack);
}

for ($i = 0; $i <= 45; $i++) {
  $n = mt_rand(1, 10);
  $x1 = mt_rand(0, HORIZ);
  $y1 = mt_rand(0, VERT);
  $x2 = mt_rand(0, HORIZ);
  $y2 = mt_rand(0, VERT);
  imageFilledRectangle($im, $x1, $y1, $x1 + 3, $y1 + 3, $c[$n]);
}

$x = -10;
for ($i = 0; $i <= 4; $i++) {
  $size = mt_rand(12, 16);
  $angle = mt_rand(-75, 75);
  $char = $pin[$i];
  $dy = (round(VERT - $size) / 2);
  $coord = imagettftext($im, $size, $angle, $x + 16, 24, $colblack, $font_dir . "verdana.ttf", $char);
  $x = $coord[2];
}

header("Content-type: image/gif");

imagegif($im);
imagedestroy($im);
