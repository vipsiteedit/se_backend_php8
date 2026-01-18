#! /usr/local/bin/php5.4
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
function export_schema()
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
    //se_db_query("SET FOREIGN_KEY_CHECKS=0;");
    foreach($tablelist as $id=>$table) {        
        echo $table . "\n";
        $i++;
        se_db_to_yaml($table, 'migration/'.$table.'.yml');
    }
    //se_db_query("SET FOREIGN_KEY_CHECKS=1;");
    echo "end\n";
}

export_schema();
