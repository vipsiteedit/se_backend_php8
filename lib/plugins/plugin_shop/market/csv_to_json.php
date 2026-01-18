<?php

$categories = [];
$id = 0;

$handle = fopen("categories.csv", "r");
while (($data = fgetcsv($handle)) !== FALSE) {
    $categories["AAA$id"] = $data[0];
    $id++;
}
