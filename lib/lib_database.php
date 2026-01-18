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
