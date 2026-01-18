<?php

if (php_sapi_name() !== 'cli') {
    echo "This script can only be run from CLI.\n";
    exit(1);
}

require_once __DIR__ . '/config_db.php';

if (empty($CONFIG) || empty($CONFIG['HostName']) || empty($CONFIG['DBName'])) {
    echo "DB config not found in system/config_db.php\n";
    exit(1);
}

$dsn = "mysql:host={$CONFIG['HostName']};dbname={$CONFIG['DBName']};charset=utf8";
$pdo = new PDO($dsn, $CONFIG['DBUserName'], $CONFIG['DBPassword'], array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
));
$pdo->exec("SET SESSION sql_mode=(SELECT REPLACE(REPLACE(@@sql_mode,'NO_ZERO_DATE',''),'NO_ZERO_IN_DATE',''))");

$zeroDefaults = array(
    '0000-00-00',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00.000000',
);

function execQuery($pdo, $sql)
{
    echo $sql . "\n";
    $pdo->exec($sql);
}

function getColumns($pdo, $dbName, $table)
{
    $stmt = $pdo->prepare(
        "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl"
    );
    $stmt->execute(array(':db' => $dbName, ':tbl' => $table));
    $cols = array();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $cols[$row['COLUMN_NAME']] = $row;
    }
    return $cols;
}

function getPrimaryKeyColumns($pdo, $dbName, $table)
{
    $stmt = $pdo->prepare(
        "SELECT COLUMN_NAME
         FROM information_schema.KEY_COLUMN_USAGE
         WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl
           AND CONSTRAINT_NAME = 'PRIMARY'"
    );
    $stmt->execute(array(':db' => $dbName, ':tbl' => $table));
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getForeignKeyColumns($pdo, $dbName, $table)
{
    $stmt = $pdo->prepare(
        "SELECT COLUMN_NAME
         FROM information_schema.KEY_COLUMN_USAGE
         WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tbl
           AND REFERENCED_TABLE_NAME IS NOT NULL"
    );
    $stmt->execute(array(':db' => $dbName, ':tbl' => $table));
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$tables = $pdo->query(
    "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()"
)->fetchAll(PDO::FETCH_COLUMN);

$skipAutoIncrementTables = array('user_urid', 'person');

foreach ($tables as $table) {
    $columns = getColumns($pdo, $CONFIG['DBName'], $table);

    if (isset($columns['id']) && !in_array($table, $skipAutoIncrementTables, true)) {
        $pkCols = getPrimaryKeyColumns($pdo, $CONFIG['DBName'], $table);
        if (empty($pkCols)) {
            execQuery($pdo, "ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
        } elseif (!in_array('id', $pkCols, true)) {
            execQuery($pdo, "ALTER TABLE `{$table}` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)");
        }

        $col = $columns['id'];
        $fkCols = getForeignKeyColumns($pdo, $CONFIG['DBName'], $table);
        if (stripos($col['EXTRA'], 'auto_increment') === false && !in_array('id', $fkCols, true)) {
            $sql = "ALTER TABLE `{$table}` MODIFY `id` {$col['COLUMN_TYPE']} NOT NULL AUTO_INCREMENT";
            execQuery($pdo, $sql);
        }
    }

    foreach ($columns as $name => $col) {
        if (!in_array($col['DATA_TYPE'], array('timestamp', 'datetime', 'date'), true)) {
            continue;
        }

        $extra = '';
        if (!empty($col['EXTRA']) && stripos($col['EXTRA'], 'on update') !== false) {
            $extra = ' ON UPDATE CURRENT_TIMESTAMP';
        }

        if ($col['IS_NULLABLE'] === 'NO') {
            $sql = "ALTER TABLE `{$table}` MODIFY `{$name}` {$col['COLUMN_TYPE']} NULL DEFAULT NULL{$extra}";
            execQuery($pdo, $sql);
        }

        $zeroList = array();
        foreach ($zeroDefaults as $zd) {
            $zeroList[] = "'{$zd}'";
        }
        $zeroList = implode(", ", $zeroList);
        $updateSql = "UPDATE `{$table}` SET `{$name}` = NULL WHERE `{$name}` IN ({$zeroList})";
        execQuery($pdo, $updateSql);

        if (!in_array($col['COLUMN_DEFAULT'], $zeroDefaults, true)) {
            continue;
        }

        $type = $col['COLUMN_TYPE'];
        if ($name === 'updated_at') {
            $sql = "ALTER TABLE `{$table}` MODIFY `{$name}` {$type} NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP";
        } elseif ($name === 'created_at') {
            $sql = "ALTER TABLE `{$table}` MODIFY `{$name}` {$type} NULL DEFAULT CURRENT_TIMESTAMP";
        } else {
            $sql = "ALTER TABLE `{$table}` MODIFY `{$name}` {$type} NULL DEFAULT NULL{$extra}";
        }
        execQuery($pdo, $sql);
    }
}

echo "Migration complete.\n";
