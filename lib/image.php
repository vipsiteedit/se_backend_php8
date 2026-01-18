<?php
error_reporting(0);
chdir('../');

if (!defined('SE_ROOT')) {
    $root_dir = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : getcwd();
    define('SE_ROOT', rtrim(str_replace('//', '/', $root_dir . '/'), '/') . '/');
}

require_once dirname(__FILE__) . '/lib.php';

function ClearCache($dir)
{
    if (is_dir($dir)) {
        $d = opendir($dir);
        while (($f = readdir($d)) !== false) {
            if ($f == '.' || $f == '..' || !is_file($dir . $f)) continue;
            unlink($dir . $f);
        }
        closedir($d);
    }
    return;
}

$root = $_SERVER['DOCUMENT_ROOT'];
if (substr($root, -1) != '/') $root .= '/';
$path = $root . 'images/cache/';

if (isset($_GET['get_image'])) {
    define('SE_INDEX_INCLUDED', true);
    session_start();
    $img = $_GET['get_image'];
    $file_image = '/images/prev/' . $img;
    if (isset($_SESSION['get_images'][$img]) && !file_exists($root . $file_image)) {
        require_once 'lib_images.php';
        $image = $_SESSION['get_images'][$img];
        $file_image = se_getDImage($image['image'], $image['size'], $image['res'], $image['water'], 0x0000FF, $image['pos'], '', $image['quality'], 'create');
    }
    if (file_exists($root . $file_image)) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $file_image);
    }

    exit;
}

if (isset($_GET['deletecache'])) {
    ClearCache($root . 'images/cache/');
    ClearCache($root . 'images/prev/');
    exit;
}


require_once 'lib/lib_images.php';
$image = urldecode($_GET['img'] ?? '');
$size = isset($_GET['size']) ? intval($_GET['size']) : 0;
@$x1 = intval($_GET['x1']);
@$y1 = intval($_GET['y1']);
@$w = intval($_GET['w']);
@$h = intval($_GET['h']);

if (empty($size)) $size = 500;
if (empty($image) || $size < 50 || $size > 800) {
    header('HTTP/1.1 404 Not Found');
    exit;
}
$dest = md5($image) . '_' . $size . '_' . $x1 . '_' . $y1 . '_' . $w . '_' . $h;

if (substr($image, 0, 1) == '/') $image = substr($image, 1);

if (!is_dir($path)) {
    @mkdir($path, 0777, true);
} else {
    $flist = glob($path . md5($image) . '*');
    if (!empty($flist) && count($flist) > 3) {
        foreach ($flist as $f) {
            if ($f != $path . $dest) unlink($f);
        }
    }
}

if (strpos($image, '://') === false) {
    $image = $root . str_replace('//', '/', $image);
}

if (!file_exists($path . $dest)) {
    thumbCreate($path . $dest, $image, '', $size, $x1, $y1, $w, $h, 75);
}

if (file_exists($path . $dest)) {
    $s = getimagesize($path . $dest);
    header('Content-Type: ' . $s['mime']);
    //header('Cache-Control: no-cache, must-revalidate'); 
    //header('Pragma: no-cache')
    //header('Content-Transfer-Encoding: binary'); 
    //header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
    echo join('', file($path . $dest)) . chr(254) . (255);
}
