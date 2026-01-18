<?php

$ext_serial = $_POST['serial'];
$ext_half1 = $_POST['half1'];
$ext_session = $_POST['session'];

if (!(isset($ext_serial, $ext_half1))) {
  header('HTTP/1.0 404');
  exit();
}

umask(0000);

require_once "function.php";

$serial = htmlspecialchars(addslashes($ext_serial));
$half1 = htmlspecialchars(addslashes($ext_half1));
@$session = htmlspecialchars(addslashes($ext_session));

$half = join("", file("../system/.rkey"));
$half2 = substr($half, 35, 10);
$sk = substr($half, 45, 32);

if (md5($half1 . $half2) != $sk) exit('no! ' . $half1 . ' ' . $half2 . ' ' . $sk);

$path = getcwd() . "/data";


if ($session !== "") { //���� �������� ���������� ������
  $fname = $path . "/" . $session . ".sid";
  if (file_exists($fname)) exit("yes");
  else exit("nof");
}

//������ ����� ������
if (empty($session))  upload_del_badfile();

if (!file_exists($path)) mkdir($path, SE_DIR_PERMISSIONS);
$session = md5($serial . date("U"));
//$path=getcwd()."/data";
//mkdir($path);
$size = 1000;
$fname = $path . "/" . $session . ".sid";
$f = fopen($fname, "w");
fputs($f, $size . "\n");
fclose($f);

chmod($fname, SE_FILE_PERMISSIONS);

$time = time();
while (strlen($time) < 11) {
  $time = '0' . $time;
}
echo "new " . $session . $time . '3.9.1';
