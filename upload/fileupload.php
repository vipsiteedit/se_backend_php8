<?php
error_reporting(0);

if (!(isset($_POST['sid'], $_POST['fld']))) {
  header('HTTP/1.0 404');
  exit();
}
set_time_limit(0);
ini_set('memory_limit', '500M');
require_once "function.php";

$session = htmlspecialchars(addslashes($_POST['sid']));
//$domain = addslashes($ext_dm);
$folder = addslashes($_POST['fld']);
$outname = addslashes($_POST['nm']);
$typch = addslashes($_POST['tp']);

if (!checkSID($session)) exit("no session");


$fldarr = explode('/', $folder);

$FILE_DIR = '/' . @$fldarr[1] . "/" . @$fldarr[0] . "/" . @$fldarr[2] . "/";

chdir("../");
$cwd = getcwd();

if ($typch == 'l' && is_dir($cwd . $FILE_DIR)) {
  if (chdir($cwd . $FILE_DIR)) {
    $d = opendir(".");
    while (($f = readdir($d)) !== false) {
      if ($f == '.' || $f == '..') continue;
      if (is_file($f)) echo $f . "\t" . filesize($f) . "\t0\r\n";
    }
    closedir($d);
    chdir($cwd);
  }
}

if ($typch == 'g') {
  $FILE_DIR = '/' . $fldarr[2] . "/" . $fldarr[3] . "/" . $fldarr[4] . "/";
  if (file_exists($cwd . $FILE_DIR . $outname)) {
    echo 'ok';
  } else echo 'no';
  exit;
}

if ($typch == 'u') {
  if (!is_dir($cwd . "/" . @$fldarr[1])) mkdir($cwd . "/" . @$fldarr[1]);
  if (!is_dir($cwd . "/" . @$fldarr[1] . "/" . @$fldarr[0])) mkdir($cwd . "/" . @$fldarr[1] . "/" . @$fldarr[0]);
  if (!is_dir($cwd . $FILE_DIR)) mkdir($cwd . $FILE_DIR);


  if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

    $userfile = $_FILES['userfile']['tmp_name'];
    $userfile_size = $_FILES['userfile']['size'];
    $user = strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));

    if (preg_match("/^.+\.(php|pl|phtml)$/u", $user)) {
      $flag = false;
      exit('error 01');
    }

    if ($userfile_size > 512000000) {
      $flag = false;
      exit('error 02');
    }
    $file = true;
  }

  if ($file) {
    $uploadfile     = $cwd . $FILE_DIR . $outname;
    move_uploaded_file($userfile, $uploadfile);
    if ($fldarr[2] == 'shopimg') {
      $ext = end(explode('.', $uploadfile));
      unlink(substr($uploadfile, 0, (0 - strlen($ext) - 1)) . '_prev.' . $ext);
      unlink(substr($uploadfile, 0, (0 - strlen($ext) - 1)) . '_mid.' . $ext);
    }
    if (file_exists($cwd . $FILE_DIR . $outname)) echo "ok";
    else echo "no";
  } else echo "no find file";
}


if ($typch == 'd') {
  if (!is_dir($cwd . "/" . @$fldarr[1])) mkdir($cwd . "/" . @$fldarr[1]);
  if (!is_dir($cwd . "/" . @$fldarr[1] . "/" . @$fldarr[0])) mkdir($cwd . "/" . @$fldarr[1] . "/" . @$fldarr[0]);
  if (!is_dir($cwd . $FILE_DIR)) mkdir($cwd . $FILE_DIR);

  if (file_exists($cwd . $FILE_DIR . $outname)) {
    @unlink($cwd . $FILE_DIR . $outname);
    echo "ok";
  } else echo "ok";
}
