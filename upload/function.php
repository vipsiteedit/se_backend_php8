<?php
error_reporting(0);
date_default_timezone_set("Europe/Moscow");
define("SE_MOD_FILES", 0644);
define("SE_FILE_PERMISSIONS", 0644);
define("SE_DIR_PERMISSIONS", 0755);


function createdir($path)
{
  $paths = explode('/', $path);
  $newpath = "";
  foreach ($paths as $id) {
    $newpath .= $id . "/";
    if (!empty($id)) {
      if (!file_exists($newpath)) {
        mkdir($newpath, SE_DIR_PERMISSIONS);
      }
    }
  }
}
//------------------------------------------------------------------------------
function ClearDir($dir, $fl_recurse = false)
{
  if (chdir($dir)) {
    $d = opendir(".");
    if (!empty($d))
      while (($f = readdir($d)) !== false) {
        if ($f == '.' || $f == '..') continue;
        if (is_dir($f)) {
          if ($fl_recurse) ClearDir($dir . $f . '/', true);
        } else
    if (($f != "index.php") && ($f != "favicon.ico") && ($f != "robots.txt") && ($f != "urlredirect.dat")) unlink($f);
      }
    closedir($d);
    if ($fl_recurse) rmdir($dir);
  }
  return;
}

//------------------------------------------------------------------------------


function checkSID($sID)
{
  $fname = getcwd() . "/data/" . $sID . ".sid";
  return file_exists($fname);
}

//------------------------------------------------------------------------------

function DirSize($dir)
{
  global $size;

  if (chdir($dir)) {
    $d = opendir(".");
    if (!empty($d))
      while (($f = readdir($d)) !== false) {
        if ($f == '.' || $f == '..') continue;
        if (is_link($f)) continue;
        if (is_dir($f)) DirSize($f);
        if (is_file($f)) $size += filesize($f);
      }
    closedir($d);
    chdir("..");
  }
  return $size;
}

//------------------------------------------------------------------------------

function OneDirSize($dir)
{
  global $size;

  $size = 0;

  if (chdir($dir)) {
    $d = opendir(".");
    if (!empty($d))
      while (($f = readdir($d)) !== false) {
        if ($f == '.' || $f == '..') continue;
        if (is_link($f)) continue;
        if (is_dir($f)) continue;
        if (is_file($f)) $size += filesize($f);
      }
    closedir($d);
    chdir("..");
  }
  return $size;
}

//------------------------------------------------------------------------------

function cutUpDir($str)
{

  while (strpos($str, "../") !== false) $str = str_replace("../", "", $str);
  return $str;
}


//------------------------------------------------------------------------------
function indexSearch($session, $language = "")
{

  //file list
  //chdir(".");
  $path = getcwd();
  $flist = gzfile($path . "/upload/data/" . $session . ".list");

  //  $fp=fopen($path.'log.dat',"a");
  //  fwrite($fp,$language."!!\r\n");
  //  fclose($fp);

  include_once(getcwd() . "/system/main/init.php");
  include_once(getcwd() . "/lib/lib.php");
  include_once(getcwd() . "/system/main/function.php");

  $thisdir = $path;
  $path = $path . "/" . $language;

  if (!file_exists($path . "searchdata")) mkdir($path . "searchdata", 755);




  foreach ($flist as $fstr) {
    $fstr = explode(chr(9), $fstr);
    if (!empty($fstr[1])) $fstr = $fstr[1];
    else $fstr = '';


    if (preg_match("/\/(\w+\.phtml)/", $fstr, $matches)) $fname = $matches[1];
    else continue;

    $page = substr($fname, 0, -6);



    if (file_exists($path . "searchdata/$page")) {
      clearDir($path . "searchdata/$page");
      rmdir($path . "searchdata/$page");
    }

    include_once $path . $fname;


    if (!$indexes) if (file_exists($path . "searchdata/$page" . ".dat")) unlink($path . "searchdata/$page" . ".dat");
    if ($indexes && ($group < 1)) {

      $link = "/$page/";



      $texts = $link . chr(1) . round(filesize($path . $fname) / 1024) . chr(1) . date("d.m.Y", filectime($path . $fname)) . "&#10;" . $titlepage . "&#10;" . $title . "&#10;" . $keywords . "&#10;" . $description . "&#10;";
      $texts .= $enteringtext;
      $ns = 0;
      @$razdelit = array_keys($raz_typ);
      while (isset($razdelit[$ns])) {
        $item = $razdelit[$ns];
        if ($raz_beg[$item] != "") $texts .=  $raz_beg[$item] . " ";
        if ($raz_tit[$item] != "") $texts .=  $raz_tit[$item] . "&#10;";
        if ($raz_txt[$item] != "") $texts .=  $raz_txt[$item] . " ";
        if ($raz_end[$item] != "") $texts .=  $raz_end[$item] . " ";
        $no = 0;
        while (isset($obj[$item][$no])) {
          $f = explode("|", @$obj[$item][@$no], 9);
          if (@$f[0] != "") $texts .= $f[0] . " ";
          if (@$f[2] != "") $texts .= $f[1] . " " . $f[2] . " ";
          if (@$f[4] != "") $texts .= $f[4] . " ";

          if (@$f[5] != "") {
            if (!file_exists($path . "/searchdata/$page")) mkdir($path . "/searchdata/$page", 0755);
            $dtext = $link . "?razdel=$item&object=$no" . chr(1) . round(strlen($f[5]) / 1024) . chr(1) . date("d.m.Y", filectime($path . $fname)) . "\n\n" . @$f[2] . "\n\n\n";
            $dt = strip_tags($f[5]);
            $dt = preg_replace("/[><\'\"]+/", " ", $dt);
            $dt = preg_replace("/[\s]+/", " ", $dt);
            $dt = str_replace("^^", "", $dt);
            $dt = str_replace("|", "", $dt);
            $dt = wordwrap($dt, 80, "\n");
            $dtext .= $dt;

            $f = fopen($path . "searchdata/$page/" . $page . "_" . $item . "_" . $no . ".dat", "w");
            fwrite($f, $dtext);
            fclose($f);
          }

          if (@$f[6] != "") $texts .= $f[6] . " ";
          if (@$f[7] != "") $texts .= $f[7] . " ";
          if (@$f[8] != "") $texts .= $f[8] . " ";
          $no++;
        }
        $ns++;
      };
      $texts .= $closingtext;
      $texts = strip_tags($texts);
      $texts = preg_replace("/[><\'\"]+/", " ", $texts);
      $texts = preg_replace("/[\s]+/", " ", $texts);
      $texts = str_replace("^^", "", $texts);
      $texts = str_replace("&#10;", "\n", $texts);
      $texts = str_replace("|", "", $texts);
      $texts = wordwrap($texts, 80, "\n");

      $f = fopen($path . "searchdata/" . $page . ".dat", "w");
      fwrite($f, $texts);
      fclose($f);

      $raz_tit = null;
      $raz_txt = null;
      $raz_end = null;
      $obj = null;
      $f = null;
    }
  }
}

function upload_del_badfile()
{

  if (chdir(dirname(__FILE__) . "/data")) {
    $d = opendir(".");
    while (($f = readdir($d)) !== false) {
      if ($f == '.' || $f == '..') continue;
      if (is_link($f)) continue;
      if (is_dir($f)) continue;
      if (is_file($f) && (filemtime($f) + 3600 < time())) unlink($f);
    }
    closedir($d);
    chdir("..");
  }
}
