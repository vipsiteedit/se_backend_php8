<?php

/*
lib_database.php,v 8.12 2009/12/13

EDGESTILE SiteEdit,
http://www.edgestile.com

Copyright (c) 2009 EDGESTILE
*/

function se_db_dsn($dsn = 'mysql')
{
	$dsnarr = array('mysql', 'pgsql');
	if (in_array($dsn, $dsnarr))
		require_once dirname(__file__) . '/database/se_db_' . $dsn . '.php';
}

if (!defined('MYSQL_ASSOC')) {
	define('MYSQL_ASSOC', MYSQLI_ASSOC);
}
if (!defined('MYSQL_NUM')) {
	define('MYSQL_NUM', MYSQLI_NUM);
}
if (!defined('MYSQL_BOTH')) {
	define('MYSQL_BOTH', MYSQLI_BOTH);
}

if (!function_exists('mysql_query')) {
	function mysql_query($query, $link_identifier = null)
	{
		$link = $link_identifier;
		if ($link === null && isset($GLOBALS['db_link'])) {
			$link = $GLOBALS['db_link'];
		}
		if ($link instanceof mysqli) {
			return mysqli_query($link, $query);
		}
		return false;
	}
}

if (!function_exists('mysql_fetch_array')) {
	function mysql_fetch_array($result, $result_type = MYSQL_BOTH)
	{
		$type = $result_type;
		if ($result_type === MYSQL_ASSOC) {
			$type = MYSQLI_ASSOC;
		} elseif ($result_type === MYSQL_NUM) {
			$type = MYSQLI_NUM;
		} elseif ($result_type === MYSQL_BOTH) {
			$type = MYSQLI_BOTH;
		}
		return mysqli_fetch_array($result, $type);
	}
}

if (!function_exists('mysql_fetch_row')) {
	function mysql_fetch_row($result)
	{
		return mysqli_fetch_row($result);
	}
}

if (!function_exists('mysql_num_rows')) {
	function mysql_num_rows($result)
	{
		return mysqli_num_rows($result);
	}
}

if (!function_exists('mysql_error')) {
	function mysql_error($link_identifier = null)
	{
		$link = $link_identifier;
		if ($link === null && isset($GLOBALS['db_link'])) {
			$link = $GLOBALS['db_link'];
		}
		if ($link instanceof mysqli) {
			return mysqli_error($link);
		}
		return '';
	}
}

if (!function_exists('se_db_error')) {
	function se_db_error($link = 'db_link')
	{
		$handle = null;
		if (isset($GLOBALS[$link])) {
			$handle = $GLOBALS[$link];
		}
		if ($handle instanceof mysqli) {
			return mysqli_error($handle);
		}
		return '';
	}
}
