<?php
error_reporting(0);

$ext_s = $_REQUEST['s'];
$ext_d = $_REQUEST['d'];
$ext_p = $_REQUEST['p'];
$ext_l = $_REQUEST['l'];
$ext_sub = $_REQUEST['sub'];
$ext_f = $_REQUEST['f'];

if (!(isset($ext_s, $ext_d, $ext_p))) {
	header('HTTP/1.0 404');
	exit();
}
require_once "function.php";
require_once "lib_crc32.php";

set_time_limit(0);
ini_set('memory_limit', '500M');

function getFileLog($ext_p, $filename, $basedir = '')
{
	if (is_dir($filename) || strpos($filename, '.log')) return;

	if (filesize($filename) == 0) {
		if (file_exists($filename . ".log")) unlink($filename . ".log");
		return;
	}
	if (file_exists($filename . ".log") && strpos($filename, '.xml.log') == false) {
		$tmplogo = explode(":", join(file($filename . ".log")));
		if (filesize($filename) > 0) {
			$filename = str_replace('/', "\\", str_replace($basedir, '', $filename));
			return $ext_p . '|' . $filename . '|' . @$tmplogo[1] . "|" . @$tmplogo[2] . "|" . @$tmplogo[3] . "\r\n";
		}
	} else {
		if (filesize($filename) > 0) {
			$crc = crc32_file($filename);
			$size = filesize($filename);
			$str = $filename . ':crc' . $crc . ':' . $size;
			if (strpos($filename, '.xml') === false) {
				$fp = fopen($filename . ".log", "w+");
				fwrite($fp, $str);
				fclose($fp);
			}
			$filename = str_replace('/', "\\", str_replace($basedir, '', $filename));
			return $ext_p . '|' . $filename . '|crc' . $crc . "|" . $size . "|0\r\n";
		}
	}
}

if (!function_exists('lsr')) {
	function lsr($indir, $act, $basedir = '')
	{
		$dirlist = scandir($indir);
		foreach ($dirlist as $line) {
			$offset = 0;
			if ($line == "." || $line == "..") continue;
			if (!is_dir($indir . "/" . $line) && file_exists($indir . "/" . $line . ".log")) {
				switch ($act) {
						//case '2': $offset = 7; break;
					case '4':
						$offset = 6;
						break;
					case '5':
						$offset = 5;
						break;
				}
				echo getFileLog($act, $indir . "/" . $line, $basedir);
			}
			if (is_dir($indir . "/" . $line))
				lsr($indir . "/" . $line, $act, $basedir);
		}
	}
}

$session = htmlspecialchars(addslashes($ext_s));
$domain = addslashes($ext_d);
if (!strpos($domain, '.')) {
	$domain .= '.e-stile.ru';
}

//$domain = $domain[0];
$act = htmlspecialchars(addslashes($ext_p));

if (!checkSID($session)) {
	exit("no SID");
}
if (!empty($ext_l))
	$lang = "/$ext_l";
else
	$lang = "";
$path = ".." . $lang;
if (!empty($ext_sub))
	$ext_sub = "/" . $ext_sub;
else
	$ext_sub = '';

if (!empty($subdir))
	$subdir = "/" . $subdir;

if ($act < 10) {
	if ($act == 2)
		$dir = $path . '/images' . $subdir;
	else 
	    if ($act == 3)
		$dir = $path;
	else 
	    if ($act == 4)
		$dir = $path . '/files' . $subdir;
	else 
	    if ($act == 5)
		$dir = $path . '/skin' . $subdir;
	else 
	    if ($act == 6)
		$dir = getCwd() . '/../projects' . $lang;
	else
		$dir = $path . '/arhiv';

	$fl_dir = false;
	if (is_dir($dir) && empty($ext_f)) {
		chdir($dir);
		if ($handle = opendir('.')) {
			while (false !== ($file = readdir($handle))) {
				if ($file == '.' || $file == '..' || strpos($file, ".log") > 1)
					continue;
				if ($act == 6 && is_dir($file) && $file == 'pages') {
					if ($handle1 = opendir($file)) {
						while (false !== ($file1 = readdir($handle1))) {
							if ($file1 == '.' || $file1 == '..' || strpos($file, ".log")) continue;
							echo getFileLog($ext_p, 'pages/' . $file1);
						}
						closedir($handle1);
					}
				}
				$fl_dir = true;
				if ($act == 3) {
					if (is_dir($file)) {
						if (!(strlen($file) > 3
							|| $file == 'system'
							|| $file == 'catalog'
							|| $file == 'stat'
							|| $file == 'lib'
							|| $file == 'admin'
							|| $file == 'modules'
							|| $file == 'rss'
							|| $file == 'order'
							|| $file == 'xml'
							|| $file == 'files'
							|| $file == 'skin'
							|| $file == 'images'
							|| $file == 'arhiv'
							|| $file == 'installation'
							|| $file == 'upload'
							|| $file == 'searchdata'
							|| $file == 'data'))
							echo getFileLog($ext_p, $file);
					} else 
                    if ($file == 'favicon.ico' || $file == 'robots.txt' || $file == 'sitelang.dat' || $file == 'hostname.dat') {
						echo getFileLog($ext_p, $file);
					}
					continue;
				}
				if (is_dir($file)) {
					if ($act == 4 || $act == 5) {
						lsr(getcwd() . "/" . $file, $act, getcwd() . '/');
					} else continue;
				}
				if (file_exists($file)) {
					echo getFileLog($ext_p, $file);
				}
			}
			closedir($handle);
		}
		if ($act == 4 && !$fl_dir) {
			rmdir(getcwd());
		}
	} else 
    if (!empty($ext_f)) {
		$file = $dir . "/" . $ext_f;
		echo getFileLog($ext_p, $file);
	}
	echo "\r\n";
}

if ($act == 10) {
	if (isset($ext_f)) {
		$filename = "$path/skin/" . str_replace(chr(13) . chr(10), "", $ext_f);
		if (file_exists($filename))
			echo implode("", file($filename));
	}
}

if ($act > 10) {
	Header("Content-type: image/jpeg");
	switch ($act) {
		case "11":
			$dir = "$path/arhiv";
			break;
		case "12":
			$dir = "$path/images" . $ext_sub;
			break;
		case "13":
			$dir = "$path" . $ext_sub;
			break;
		case "14":
			$dir = "$path/files" . $ext_sub;
			break;
		case "15":
			$dir = "$path/skin" . $ext_sub;
			break;
		case "16":
			$dir = getCwd() . "/../projects{$lang}";
			break;
		default:
			$dir = "$path/arhiv";
	}
	if (isset($ext_f)) {
		$filename = $dir . "/" . $ext_f;
		if (is_dir($dir) && file_exists($filename))
			echo join("", file($filename));
		else exit();
	}
}
