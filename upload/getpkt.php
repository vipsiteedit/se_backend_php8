<?php

if (!(isset($_POST['session'], $_POST['act']))) {
  header('HTTP/1.0 404');
  exit();
}

require_once "function.php";

$session = htmlspecialchars(addslashes($_POST['session']));

$act = htmlspecialchars(addslashes($_POST['act']));

if (!checkSID($session)) exit("no");

$path = getcwd() . "/data/";

switch ($act) {
  case "pkt":
    $userfile = $_FILES['userfile']['tmp_name'];
    $userfile_size = $_FILES['userfile']['size'];
    $user = $_FILES['userfile']['name'];

    $id = htmlspecialchars(addslashes($_POST['id']));
    $block = htmlspecialchars(addslashes($_POST['block']));

    $block = explode(":", $block);

    $uploadfile = $path . $id . "_" . $block[0] . ".tmp";

    if (move_uploaded_file($userfile, $uploadfile)) echo "ok";
    else exit("no");
    break;

  case "make":
    $id = htmlspecialchars(addslashes($_POST['id']));
    $name = htmlspecialchars(addslashes($_POST['name']));
    if ($name == 'list') $name = $session . ".list";
    if ($name == 'dellist') $name = $session . ".del";

    $data = "";
    $i = 1;

    while (file_exists($path . $id . "_" . $i . ".tmp")) {
      $data .= join('', file($path . $id . "_" . $i . ".tmp"));
      $i++;
    }

    $fname = $path . $name;
    $f = fopen($fname, "w+b");
    fwrite($f, $data);
    fclose($f);
    chmod($fname, 0644);

    $i = 1;
    while (file_exists($path . $id . "_" . $i . ".tmp")) {
      unlink($path . $id . "_" . $i . ".tmp");
      $i++;
    }
    echo "ok";
    break;
}
