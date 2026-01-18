#! /usr/bin/php7.3
<?php

function install_schema()
{
    $root = getcwd().'/../../';
    require_once $root."lib/yaml/seYaml.class.php";
    require_once $root."lib/lib_database.php";
    include($root . 'system/config_db.php');
    se_db_dsn('mysql');
    se_db_connect($CONFIG);

    $tables = seYAML::Load('tables.yml');
    $dir = 'schema';
    //if (chdir($dir)) {
    $tablelist = $tables['tables'];
    $i = -1;
    se_db_query("SET FOREIGN_KEY_CHECKS=0;");
    foreach($tablelist as $id=>$table) {
        echo $table . "\n";
        $i++;
        se_table_migration($table);
        se_db_query("DROP TABLE `{$table}_tmp`");
    }

    echo "\n\n";	
    for($j = $i; $j >= 0; $j--) {
        if (empty($tablelist[$j])) continue;
        $table = $tablelist[$j];
        echo "DROP TABLE `{$table}_tmp`\n";
        se_db_query("DROP TABLE `{$table}_tmp`");
    }
        
    se_db_query("SET FOREIGN_KEY_CHECKS=1;");
    echo "end\n";
}

install_schema();
