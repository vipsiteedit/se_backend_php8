<?php

define("PREG_EXT", "/\.php|phtml/i");

function se_copy($source, $dest)
{
  if (preg_match(PREG_EXT, $source) || preg_match(PREG_EXT, $dest)) exit("Access denied for copy PHP-files");
  else return copy($source, $dest);
}

function se_file_get_contents($filename, $use_include_path = 0)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return file_get_contents($filename);
}

function se_file($filename, $use_include_path = 0)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return file($filename);
}

function se_filegroup($filename)
{
  exit("Access denied");
}

function se_fileinode($filename)
{
  exit("Access denied");
}

function se_filemtime($filename)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return filemtime($filename);
}

function se_fileowner($filename)
{
  exit("Access denied");
}

function se_fileperms($filename)
{
  exit("Access denied");
}

function se_filesize($filename)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return filesize($filename);
}

function se_fopen($filename, $mode, $use_include_path = 0)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return fopen($filename, $mode);
}

function se_link($target, $link)
{
  exit("Access denied");
}

function se_linkinfo($path)
{
  exit("Access denied");
}

function se_lstat($filename)
{
  exit("Access denied");
}

function se_move_uploaded_file($filename, $dest)
{
  if (preg_match(PREG_EXT, $filename) || preg_match(PREG_EXT, $dest)) exit("Access denied for copy PHP-files");
  else return move_uploaded_file($filename, $dest);
}

function se_parse_ini_file($filename, $process_sections = FALSE)
{
  if (preg_match(PREG_EXT, $filename)) exit("Access denied for read PHP-files");
  else return parse_ini_file($filename, $process_sections);
}

function se_pathinfo($filename)
{
  exit("Access denied");
}

function se_popen($filename, $mode)
{
  exit("Access denied");
}

function se_readfile($filename, $mode)
{
  exit("Access denied");
}

function se_readlink($path)
{
  exit("Access denied");
}

function se_realpath($path)
{
  exit("Access denied");
}

function se_rename($oldname, $newname)
{
  if (preg_match(PREG_EXT, $oldname) || preg_match(PREG_EXT, $newname)) exit("Access denied for rename PHP-files");
  else return rename($oldname, $newname);
}

function se_stat($filename)
{
  exit("Access denied");
}

function se_symlink($target, $link)
{
  symlink($target, $link);
  //exit("Access denied");
}
