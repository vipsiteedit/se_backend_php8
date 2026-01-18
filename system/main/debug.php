<?php

if (!defined('SE_INDEX_INCLUDED')) die('Direct access denied');

if (isset($_GET['err_rep'])) {
	$errlevel = ($_GET['err_rep']=='') ? 1 : intval($_GET['err_rep']);
	if ($errlevel>=0 && $errlevel<=2) $_SESSION['se_errlevel'] = $errlevel;
} elseif (!isset($_SESSION['se_errlevel'])) {
	$_SESSION['se_errlevel'] = 0;
}
$errlevel = $_SESSION['se_errlevel'];
switch ($errlevel) {
	case 0: error_reporting(0); break;
	case 1: error_reporting(E_ALL & ~E_NOTICE); break;
	case 2: error_reporting(E_ALL); break;
}

function exec_microtime($current=false) {
	static $mt;
	if ($current!==false || !isset($mt)) $mt = (floatval($current));
	return microtime(true)-$mt;
}