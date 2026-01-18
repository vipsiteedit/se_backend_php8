<?php
//import_request_variables("p", "ext_");
if (!(isset($_POST['session'], $_POST['domain'], $_POST['fullsize'], $_POST['pktsize']))) {
  header('HTTP/1.0 404');
  exit();
}

require_once "function.php";

$session = htmlspecialchars(addslashes($_POST['session']));
$domain = addslashes($_POST['domain']);
$fullsize = htmlspecialchars(addslashes($_POST['fullsize']));
$pktsize = htmlspecialchars(addslashes($_POST['pktsize']));

if (!checkSID($session)) exit("no");

$path=getcwd()."/data/";

$pktnum=ceil($fullsize / $pktsize);
$fname = $path.$session.".sid";
$f = fopen($fname, "a");
flock($f, LOCK_EX);
fputs($f, $fullsize."\n".$pktsize."\n".$pktnum);
fflush($f);
flock($f, LOCK_UN);
fclose($f);

//���� ������
//$st = str_repeat(" ", $fullsize);
$st ="";
$fname = $path.$session.".dat";
$f = fopen($fname, "w");
flock($f, LOCK_EX);
fwrite($f, $st);
fflush($f);
flock($f, LOCK_UN);
fclose($f);

echo "ok";
