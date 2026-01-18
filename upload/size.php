<?php

if (!(isset($_POST['session'], $_POST['domain']))) {
  header('HTTP/1.0 404');
  exit();
}

require_once "function.php";

$session = htmlspecialchars(addslashes($_POST['session']));
$domain = $_POST['domain'];

if (!checkSID($session)) exit("no");

$path = getcwd() . "/data/";

$fname = $path . $session . ".sid";
$f = fopen($fname, "r");
$st = fgets($f);
fclose($f);

if ($st == 0) $st = 200;
$hostsize = $st * 1024 * 1024;
$path = "..";


// ������� ������ ���������
$delsize = 0;

$fname = getcwd() . "/data/dellist";

if (file_exists($fname)) {
  $fdels = gzfile($fname);
  foreach ($fdels as $fdel) {
    $fdel = trim(cutUpDir($fdel)); //�������� ������� "../"
    if (is_file($path . $fdel)) $delsize += filesize($path . $fdel);
    if (is_dir($path . $fdel)) $delsize += OneDirSize($path . $fdel);
  }
}

$size = 0;
//echo (DirSize($path)-$delsize).":".$hostsize;
echo "0:$hostsize";
