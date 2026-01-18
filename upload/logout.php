<?php

function repldata($tmpf)
{

    preg_match_all("/(\[INCLUDE_MODUL_)\w{1,}\]/m", $tmpf, $res_math);
    $res_math = $res_math[0];
    foreach ($res_math as $mathitem) {
        $mathitem = substr($mathitem, 15, -1);
        $mitem = explode('_', $mathitem);
        $modul = $mitem[0];

        $razdel = $mitem[count($mitem) - 1];
        for ($i = 1; $i < count($mitem) - 1; $i++) {
            $modul .= "_" . $mitem[$i];
        }

        $srep = "\$__PathModul=\"\";
   if (file_exists(\"modules/typ_" . $modul . ".php\")) \$__PathModul=\"modules/\";
   else if (file_exists(\"lib/modules/typ_" . $modul . ".php\")) \$__PathModul=\"lib/modules/\";
    if (\$__PathModul!=\"\") {
      if (file_exists(\$__PathModul.\"typ_" . $modul . ".php\"))
        require_once \$__PathModul.\"typ_" . $modul . ".php\";
      if (function_exists('start_$modul')) start_" . $modul . "(\"" . $razdel . "\");
  }\n";
        $tmpf = str_replace("[INCLUDE_MODUL_" . $mathitem . "]", $srep, $tmpf);
    }

    $res_math = array();
    preg_match_all("/(\[INCLUDE_END_)\w{1,}\]/m", $tmpf, $res_math);
    $res_math = $res_math[0];
    foreach ($res_math as $mathitem) {
        $mathitem = substr($mathitem, 13, -1);
        $mitem = explode('_', $mathitem);
        $modul = $mitem[0];

        $razdel = $mitem[count($mitem) - 1];
        for ($i = 1; $i < count($mitem) - 1; $i++) {
            $modul .= "_" . $mitem[$i];
        }

        $srep = "if (function_exists('end_$modul'))  end_" . $modul . "(\"" . $razdel . "\");";
        $tmpf = str_replace("[INCLUDE_END_" . $mathitem . "]", $srep, $tmpf);
    }

    return trim($tmpf);
}

function safe_function($s)
{
    return trim($s);
}

require_once "function.php";

$session = htmlspecialchars(addslashes($_POST['session']));
$domain = htmlspecialchars(addslashes($_POST['domain']));

if (!checkSID($session)) {
    exit("no");
}

$path = getcwd() . "/data/";

//dellist

$fname = $path . $session . ".del";
chdir("../");
$www = getcwd();

umask(0000);

if (file_exists($fname)) {
    $fdels = gzfile($fname);
    foreach ($fdels as $fdel) {
        $fdel = trim(cutUpDir($fdel));
        $fname = $www . $fdel;
        // dirname
        if (is_dir($fname) && file_exists($fname) && $fdel != "/") {
            ClearDir($fname, true);
            chdir($www);
        }
        if (is_file($fname) && file_exists($fname)) {
            unlink($fname);
        }
    }
}

$fmap = gzfile($path . $session . ".list");
$fnamedat = $path . $session . ".dat";

$fd = fopen($fnamedat, "rb");
$language = "";

foreach ($fmap as $str) {
    $str = explode(chr(9), $str);
    $pack = $str[0];
    $file = $str[1];
    $seek = $str[2];
    $size = $str[3];
    // dobavleno 30.08.2005 Shchelkonogov
    $logfile = str_replace("\r\n", "", @$str[4]);
    //

    fseek($fd, $seek);
    flock($fd, LOCK_SH);
    if ($size == 0) {
        $data = "";
    } else {
        $data = fread($fd, $size);
    }

    flock($fd, LOCK_UN);

    $file = cutUpDir($file);

    $fname = $www . $file;
    if (strpos($file, '/projects/') !== false && $size == 0) {
        continue;
    }

    if (!(file_exists(dirname($fname)))) {
        createdir(dirname($fname));
    }

    if ((preg_match("/\/(\w+)\/\w+\.phtml/i", $file, $matches)) && empty($language)) {
        $language = $matches[1] . "/";
    }

    if ($pack == 1) {
        $data = gzuncompress($data);
    }

    if (strpos($fname, '.phtml') !== false) {
        $data = repldata($data);
    }

    if (strpos($fname, '.php') !== false) {
        $data = safe_function(repldata($data));
    }

    if (
        strpos($fname, '.php') !== false || strpos($fname, '.tpl') !== false || strpos($fname, '.xml') !== false
        || strpos($fname, '.tag') !== false || strpos($fname, '.tag') !== false || strpos($fname, '.svg') !== false
    ) {
        $data = trim(preg_replace('|\xEF\xBB\xBF|', "", $data));
    }

    if (!is_dir($fname)) {
        $f2 = fopen($fname, "w+b");
        fwrite($f2, $data);
        fclose($f2);

        if ($logfile && strpos($fname, '.xml') == false) {
            $ff = fopen($fname . ".log", "w+b");
            fwrite($ff, $logfile);
            fclose($ff);
        }
        if (strpos($fname, '.xml') !== false && file_exists($fname . ".log")) {
            unlink($fname . ".log");
        }

        if (is_file($fname)) {
            chmod($fname, SE_FILE_PERMISSIONS);
        }
    }
}

@unlink($path . $session . '.sid');
@unlink($path . $session . '.del');
@unlink($path . $session . '.list');
@unlink($path . $session . '.dat');
